<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/libs/tcpdf/tcpdf.php";

$id = intval($_GET['id']);

$result = $conn->query("
SELECT sr.*, h.name as hospital_name, h.address, 
h.doctor_name, h.designation, h.license_number
FROM semen_reports sr
LEFT JOIN hospitals h ON sr.hospital_id = h.id
WHERE sr.id = $id
");

$row = $result->fetch_assoc();
if(!$row) die("Report not found");

$pdf = new TCPDF();
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(20,20,20);
$pdf->AddPage();
$pdf->SetFont('helvetica','',11);

/* ---------- HELPER ---------- */
function ab($value,$threshold,$type='min'){
    if($type=='min' && $value<$threshold) return true;
    if($type=='max' && $value>$threshold) return true;
    return false;
}

/* ---------- TITLE ---------- */
$pdf->SetFont('helvetica','B',16);
$pdf->Cell(0,8,'SEMEN ANALYSIS REPORT',0,1,'C');
$pdf->Ln(2);

$pdf->SetLineWidth(0.3);
$pdf->Line(20,$pdf->GetY(),190,$pdf->GetY());
$pdf->Ln(6);

/* ---------- PATIENT BLOCK ---------- */
$pdf->SetFont('helvetica','',11);
$pdf->Cell(0,6,'Hospital: '.$row['hospital_name'],0,1);
$pdf->Cell(0,6,'Patient: '.$row['patient_name'].'      Age: '.$row['patient_age'],0,1);
$pdf->Cell(0,6,'Report #: '.$row['report_number'],0,1);
$pdf->Ln(6);

/* ---------- SECTION FUNCTION ---------- */
function sectionTitle($pdf,$title){
    $pdf->SetFont('helvetica','B',12);
    $pdf->Cell(0,7,$title,0,1);
    $pdf->SetLineWidth(0.2);
    $pdf->Line(20,$pdf->GetY(),190,$pdf->GetY());
    $pdf->Ln(4);
    $pdf->SetFont('helvetica','',11);
}

function rowLine($pdf,$label,$value,$ref,$isAb=false){
    if($isAb){
        $pdf->SetTextColor(200,0,0);
        $pdf->SetFont('helvetica','B',11);
    } else {
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('helvetica','',11);
    }

    $pdf->Cell(70,6,$label,0,0);
    $pdf->Cell(40,6,$value,0,0);
    $pdf->Cell(40,6,$ref,0,1);
}

/* ---------- MACRO ---------- */
sectionTitle($pdf,'MACROSCOPIC EXAMINATION');

rowLine($pdf,'Volume',$row['volume'].' mL','≥ 1.4 mL',ab($row['volume'],1.4));
rowLine($pdf,'pH',$row['ph'],'≥ 7.2');
rowLine($pdf,'Liquefaction',$row['liquefaction'],'≤ 60 min');
rowLine($pdf,'Appearance',$row['appearance'],'Gray opalescent');
rowLine($pdf,'Viscosity',$row['viscosity'],'Normal');

$pdf->Ln(6);

/* ---------- CONCENTRATION ---------- */
sectionTitle($pdf,'SPERM CONCENTRATION & MOTILITY');

rowLine($pdf,'Concentration',$row['concentration'].' M/mL','≥ 16',ab($row['concentration'],16));
rowLine($pdf,'Total Count',$row['total_count'].' Million','≥ 39',ab($row['total_count'],39));
rowLine($pdf,'Progressive',$row['progressive'].'%','≥ 30%',ab($row['progressive'],30));
rowLine($pdf,'Total Motility',$row['total_motility'].'%','≥ 42%',ab($row['total_motility'],42));

$pdf->Ln(6);

/* ---------- MORPHOLOGY ---------- */
sectionTitle($pdf,'MORPHOLOGY & OTHER CELLS');

rowLine($pdf,'Normal Forms',$row['morphology'].'%','≥ 4%',ab($row['morphology'],4));
rowLine($pdf,'Vitality',$row['vitality'].'%','≥ 54%',ab($row['vitality'],54));
rowLine($pdf,'WBC',$row['wbc'].' M/mL','< 1 M/mL',ab($row['wbc'],1,'max'));
rowLine($pdf,'RBC',$row['rbc'],'-');

$pdf->Ln(8);

/* ---------- INTERPRETATION ---------- */
$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'INTERPRETATION',0,1);
$pdf->Ln(2);

$pdf->SetFont('helvetica','',11);
$pdf->MultiCell(0,6,$row['interpretation']);
$pdf->Ln(8);

/* ---------- DOCTOR BLOCK ---------- */
$pdf->SetFont('helvetica','',10);
$pdf->MultiCell(0,6,
"Doctor: ".$row['doctor_name'].
"\n".$row['designation'].
"\nLicense #: ".$row['license_number']
);

$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');