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

    // BASIC INFO
    $hospital_id      = intval($_POST['hospital_id']);
    $mr_number        = trim($_POST['mr_number']);
    $patient_name     = trim($_POST['patient_name']);
    $patient_age      = intval($_POST['patient_age']);
    $abstinence_days  = intval($_POST['abstinence_days']);

    $collection_datetime = $_POST['collection_datetime'] ?: NULL;
    $analysis_datetime   = $_POST['analysis_datetime'] ?: NULL;

    // MACROSCOPIC
    $volume         = floatval($_POST['volume']);
    $ph             = floatval($_POST['ph']);
    $liquefaction   = $_POST['liquefaction'] ?? '';
    $appearance     = $_POST['appearance'] ?? '';
    $viscosity      = $_POST['viscosity'] ?? '';
    $collection_method = $_POST['collection_method'] ?? '';

    // MICROSCOPIC
    $concentration      = floatval($_POST['concentration']);
    $progressive        = floatval($_POST['progressive']);
    $non_progressive    = floatval($_POST['non_progressive']);
    $total_motility     = $progressive + $non_progressive;
    $immotile           = 100 - $total_motility;
    if($immotile < 0) $immotile = 0;

    $total_count    = $volume * $concentration;

    $morphology     = floatval($_POST['morphology']);
    $head_defects   = floatval($_POST['head_defects']);
    $midpiece_defects = floatval($_POST['midpiece_defects']);
    $tail_defects   = floatval($_POST['tail_defects']);

    $vitality       = floatval($_POST['vitality']);
    $round_cells    = floatval($_POST['round_cells']);
    $wbc            = floatval($_POST['wbc']);
    $rbc            = floatval($_POST['rbc']);
    $agglutination  = $_POST['agglutination'] ?? '';

    $sample_quality = $_POST['sample_quality'] ?? '';
    $recommended_for = $_POST['recommended_for'] ?? '';
    $clinical_note  = $_POST['clinical_note'] ?? '';

    // AUTO MR if empty
    if(empty($mr_number)){
        $mr_number = "IVF-MR-".date("Y")."-".rand(1000,9999);
    }

    // INTERPRETATION
    $interpretation = classify_who6([
        'volume' => $volume,
        'concentration' => $concentration,
        'progressive' => $progressive,
        'morphology' => $morphology,
        'wbc' => $wbc
    ]);

    $stmt = $conn->prepare("
INSERT INTO semen_reports (
    hospital_id, report_number, mr_number,
    patient_name, patient_age, abstinence_days,
    collection_datetime, analysis_datetime,
    volume, ph, liquefaction, appearance, viscosity, collection_method,
    concentration, progressive, non_progressive, immotile,
    total_motility, total_count,
    morphology, head_defects, midpiece_defects, tail_defects,
    vitality, round_cells, wbc, rbc, agglutination,
    sample_quality, recommended_for, clinical_note,
    interpretation
)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "isssiissddsssddddddddddddddssss",
    $hospital_id,
    $report_number,
    $mr_number,
    $patient_name,
    $patient_age,
    $abstinence_days,
    $collection_datetime,
    $analysis_datetime,
    $volume,
    $ph,
    $liquefaction,
    $appearance,
    $viscosity,
    $collection_method,
    $concentration,
    $progressive,
    $non_progressive,
    $immotile,
    $total_motility,
    $total_count,
    $morphology,
    $head_defects,
    $midpiece_defects,
    $tail_defects,
    $vitality,
    $round_cells,
    $wbc,
    $rbc,
    $agglutination,
    $sample_quality,
    $recommended_for,
    $clinical_note,
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

<div class="grid grid-cols-4 gap-4">
<select name="hospital_id" required class="input">
<option value="">Select Hospital</option>
<?php while($h = $hospitals->fetch_assoc()): ?>
<option value="<?= $h['id'] ?>"><?= $h['name'] ?></option>
<?php endwhile; ?>
</select>

<input type="text" name="mr_number" placeholder="MR Number (optional)" class="input">
<input type="text" name="patient_name" required placeholder="Patient Name" class="input">
<input type="number" name="patient_age" required placeholder="Age" class="input">
<input type="number" name="abstinence_days" placeholder="Abstinence Days" class="input">
<input type="datetime-local" name="collection_datetime" class="input">
<input type="datetime-local" name="analysis_datetime" class="input">
</div>

<hr>

<h3 class="section-title">MACROSCOPIC EXAMINATION</h3>
<div class="grid grid-cols-3 gap-4">
<input id="volume" type="number" step="0.01" name="volume" placeholder="Volume ≥ 1.4 mL" class="input">
<input type="number" step="0.01" name="ph" placeholder="pH ≥ 7.2" class="input">
<input type="text" name="liquefaction" placeholder="Liquefaction ≤ 60 min" class="input">
<input type="text" name="appearance" placeholder="Appearance" class="input">
<input type="text" name="viscosity" placeholder="Viscosity" class="input">
<input type="text" name="collection_method" placeholder="Collection Method" class="input">
</div>

<hr>

<h3 class="section-title">SPERM CONCENTRATION & MOTILITY</h3>
<div class="grid grid-cols-3 gap-4">
<input id="concentration" type="number" step="0.01" name="concentration" placeholder="Concentration ≥ 16 M/mL" class="input">
<input id="progressive" type="number" step="0.01" name="progressive" placeholder="Progressive ≥ 30%" class="input">
<input id="non_progressive" type="number" step="0.01" name="non_progressive" placeholder="Non Progressive %" class="input">
<input id="immotile" type="number" step="0.01" name="immotile" placeholder="Immotile %" class="input">
<input id="total_motility" type="number" step="0.01" name="total_motility" placeholder="Total Motility ≥ 42%" class="input">
</div>

<hr>

<h3 class="section-title">SPERM MORPHOLOGY (Strict Criteria)</h3>
<div class="grid grid-cols-3 gap-4">
<input type="number" step="0.01" name="morphology" placeholder="Normal Forms ≥ 4%" class="input">
<input type="number" step="0.01" name="head_defects" placeholder="Head Defects %" class="input">
<input type="number" step="0.01" name="midpiece_defects" placeholder="Midpiece Defects %" class="input">
<input type="number" step="0.01" name="tail_defects" placeholder="Tail Defects %" class="input">
<input type="number" step="0.01" name="vitality" placeholder="Vitality ≥ 54%" class="input">
</div>

<hr>

<h3 class="section-title">OTHER CELLS</h3>
<div class="grid grid-cols-3 gap-4">
<input type="number" step="0.01" name="round_cells" placeholder="Round Cells < 5 M/mL" class="input">
<input type="number" step="0.01" name="wbc" placeholder="WBC < 1 M/mL" class="input">
<input type="number" step="0.01" name="rbc" placeholder="RBC" class="input">
<input type="text" name="agglutination" placeholder="Agglutination" class="input">
</div>

<hr>

<h3 class="section-title">DIAGNOSIS & CLINICAL INTERPRETATION</h3>
<div class="grid grid-cols-2 gap-4">
<select name="sample_quality" class="input">
<option value="">Sample Quality</option>
<option>Good</option>
<option>Moderate</option>
<option>Poor</option>
</select>
<input type="text" name="recommended_for" placeholder="Recommended For" class="input">
<textarea name="clinical_note" placeholder="Clinical Note / Recommendation"
class="input col-span-2 h-28"></textarea>
</div>

<button class="bg-teal-600 text-white px-6 py-3 rounded-lg mt-6">
Generate Report
</button>

</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/admin/includes/footer.php"; ?>