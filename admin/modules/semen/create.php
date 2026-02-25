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

    $hospital_id = $_POST['hospital_id'] ?? 0;

    $patient_name = $_POST['patient_name'] ?? '';
    $patient_age = $_POST['patient_age'] ?? 0;
    $abstinence_days = $_POST['abstinence_days'] ?? 0;

    $volume = $_POST['volume'] ?? 0;
    $ph = $_POST['ph'] ?? 0;
    $concentration = $_POST['concentration'] ?? 0; // sperm_count
    $progressive = $_POST['progressive'] ?? 0;
    $non_progressive = $_POST['non_progressive'] ?? 0;
    $immotile = $_POST['immotile'] ?? 0;
    $morphology = $_POST['morphology'] ?? 0;
    $total_count = $_POST['total_count'] ?? 0;
    $total_motility = $_POST['total_motility'] ?? 0;
    $vitality = $_POST['vitality'] ?? 0;
    $round_cells = $_POST['round_cells'] ?? 0;
    $wbc = $_POST['wbc'] ?? 0;
    $rbc = $_POST['rbc'] ?? 0;

    $interpretation = classify_who6([
        'volume' => $volume,
        'concentration' => $concentration,
        'progressive' => $progressive,
        'morphology' => $morphology
    ]);

    $stmt = $conn->prepare("INSERT INTO semen_reports
    (hospital_id, report_number, patient_name, patient_age, abstinence_days,
     volume, ph, sperm_count, progressive, non_progressive, immotile,
     morphology, total_count, total_motility,
     vitality, round_cells, wbc, rbc,
     abstinence, collection_method, appearance,
     liquefaction, viscosity, agglutination,
     interpretation)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "issiiddddddddddddddssssss",
        $hospital_id,
        $report_number,
        $patient_name,
        $patient_age,
        $abstinence_days,
        $volume,
        $ph,
        $concentration,
        $progressive,
        $non_progressive,
        $immotile,
        $morphology,
        $total_count,
        $total_motility,
        $vitality,
        $round_cells,
        $wbc,
        $rbc,
        $_POST['abstinence'],
        $_POST['collection_method'],
        $_POST['appearance'],
        $_POST['liquefaction'],
        $_POST['viscosity'],
        $_POST['agglutination'],
        $interpretation
    );

    $stmt->execute();
    $stmt->close();

    header("Location: list.php");
    exit();
}
?>

<h1 class="text-2xl font-bold mb-6">WHO 6 Semen Analysis</h1>

<form method="POST" class="bg-white p-8 rounded-xl shadow space-y-6">

<div class="grid grid-cols-3 gap-6">

<select name="hospital_id" required class="border p-3 rounded">
<option value="">Select Hospital</option>
<?php while($h = $hospitals->fetch_assoc()): ?>
<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
<?php endwhile; ?>
</select>

<input type="text" name="patient_name" placeholder="Patient Name" required class="border p-3 rounded">
<input type="number" name="patient_age" placeholder="Age" class="border p-3 rounded">

<input type="number" name="abstinence_days" placeholder="Abstinence Days" class="border p-3 rounded">

</div>

<hr>

<h2 class="font-semibold text-lg">Macroscopic Examination</h2>

<div class="grid grid-cols-3 gap-6">

<input id="volume" type="number" step="0.01" name="volume" placeholder="Volume (mL)" class="border p-3 rounded">
<input id="ph" type="number" step="0.01" name="ph" placeholder="pH" class="border p-3 rounded">

<input type="text" name="appearance" placeholder="Appearance" class="border p-3 rounded">
<input type="text" name="liquefaction" placeholder="Liquefaction Time" class="border p-3 rounded">
<input type="text" name="viscosity" placeholder="Viscosity" class="border p-3 rounded">

<input type="text" name="collection_method" placeholder="Collection Method" class="border p-3 rounded">
<input type="text" name="abstinence" placeholder="Abstinence Period (text)" class="border p-3 rounded">

</div>

<hr>

<h2 class="font-semibold text-lg">Sperm Concentration & Motility</h2>

<div class="grid grid-cols-3 gap-6">

<input id="concentration" type="number" step="0.01" name="concentration" placeholder="Concentration (M/mL)" class="border p-3 rounded">

<input id="total_count" type="number" step="0.01" name="total_count" placeholder="Total Count (Auto)" readonly class="border p-3 rounded bg-gray-100">

<input id="progressive" type="number" step="0.01" name="progressive" placeholder="Progressive (%)" class="border p-3 rounded">

<input id="non_progressive" type="number" step="0.01" name="non_progressive" placeholder="Non-Progressive (%)" class="border p-3 rounded">

<input id="immotile" type="number" step="0.01" name="immotile" placeholder="Immotile (%)" readonly class="border p-3 rounded bg-gray-100">

<input id="total_motility" type="number" step="0.01" name="total_motility" placeholder="Total Motility (%)" readonly class="border p-3 rounded bg-gray-100">

</div>

<hr>

<h2 class="font-semibold text-lg">Morphology & Other Cells</h2>

<div class="grid grid-cols-3 gap-6">

<input id="morphology" type="number" step="0.01" name="morphology" placeholder="Morphology (%)" class="border p-3 rounded">

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

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>