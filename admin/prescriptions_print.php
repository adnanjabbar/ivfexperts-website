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
               h.name as hospital_name, h.letterhead_top, h.letterhead_bottom, h.letterhead_left, h.letterhead_right, h.digital_signature_path 
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

// Setup Margins
$mt = $rx['letterhead_top'] ?? 20;
$mb = $rx['letterhead_bottom'] ?? 20;
$ml = $rx['letterhead_left'] ?? 15;
$mr = $rx['letterhead_right'] ?? 15;
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
            /* Margins applied from Database */
            margin: <?php echo $mt; ?>mm <?php echo $mr; ?>mm <?php echo $mb; ?>mm <?php echo $ml; ?>mm;
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
            /* Simulating paper margins for screen view */
            padding: <?php echo $mt; ?>mm <?php echo $mr; ?>mm <?php echo $mb; ?>mm <?php echo $ml; ?>mm;
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
        <div class="border-b-2 border-gray-800 pb-3 mb-6">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-bold uppercase tracking-wider mb-1">Patient Info</h2>
                    <table class="text-sm">
                        <tr><td class="font-bold pr-3 text-gray-600">Name:</td><td class="font-bold text-lg"><?php echo esc($rx['first_name'] . ' ' . $rx['last_name']); ?></td></tr>
                        <tr><td class="font-bold pr-3 text-gray-600">MR #:</td><td class="font-mono"><?php echo esc($rx['mr_number']); ?></td></tr>
                        <tr><td class="font-bold pr-3 text-gray-600">Gender/Phone:</td><td><?php echo esc($rx['gender']); ?> / <?php echo esc($rx['phone'] ?: 'N/A'); ?></td></tr>
                    </table>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-500 mb-1">Date</div>
                    <div class="text-lg font-medium"><?php echo date('d M Y', strtotime($rx['created_at'])); ?></div>
                    <div class="text-xs text-gray-400 mt-1 uppercase tracking-widest">RX-<?php echo str_pad($id, 6, '0', STR_PAD_LEFT); ?></div>
                </div>
            </div>
        </div>

        <!-- Big Rx Symbol -->
        <div class="mb-4">
            <span class="text-6xl font-serif italic font-bold">Rx</span>
        </div>

        <!-- Medication List -->
        <div class="flex-grow mb-8 px-4">
            <?php if (empty($items)): ?>
                <p class="text-gray-500 italic">No medications prescribed.</p>
            <?php
else: ?>
                <ul class="space-y-6">
                    <?php foreach ($items as $idx => $item): ?>
                        <li class="flex items-start gap-4">
                            <span class="font-bold text-lg mt-0.5"><?php echo $idx + 1; ?>.</span>
                            <div class="w-full">
                                <div class="flex items-baseline justify-between border-b border-gray-200 border-dotted pb-1 mb-1">
                                    <h3 class="font-bold text-xl uppercase"><?php echo esc($item['name']); ?> <span class="text-xs font-normal text-gray-500 bg-gray-100 px-1 rounded ml-1"><?php echo esc($item['med_type']); ?></span></h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-2 pl-2 border-l-2 border-gray-300">
                                    <?php if (!empty($item['dosage'])): ?>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Dosage</span>
                                            <span class="text-md font-medium"><?php echo esc($item['dosage']); ?></span>
                                        </div>
                                    <?php
        endif; ?>
                                    <?php if (!empty($item['instructions'])): ?>
                                        <div>
                                            <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Instructions</span>
                                            <span class="text-md font-medium"><?php echo esc($item['instructions']); ?></span>
                                        </div>
                                    <?php
        endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php
    endforeach; ?>
                </ul>
            <?php
endif; ?>
        </div>

        <!-- Clinical Notes -->
        <?php if (!empty($rx['notes'])): ?>
            <div class="mt-8 pt-4 border-t-2 border-gray-800">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-600 mb-2">Advice / Next Visit</h3>
                <p class="text-md whitespace-pre-wrap leading-relaxed"><?php echo esc($rx['notes']); ?></p>
            </div>
        <?php
endif; ?>

        <!-- Footer: Signature & QR Code absolute positioned to bottom -->
        <div class="absolute bottom-4 left-0 right-0 flex justify-between items-end border-t border-gray-300 pt-4">
            
            <div class="flex items-center gap-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://ivfexperts.pk/portal/verify.php?hash=<?php echo urlencode($rx['qrcode_hash']); ?>" alt="QR Code" class="w-20 h-20 border p-1" />
                <div class="text-xs text-gray-500 w-48 leading-snug">
                    <span class="font-bold block text-gray-700">Scan to Verify & Download</span>
                    Point your camera at this code to view the EMR portal and download the original PDF.
                </div>
            </div>

            <div class="text-center">
                <?php if (!empty($rx['digital_signature_path'])): ?>
                    <img src="../<?php echo esc($rx['digital_signature_path']); ?>" alt="Signature" class="h-20 mx-auto object-contain mb-1" />
                <?php
else: ?>
                    <div class="h-20 sm:w-48 text-gray-300 italic flex items-end justify-center pb-2 border-b border-gray-300">Unsigned</div>
                <?php
endif; ?>
                <div class="font-bold uppercase text-sm border-t border-gray-800 pt-1 w-48 mx-auto">Dr. Adnan Jabbar</div>
                <div class="text-xs text-gray-600">Clinical Director</div>
            </div>

        </div>

    </div>

    <!-- Inject printer script automatically for immediate preview -->
    <script>
        // window.onload = () => { setTimeout(() => { window.print(); }, 500); };
    </script>
</body>
</html>
