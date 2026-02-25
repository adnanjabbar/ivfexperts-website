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

<div class="max-w-5xl mx-auto bg-white shadow-xl rounded-2xl p-10">

<!-- HEADER -->
<div class="border-b pb-6 mb-6">
    <h1 class="text-2xl font-bold text-center tracking-wide">
        SEMEN ANALYSIS REPORT
    </h1>

    <div class="mt-4 text-sm text-gray-700">
        <p><strong>Hospital:</strong> <?= $row['hospital_name'] ?></p>
        <p>
            <strong>Patient:</strong> <?= $row['patient_name'] ?>
            <span class="ml-8"><strong>Age:</strong> <?= $row['patient_age'] ?></span>
        </p>
        <p><strong>Report #:</strong> <?= $row['report_number'] ?></p>
    </div>
</div>

<!-- MACROSCOPIC -->
<h2 class="text-lg font-semibold border-b pb-2 mb-3">
MACROSCOPIC EXAMINATION
</h2>

<table class="w-full text-sm mb-8">
<tr class="font-semibold border-b">
<td class="py-2">Parameter</td>
<td>Result</td>
<td>WHO 6 Ref</td>
</tr>

<tr><td>Volume</td><td><?= $row['volume'] ?> mL</td><td>≥ 1.4 mL</td></tr>
<tr><td>pH</td><td><?= $row['ph'] ?></td><td>≥ 7.2</td></tr>
<tr><td>Liquefaction</td><td><?= $row['liquefaction'] ?></td><td>≤ 60 min</td></tr>
<tr><td>Appearance</td><td><?= $row['appearance'] ?></td><td>Gray opalescent</td></tr>
<tr><td>Viscosity</td><td><?= $row['viscosity'] ?></td><td>Normal</td></tr>
</table>

<!-- CONCENTRATION -->
<h2 class="text-lg font-semibold border-b pb-2 mb-3">
SPERM CONCENTRATION & MOTILITY
</h2>

<table class="w-full text-sm mb-8">
<tr class="font-semibold border-b">
<td>Parameter</td>
<td>Result</td>
<td>WHO 6th Edition Reference</td>
</tr>

<tr><td>Concentration</td><td><?= $row['concentration'] ?> M/mL</td><td>≥ 16</td></tr>
<tr><td>Total Count</td><td><?= $row['total_count'] ?> Million</td><td>≥ 39</td></tr>
<tr><td>Progressive</td><td><?= $row['progressive'] ?>%</td><td>≥ 30%</td></tr>
<tr><td>Non Progressive</td><td><?= $row['non_progressive'] ?>%</td><td>-</td></tr>
<tr><td>Immotile</td><td><?= $row['immotile'] ?>%</td><td>-</td></tr>
<tr><td>Total Motility</td><td><?= $row['total_motility'] ?>%</td><td>≥ 42%</td></tr>
</table>

<!-- MORPHOLOGY -->
<h2 class="text-lg font-semibold border-b pb-2 mb-3">
MORPHOLOGY & OTHER CELLS
</h2>

<table class="w-full text-sm mb-8">
<tr class="font-semibold border-b">
<td>Parameter</td>
<td>Result</td>
<td>WHO 6th Edition Reference</td>
</tr>

<tr><td>Normal Forms</td><td><?= $row['morphology'] ?>%</td><td>≥ 4%</td></tr>
<tr><td>Vitality</td><td><?= $row['vitality'] ?>%</td><td>≥ 54%</td></tr>
<tr><td>Round Cells</td><td><?= $row['round_cells'] ?> M/mL</td><td>&lt; 5</td></tr>
<tr><td>WBC</td><td><?= $row['wbc'] ?> M/mL</td><td>&lt; 1</td></tr>
<tr><td>RBC</td><td><?= $row['rbc'] ?></td><td>-</td></tr>
</table>

<!-- INTERPRETATION -->
<div class="bg-gray-100 p-6 rounded-xl">
    <h3 class="font-semibold mb-2">INTERPRETATION</h3>
    <p class="text-lg font-medium"><?= $row['interpretation'] ?></p>
</div>

<!-- DOCTOR -->
<div class="mt-8 text-sm">
    <strong>Doctor:</strong> <?= $row['doctor_name'] ?><br>
    <?= $row['designation'] ?><br>
    License #: <?= $row['license_number'] ?>
</div>

<div class="mt-8 flex gap-4">
    <a href="pdf.php?id=<?= $row['id'] ?>" 
       class="bg-green-600 text-white px-5 py-2 rounded-lg">
        Download PDF
    </a>

    <a href="edit.php?id=<?= $row['id'] ?>" 
       class="bg-blue-600 text-white px-5 py-2 rounded-lg">
        Edit Report
    </a>
</div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>