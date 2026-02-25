<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/functions.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";

$hospitals = $conn->query("SELECT * FROM hospitals WHERE active=1");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $report_number = generate_report_number("semen");

    $patient_id = $_POST['patient_id'];
    $hospital_id = $_POST['hospital_id'];

    $volume = $_POST['volume'];
    $ph = $_POST['ph'];
    $concentration = $_POST['concentration'];
    $progressive = $_POST['progressive'];
    $morphology = $_POST['morphology'];

    $interpretation = classify_who6($_POST);

   $conn->query("INSERT INTO semen_reports 
(patient_id, hospital_id, report_number, abstinence, collection_method, appearance, liquefaction, viscosity, volume, ph, concentration, total_count, progressive, non_progressive, immotile, morphology, vitality, round_cells, wbc, rbc, agglutination, interpretation)
VALUES
('$patient_id','$hospital_id','$report_number',
'".$_POST['abstinence']."',
'".$_POST['collection_method']."',
'".$_POST['appearance']."',
'".$_POST['liquefaction']."',
'".$_POST['viscosity']."',
'$volume','$ph','$concentration',
'".$_POST['total_count']."',
'$progressive',
'".$_POST['non_progressive']."',
'".$_POST['immotile']."',
'$morphology',
'".$_POST['vitality']."',
'".$_POST['round_cells']."',
'".$_POST['wbc']."',
'".$_POST['rbc']."',
'".$_POST['agglutination']."',
'$interpretation')");
}
?>

<h1 class="text-2xl font-bold mb-6">WHO 6 Semen Analysis</h1>

<form method="POST" class="bg-white p-8 rounded-xl shadow space-y-6">

<div class="grid grid-cols-2 gap-6">

<select name="hospital_id" required class="border p-3 rounded">
<option value="">Select Hospital</option>
<?php while($h = $hospitals->fetch_assoc()): ?>
<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
<?php endwhile; ?>
</select>

<input type="text" name="patient_id" placeholder="Patient ID" required class="border p-3 rounded">

</div>

<hr>

<h2 class="font-semibold text-lg">Macroscopic Examination</h2>

<div class="grid grid-cols-3 gap-6">

<input type="text" name="abstinence" placeholder="Abstinence (days)" class="border p-3 rounded">
<input type="text" name="collection_method" placeholder="Collection Method" class="border p-3 rounded">
<input type="text" name="appearance" placeholder="Appearance" class="border p-3 rounded">

<input type="text" name="liquefaction" placeholder="Liquefaction Time" class="border p-3 rounded">
<input type="text" name="viscosity" placeholder="Viscosity" class="border p-3 rounded">
<input type="number" step="0.01" name="volume" placeholder="Volume (mL)" class="border p-3 rounded">

<input type="number" step="0.01" name="ph" placeholder="pH" class="border p-3 rounded">

</div>

<hr>

<h2 class="font-semibold text-lg">Microscopic Examination</h2>

<div class="grid grid-cols-3 gap-6">

<input type="number" step="0.01" name="concentration" placeholder="Concentration (M/mL)" class="border p-3 rounded">
<input type="number" step="0.01" name="total_count" placeholder="Total Count (Millions)" class="border p-3 rounded">

<input type="number" step="0.01" name="progressive" placeholder="Progressive Motility (%)" class="border p-3 rounded">
<input type="number" step="0.01" name="non_progressive" placeholder="Non-Progressive (%)" class="border p-3 rounded">
<input type="number" step="0.01" name="immotile" placeholder="Immotile (%)" class="border p-3 rounded">

<input type="number" step="0.01" name="morphology" placeholder="Morphology (%)" class="border p-3 rounded">
<input type="number" step="0.01" name="vitality" placeholder="Vitality (%)" class="border p-3 rounded">

<input type="number" step="0.01" name="round_cells" placeholder="Round Cells (M/mL)" class="border p-3 rounded">
<input type="number" step="0.01" name="wbc" placeholder="WBC (M/mL)" class="border p-3 rounded">
<input type="number" step="0.01" name="rbc" placeholder="RBC (M/mL)" class="border p-3 rounded">

<input type="text" name="agglutination" placeholder="Agglutination" class="border p-3 rounded">

</div>

<button class="bg-teal-600 text-white px-6 py-3 rounded-lg">
Generate Report
</button>

</form>

<?php include("../../includes/footer.php"); ?>