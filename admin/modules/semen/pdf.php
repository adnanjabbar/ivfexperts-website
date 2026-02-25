<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/report_helpers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/libs/tcpdf/tcpdf.php";

$id = intval($_GET['id']);

$result = $conn->query("
SELECT sr.*, h.name as hospital_name
FROM semen_reports sr
LEFT JOIN hospitals h ON sr.hospital_id = h.id
WHERE sr.id = $id
");

$row = $result->fetch_assoc();
if(!$row) die("Report not found");

/* ---------- PDF SETUP ---------- */
$pdf = new TCPDF('P','mm','A4',true,'UTF-8',false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(18,18,18);
$pdf->AddPage();

/* ---------- BASE FONT ---------- */
$pdf->SetFont('helvetica','',9.5);

/* ---------- LOGO SPACE ---------- */
/* Left Logo */
$pdf->Image($_SERVER['DOCUMENT_ROOT'].'/assets/images/logo.png',18,15,30);

/* Right Logo Placeholder (Hospital Logo if needed) */
/* $pdf->Image('path',160,15,30); */

$pdf->Ln(20);

/* ---------- TITLE ---------- */
$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0,6,'SEMEN ANALYSIS REPORT',0,1,'C');
$pdf->Ln(4);

/* ---------- PATIENT INFO ---------- */
$pdf->SetFont('helvetica','',9.5);

$pdf->SetFillColor(245,245,245);
$pdf->Cell(85,6,'Patient Name: '.$row['patient_name'],0,0,'L',1);
$pdf->Cell(85,6,'Sample No: '.$row['report_number'],0,1,'L',1);

$pdf->Cell(85,6,'Age: '.$row['patient_age'],0,0,'L',1);
$pdf->Cell(85,6,'Abstinence: '.$row['abstinence_days'].' days',0,1,'L',1);

$pdf->Ln(6);

/* ---------- SECTION BAR ---------- */
function sectionBar($pdf,$title){
    $pdf->SetFillColor(60,70,90);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('helvetica','B',10.5);
    $pdf->Cell(0,6,$title,0,1,'L',1);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('helvetica','',9.5);
}

/* ---------- TABLE HEADER ---------- */
function headerRow($pdf){
    $pdf->SetFillColor(230,230,230);
    $pdf->SetFont('helvetica','B',9.5);
    $pdf->Cell(65,6,'Parameter',1,0,'L',1);
    $pdf->Cell(35,6,'Result',1,0,'L',1);
    $pdf->Cell(55,6,'WHO 6th Edition Reference',1,1,'L',1);
    $pdf->SetFont('helvetica','',9.5);
}

/* ---------- ROW ---------- */
function dataRow($pdf,$label,$value,$ref,$bold=false){
    if($bold){
        $pdf->SetFont('helvetica','B',9.5);
    } else {
        $pdf->SetFont('helvetica','',9.5);
    }

    $pdf->Cell(65,6,$label,1);
    $pdf->Cell(35,6,$value,1);
    $pdf->Cell(55,6,$ref,1,1);
}

/* ---------- ABNORMAL CHECK ---------- */
function ab($value,$threshold,$type='min'){
    if($type=='min' && $value<$threshold) return true;
    if($type=='max' && $value>$threshold) return true;
    return false;
}

/* ---------- MACRO ---------- */
sectionBar($pdf,'MACROSCOPIC EXAMINATION');
headerRow($pdf);

dataRow($pdf,'Volume',$row['volume'].' mL','≥ 1.4 mL',ab($row['volume'],1.4));
dataRow($pdf,'pH',$row['ph'],'≥ 7.2');
dataRow($pdf,'Liquefaction',$row['liquefaction'],'Complete within 60 min');
dataRow($pdf,'Appearance',$row['appearance'],'Gray opalescent');
dataRow($pdf,'Viscosity',$row['viscosity'],'Normal');

$pdf->Ln(4);

/* ---------- CONCENTRATION ---------- */
sectionBar($pdf,'SPERM CONCENTRATION & MOTILITY');
headerRow($pdf);

dataRow($pdf,'Sperm Concentration',$row['concentration'].' M/mL','≥ 16 M/mL',ab($row['concentration'],16));
dataRow($pdf,'Total Sperm Count',$row['total_count'].' Million','≥ 39 Million',ab($row['total_count'],39));
dataRow($pdf,'Progressive Motility (PR)',$row['progressive'].'%','≥ 30%',ab($row['progressive'],30));
dataRow($pdf,'Total Motility (PR+NP)',$row['total_motility'].'%','≥ 42%',ab($row['total_motility'],42));

$pdf->Ln(4);

/* ---------- MORPHOLOGY ---------- */
sectionBar($pdf,'SPERM MORPHOLOGY (Kruger Strict Criteria)');
headerRow($pdf);

dataRow($pdf,'Normal Forms',$row['morphology'].'%','≥ 4%',ab($row['morphology'],4));
dataRow($pdf,'Vitality (Live Sperm)',$row['vitality'].'%','≥ 54%',ab($row['vitality'],54));
dataRow($pdf,'Round Cells',$row['round_cells'].' M/mL','< 5 M/mL');
dataRow($pdf,'WBC (Leukocytes)',$row['wbc'].' M/mL','< 1 M/mL',ab($row['wbc'],1,'max'));

$pdf->Ln(6);

/* ---------- INTERPRETATION ---------- */
$pdf->SetFillColor(240,240,240);
$pdf->SetFont('helvetica','B',9.5);
$pdf->Cell(0,6,'DIAGNOSIS & CLINICAL INTERPRETATION',0,1,'L',1);
$pdf->SetFont('helvetica','',9.5);
$pdf->MultiCell(0,6,$row['interpretation'],0,'L',1);

$pdf->Output('Semen_Report_'.$row['report_number'].'.pdf','I');