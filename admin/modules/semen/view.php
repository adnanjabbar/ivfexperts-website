<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";

$id = intval($_GET['id']);
$printMode = isset($_GET['print']);

$result = $conn->query("
    SELECT sr.*, h.name as hospital_name, h.address, 
           h.doctor_name, h.designation, h.license_number
    FROM semen_reports sr
    LEFT JOIN hospitals h ON sr.hospital_id = h.id
    WHERE sr.id = $id
");

$row = $result->fetch_assoc();

if(!$row){
    echo "Report not found.";
    exit;
}

/* Abnormal helper */
function abnormal($value, $threshold, $type='min'){
    if($value === null || $value === '') return false;
    if($type=='min' && $value < $threshold) return true;
    if($type=='max' && $value > $threshold) return true;
    return false;
}

/* ===============================
   LOAD LAYOUT ONLY IF NOT PRINT
================================= */

if(!$printMode){
    include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";
}
?>

<?php if(!$printMode): ?>
<div style="margin:20px;" class="no-print">
    <a href="?id=<?= $row['id'] ?>&print=1" 
       target="_blank"
       class="bg-teal-600 text-white px-5 py-2 rounded">
        Print / Save as PDF
    </a>

    <a href="edit.php?id=<?= $row['id'] ?>" 
       class="bg-blue-600 text-white px-5 py-2 rounded ml-3">
        Edit Report
    </a>

    <a href="list.php" 
       class="bg-gray-600 text-white px-5 py-2 rounded ml-3">
        Back
    </a>
</div>
<?php endif; ?>


<!-- ===============================
     CLEAN REPORT BODY
================================= -->

<div style="
    max-width: 900px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    font-size: 11pt;
    font-family: Arial, sans-serif;
">

    <div style="text-align:center; font-weight:bold; font-size:14pt; margin-bottom:20px;">
        SEMEN ANALYSIS REPORT
    </div>

    <!-- Patient Info -->
    <table width="100%" style="margin-bottom:20px; font-size:11pt;">
        <tr>
            <td><strong>Patient Name:</strong> <?= $row['patient_name'] ?></td>
            <td><strong>Report No:</strong> <?= $row['report_number'] ?></td>
        </tr>
        <tr>
            <td><strong>Age:</strong> <?= $row['patient_age'] ?></td>
            <td><strong>Abstinence Period:</strong> <?= $row['abstinence_days'] ?> days</td>
        </tr>
    </table>

    <!-- SECTION HEADER STYLE -->
    <div style="background:#2f3e52;color:white;padding:6px;font-weight:bold;margin-top:15px;">
        MACROSCOPIC EXAMINATION
    </div>

    <table width="100%" border="1" cellspacing="0" cellpadding="6">
        <tr style="background:#efefef;font-weight:bold;">
            <td>Parameter</td>
            <td>Result</td>
            <td>WHO 6th Edition Reference</td>
        </tr>

        <tr>
            <td>Volume</td>
            <td style="<?= abnormal($row['volume'],1.4)?'font-weight:bold;':'' ?>">
                <?= $row['volume'] ?> mL
            </td>
            <td>≥ 1.4 mL</td>
        </tr>

        <tr>
            <td>pH</td>
            <td><?= $row['ph'] ?></td>
            <td>≥ 7.2</td>
        </tr>

        <tr>
            <td>Liquefaction Time</td>
            <td><?= $row['liquefaction'] ?></td>
            <td>Complete within 60 min</td>
        </tr>

        <tr>
            <td>Appearance</td>
            <td><?= $row['appearance'] ?></td>
            <td>Gray opalescent</td>
        </tr>

        <tr>
            <td>Viscosity</td>
            <td><?= $row['viscosity'] ?></td>
            <td>Normal</td>
        </tr>
    </table>


    <!-- CONCENTRATION -->
    <div style="background:#2f3e52;color:white;padding:6px;font-weight:bold;margin-top:20px;">
        SPERM CONCENTRATION & MOTILITY
    </div>

    <table width="100%" border="1" cellspacing="0" cellpadding="6">
        <tr style="background:#efefef;font-weight:bold;">
            <td>Parameter</td>
            <td>Result</td>
            <td>WHO 6th Edition Reference</td>
        </tr>

        <tr>
            <td>Sperm Concentration</td>
            <td style="<?= abnormal($row['concentration'],16)?'font-weight:bold;':'' ?>">
                <?= $row['concentration'] ?> M/mL
            </td>
            <td>≥ 16 M/mL</td>
        </tr>

        <tr>
            <td>Total Sperm Count</td>
            <td style="<?= abnormal($row['total_count'],39)?'font-weight:bold;':'' ?>">
                <?= $row['total_count'] ?> Million
            </td>
            <td>≥ 39 Million</td>
        </tr>

        <tr>
            <td>Progressive Motility</td>
            <td style="<?= abnormal($row['progressive'],30)?'font-weight:bold;':'' ?>">
                <?= $row['progressive'] ?> %
            </td>
            <td>≥ 30 %</td>
        </tr>

        <tr>
            <td>Total Motility</td>
            <td style="<?= abnormal($row['total_motility'],42)?'font-weight:bold;':'' ?>">
                <?= $row['total_motility'] ?> %
            </td>
            <td>≥ 42 %</td>
        </tr>
    </table>


    <!-- INTERPRETATION -->
    <div style="margin-top:20px;font-weight:bold;">
        DIAGNOSIS & CLINICAL INTERPRETATION
    </div>

    <div style="border:1px solid #ccc;padding:10px;margin-top:5px;">
        <?= $row['interpretation'] ?>
    </div>

    <!-- Doctor -->
    <div style="margin-top:30px;font-size:10pt;">
        <strong>Doctor:</strong> <?= $row['doctor_name'] ?><br>
        <?= $row['designation'] ?><br>
        License #: <?= $row['license_number'] ?>
    </div>

</div>

<?php if(!$printMode){
    include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php";
} ?>