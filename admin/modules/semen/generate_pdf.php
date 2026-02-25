<?php
require("../../../tcpdf/tcpdf.php");
require("../../../config/db.php");
require("../../includes/report_helpers.php");

$id = $_GET['id'];

$report = $conn->query("SELECT * FROM semen_reports WHERE id=$id")->fetch_assoc();
$hospital = $conn->query("SELECT * FROM hospitals WHERE id=".$report['hospital_id'])->fetch_assoc();

$pdf = new TCPDF();
$pdf->AddPage();

$logoLeft = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo.png';
if (file_exists($logoLeft)) {
    $pdf->Image($logoLeft, 10, 10, 40);
}

if ($hospital['logo_path']) {
    $pdf->Image($_SERVER['DOCUMENT_ROOT'].$hospital['logo_path'], 150, 10, 40);
}

$pdf->Ln(30);

$pdf->SetFont('helvetica','B',14);
$pdf->Cell(0,10,"Semen Analysis Report",0,1,'C');

$pdf->SetFont('helvetica','',11);
$pdf->Ln(5);

$table = '
<table border="1" cellpadding="6">
<tr>
<th>Parameter</th>
<th>Result</th>
<th>Reference (WHO 6)</th>
</tr>
<tr>
<td>Volume</td>
<td>'.($report['volume'] < 1.4 ? '<b><span style="color:red">'.$report['volume'].'</span></b>' : $report['volume']).'</td>
<td>>= 1.4 mL</td>
</tr>
<tr>
<td>Concentration</td>
<td>'.($report['concentration'] < 16 ? '<b><span style="color:red">'.$report['concentration'].'</span></b>' : $report['concentration']).'</td>
<td>>= 16 M/mL</td>
</tr>
<tr>
<td>Progressive Motility</td>
<td>'.($report['progressive'] < 30 ? '<b><span style="color:red">'.$report['progressive'].'</span></b>' : $report['progressive']).'</td>
<td>>= 30%</td>
</tr>
<tr>
<td>Morphology</td>
<td>'.($report['morphology'] < 4 ? '<b><span style="color:red">'.$report['morphology'].'</span></b>' : $report['morphology']).'</td>
<td>>= 4%</td>
</tr>
</table>
';

$pdf->writeHTML($table, true, false, false, false, '');

$pdf->Ln(10);

$pdf->Cell(0,10,"Interpretation: ".$report['interpretation'],0,1);

$pdf->Ln(15);

$pdf->Cell(0,10,$hospital['doctor_name'],0,1);
$pdf->Cell(0,10,$hospital['designation'],0,1);
$pdf->Cell(0,10,"License No: ".$hospital['license_number'],0,1);

$pdf->Output("report.pdf","I");