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
            <tr>
                <td class="p-2 border">Volume</td>
                <td class="p-2 border"><?= $row['volume'] ?> mL</td>
                <td class="p-2 border">≥ 1.4</td>
            </tr>
            <tr>
                <td class="p-2 border">Concentration</td>
                <td class="p-2 border"><?= $row['concentration'] ?> M/mL</td>
                <td class="p-2 border">≥ 16</td>
            </tr>
            <tr>
                <td class="p-2 border">Progressive</td>
                <td class="p-2 border"><?= $row['progressive'] ?> %</td>
                <td class="p-2 border">≥ 30%</td>
            </tr>
            <tr>
                <td class="p-2 border">Morphology</td>
                <td class="p-2 border"><?= $row['morphology'] ?> %</td>
                <td class="p-2 border">≥ 4%</td>
            </tr>
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