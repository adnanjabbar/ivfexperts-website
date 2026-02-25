<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";

require_once $_SERVER['DOCUMENT_ROOT']."/admin/libs/tcpdf/tcpdf.php";

$id = intval($_GET['id']);

$result = $conn->query("
    SELECT sr.*, h.name as hospital_name, h.address, h.doctor_name, h.designation
    FROM semen_reports sr
    LEFT JOIN hospitals h ON sr.hospital_id = h.id
    WHERE sr.id = $id
");

$row = $result->fetch_assoc();

if(!$row){
    die("Report not found");
}

$pdf = new TCPDF();
$pdf->SetCreator('IVF Experts');
$pdf->SetAuthor('Dr Adnan Jabbar');
$pdf->SetTitle('Semen Analysis Report');
$pdf->AddPage();

$html = '
<h2>Semen Analysis Report</h2>
<p><strong>Hospital:</strong> '.$row['hospital_name'].'</p>
<p><strong>Patient:</strong> '.$row['patient_name'].'</p>
<p><strong>Age:</strong> '.$row['patient_age'].'</p>
<p><strong>Report #:</strong> '.$row['report_number'].'</p>
<hr>
<table border="1" cellpadding="5">
<tr><th>Parameter</th><th>Result</th><th>WHO 6 Ref</th></tr>
<tr><td>Volume</td><td>'.$row['volume'].' mL</td><td>>= 1.4</td></tr>
<tr><td>Concentration</td><td>'.$row['concentration'].' M/mL</td><td>>= 16</td></tr>
<tr><td>Progressive</td><td>'.$row['progressive'].'%</td><td>>= 30%</td></tr>
<tr><td>Morphology</td><td>'.$row['morphology'].'%</td><td>>= 4%</td></tr>
</table>
<br>
<strong>Interpretation:</strong> '.$row['interpretation'].'
';

$pdf->writeHTML($html);
$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');