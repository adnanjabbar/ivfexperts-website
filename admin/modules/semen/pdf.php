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
<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th width="40%">Parameter</th>
<th width="30%">Result</th>
<th width="30%">WHO 6 Ref</th>
</tr>

<tr>
<td>Volume</td>
<td style="'.abnormal_style($row['volume'],1.4).'">'.$row['volume'].' mL</td>
<td>≥ 1.4 mL</td>
</tr>

<tr>
<td>Concentration</td>
<td style="'.abnormal_style($row['concentration'],16).'">'.$row['concentration'].' M/mL</td>
<td>≥ 16 M/mL</td>
</tr>

<tr>
<td>Progressive Motility</td>
<td style="'.abnormal_style($row['progressive'],30).'">'.$row['progressive'].'%</td>
<td>≥ 30%</td>
</tr>

<tr>
<td>Total Motility</td>
<td style="'.abnormal_style($row['total_motility'],42).'">'.$row['total_motility'].'%</td>
<td>≥ 42%</td>
</tr>

<tr>
<td>Morphology (Normal Forms)</td>
<td style="'.abnormal_style($row['morphology'],4).'">'.$row['morphology'].'%</td>
<td>≥ 4%</td>
</tr>

<tr>
<td>Vitality</td>
<td style="'.abnormal_style($row['vitality'],54).'">'.$row['vitality'].'%</td>
<td>≥ 54%</td>
</tr>

<tr>
<td>WBC</td>
<td style="'.abnormal_style($row['wbc'],1,"max").'">'.$row['wbc'].' M/mL</td>
<td>&lt; 1 M/mL</td>
</tr>

</table>

<br><br>

<b>INTERPRETATION:</b><br>
<div style="font-size:13px; padding:8px; background-color:#f5f5f5;">
'.$row['interpretation'].'
</div>

<br><br>

<b>Doctor:</b> '.$row['doctor_name'].'<br>
'.$row['designation'].'<br>
License #: '.$row['license_number'].'
';

$pdf->writeHTML($html);
$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');