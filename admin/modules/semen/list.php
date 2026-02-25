<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";

$result = $conn->query("
    SELECT sr.*, h.name AS hospital_name 
    FROM semen_reports sr
    LEFT JOIN hospitals h ON sr.hospital_id = h.id
    ORDER BY sr.created_at DESC
");
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Semen Analysis Reports</h1>
    <a href="create.php" class="bg-teal-600 text-white px-4 py-2 rounded-lg shadow">
        + New Report
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-3">Report #</th>
                <th class="p-3">Patient</th>
                <th class="p-3">Hospital</th>
                <th class="p-3">Date</th>
                <th class="p-3">Interpretation</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium"><?= $row['report_number'] ?></td>
                    <td class="p-3"><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($row['hospital_name']) ?></td>
                    <td class="p-3"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                    <td class="p-3">
                        <?php
                            $badge = "bg-green-100 text-green-700";
                            if(stripos($row['interpretation'], 'oligo') !== false ||
                               stripos($row['interpretation'], 'azoospermia') !== false ||
                               stripos($row['interpretation'], 'abnormal') !== false){
                                $badge = "bg-red-100 text-red-700";
                            }
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $badge ?>">
                            <?= $row['interpretation'] ?>
                        </span>
                    </td>
                    <td class="p-3 text-right space-x-2">
                        <a href="view.php?id=<?= $row['id'] ?>" 
                           class="text-blue-600 hover:underline">View</a>
                        <a href="pdf.php?id=<?= $row['id'] ?>" 
                           class="text-green-600 hover:underline">PDF</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="p-6 text-center text-gray-500">
                    No reports found.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>