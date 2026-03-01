<?php
$pageTitle = "Patient 360 Profile";
require_once __DIR__ . '/includes/auth.php';

$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($patient_id <= 0) {
    header("Location: patients.php");
    exit;
}

$error = '';
$success = '';

// Handle Add Clinical History form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_history'])) {
    $lh = trim($_POST['lh'] ?? '');
    $fsh = trim($_POST['fsh'] ?? '');
    $testo = trim($_POST['testosterone'] ?? '');
    $prol = trim($_POST['prolactin'] ?? '');
    $vitd = trim($_POST['vit_d'] ?? '');
    $iron = trim($_POST['iron'] ?? '');
    $tb = trim($_POST['tb'] ?? '');
    $notes = trim($_POST['clinical_notes'] ?? '');

    $stmt = $conn->prepare("INSERT INTO patient_history (patient_id, lh, fsh, testosterone, prolactin, vit_d, iron, tb, clinical_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("issssssss", $patient_id, $lh, $fsh, $testo, $prol, $vitd, $iron, $tb, $notes);
        if ($stmt->execute()) {
            $success = "Clinical history & lab records added successfully.";
        }
        else {
            $error = "Failed to add history: " . $stmt->error;
        }
    }
}

// Fetch Patient Details
$patient = null;
try {
    $stmt = $conn->prepare("SELECT p.*, h.name as hospital_name FROM patients p LEFT JOIN hospitals h ON p.referring_hospital_id = h.id WHERE p.id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $patient = $res->fetch_assoc();
    }
}
catch (Exception $e) {
}

if (!$patient) {
    die("Patient not found.");
}

