<?php
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0)
    die("Invalid Prescription ID");

// Fetch Prescription Data
$rx = null;
try {
    $stmt = $conn->prepare("
        SELECT rx.*, p.first_name, p.last_name, p.mr_number, p.gender, p.phone, p.cnic, 
               h.name as hospital_name, h.margin_top, h.margin_bottom, h.margin_left, h.margin_right, h.digital_signature_path 
        FROM prescriptions rx 
        JOIN patients p ON rx.patient_id = p.id 
        JOIN hospitals h ON rx.hospital_id = h.id 
        WHERE rx.id = ?
    ");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $rx = $stmt->get_result()->fetch_assoc();
    }
}
catch (Exception $e) {
    die("DB Error");
}

if (!$rx)
    die("Prescription not found.");

// Fetch Items
$items = [];
try {
    $stmt = $conn->prepare("SELECT pi.*, m.name, m.med_type FROM prescription_items pi JOIN medications m ON pi.medication_id = m.id WHERE pi.prescription_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc())
            $items[] = $row;
    }
}
catch (Exception $e) {
}

// Setup Margins explicitly to handle pre-printed hospital letterheads
$mt = $rx['margin_top'] ?? '40mm';
$mb = $rx['margin_bottom'] ?? '30mm';
$ml = $rx['margin_left'] ?? '20mm';
$mr = $rx['margin_right'] ?? '20mm';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescription #<?php echo $id; ?></title>
    <!-- Tailwind via CDN for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page {
            size: A4;
            margin: <?php echo $mt; ?> <?php echo $mr; ?> <?php echo $mb; ?> <?php echo $ml; ?>;
        }
        body {
            background-color: #f3f4f6; /* Gray background on screen */
            -webkit-print-color-adjust: exact;
            color: #000;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            margin: 0 auto;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: <?php echo $mt; ?> <?php echo $mr; ?> <?php echo $mb; ?> <?php echo $ml; ?>;
            box-sizing: border-box;
        }
        @media print {
            body { background: #fff; }
            .a4-container {
                width: auto;
                min-height: auto;
                box-shadow: none;
                padding: 0; /* Let @page handle margins */
                margin: 0;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="py-10 print:py-0">

    <!-- Screen-only controls -->
    <div class="fixed top-4 right-4 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow-lg font-bold">
            <i class="fa-solid fa-print"></i> Print on Letterhead
        </button>
        <button onclick="window.close()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg">
            Close
        </button>
    </div>

    <!-- The Actual Document -->
    <div class="a4-container flex flex-col relative pb-32">
        
        <!-- Patient Demographics Block -->
        <div class="border-b-2 border-gray-300 pb-2 mb-3 flex justify-between items-end px-2">
            <div>
                <table class="text-[11px] leading-tight">
                    <tr><td class="font-bold pr-2 text-gray-500 py-0.5">Patient Name:</td><td class="font-bold text-gray-800 text-[12px] uppercase py-0.5"><?php echo esc($rx['first_name'] . ' ' . $rx['last_name']); ?></td></tr>
                    <tr><td class="font-bold pr-2 text-gray-500 py-0.5">MR Number:</td><td class="font-mono font-bold text-indigo-800 py-0.5"><?php echo esc($rx['mr_number']); ?></td></tr>
                    <tr><td class="font-bold pr-2 text-gray-500 py-0.5">Gender / Phone:</td><td class="py-0.5"><?php echo esc($rx['gender']); ?> / <?php echo esc($rx['phone'] ?: 'N/A'); ?></td></tr>
                </table>
            </div>
            <div class="text-right text-[11px]">
                <div class="font-bold text-gray-500">Date Printed</div>
                <div class="font-bold text-gray-800 mb-0.5"><?php echo date('d M Y'); ?></div>
                <div class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">RX-<?php echo str_pad($id, 6, '0', STR_PAD_LEFT); ?></div>
            </div>
        </div>

        <!-- Clinical Assessment -->
        <div class="mb-4 text-[11px] px-2 bg-gray-50 border border-gray-100 p-2 rounded">
            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                <?php if (!empty($rx['presenting_complaint'])): ?>
                    <div class="col-span-2 flex">
                        <span class="font-bold text-gray-500 uppercase text-[10px] w-32 shrink-0">History/Complaint:</span>
                        <span class="font-medium text-gray-800 flex-grow"><?php echo esc($rx['presenting_complaint']); ?></span>
                    </div>
                <?php
endif; ?>
                
                <?php if (!empty($rx['icd_disease'])): ?>
                    <div class="flex">
                        <span class="font-bold text-gray-500 uppercase text-[10px] w-24 shrink-0">Diagnosis:</span>
                        <span class="font-medium text-emerald-800 flex-grow">
                            <?php if (!empty($rx['icd_code']))
        echo '<strong class="mr-1">[' . esc($rx['icd_code']) . ']</strong>'; ?>
                            <?php echo esc($rx['icd_disease']); ?>
                        </span>
                    </div>
                <?php
endif; ?>

                <?php if (!empty($rx['cpt_procedure'])): ?>
                    <div class="flex">
                        <span class="font-bold text-gray-500 uppercase text-[10px] w-24 shrink-0">Procedure:</span>
                        <span class="font-medium text-indigo-800 flex-grow">
                            <?php if (!empty($rx['cpt_code']))
        echo '<strong class="mr-1">[' . esc($rx['cpt_code']) . ']</strong>'; ?>
                            <?php echo esc($rx['cpt_procedure']); ?>
                        </span>
                    </div>
                <?php
endif; ?>
            </div>
        </div>

        <!-- Big Rx Symbol -->
        <div class="mb-2 px-2 mt-4">
            <span class="text-4xl font-serif italic font-bold text-slate-800 pr-2">Rx</span><span class="text-[10px] text-gray-400 uppercase tracking-widest inline-block align-top mt-1">Prescription Form</span>
        </div>

        <!-- Medication List Table -->
        <div class="flex-grow mb-6 px-2">
            <?php if (empty($items)): ?>
                <p class="text-[11px] text-gray-500 mx-2 italic">No medications prescribed.</p>
            <?php
else: ?>
                <table class="w-full text-left border-collapse border border-gray-200 shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 text-[10px] uppercase tracking-wider">
                            <th class="p-1.5 border border-gray-300 w-8 text-center">Sr.</th>
                            <th class="p-1.5 border border-gray-300 w-1/3">Medicine Name</th>
                            <th class="p-1.5 border border-gray-300">Dosage</th>
                            <th class="p-1.5 border border-gray-300">Frequency</th>
                            <th class="p-1.5 border border-gray-300">Duration</th>
                            <th class="p-1.5 border border-gray-300 w-1/4">Instructions</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px]">
                        <?php foreach ($items as $idx => $item): ?>
                            <tr class="<?php echo $idx % 2 == 0 ? 'bg-white' : 'bg-gray-50'; ?>">
                                <td class="p-1.5 border border-gray-200 text-center font-bold text-gray-500"><?php echo $idx + 1; ?>.</td>
                                <td class="p-1.5 border border-gray-200 font-bold text-gray-900 text-[12px] uppercase">
                                    <?php echo esc($item['name']); ?>
                                    <?php if (!empty($item['med_type'])): ?>
                                        <span class="text-[9px] font-normal text-gray-500 ml-1 italic capitalize">(<?php echo esc($item['med_type']); ?>)</span>
                                    <?php
        endif; ?>
                                </td>
                                <td class="p-1.5 border border-gray-200 font-medium text-gray-800"><?php echo esc($item['dosage'] ?: '-'); ?></td>
                                <td class="p-1.5 border border-gray-200 font-bold text-indigo-700"><?php echo esc($item['usage_frequency'] ?: '-'); ?></td>
                                <td class="p-1.5 border border-gray-200 font-medium whitespace-nowrap text-gray-800"><?php echo esc($item['duration'] ?: '-'); ?></td>
                                <td class="p-1.5 border border-gray-200 text-[10px] text-gray-700 font-medium italic"><?php echo esc($item['instructions'] ?: '-'); ?></td>
                            </tr>
                        <?php
    endforeach; ?>
                    </tbody>
                </table>
            <?php
endif; ?>
        </div>

        <!-- General Notes -->
        <?php if (!empty($rx['notes'])): ?>
            <div class="mt-2 pt-2 border-t border-gray-300 px-2">
                <h3 class="text-[10px] font-bold uppercase tracking-wider text-gray-600 mb-1">Advice / General Notes</h3>
                <p class="text-[11px] whitespace-pre-wrap leading-snug"><?php echo esc($rx['notes']); ?></p>
            </div>
        <?php
endif; ?>

        <!-- Revisit Date -->
        <?php if (!empty($rx['revisit_date'])): ?>
            <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded p-1.5 text-center w-64 mx-auto shadow-sm">
                <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-widest block mb-0">Next Follow-up Visit</span>
                <span class="text-[12px] font-bold text-emerald-900"><?php echo date('l, d M Y', strtotime($rx['revisit_date'])); ?></span>
            </div>
        <?php
endif; ?>

        <!-- Footer: Signature & QR Code absolute positioned to bottom -->
        <div class="absolute bottom-2 left-0 right-0 flex justify-between items-end border-t border-gray-300 pt-2 px-2 pb-0">
            
            <div class="flex items-center gap-2">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo urlencode('https://ivfexperts.pk/portal/verify.php?hash=' . $rx['qrcode_hash']); ?>" alt="QR Code" class="w-14 h-14 border p-0.5" />
                <div class="text-[9px] text-gray-500 w-32 leading-tight">
                    <span class="font-bold block text-gray-700">Digital Record PDF</span>
                    Scan this verification code with phone camera to download.
                </div>
            </div>

            <div class="text-center">
                <?php if (!empty($rx['digital_signature_path'])): ?>
                    <img src="../<?php echo esc($rx['digital_signature_path']); ?>" alt="Signature" class="h-12 mx-auto object-contain mb-0" />
                <?php
else: ?>
                    <div class="h-12 sm:w-48 text-gray-300 italic flex items-end justify-center pb-1 border-b border-gray-200 text-[10px]">Unsigned Document</div>
                <?php
endif; ?>
                <div class="font-bold uppercase text-[11px] text-slate-800 mt-0.5">Dr. Adnan Jabbar</div>
                <div class="text-[9px] text-gray-500 border-t border-gray-300 pt-0.5 mx-auto inline-block w-40 italic">Clinical Director</div>
            </div>

        </div>

    </div>

    <!-- Inject printer script automatically for immediate preview -->
    <script>
        // window.onload = () => { setTimeout(() => { window.print(); }, 500); };
    </script>
</body>
</html>
