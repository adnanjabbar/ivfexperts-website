<?php
$pageTitle = "Manage Hospitals & Clinics";
require_once __DIR__ . '/includes/auth.php';

// Handle Delete (optional, but probably shouldn't allow deleting hospitals if linked to patients. Let's just list them)

// Fetch Hospitals
$hospitals = [];
try {
    $res = $conn->query("SELECT * FROM hospitals ORDER BY name ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $hospitals[] = $row;
        }
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Hospital & Clinic Management</h2>
        <p class="text-gray-500 text-sm mt-1">Configure logos, digital signatures, and custom print layout margins per location.</p>
    </div>
    <a href="hospitals_edit.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add New Hospital
    </a>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'saved'): ?>
    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6 flex gap-2 items-center border border-emerald-100 shadow-sm">
        <i class="fa-solid fa-circle-check text-lg mt-0.5"></i> <span class="font-bold">Hospital details saved successfully!</span>
    </div>
<?php
endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-[11px] tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Hospital Name & Layout</th>
                    <th class="px-6 py-4 text-center">Brand Logo</th>
                    <th class="px-6 py-4 text-center">Digital Signature</th>
                    <th class="px-6 py-4">Letterhead Margins (T / B / L / R)</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($hospitals)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No hospitals found. Add one above.</td></tr>
                <?php
else:
    foreach ($hospitals as $h): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800 text-base flex items-center gap-2">
                                <i class="fa-regular fa-hospital text-indigo-400"></i> <?php echo esc($h['name']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if (!empty($h['logo_path'])): ?>
                                <img src="../<?php echo esc($h['logo_path']); ?>" alt="Logo" class="h-10 mx-auto object-contain bg-gray-100 p-1 rounded border border-gray-200" title="Custom Logo Active">
                            <?php
        else: ?>
                                <span class="text-xs text-gray-400 italic">IVF Experts Default</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if (!empty($h['digital_signature_path'])): ?>
                                <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-2 py-1 rounded text-xs font-bold whitespace-nowrap"><i class="fa-solid fa-check mr-1"></i> Uploaded</span>
                            <?php
        else: ?>
                                <span class="bg-red-50 border border-red-200 text-red-600 px-2 py-1 rounded text-xs whitespace-nowrap">Missing</span>
                            <?php
        endif; ?>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-600 whitespace-nowrap">
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded border border-gray-200"><?php echo esc($h['margin_top'] ?? '20mm'); ?></span> / 
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded border border-gray-200"><?php echo esc($h['margin_bottom'] ?? '20mm'); ?></span> / 
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded border border-gray-200"><?php echo esc($h['margin_left'] ?? '20mm'); ?></span> / 
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded border border-gray-200"><?php echo esc($h['margin_right'] ?? '20mm'); ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="hospitals_edit.php?id=<?php echo $h['id']; ?>" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md font-medium transition-colors inline-block border border-indigo-100">
                                Configure <i class="fa-solid fa-arrow-right ml-1"></i>
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