// Fetch History
$histories = [];
try {
    $stmt = $conn->prepare("SELECT * FROM patient_history WHERE patient_id = ? ORDER BY recorded_at DESC");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $histories[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Semen Analyses
$semen_reports = [];
try {
    $stmt = $conn->prepare("SELECT id, collection_time, auto_diagnosis, qrcode_hash FROM semen_analyses WHERE patient_id = ? ORDER BY collection_time DESC");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $semen_reports[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Prescriptions
$prescriptions = [];
try {
    $stmt = $conn->prepare("SELECT id, created_at, scanned_report_path FROM prescriptions WHERE patient_id = ? ORDER BY created_at DESC");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $prescriptions[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Ultrasounds
$ultrasounds = [];
try {
    $stmt = $conn->prepare("SELECT id, created_at, report_title, scanned_report_path FROM patient_ultrasounds WHERE patient_id = ? ORDER BY created_at DESC");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $ultrasounds[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Lab Results
$lab_results = [];
try {
    $stmt = $conn->prepare("SELECT plt.*, ltd.test_name, ltd.reference_range, ltd.unit FROM patient_lab_results plt JOIN lab_tests_directory ltd ON plt.test_id = ltd.id WHERE plt.patient_id = ? ORDER BY plt.test_date DESC, plt.id DESC");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $lab_results[] = $row;
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<!-- Alpine component for Tabs -->
<div x-data="{ currentTab: 'history' }" class="flex flex-col lg:flex-row gap-6">

    <!-- Left Column: Patient Demographics -->
    <div class="w-full lg:w-1/3 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="bg-teal-900 p-6 text-center relative">
                <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center text-3xl text-white mb-3 shadow-inner">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="relative flex flex-col items-center">
                    <h2 class="text-xl font-bold text-white"><?php echo esc($patient['first_name'] . ' ' . $patient['last_name']); ?></h2>
                    <a href="patients_edit.php?id=<?php echo $patient['id']; ?>" class="absolute right-0 top-0 mt-1 mr-1 text-teal-300 hover:text-white transition-colors tooltip" title="Edit Patient"><i class="fa-solid fa-pen"></i></a>
                </div>
                <p class="text-teal-200 text-sm font-mono mt-1">MR: <?php echo esc($patient['mr_number']); ?></p>
            </div>
            
            <div class="p-6">
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-gray-500 font-medium">Gender</span>
                        <span class="text-gray-800 font-semibold"><?php echo esc($patient['gender']); ?></span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-gray-500 font-medium">Phone</span>
                        <span class="text-gray-800 font-semibold"><?php echo esc($patient['phone'] ?: 'N/A'); ?></span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-gray-500 font-medium">CNIC</span>
                        <span class="text-gray-800 font-mono"><?php echo esc($patient['cnic'] ?: 'N/A'); ?></span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-50 pb-3">
                        <span class="text-gray-500 font-medium">Spouse</span>
                        <span class="text-gray-800 font-semibold"><?php echo esc($patient['spouse_name'] ?: 'N/A'); ?></span>
                    </li>
                    <li class="flex justify-between items-center pb-1">
                        <span class="text-gray-500 font-medium">Hospital Ref</span>
                        <span class="text-gray-800 text-right"><?php echo esc($patient['hospital_name'] ?: 'Main Clinic'); ?></span>
                    </li>
                </ul>
                
                <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-3">
                    <a href="semen_analyses_add.php?patient_id=<?php echo $patient['id']; ?>" class="bg-sky-50 hover:bg-sky-100 text-sky-700 font-medium py-2 rounded-lg text-center text-sm transition-colors cursor-pointer border border-sky-100">
                        <i class="fa-solid fa-flask mb-1 block"></i> Semen Analysis
                    </a>
                    <a href="prescriptions_add.php?patient_id=<?php echo $patient['id']; ?>" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium py-2 rounded-lg text-center text-sm transition-colors cursor-pointer border border-indigo-100">
                        <i class="fa-solid fa-prescription mb-1 block"></i> Web Rx
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Tabs & Content -->
    <div class="w-full lg:w-2/3">
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo esc($error); ?>
            </div>
        <?php
endif; ?>
        <?php if (!empty($success)): ?>
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 border border-emerald-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> <?php echo esc($success); ?>
            </div>
        <?php
endif; ?>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 flex overflow-hidden">
            <button @click="currentTab = 'history'" :class="{'bg-teal-50 text-teal-700 font-bold border-b-2 border-teal-600': currentTab === 'history', 'text-gray-500 hover:bg-gray-50': currentTab !== 'history'}" class="flex-1 py-4 text-sm font-medium transition-colors">
                <i class="fa-solid fa-notes-medical mr-1"></i> History & Labs
            </button>
            <button @click="currentTab = 'semen'" :class="{'bg-teal-50 text-teal-700 font-bold border-b-2 border-teal-600': currentTab === 'semen', 'text-gray-500 hover:bg-gray-50': currentTab !== 'semen'}" class="flex-1 py-4 text-sm font-medium transition-colors">
                <i class="fa-solid fa-microscope mr-1"></i> Semen Reports
            </button>
            <button @click="currentTab = 'rx'" :class="{'bg-teal-50 text-teal-700 font-bold border-b-2 border-teal-600': currentTab === 'rx', 'text-gray-500 hover:bg-gray-50': currentTab !== 'rx'}" class="flex-1 py-4 text-sm font-medium transition-colors">
                <i class="fa-solid fa-pills mr-1"></i> Prescriptions
            </button>
            <button @click="currentTab = 'usg'" :class="{'bg-teal-50 text-teal-700 font-bold border-b-2 border-teal-600': currentTab === 'usg', 'text-gray-500 hover:bg-gray-50': currentTab !== 'usg'}" class="flex-1 py-4 text-sm font-medium transition-colors">
                <i class="fa-solid fa-image mr-1"></i> Ultrasounds
            </button>
            <button @click="currentTab = 'labs'" :class="{'bg-teal-50 text-teal-700 font-bold border-b-2 border-teal-600': currentTab === 'labs', 'text-gray-500 hover:bg-gray-50': currentTab !== 'labs'}" class="flex-1 py-4 text-sm font-medium transition-colors border-l border-gray-100">
                <i class="fa-solid fa-vials mr-1"></i> Lab Reports
            </button>
        </div>

        <!-- Tab 1: History -->
        <div x-show="currentTab === 'history'" x-cloak>
            
            <!-- Add History Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6" x-data="{ expanded: false }">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center cursor-pointer" @click="expanded = !expanded">
                    <h3 class="font-bold text-gray-800"><i class="fa-solid fa-plus-circle text-teal-600 mr-2"></i> Add Clinical Record / Labs</h3>
                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="expanded ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="expanded" x-collapse>
                    <div class="p-6">
                        <form method="POST">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div><label class="block text-xs text-gray-500 mb-1">LH</label><input type="text" name="lh" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">FSH</label><input type="text" name="fsh" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">Testosterone</label><input type="text" name="testosterone" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">Prolactin</label><input type="text" name="prolactin" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">Vit D</label><input type="text" name="vit_d" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">Iron</label><input type="text" name="iron" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                                <div><label class="block text-xs text-gray-500 mb-1">TB</label><input type="text" name="tb" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs text-gray-500 mb-1">Clinical Notes & Findings</label>
                                <textarea name="clinical_notes" rows="3" class="w-full px-3 py-2 border rounded-md focus:border-teal-500 outline-none text-sm"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" name="add_history" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-bold transition-colors">Save Record</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- History Timeline -->
            <div class="space-y-4">
                <?php if (empty($histories)): ?>
                    <div class="text-center py-8 text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">No history records found.</div>
                <?php
else:
    foreach ($histories as $h): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative pl-6">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-l-xl"></div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-1 rounded">
                                <?php echo date('d M Y, h:i A', strtotime($h['recorded_at'])); ?>
                            </span>
                        </div>
                        
                        <!-- Lab grid, only show if filled -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <?php
        $labs = ['LH' => $h['lh'], 'FSH' => $h['fsh'], 'Testo' => $h['testosterone'], 'Prolactin' => $h['prolactin'], 'Vit D' => $h['vit_d'], 'Iron' => $h['iron'], 'TB' => $h['tb']];
        foreach ($labs as $l => $v):
            if (!empty($v)):
?>
                            <div class="bg-gray-50 border border-gray-100 rounded px-2 py-1 text-xs">
                                <span class="text-gray-400 mr-1"><?php echo $l; ?>:</span><span class="font-semibold text-gray-700"><?php echo esc($v); ?></span>
                            </div>
                            <?php
            endif;
        endforeach; ?>
                        </div>
                        
                        <?php if (!empty($h['clinical_notes'])): ?>
                            <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed bg-yellow-50/50 p-3 rounded-md border border-yellow-100/50">
                                <?php echo esc($h['clinical_notes']); ?>
                            </div>
                        <?php
        endif; ?>
                    </div>
                <?php
    endforeach;
endif; ?>
            </div>

        </div>

        <!-- Tab 2: Semen Reports -->
        <div x-show="currentTab === 'semen'" x-cloak>
            <div class="space-y-4">
                <?php if (empty($semen_reports)): ?>
                    <div class="text-center py-8 text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">No semen analyses recorded yet.</div>
                <?php
else:
    foreach ($semen_reports as $sr): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex justify-between items-center hover:border-sky-200 transition-colors">
                        <div>
                            <div class="font-bold text-gray-800 mb-1">Analysis Report</div>
                            <div class="text-xs text-gray-500 mb-2">Collected: <?php echo date('d M Y', strtotime($sr['collection_time'])); ?></div>
                            <span class="text-xs font-mono bg-sky-50 text-sky-700 px-2 py-1 rounded border border-sky-100">
                                <?php echo esc($sr['auto_diagnosis'] ?: 'Diagnosis Pending'); ?>
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <a href="semen_analyses_print.php?id=<?php echo $sr['id']; ?>" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition-colors">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                <?php
    endforeach;
endif; ?>
            </div>
        </div>

        <!-- Tab 3: Prescriptions -->
        <div x-show="currentTab === 'rx'" x-cloak>
            <div class="space-y-4">
                <?php if (empty($prescriptions)): ?>
                    <div class="text-center py-8 text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">No prescriptions written yet.</div>
                <?php
else:
    foreach ($prescriptions as $rx): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex justify-between items-center hover:border-indigo-200 transition-colors">
                        <div>
                            <div class="font-bold text-gray-800 mb-1">Digital Prescription</div>
                            <div class="text-xs text-gray-500">Issued: <?php echo date('d M Y, h:i A', strtotime($rx['created_at'])); ?></div>
                        </div>
                        <div class="flex gap-2">
                            <?php if (!empty($rx['scanned_report_path'])): ?>
                                <a href="../<?php echo htmlspecialchars($rx['scanned_report_path']); ?>" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition-colors border border-gray-200" title="Original Scan">
                                    <i class="fa-solid fa-paperclip"></i>
                                </a>
                            <?php
        endif; ?>
                            <a href="prescriptions_print.php?id=<?php echo $rx['id']; ?>" target="_blank" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-sm transition-colors">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                <?php
    endforeach;
endif; ?>
            </div>
        </div>

        <!-- Tab 4: Ultrasounds -->
        <div x-show="currentTab === 'usg'" x-cloak>
            <div class="space-y-4">
                <?php if (empty($ultrasounds)): ?>
                    <div class="text-center py-8 text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">No ultrasound reports recorded yet.</div>
                <?php
else:
    foreach ($ultrasounds as $u): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex justify-between items-center hover:border-sky-200 transition-colors">
                        <div>
                            <div class="font-bold text-gray-800 mb-1"><?php echo esc($u['report_title']); ?></div>
                            <div class="text-xs text-gray-500">Recorded: <?php echo date('d M Y, h:i A', strtotime($u['created_at'])); ?></div>
                        </div>
                        <div class="flex gap-2">
                            <?php if (!empty($u['scanned_report_path'])): ?>
                                <a href="../<?php echo htmlspecialchars($u['scanned_report_path']); ?>" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition-colors border border-gray-200" title="Original Scan">
                                    <i class="fa-solid fa-paperclip"></i>
                                </a>
                            <?php
        endif; ?>
                            <a href="ultrasounds_print.php?id=<?php echo $u['id']; ?>" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm transition-colors">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                        </div>
                    <?php
    endforeach;
endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Tab 5: Lab Reports -->
        <div x-show="currentTab === 'labs'" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800"><i class="fa-solid fa-vials text-teal-600 mr-2"></i> External / Internal Lab Results</h3>
                    <a href="lab_results_add.php?patient_id=<?php echo $patient['id']; ?>" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Add Lab Result
                    </a>
                </div>
                
                <div class="div p-0">
                    <?php if (empty($lab_results)): ?>
                        <div class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-flask mb-2 text-3xl block"></i>
                            No lab test results have been recorded for this patient yet.
                        </div>
                    <?php
else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                        <th class="p-4 font-medium border-b border-gray-100">Test name</th>
                                        <th class="p-4 font-medium border-b border-gray-100">Result value</th>
                                        <th class="p-4 font-medium border-b border-gray-100">Reference / Normal</th>
                                        <th class="p-4 font-medium border-b border-gray-100">Date & Source</th>
                                        <th class="p-4 font-medium border-b border-gray-100 text-right"><i class="fa-solid fa-paperclip"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    <?php foreach ($lab_results as $lr): ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="p-4 font-semibold text-gray-900"><?php echo htmlspecialchars($lr['test_name']); ?></td>
                                        <td class="p-4">
                                            <span class="font-bold text-lg text-gray-900"><?php echo htmlspecialchars($lr['result_value']); ?></span>
                                            <span class="text-xs text-gray-500 font-mono ml-1"><?php echo htmlspecialchars($lr['unit']); ?></span>
                                        </td>
                                        <td class="p-4 text-gray-600 text-xs leading-relaxed"><?php echo $lr['reference_range'] ? nl2br(htmlspecialchars($lr['reference_range'])) : '-'; ?></td>
                                        <td class="p-4">
                                            <div class="font-medium text-gray-800"><?php echo date('d M Y', strtotime($lr['test_date'])); ?></div>
                                            <div class="text-[10px] text-gray-500 uppercase"><?php echo htmlspecialchars($lr['lab_name'] ?: 'In-House'); ?> <?php echo $lr['lab_city'] ? '- ' . htmlspecialchars($lr['lab_city']) : ''; ?></div>
                                        </td>
                                        <td class="p-4 text-right">
                                            <?php if (!empty($lr['scanned_report_path'])): ?>
                                                <a href="../<?php echo htmlspecialchars($lr['scanned_report_path']); ?>" target="_blank" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-xs font-bold transition-colors border border-indigo-100">
                                                    View PDF
                                                </a>
                                            <?php
        else: ?>
                                                <span class="text-gray-300">-</span>
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
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
