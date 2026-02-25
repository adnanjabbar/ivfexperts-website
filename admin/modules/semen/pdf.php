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
$pdf->SetMargins(18,20,18);
$pdf->AddPage();
$pdf->SetFont('helvetica','',10);

/* Helper for abnormal bold only */
function ab($value,$threshold,$type='min'){
    if($type=='min' && $value<$threshold) return true;
    if($type=='max' && $value>$threshold) return true;
    return false;
}

/* ---------- TITLE ---------- */
$pdf->SetFont('helvetica','B',14);
$pdf->Cell(0,8,'SEMEN ANALYSIS REPORT',0,1,'C');
$pdf->Ln(3);

$pdf->SetLineWidth(0.4);
$pdf->Line(18,$pdf->GetY(),192,$pdf->GetY());
$pdf->Ln(6);

/* ---------- PATIENT INFO BOX ---------- */
$pdf->SetFillColor(245,245,245);
$pdf->Cell(87,7,'Patient Name: '.$row['patient_name'],0,0,'L',1);
$pdf->Cell(87,7,'MR Number: -',0,1,'L',1);

$pdf->Cell(87,7,'Age: '.$row['patient_age'],0,0,'L',1);
$pdf->Cell(87,7,'Sample Number: '.$row['report_number'],0,1,'L',1);

$pdf->Cell(87,7,'Collection Date: -',0,0,'L',1);
$pdf->Cell(87,7,'Collection Time: -',0,1,'L',1);

$pdf->Cell(87,7,'Abstinence Period: '.$row['abstinence_days'].' days',0,0,'L',1);
$pdf->Cell(87,7,'Collection Method: '.$row['collection_method'],0,1,'L',1);

$pdf->Ln(8);

/* ---------- SECTION FUNCTION ---------- */
function section($pdf,$title){
    $pdf->SetFillColor(40,60,90);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica','B',10);
    $pdf->Cell(0,7,$title,0,1,'L',1);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica','',10);
}

/* ---------- TABLE HEADER ---------- */
function tableHeader($pdf){
    $pdf->SetFillColor(230,230,230);
    $pdf->SetFont('helvetica','B',10);
    $pdf->Cell(70,7,'Parameter',1,0,'L',1);
    $pdf->Cell(40,7,'Result',1,0,'L',1);
    $pdf->Cell(60,7,'WHO 6th Edition Reference',1,1,'L',1);
    $pdf->SetFont('helvetica','',10);
}

/* ---------- ROW ---------- */
function row($pdf,$label,$value,$ref,$bold=false){
    if($bold){
        $pdf->SetFont('helvetica','B',10);
    } else {
        $pdf->SetFont('helvetica','',10);
    }
    $pdf->Cell(70,7,$label,1);
    $pdf->Cell(40,7,$value,1);
    $pdf->Cell(60,7,$ref,1,1);
}

/* ---------- MACRO ---------- */
section($pdf,'MACROSCOPIC EXAMINATION');
tableHeader($pdf);

row($pdf,'Volume',$row['volume'].' mL','&ge; 1.4 mL',ab($row['volume'],1.4));
row($pdf,'pH',$row['ph'],'&ge; 7.2');
row($pdf,'Liquefaction Time',$row['liquefaction'],'Complete within 60 min');
row($pdf,'Appearance',$row['appearance'],'Gray opalescent');
row($pdf,'Viscosity',$row['viscosity'],'Normal');

$pdf->Ln(5);

/* ---------- CONCENTRATION ---------- */
section($pdf,'SPERM CONCENTRATION & MOTILITY');
tableHeader($pdf);

row($pdf,'Sperm Concentration',$row['concentration'].' M/mL','&ge; 16 M/mL',ab($row['concentration'],16));
row($pdf,'Total Sperm Count',$row['total_count'].' Million/ejaculate','&ge; 39 Million/ejaculate',ab($row['total_count'],39));
row($pdf,'Progressive Motility (PR)',$row['progressive'].'%','&ge; 30%',ab($row['progressive'],30));
row($pdf,'Total Motility (PR+NP)',$row['total_motility'].'%','&ge; 42%',ab($row['total_motility'],42));

$pdf->Ln(5);

/* ---------- MORPHOLOGY ---------- */
section($pdf,'SPERM MORPHOLOGY (Kruger Strict Criteria)');
tableHeader($pdf);

row($pdf,'Normal Forms',$row['morphology'].'%','&ge; 4%',ab($row['morphology'],4));
row($pdf,'Vitality (Live Sperm)',$row['vitality'].'%','&ge; 54%',ab($row['vitality'],54));
row($pdf,'Round Cells',$row['round_cells'].' M/mL','&lt; 5 M/mL');
row($pdf,'WBC (Leukocytes)',$row['wbc'].' M/mL','&lt; 1 M/mL',ab($row['wbc'],1,'max'));

$pdf->Ln(8);

/* ---------- INTERPRETATION BOX ---------- */
$pdf->SetFillColor(245,245,245);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(0,7,'DIAGNOSIS & CLINICAL INTERPRETATION',0,1,'L',1);

$pdf->SetFont('helvetica','',10);
$pdf->MultiCell(0,7,$row['interpretation'],0,'L',1);

$pdf->Ln(8);

/* ---------- FOOTER ---------- */
$pdf->SetFont('helvetica','',9);
$pdf->MultiCell(0,6,
"Reference Standards:\nThis report follows the World Health Organization (WHO) Laboratory Manual for the Examination and Processing of Human Semen, 6th Edition (2021)."
);

$pdf->Ln(15);

$pdf->Cell(80,6,'Analyzed by (Embryologist)','T',0,'L');
$pdf->Cell(20);
$pdf->Cell(80,6,'Verified by (Lab Director)','T',1,'L');

$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');