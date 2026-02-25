<?php
require("../config/db.php");
require("../tcpdf/tcpdf.php");

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM semen_reports WHERE id = $id");
$data = $result->fetch_assoc();

$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);

$html = "
<h1>IVF Experts</h1>
<h3>Semen Analysis Report</h3>
<hr>
<strong>Patient Name:</strong> {$data['patient_name']}<br>
<strong>Age:</strong> {$data['patient_age']}<br>
<strong>Abstinence:</strong> {$data['abstinence_days']} days<br><br>

<strong>Volume:</strong> {$data['volume']} ml<br>
<strong>Sperm Count:</strong> {$data['sperm_count']} million/ml<br>
<strong>Motility:</strong> {$data['motility']} %<br>
<strong>Morphology:</strong> {$data['morphology']} %<br><br>

<strong>Interpretation:</strong> {$data['interpretation']}<br><br>

<strong>Remarks:</strong><br>{$data['remarks']}
";

$pdf->writeHTML($html);
$pdf->Output("Semen_Report_{$data['patient_name']}.pdf", "I");