<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/libs/tcpdf/tcpdf.php";

$id = intval($_GET['id']);

$result = $conn->query("
SELECT sr.*, h.name as hospital_name, h.address, h.doctor_name, 
h.designation, h.license_number, h.watermark_path
FROM semen_reports sr
LEFT JOIN hospitals h ON sr.hospital_id = h.id
WHERE sr.id = $id
");

$row = $result->fetch_assoc();
if(!$row) die("Report not found");

$pdf = new TCPDF();
$pdf->SetMargins(15,20,15);
$pdf->AddPage();

/* ---------- WATERMARK ---------- */
if(!empty($row['watermark_path'])){
    $pdf->SetAlpha(0.07);
    $pdf->Image($_SERVER['DOCUMENT_ROOT'].'/'.$row['watermark_path'], 40, 80, 120);
    $pdf->SetAlpha(1);
}

/* ---------- HEADER ---------- */
$pdf->SetFont('helvetica','B',16);
$pdf->Cell(0,10,'SEMEN ANALYSIS REPORT',0,1,'C');

$pdf->SetFont('helvetica','',11);
$pdf->Cell(0,6,'Hospital: '.$row['hospital_name'],0,1);
$pdf->Cell(0,6,'Patient: '.$row['patient_name'].'    Age: '.$row['patient_age'],0,1);
$pdf->Cell(0,6,'Report #: '.$row['report_number'],0,1);
$pdf->Ln(5);

/* ---------- HELPER ---------- */
function abnormal_style($value,$threshold,$type='min'){
    if($type=='min' && $value<$threshold) return 'color:red;font-weight:bold;';
    if($type=='max' && $value>$threshold) return 'color:red;font-weight:bold;';
    return '';
}

/* ---------- TABLE ---------- */
$html = '
<style>
.section-title{
    font-size:13px;
    font-weight:bold;
    border-bottom:1px solid #000;
    padding-bottom:4px;
    margin-top:10px;
}
.table-clean td{
    padding:5px 0;
}
.abnormal{
    color:red;
    font-weight:bold;
}
</style>

<div style="text-align:center; font-size:16px; font-weight:bold;">
SEMEN ANALYSIS REPORT
</div>

<br>

Hospital: '.$row['hospital_name'].'<br>
Patient: '.$row['patient_name'].' &nbsp;&nbsp; Age: '.$row['patient_age'].'<br>
Report #: '.$row['report_number'].'<br><br>

<div class="section-title">MACROSCOPIC EXAMINATION</div>
<table width="100%" class="table-clean">
<tr>
<td width="40%">Volume</td>
<td width="30%" '.($row['volume']<1.4?'class="abnormal"':'').'>'.$row['volume'].' mL</td>
<td width="30%">≥ 1.4 mL</td>
</tr>
<tr>
<td>pH</td>
<td>'.$row['ph'].'</td>
<td>≥ 7.2</td>
</tr>
<tr>
<td>Liquefaction</td>
<td>'.$row['liquefaction'].'</td>
<td>≤ 60 min</td>
</tr>
</table>

<div class="section-title">SPERM CONCENTRATION & MOTILITY</div>
<table width="100%" class="table-clean">
<tr>
<td width="40%">Concentration</td>
<td width="30%" '.($row['concentration']<16?'class="abnormal"':'').'>'.$row['concentration'].' M/mL</td>
<td width="30%">≥ 16</td>
</tr>
<tr>
<td>Progressive</td>
<td '.($row['progressive']<30?'class="abnormal"':'').'>'.$row['progressive'].'%</td>
<td>≥ 30%</td>
</tr>
<tr>
<td>Total Motility</td>
<td '.($row['total_motility']<42?'class="abnormal"':'').'>'.$row['total_motility'].'%</td>
<td>≥ 42%</td>
</tr>
</table>

<br>
<b>INTERPRETATION:</b><br>
'.$row['interpretation'].'

<br><br>
Doctor: '.$row['doctor_name'].'<br>
'.$row['designation'].'
';

$pdf->writeHTML($html);
$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');