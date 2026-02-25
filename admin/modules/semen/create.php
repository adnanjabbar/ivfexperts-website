<?php
require("../../../config/db.php");
require("../../includes/auth.php");
require("../../includes/functions.php");
require("../../includes/report_helpers.php");
include("../../includes/header.php");

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
        (patient_id, hospital_id, report_number, volume, ph, concentration, progressive, morphology, interpretation)
        VALUES 
        ('$patient_id','$hospital_id','$report_number','$volume','$ph','$concentration','$progressive','$morphology','$interpretation')");

    header("Location: view.php?id=" . $conn->insert_id);
}
?>

<h1 class="text-2xl font-bold mb-6">New WHO6 Semen Report</h1>

<form method="POST" class="grid grid-cols-2 gap-6 bg-white p-8 rounded-xl shadow">

<select name="hospital_id" required class="border p-3 rounded">
<option value="">Select Hospital</option>
<?php while($h = $hospitals->fetch_assoc()): ?>
<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
<?php endwhile; ?>
</select>

<input type="text" name="patient_id" placeholder="Patient ID" required class="border p-3 rounded">

<input type="number" step="0.01" name="volume" placeholder="Volume (mL)" class="border p-3 rounded">
<input type="number" step="0.01" name="ph" placeholder="pH" class="border p-3 rounded">

<input type="number" step="0.01" name="concentration" placeholder="Concentration (M/mL)" class="border p-3 rounded">
<input type="number" step="0.01" name="progressive" placeholder="Progressive Motility (%)" class="border p-3 rounded">

<input type="number" step="0.01" name="morphology" placeholder="Morphology (%)" class="border p-3 rounded">

<button class="col-span-2 bg-teal-600 text-white py-3 rounded-lg">
Generate Report
</button>

</form>

<?php include("../../includes/footer.php"); ?>