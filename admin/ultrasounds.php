<?php
$pageTitle = "Ultrasound Records";
require_once __DIR__ . '/includes/auth.php';

$msg = $_GET['msg'] ?? '';

// Fetch USG Records
$records = [];
try {
    $stmt = $conn->query("
        SELECT u.id, u.created_at, u.report_title, p.first_name, p.last_name, p.mr_number, h.name as hospital_name 
        FROM patient_ultrasounds u 
        JOIN patients p ON u.patient_id = p.id 
        LEFT JOIN hospitals h ON u.hospital_id = h.id 
        ORDER BY u.created_at DESC LIMIT 100
    ");
    if ($stmt) {
        while ($row = $stmt->fetch_assoc())
            $records[] = $row;
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Diagnostic Ultrasound Reports</h2>
    <div class="flex gap-2">
        <a href="ultrasound_templates.php" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-file-invoice"></i> Manage Templates
        </a>
        <a href="ultrasounds_add.php" class="bg-sky-600 hover:bg-sky-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Write Report
        </a>
    </div>
</div>

<?php if ($msg === 'saved'): ?>
    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 border border-emerald-100 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-xl"></i> 
        <span class="block font-bold">Ultrasound report finalized and saved successfully.</span>
    </div>
<?php
endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Patient</th>
                    <th class="px-6 py-4">Hospital Branch</th>
                    <th class="px-6 py-4">Report Type</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($records)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No ultrasound reports recorded yet.</td></tr>
                <?php
else:
    foreach ($records as $r): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800 block"><?php echo date('d M Y', strtotime($r['created_at'])); ?></span>
                            <span class="text-xs text-gray-500"><?php echo date('h:i A', strtotime($r['created_at'])); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800"><?php echo esc($r['first_name'] . ' ' . $r['last_name']); ?></div>
                            <div class="text-xs text-gray-500 font-mono">MR: <?php echo esc($r['mr_number']); ?></div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-sky-700">
                            <i class="fa-regular fa-hospital mr-1 text-sky-500"></i> <?php echo esc($r['hospital_name'] ?: 'Main Clinic'); ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-xs border border-sky-100 font-medium">
                                <?php echo esc($r['report_title']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="text-sky-600 hover:text-sky-900 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-md font-medium transition-colors inline-flex items-center gap-1 border border-sky-100" onclick="alert('Print layout engine loading in Phase 7')">
                                <i class="fa-solid fa-print"></i> Print (A4)
                            </a>
                        </td>
                    </tr>
                <?php
    endforeach;
endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
