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

    $volume = floatval($_POST['volume']);
    $ph = $_POST['ph'] ?? 0;
    $concentration = floatval($_POST['concentration']);
    $progressive = floatval($_POST['progressive']);
    $non_progressive = floatval($_POST['non_progressive']);
    $immotile = 100 - $total_motility;
    if($immotile < 0) $immotile = 0;
    $morphology = floatval($_POST['morphology']);
    $total_count = $volume * $concentration;
    $total_motility = $progressive + $non_progressive;
    $vitality = $_POST['vitality'] ?? 0;
    $round_cells = $_POST['round_cells'] ?? 0;
    $wbc = $_POST['wbc'] ?? 0;
    $rbc = $_POST['rbc'] ?? 0;

    $interpretation = classify_who6([
    'volume' => $volume,
    'concentration' => $concentration,
    'progressive' => $progressive,
    'total_motility' => $total_motility,
    'morphology' => $morphology
]);

    $stmt = $conn->prepare("INSERT INTO semen_reports
    (hospital_id, report_number, patient_name, patient_age, abstinence_days,
     volume, ph, concentration, progressive, non_progressive, immotile,
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

<h2 class="font-semibold text-lg mt-6">Macroscopic Examination</h2>

<div class="grid grid-cols-3 gap-4 text-sm font-semibold border-b pb-2">
    <div>Parameter</div>
    <div>Result</div>
    <div>WHO 6 Reference</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center mt-2">
    <div>Volume (mL)</div>
    <input id="volume" type="number" step="0.01" name="volume" class="border p-2 rounded">
    <div>≥ 1.4 mL</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>pH</div>
    <input id="ph" type="number" step="0.01" name="ph" class="border p-2 rounded">
    <div>≥ 7.2</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Liquefaction Time</div>
    <input type="text" name="liquefaction" class="border p-2 rounded">
    <div>Complete within 60 min</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Appearance</div>
    <input type="text" name="appearance" class="border p-2 rounded">
    <div>Gray opalescent</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Viscosity</div>
    <input type="text" name="viscosity" class="border p-2 rounded">
    <div>Normal</div>
</div>

<hr>

<h2 class="font-semibold text-lg mt-8">Sperm Concentration & Motility</h2>

<div class="grid grid-cols-3 gap-4 text-sm font-semibold border-b pb-2">
    <div>Parameter</div>
    <div>Result</div>
    <div>WHO 6 Reference</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center mt-2">
    <div>Sperm Concentration (M/mL)</div>
    <input id="concentration" type="number" step="0.01" name="concentration" class="border p-2 rounded">
    <div>≥ 16 M/mL</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Total Sperm Count (Million)</div>
    <input id="total_count" type="number" step="0.01" name="total_count" readonly class="border p-2 rounded bg-gray-100">
    <div>≥ 39 Million/ejaculate</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Progressive Motility (%)</div>
    <input id="progressive" type="number" step="0.01" name="progressive" class="border p-2 rounded">
    <div>≥ 30%</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Non-Progressive (%)</div>
    <input id="non_progressive" type="number" step="0.01" name="non_progressive" class="border p-2 rounded">
    <div>-</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Immotile (%)</div>
    <input id="immotile" type="number" step="0.01" name="immotile" readonly class="border p-2 rounded bg-gray-100">
    <div>-</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Total Motility (PR + NP)</div>
    <input id="total_motility" type="number" step="0.01" name="total_motility" readonly class="border p-2 rounded bg-gray-100">
    <div>≥ 42%</div>
</div>

<hr>

<h2 class="font-semibold text-lg mt-8">Morphology & Other Cells</h2>

<div class="grid grid-cols-3 gap-4 text-sm font-semibold border-b pb-2">
    <div>Parameter</div>
    <div>Result</div>
    <div>WHO 6 Reference</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center mt-2">
    <div>Normal Forms (%)</div>
    <input id="morphology" type="number" step="0.01" name="morphology" class="border p-2 rounded">
    <div>≥ 4%</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Vitality (%)</div>
    <input type="number" step="0.01" name="vitality" class="border p-2 rounded">
    <div>≥ 54%</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>Round Cells (M/mL)</div>
    <input type="number" step="0.01" name="round_cells" class="border p-2 rounded">
    <div>&lt; 5 M/mL</div>
</div>

<div class="grid grid-cols-3 gap-4 items-center">
    <div>WBC (M/mL)</div>
    <input type="number" step="0.01" name="wbc" class="border p-2 rounded">
    <div>&lt; 1 M/mL</div>
</div>

<!-- ================= LIVE DIAGNOSIS ================= -->

<div id="diagnosisBox" class="mt-6 p-4 rounded-xl bg-gray-100 border">
    <strong>Live WHO6 Diagnosis:</strong>
    <div id="diagnosisText" class="mt-2 text-lg font-semibold text-gray-700">
        Awaiting values...
    </div>
</div>

<button class="bg-teal-600 text-white px-6 py-3 rounded-lg mt-4">
Generate Report
</button>

</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>