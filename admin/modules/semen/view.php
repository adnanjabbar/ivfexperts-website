<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";

$id = intval($_GET['id']);

$result = $conn->query("
    SELECT sr.*, h.name as hospital_name, h.address, h.doctor_name, h.designation
    FROM semen_reports sr
    LEFT JOIN hospitals h ON sr.hospital_id = h.id
    WHERE sr.id = $id
");

$row = $result->fetch_assoc();

if(!$row){
    echo "Report not found.";
    exit;
}
?>

<div class="bg-white p-8 rounded-xl shadow">

    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Semen Analysis Report</h1>
            <p class="text-sm text-gray-500"><?= $row['hospital_name'] ?></p>
        </div>
        <div class="text-right">
            <p><strong>Report #:</strong> <?= $row['report_number'] ?></p>
            <p><strong>Date:</strong> <?= date("d M Y", strtotime($row['created_at'])) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div><strong>Patient:</strong> <?= htmlspecialchars($row['patient_name']) ?></div>
        <div><strong>Age:</strong> <?= $row['patient_age'] ?></div>
    </div>

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Parameter</th>
                <th class="p-2 border">Result</th>
                <th class="p-2 border">WHO 6 Ref</th>
            </tr>
        </thead>
        <tbody>
<tr><td>Volume</td><td><?= $row['volume'] ?> mL</td><td>>= 1.4</td></tr>
<tr><td>pH</td><td><?= $row['ph'] ?></td><td>>= 7.2</td></tr>
<tr><td>Liquefaction</td><td><?= $row['liquefaction'] ?></td><td><= 60 min</td></tr>
<tr><td>Appearance</td><td><?= $row['appearance'] ?></td><td>Gray opalescent</td></tr>
<tr><td>Viscosity</td><td><?= $row['viscosity'] ?></td><td>Normal</td></tr>

<tr><td>Concentration</td><td><?= $row['concentration'] ?> M/mL</td><td>>= 16</td></tr>
<tr><td>Total Count</td><td><?= $row['total_count'] ?> Million</td><td>>= 39</td></tr>
<tr><td>Progressive</td><td><?= $row['progressive'] ?>%</td><td>>= 30%</td></tr>
<tr><td>Non Progressive</td><td><?= $row['non_progressive'] ?>%</td><td>-</td></tr>
<tr><td>Immotile</td><td><?= $row['immotile'] ?>%</td><td>-</td></tr>
<tr><td>Total Motility</td><td><?= $row['total_motility'] ?>%</td><td>>= 42%</td></tr>

<tr><td>Morphology</td><td><?= $row['morphology'] ?>%</td><td>>= 4%</td></tr>
<tr><td>Vitality</td><td><?= $row['vitality'] ?>%</td><td>>= 54%</td></tr>
<tr><td>Round Cells</td><td><?= $row['round_cells'] ?> M/mL</td><td>< 5</td></tr>
<tr><td>WBC</td><td><?= $row['wbc'] ?> M/mL</td><td>< 1</td></tr>
<tr><td>RBC</td><td><?= $row['rbc'] ?></td><td>-</td></tr>
</tbody>
    </table>

    <div class="mt-6 p-4 bg-gray-100 rounded">
        <strong>Interpretation:</strong>
        <div class="mt-2 text-lg font-semibold">
            <?= $row['interpretation'] ?>
        </div>
    </div>

    <div class="mt-6 flex gap-4">
        <a href="pdf.php?id=<?= $row['id'] ?>" 
           class="bg-green-600 text-white px-4 py-2 rounded">
           Download PDF
        </a>

        <a href="list.php" 
           class="bg-gray-500 text-white px-4 py-2 rounded">
           Back
        </a>
    </div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>