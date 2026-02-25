<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php'; // TCPDF if installed

$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM semen_reports WHERE id = $id");
$row = $result->fetch_assoc();

if(!$row){
    die("Report not found");
}

$pdf = new TCPDF();
$pdf->AddPage();

$html = "
<h2>Semen Analysis Report</h2>
<p><strong>Patient:</strong> {$row['patient_name']}</p>
<p><strong>Age:</strong> {$row['patient_age']}</p>
<p><strong>Report #:</strong> {$row['report_number']}</p>
<br>
<table border='1' cellpadding='5'>
<tr><th>Parameter</th><th>Result</th></tr>
<tr><td>Volume</td><td>{$row['volume']} mL</td></tr>
<tr><td>Concentration</td><td>{$row['concentration']} M/mL</td></tr>
<tr><td>Progressive</td><td>{$row['progressive']}%</td></tr>
<tr><td>Morphology</td><td>{$row['morphology']}%</td></tr>
</table>
<br>
<p><strong>Interpretation:</strong> {$row['interpretation']}</p>
";

$pdf->writeHTML($html);
$pdf->Output("report.pdf","I");