<?php
require("../config/db.php");
require("auth.php");
require("who6.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['patient_name'];
    $age = $_POST['patient_age'];
    $abstinence = $_POST['abstinence_days'];
    $volume = $_POST['volume'];
    $count = $_POST['sperm_count'];
    $motility = $_POST['motility'];
    $morphology = $_POST['morphology'];
    $remarks = $_POST['remarks'];

    $interpretation = classify_semen($count, $motility, $morphology);

    $stmt = $conn->prepare("INSERT INTO semen_reports (patient_name, patient_age, abstinence_days, volume, sperm_count, motility, morphology, interpretation, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiidddss", $name, $age, $abstinence, $volume, $count, $motility, $morphology, $interpretation, $remarks);
    $stmt->execute();

    header("Location: generate_pdf.php?id=" . $stmt->insert_id);
    exit();
}
?>

<h2>Create Semen Analysis Report</h2>

<form method="post">
<input type="text" name="patient_name" placeholder="Patient Name" required><br>
<input type="number" name="patient_age" placeholder="Age"><br>
<input type="number" name="abstinence_days" placeholder="Abstinence Days"><br>
<input type="number" step="0.1" name="volume" placeholder="Volume (ml)"><br>
<input type="number" step="0.1" name="sperm_count" placeholder="Sperm Count (million/ml)"><br>
<input type="number" step="0.1" name="motility" placeholder="Total Motility (%)"><br>
<input type="number" step="0.1" name="morphology" placeholder="Normal Morphology (%)"><br>
<textarea name="remarks" placeholder="Doctor Remarks"></textarea><br>
<button type="submit">Generate Report</button>
</form>