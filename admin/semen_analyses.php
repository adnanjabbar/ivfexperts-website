<?php
$pageTitle = "Semen Analysis Registry";
require_once __DIR__ . '/includes/auth.php';

$msg = $_GET['msg'] ?? '';

// Fetch SA Records
$records = [];
try {
    $stmt = $conn->query("
        SELECT sa.id, sa.collection_time, sa.auto_diagnosis, sa.concentration, sa.pr_motility, sa.np_motility, p.first_name, p.last_name, p.mr_number, h.name as hospital_name 
        FROM semen_analyses sa 
        JOIN patients p ON sa.patient_id = p.id 
        LEFT JOIN hospitals h ON sa.hospital_id = h.id 
        ORDER BY sa.collection_time DESC LIMIT 100
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
    <h2 class="text-xl font-bold text-gray-800">Semen Analysis Registry</h2>
    <a href="semen_analyses_add.php" class="bg-sky-600 hover:bg-sky-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> New Report
    </a>
</div>

<?php if ($msg === 'saved'): ?>
    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 border border-emerald-100 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-xl"></i> 
        <span class="block font-bold">Advanced Semen Analysis record finalized and saved successfully.</span>
    </div>
<?php
endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Report Date</th>
                    <th class="px-6 py-4">Patient</th>
                    <th class="px-6 py-4">Diagnosis</th>
                    <th class="px-6 py-4">Key Metrics</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($records)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No semen analyses recorded yet.</td></tr>
                <?php
else:
    foreach ($records as $r):
        // formatting
        $isNormal = ($r['auto_diagnosis'] === 'Normozoospermia');
        $isAzoo = ($r['auto_diagnosis'] === 'Azoospermia');
        $theme = $isNormal ? 'emerald' : ($isAzoo ? 'red' : 'amber');
?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800 block"><?php echo date('d M Y', strtotime($r['collection_time'])); ?></span>
                            <span class="text-xs text-gray-500">ID: #SA-<?php echo str_pad($r['id'], 4, '0', STR_PAD_LEFT); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800"><?php echo esc($r['first_name'] . ' ' . $r['last_name']); ?></div>
                            <div class="text-xs text-gray-500 font-mono">MR: <?php echo esc($r['mr_number']); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-<?php echo $theme; ?>-50 text-<?php echo $theme; ?>-700 px-3 py-1 rounded border border-<?php echo $theme; ?>-200 font-bold text-xs">
                                <?php echo esc($r['auto_diagnosis']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            <div class="text-gray-500">Conc: <span class="text-gray-800 font-semibold"><?php echo $r['concentration']; ?> M/ml</span></div>
                            <div class="text-gray-500 mt-1">Motility: <span class="text-gray-800 font-semibold"><?php echo($r['pr_motility'] + $r['np_motility']); ?>%</span></div>
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
