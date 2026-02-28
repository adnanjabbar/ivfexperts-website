<?php
$pageTitle = "Patient Registry";
require_once __DIR__ . '/includes/auth.php';

// Handle Search
$search = trim($_GET['q'] ?? '');
$patients = [];

try {
    if (!empty($search)) {
        $stmt = $conn->prepare("SELECT p.*, h.name as hospital_name FROM patients p LEFT JOIN hospitals h ON p.referring_hospital_id = h.id WHERE p.mr_number LIKE ? OR p.cnic LIKE ? OR p.phone LIKE ? OR p.first_name LIKE ? OR p.last_name LIKE ? ORDER BY p.id DESC LIMIT 50");
        $like = "%$search%";
        if ($stmt) {
            $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc())
                $patients[] = $row;
        }
    }
    else {
        $res = $conn->query("SELECT p.*, h.name as hospital_name FROM patients p LEFT JOIN hospitals h ON p.referring_hospital_id = h.id ORDER BY p.id DESC LIMIT 50");
        if ($res) {
            while ($row = $res->fetch_assoc())
                $patients[] = $row;
        }
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div class="w-full sm:w-96 relative">
        <form method="GET">
            <input type="text" name="q" value="<?php echo esc($search); ?>" placeholder="Search MR, CNIC, Phone, Name..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors bg-white">
            <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
        </form>
    </div>
    <a href="patients_add.php" class="shrink-0 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Register Patient
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">MR Number</th>
                    <th class="px-6 py-4">Patient Name</th>
                    <th class="px-6 py-4">Phone / CNIC</th>
                    <th class="px-6 py-4">Gender</th>
                    <th class="px-6 py-4">Referred By</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
                            No patients found.
                        </td>
                    </tr>
                <?php
else:
    foreach ($patients as $p): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-medium text-teal-700">
                            <?php echo esc($p['mr_number']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800"><?php echo esc($p['first_name'] . ' ' . $p['last_name']); ?></div>
                            <div class="text-xs text-gray-500">Spouse: <?php echo esc($p['spouse_name']); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div><i class="fa-solid fa-phone text-gray-400 w-4"></i> <?php echo esc($p['phone'] ?: 'N/A'); ?></div>
                            <div class="text-xs mt-1 text-gray-500"><i class="fa-regular fa-id-card text-gray-400 w-4"></i> <?php echo esc($p['cnic'] ?: 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                <?php echo esc($p['gender']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <?php echo esc($p['hospital_name'] ?: 'Direct / Walk-in'); ?>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="patients_view.php?id=<?php echo $p['id']; ?>" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-md font-medium transition-colors inline-block mr-2">
                                <i class="fa-regular fa-folder-open"></i> Open File
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
