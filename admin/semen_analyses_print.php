<?php
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0)
    die("Invalid Report ID");

// Fetch SA Data
$sa = null;
try {
    $stmt = $conn->prepare("
        SELECT sa.*, p.first_name, p.last_name, p.mr_number, p.gender, p.phone, p.cnic, p.spouse_name,
               h.name as hospital_name, h.logo_path, h.digital_signature_path 
        FROM semen_analyses sa 
        JOIN patients p ON sa.patient_id = p.id 
        JOIN hospitals h ON sa.hospital_id = h.id 
        WHERE sa.id = ?
    ");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $sa = $stmt->get_result()->fetch_assoc();
    }
}
catch (Exception $e) {
    die("DB Error");
}

if (!$sa)
    die("Report not found.");

// Since this is a custom plain A4 layout (not using hospital letterhead margins, we supply logos ourselves)
$mt = 15;
$mb = 15;
$ml = 15;
$mr = 15;

// Calculated Totals
$total_motility = $sa['pr_motility'] + $sa['np_motility'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Semen Analysis #<?php echo $id; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page { size: A4; margin: <?php echo $mt; ?>mm <?php echo $mr; ?>mm <?php echo $mb; ?>mm <?php echo $ml; ?>mm; }
        body { background: #f3f4f6; -webkit-print-color-adjust: exact; color: #000; }
        .a4-container {
            width: 210mm; min-height: 297mm; background: #fff; margin: 0 auto;
            padding: <?php echo $mt; ?>mm <?php echo $mr; ?>mm <?php echo $mb; ?>mm <?php echo $ml; ?>mm;
            box-sizing: border-box; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        @media print {
            body { background: #fff; }
            .a4-container { width: auto; min-height: auto; box-shadow: none; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
        .sa-table th { padding: 6px; text-align: left; background: #f9fafb; font-size: 13px; text-transform: uppercase; border: 1px solid #e5e7eb; }
        .sa-table td { padding: 6px 10px; font-size: 14px; border: 1px solid #e5e7eb; }
        .red-flag { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body class="py-10 print:py-0">

    <div class="fixed top-4 right-4 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg shadow-lg font-bold">
            <i class="fa-solid fa-print"></i> Print Report
        </button>
        <button onclick="window.close()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg">Close</button>
    </div>

    <!-- The Actual Document -->
    <div class="a4-container flex flex-col relative pb-32 font-sans">
        
        <!-- Header: 2 Logos -->
        <div class="flex justify-between items-center mb-6 border-b-2 border-slate-800 pb-4">
            <!-- Left Side: IVF Experts Standard Logo (we pull from web root assets if exists, or text fallback) -->
            <div class="w-1/3">
                <img src="../assets/images/logo.png" alt="IVF Experts" class="h-16 object-contain" onerror="this.style.display='none'; document.getElementById('fb1').style.display='block';">
                <div id="fb1" style="display:none;" class="font-extrabold text-2xl text-blue-900 tracking-tight">IVF EXPERTS</div>
            </div>
            
            <div class="w-1/3 text-center">
                <h1 class="font-bold text-xl uppercase tracking-widest text-slate-800">Semen Analysis</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">WHO 6th Edition Standard</p>
            </div>
            
            <div class="w-1/3 flex justify-end">
                <?php if (!empty($sa['logo_path']) && $sa['logo_path'] !== 'assets/images/logo.png'): ?>
                    <img src="../<?php echo esc($sa['logo_path']); ?>" alt="<?php echo esc($sa['hospital_name']); ?>" class="h-16 object-contain">
                <?php
endif; ?>
            </div>
        </div>

        <!-- Patient Demographics Box -->
        <div class="border border-slate-300 rounded p-3 mb-6 grid grid-cols-2 gap-y-2 text-sm bg-slate-50">
            <div><span class="font-semibold w-24 inline-block text-slate-600">Patient Name:</span> <span class="font-bold text-lg"><?php echo esc($sa['first_name'] . ' ' . $sa['last_name']); ?></span></div>
            <div><span class="font-semibold w-32 inline-block text-slate-600">Spouse Name:</span> <span class="font-medium"><?php echo esc($sa['spouse_name'] ?: '-'); ?></span></div>
            <div><span class="font-semibold w-24 inline-block text-slate-600">MR Number:</span> <span class="font-mono text-[15px] font-bold"><?php echo esc($sa['mr_number']); ?></span></div>
            <div><span class="font-semibold w-32 inline-block text-slate-600">Referred By:</span> <span class="font-medium">Dr. Adnan Jabbar</span></div>
            <div class="col-span-2 border-t border-slate-200 mt-2 pt-2 grid grid-cols-3">
                <div><span class="font-semibold text-slate-600">Collection:</span> <?php echo date('d M Y, h:i A', strtotime($sa['collection_time'])); ?></div>
                <div><span class="font-semibold text-slate-600">Examination:</span> <?php echo $sa['examination_time'] ? date('d M Y, h:i A', strtotime($sa['examination_time'])) : '-'; ?></div>
                <div><span class="font-semibold text-slate-600">Abstinence:</span> <?php echo esc($sa['abstinence_days']); ?> Days</div>
            </div>
        </div>

        <!-- Macroscopic -->
        <h3 class="font-bold uppercase tracking-wider text-sm mb-2 text-slate-800 border-b border-slate-800 pb-1">Macroscopic Examination</h3>
        <table class="w-full sa-table mb-6">
            <tr>
                <td class="w-1/3">Volume</td>
                <td class="w-1/3 font-mono font-bold <?php echo($sa['volume'] > 0 && $sa['volume'] < 1.4) ? 'red-flag' : ''; ?>"><?php echo $sa['volume']; ?> ml</td>
                <td class="w-1/3 text-xs text-slate-500 italic">Reference: ≥ 1.4 ml</td>
            </tr>
            <tr>
                <td>pH</td>
                <td class="font-mono font-bold <?php echo($sa['ph'] > 0 && $sa['ph'] < 7.2) ? 'red-flag' : ''; ?>"><?php echo $sa['ph']; ?></td>
                <td class="text-xs text-slate-500 italic">Reference: ≥ 7.2</td>
            </tr>
            <tr>
                <td>Appearance / Color</td>
                <td class="font-mono font-bold">Greyish White</td>
                <td class="text-xs text-slate-500 italic">Reference: Normal</td>
            </tr>
            <tr>
                <td>Liquefaction</td>
                <td class="font-mono font-bold">< 60 mins</td>
                <td class="text-xs text-slate-500 italic">Reference: Normal</td>
            </tr>
        </table>

        <!-- Microscopic -->
        <h3 class="font-bold uppercase tracking-wider text-sm mb-2 text-slate-800 border-b border-slate-800 pb-1">Microscopic Examination</h3>
        <table class="w-full sa-table mb-6">
            <tr>
                <td class="w-1/3 font-bold bg-slate-100">Sperm Concentration</td>
                <td class="w-1/3 font-mono font-bold text-base <?php echo($sa['concentration'] > 0 && $sa['concentration'] < 16) ? 'red-flag' : ''; ?>"><?php echo $sa['concentration']; ?> <span class="text-xs">M/ml</span></td>
                <td class="w-1/3 text-xs text-slate-500 italic">Reference: ≥ 16 M/ml</td>
            </tr>
            <tr>
                <td>Progressive Motility (PR)</td>
                <td class="font-mono font-bold <?php echo($sa['pr_motility'] > 0 && $sa['pr_motility'] < 30) ? 'red-flag' : ''; ?>"><?php echo $sa['pr_motility']; ?> %</td>
                <td class="text-xs text-slate-500 italic">Reference: ≥ 30 %</td>
            </tr>
            <tr>
                <td>Non-Progressive Motility (NP)</td>
                <td class="font-mono font-bold"><?php echo $sa['np_motility']; ?> %</td>
                <td></td>
            </tr>
            <tr>
                <td>Immotility (IM)</td>
                <td class="font-mono font-bold"><?php echo $sa['im_motility']; ?> %</td>
                <td></td>
            </tr>
            <tr>
                <td class="font-bold bg-slate-100 text-blue-900 border-t-2 border-slate-300">Total Motility (PR + NP)</td>
                <td class="font-mono font-bold text-lg border-t-2 border-slate-300 <?php echo($total_motility > 0 && $total_motility < 42) ? 'red-flag' : 'text-blue-700'; ?>"><?php echo $total_motility; ?> %</td>
                <td class="text-xs text-slate-500 border-t-2 border-slate-300 italic">Reference: ≥ 42 %</td>
            </tr>
        </table>

        <!-- Morphology & Others -->
        <table class="w-full sa-table mb-6">
            <tr>
                <td class="w-1/3">Normal Morphology</td>
                <td class="w-1/3 font-mono font-bold <?php echo($sa['normal_morphology'] > 0 && $sa['normal_morphology'] < 4) ? 'red-flag' : ''; ?>"><?php echo $sa['normal_morphology']; ?> %</td>
                <td class="w-1/3 text-xs text-slate-500 italic">Reference: ≥ 4 %</td>
            </tr>
            <tr>
                <td>Abnormal Forms</td>
                <td class="font-mono font-bold"><?php echo $sa['abnormal_morphology']; ?> %</td>
                <td></td>
            </tr>
            <tr>
                <td>Pus Cells (WBC)</td>
                <td class="font-mono font-bold text-sm" colspan="2"><?php echo esc($sa['wbc'] ?: 'Nil'); ?></td>
            </tr>
            <tr>
                <td>Agglutination</td>
                <td class="font-mono font-bold text-sm" colspan="2"><?php echo esc($sa['agglutination'] ?: 'Nil'); ?></td>
            </tr>
        </table>

        <!-- Diagnosis Box -->
        <div class="mt-4 bg-slate-800 text-white rounded p-4 text-center border-4 border-double border-slate-500 shadow-inner">
            <h4 class="uppercase tracking-widest text-[10px] text-slate-300 mb-1">Conclusion / Diagnosis</h4>
            <div class="text-2xl font-bold tracking-wide break-words">
                <?php echo esc($sa['auto_diagnosis']); ?>
            </div>
        </div>

        <?php if (!empty($sa['admin_notes'])): ?>
            <div class="mt-4 p-3 bg-yellow-50 text-sm border border-yellow-200 text-slate-800 rounded">
                <span class="font-bold uppercase text-[10px] block mb-1">Physician Notes</span>
                <?php echo nl2br(esc($sa['admin_notes'])); ?>
            </div>
        <?php
endif; ?>

        <!-- Footer -->
        <div class="absolute bottom-6 left-0 right-0 flex justify-between items-end pb-4">
            
            <div class="flex items-center gap-3">
                <!-- QR Code points to Patient Portal for 2FA unlock -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo urlencode('https://ivfexperts.pk/portal/verify.php?hash=' . $sa['qrcode_hash']); ?>" alt="QR Code" class="w-20 h-20 border border-slate-800 p-1" />
                <div class="text-[10px] text-slate-500 w-48 leading-tight">
                    <span class="font-bold block text-slate-800">Scan for Secure PDF version</span>
                    Unlock code required via patient portal.
                </div>
            </div>

            <div class="text-center">
                <?php if (!empty($sa['digital_signature_path'])): ?>
                    <img src="../<?php echo esc($sa['digital_signature_path']); ?>" alt="Signature" class="h-20 mx-auto object-contain mb-1" />
                <?php
else: ?>
                    <div class="h-20 sm:w-48 text-gray-300 italic flex items-end justify-center pb-2 border-b border-gray-400">Unsigned Document</div>
                <?php
endif; ?>
                <div class="font-bold uppercase text-sm border-t border-slate-800 pt-1 w-48 mx-auto">Prof. Dr. Adnan Jabbar</div>
                <div class="text-[11px] text-slate-600">Male Infertility & IVF Specialist</div>
            </div>

        </div>

    </div>

</body>
</html>
