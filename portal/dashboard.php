<?php
session_start();
if (!isset($_SESSION['portal_patient_id'])) {
    header("Location: index.php");
    exit;
}

require_once dirname(__DIR__) . '/config/db.php';
$patient_id = intval($_SESSION['portal_patient_id']);

// Fetch Patient Info
$stmt = $conn->prepare("SELECT mr_number, first_name, last_name, gender FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

if (!$patient) {
    session_destroy();
    die("Account anomaly detected.");
}

// 1. Gather all linked patient IDs (Spouse linking)
$patient_ids = [$patient_id];
$cnic_clean = preg_replace('/[^0-9]/', '', $patient['cnic'] ?? '');
$phone = $patient['phone'] ?? '';
$mr = $patient['mr_number'] ?? '';

// Find if anyone matches this patient's spouse_name and shares contact info
if (!empty($patient['spouse_name'])) {
    $stmt_spouse = $conn->prepare("SELECT id FROM patients WHERE first_name = ? AND (phone = ? OR mr_number = ? OR REPLACE(cnic, '-', '') = ?)");
    $stmt_spouse->bind_param("ssss", $patient['spouse_name'], $phone, $mr, $cnic_clean);
    $stmt_spouse->execute();
    $res_spouse = $stmt_spouse->get_result();
    while ($row = $res_spouse->fetch_assoc()) {
        $patient_ids[] = $row['id'];
    }
}

// Find if anyone listed THIS patient as their spouse
$stmt_rev = $conn->prepare("SELECT id FROM patients WHERE spouse_name = ? AND (phone = ? OR mr_number = ? OR REPLACE(cnic, '-', '') = ?)");
$stmt_rev->bind_param("ssss", $patient['first_name'], $phone, $mr, $cnic_clean);
$stmt_rev->execute();
$res_rev = $stmt_rev->get_result();
while ($row = $res_rev->fetch_assoc()) {
    $patient_ids[] = $row['id'];
}

$patient_ids = array_unique($patient_ids);
$ids_csv = implode(',', $patient_ids);

// Fetch all 4 document streams for all linked IDs
$prescriptions = [];
$res = $conn->query("SELECT p.id, p.created_at, p.notes, p.qrcode_hash, pt.first_name, pt.last_name FROM prescriptions p JOIN patients pt ON p.patient_id = pt.id WHERE p.patient_id IN ($ids_csv) ORDER BY p.created_at DESC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $prescriptions[] = $row;
}

$ultrasounds = [];
$res = $conn->query("SELECT u.id, u.created_at, u.report_title, u.qrcode_hash, pt.first_name, pt.last_name FROM patient_ultrasounds u JOIN patients pt ON u.patient_id = pt.id WHERE u.patient_id IN ($ids_csv) ORDER BY u.created_at DESC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $ultrasounds[] = $row;
}

$semen = [];
$res = $conn->query("SELECT s.id, s.collection_time as created_at, s.auto_diagnosis, s.qrcode_hash, pt.first_name, pt.last_name FROM semen_analyses s JOIN patients pt ON s.patient_id = pt.id WHERE s.patient_id IN ($ids_csv) ORDER BY s.collection_time DESC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $semen[] = $row;
}

$receipts = [];
$res = $conn->query("SELECT r.id, r.receipt_date as created_at, r.procedure_name, r.amount, r.qrcode_hash, pt.first_name, pt.last_name FROM receipts r JOIN patients pt ON r.patient_id = pt.id WHERE r.patient_id IN ($ids_csv) ORDER BY r.receipt_date DESC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $receipts[] = $row;
}

$lab_results = [];
try {
    $res = $conn->query("SELECT plt.id, plt.test_date as created_at, plt.result_value, plt.scanned_report_path, ltd.test_name, ltd.reference_range, ltd.unit, pt.first_name, pt.last_name FROM patient_lab_results plt JOIN lab_tests_directory ltd ON plt.test_id = ltd.id JOIN patients pt ON plt.patient_id = pt.id WHERE plt.patient_id IN ($ids_csv) ORDER BY plt.test_date DESC, plt.id DESC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $lab_results[] = $row;
    }
}
catch (Exception $e) {
}

$advised_procedures = [];
try {
    $res = $conn->query("SELECT ap.*, pt.first_name, pt.last_name, 
            (SELECT GROUP_CONCAT(status SEPARATOR ',') FROM receipts WHERE advised_procedure_id = ap.id) as payment_statuses,
            (SELECT SUM(amount) FROM receipts WHERE advised_procedure_id = ap.id) as total_billed
            FROM advised_procedures ap JOIN patients pt ON ap.patient_id = pt.id WHERE ap.patient_id IN ($ids_csv) ORDER BY ap.date_advised DESC, ap.id DESC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $advised_procedures[] = $row;
    }
}
catch (Exception $e) {
}

// Logout handler
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My EMR Dashboard - IVF Experts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-indigo-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="/assets/images/logo.png" class="h-10 bg-white rounded p-1 object-contain" alt="Logo">
                <div>
                    <div class="font-bold tracking-wider leading-tight">Patient Portal</div>
                    <div class="text-xs text-indigo-300">Secure EMR Access</div>
                </div>
            </div>
            <a href="?logout=1" class="text-sm bg-indigo-800 hover:bg-indigo-700 px-4 py-2 rounded-lg transition-colors border border-indigo-600">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Welcome Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between mb-8">
            <div class="flex items-center gap-4 mb-4 md:mb-0">
                <div class="w-16 h-16 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center text-3xl shadow-inner">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?></h1>
                    <p class="text-sm font-mono text-gray-500">Medical Record Number (MR): <span class="font-bold text-indigo-600"><?php echo htmlspecialchars($patient['mr_number']); ?></span></p>
                </div>
            </div>
            <div class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-lg border border-emerald-100 text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-shield-check"></i> Identity Verified
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Diagnositic Reports (Semen) -->
            <div>
                <h2 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fa-solid fa-microscope text-teal-600 mr-2"></i> Lab & Semen Reports</h2>
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50">
                    <?php if (empty($semen)): ?>
                        <div class="p-6 text-center text-gray-400 text-sm">No semen analysis reports available.</div>
                    <?php
else:
    foreach ($semen as $sa): ?>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="font-bold text-gray-800">Advanced Semen Analysis</div>
                                <div class="text-[10px] text-indigo-600 font-bold uppercase mb-1">Patient: <?php echo htmlspecialchars($sa['first_name'] . ' ' . $sa['last_name']); ?></div>
                                <div class="text-xs text-gray-500">Recorded: <?php echo date('d M Y', strtotime($sa['created_at'])); ?></div>
                                <div class="text-[10px] uppercase font-bold text-teal-600 mt-1"><?php echo htmlspecialchars($sa['auto_diagnosis']); ?></div>
                            </div>
                            <a href="view.php?type=sa&hash=<?php echo $sa['qrcode_hash']; ?>" target="_blank" class="bg-teal-50 hover:bg-teal-100 text-teal-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-teal-100 whitespace-nowrap">
                                <i class="fa-solid fa-eye mr-1"></i> View PDF
                            </a>
                        </div>
                    <?php
    endforeach;
endif; ?>
                </div>
            </div>

            <!-- Ultrasounds -->
            <div>
                <h2 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fa-solid fa-image text-sky-600 mr-2"></i> Diagnostic Ultrasounds</h2>
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50">
                    <?php if (empty($ultrasounds)): ?>
                        <div class="p-6 text-center text-gray-400 text-sm">No ultrasound reports available.</div>
                    <?php
else:
    foreach ($ultrasounds as $u): ?>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="font-bold text-gray-800"><?php echo htmlspecialchars($u['report_title']); ?></div>
                                <div class="text-[10px] text-indigo-600 font-bold uppercase mb-1">Patient: <?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></div>
                                <div class="text-xs text-gray-500">Report Date: <?php echo date('d M Y', strtotime($u['created_at'])); ?></div>
                            </div>
                            <a href="view.php?type=usg&hash=<?php echo $u['qrcode_hash']; ?>" target="_blank" class="bg-sky-50 hover:bg-sky-100 text-sky-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-sky-100 whitespace-nowrap">
                                <i class="fa-solid fa-eye mr-1"></i> View PDF
                            </a>
                        </div>
                    <?php
    endforeach;
endif; ?>
                </div>
            </div>

            <!-- Lab Results -->
            <div class="lg:col-span-2">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fa-solid fa-vials text-indigo-600 mr-2"></i> Laboratory Results</h2>
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50">
                    <?php if (empty($lab_results)): ?>
                        <div class="p-6 text-center text-gray-400 text-sm">No laboratory results found.</div>
                    <?php
else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                        <th class="p-4 font-medium">Patient</th>
                                        <th class="p-4 font-medium">Test Name</th>
                                        <th class="p-4 font-medium">Result Value</th>
                                        <th class="p-4 font-medium">Reference Range</th>
                                        <th class="p-4 font-medium text-right">Date / Report</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    <?php foreach ($lab_results as $lr): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4">
                                            <div class="font-bold text-gray-900"><?php echo htmlspecialchars($lr['first_name'] . ' ' . $lr['last_name']); ?></div>
                                            <?php if ($lr['first_name'] === $patient['first_name']): ?>
                                                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded uppercase font-bold tracking-wider">You</span>
                                            <?php
        else: ?>
                                                <span class="text-[10px] bg-pink-50 text-pink-600 px-1.5 py-0.5 rounded uppercase font-bold tracking-wider">Spouse</span>
                                            <?php
        endif; ?>
                                        </td>
                                        <td class="p-4 font-semibold text-gray-900"><?php echo htmlspecialchars($lr['test_name']); ?></td>
                                        <td class="p-4">
                                            <span class="font-bold text-lg text-gray-900"><?php echo htmlspecialchars($lr['result_value']); ?></span>
                                            <span class="text-xs text-gray-500 font-mono ml-1"><?php echo htmlspecialchars($lr['unit']); ?></span>
                                        </td>
                                        <td class="p-4 text-gray-600 text-xs"><?php echo htmlspecialchars($lr['reference_range'] ?: 'N/A'); ?></td>
                                        <td class="p-4 text-right">
                                            <div class="font-medium text-gray-800 mb-2"><?php echo date('d M Y', strtotime($lr['created_at'])); ?></div>
                                            <?php if (!empty($lr['scanned_report_path'])): ?>
                                                <a href="../<?php echo htmlspecialchars($lr['scanned_report_path']); ?>" target="_blank" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-xs font-bold transition-colors inline-block border border-indigo-100">
                                                    <i class="fa-solid fa-file-pdf mr-1"></i> Original PDF
                                                </a>
                                            <?php
        else: ?>
                                                <span class="text-xs text-gray-400">Recorded internally</span>
                                            <?php
        endif; ?>
                                        </td>
                                    </tr>
                                    <?php
    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
endif; ?>
                </div>
            </div>

            <!-- Prescriptions -->
            <div>
                <h2 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fa-solid fa-prescription text-indigo-600 mr-2"></i> Prescriptions</h2>
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50">
                    <?php if (empty($prescriptions)): ?>
                        <div class="p-6 text-center text-gray-400 text-sm">No active prescriptions.</div>
                    <?php
else:
    foreach ($prescriptions as $rx): ?>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="font-bold text-gray-800">E-Prescription #<?php echo $rx['id']; ?></div>
                                <div class="text-[10px] text-indigo-600 font-bold uppercase mb-1">Patient: <?php echo htmlspecialchars($rx['first_name'] . ' ' . $rx['last_name']); ?></div>
                                <div class="text-xs text-gray-500">Issued On: <?php echo date('d M Y', strtotime($rx['created_at'])); ?></div>
                            </div>
                            <a href="view.php?type=rx&hash=<?php echo $rx['qrcode_hash']; ?>" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-indigo-100 whitespace-nowrap">
                                <i class="fa-solid fa-eye mr-1"></i> View PDF
                            </a>
                        </div>
                    <?php
    endforeach;
endif; ?>
                </div>
            </div>

            <!-- Financial Receipts -->
            <div>
                <h2 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fa-solid fa-file-invoice-dollar text-emerald-600 mr-2"></i> Billing & Receipts</h2>
                <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50">
                    <?php if (empty($receipts)): ?>
                        <div class="p-6 text-center text-gray-400 text-sm">No billing records found.</div>
                    <?php
else:
    foreach ($receipts as $r): ?>
                        <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="font-bold text-gray-800"><?php echo htmlspecialchars($r['procedure_name']); ?></div>
                                <div class="text-[10px] text-indigo-600 font-bold uppercase mb-1">For: <?php echo htmlspecialchars($r['first_name'] . ' ' . $r['last_name']); ?></div>
                                <div class="text-xs text-gray-500">Date: <?php echo date('d M Y', strtotime($r['created_at'])); ?></div>
                                <div class="text-sm font-mono font-bold text-emerald-700 mt-1">Rs. <?php echo number_format($r['amount'], 2); ?></div>
                            </div>
                            <a href="view.php?type=receipt&hash=<?php echo $r['qrcode_hash']; ?>" target="_blank" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-emerald-100 whitespace-nowrap">
                                <i class="fa-solid fa-eye mr-1"></i> View PDF
                            </a>
                        </div>
                    <?php
    endforeach;
endif; ?>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
