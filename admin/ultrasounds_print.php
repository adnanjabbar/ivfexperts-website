<?php
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0)
    die("Invalid Report ID");

// Fetch USG Data
$usg = null;
try {
    $stmt = $conn->prepare("
        SELECT u.*, p.first_name, p.last_name, p.mr_number, p.gender, p.phone, p.cnic, 
               h.name as hospital_name, h.margin_top, h.margin_bottom, h.margin_left, h.margin_right, h.digital_signature_path 
        FROM patient_ultrasounds u 
        JOIN patients p ON u.patient_id = p.id 
        JOIN hospitals h ON u.hospital_id = h.id 
        WHERE u.id = ?
    ");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $usg = $stmt->get_result()->fetch_assoc();
    }
}
catch (Exception $e) {
    die("DB Error");
}

if (!$usg)
    die("Report not found.");

// Setup Margins explicitly to handle pre-printed hospital letterheads
$mt = $usg['margin_top'] ?? '40mm';
$mb = $usg['margin_bottom'] ?? '30mm';
$ml = $usg['margin_left'] ?? '20mm';
$mr = $usg['margin_right'] ?? '20mm';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ultrasound #<?php echo $id; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page {
            size: A4;
            margin: <?php echo $mt; ?> <?php echo $mr; ?> <?php echo $mb; ?> <?php echo $ml; ?>;
        }
        body {
            background-color: #f3f4f6;
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
                padding: 0; 
                margin: 0;
            }
            .no-print { display: none !important; }
        }
        /* WYSIWYG Content Styles reset */
        .usg-content table { width: 100%; border-collapse: collapse; margin-bottom: 1em; }
        .usg-content th, .usg-content td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .usg-content p { margin-bottom: 1em; line-height: 1.6; }
        .usg-content ul, .usg-content ol { margin-left: 1.5em; margin-bottom: 1em; }
        .usg-content ul { list-style-type: disc; }
        .usg-content ol { list-style-type: decimal; }
    </style>
</head>
<body class="py-10 print:py-0">

    <div class="fixed top-4 right-4 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2 rounded-lg shadow-lg font-bold">
            <i class="fa-solid fa-print"></i> Print on Letterhead
        </button>
        <button onclick="window.close()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg">
            Close
        </button>
    </div>

    <!-- The Actual Document -->
    <div class="a4-container flex flex-col relative pb-32">
        
        <!-- Document Title -->
        <div class="text-center uppercase tracking-widest font-bold text-2xl mb-8 border-b-2 border-gray-800 pb-2">
            <?php echo esc($usg['report_title']); ?>
        </div>
        
        <!-- Patient Banner -->
        <div class="bg-gray-50 border border-gray-200 p-4 mb-8 flex justify-between text-sm">
            <div>
                <span class="font-bold text-gray-500 uppercase">Patient Name:</span> 
                <span class="font-bold text-lg block"><?php echo esc($usg['first_name'] . ' ' . $usg['last_name']); ?></span>
            </div>
            <div>
                <span class="font-bold text-gray-500 uppercase">MR Number:</span> 
                <span class="font-mono block text-md leading-6 text-gray-800"><?php echo esc($usg['mr_number']); ?></span>
            </div>
            <div>
                <span class="font-bold text-gray-500 uppercase">Date of Report:</span> 
                <span class="block text-md leading-6 text-gray-800"><?php echo date('d M Y', strtotime($usg['created_at'])); ?></span>
            </div>
        </div>

        <!-- Rendered TinyMCE Content -->
        <div class="usg-content flex-grow text-[15px] px-2">
            <?php echo $usg['content']; // Output Raw HTML since it comes from TinyMCE safely ?>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-4 left-0 right-0 flex justify-between items-end border-t border-gray-300 pt-4">
            
            <div class="flex items-center gap-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo urlencode('https://ivfexperts.pk/portal/verify.php?hash=' . $usg['qrcode_hash']); ?>" alt="QR Code" class="w-16 h-16 border border-gray-200" />
                <div class="text-[10px] text-gray-500 w-48">
                    Scan to verify authenticity of this report digitally.
                </div>
            </div>

            <div class="text-center">
                <?php if (!empty($usg['digital_signature_path'])): ?>
                    <img src="../<?php echo esc($usg['digital_signature_path']); ?>" alt="Signature" class="h-20 mx-auto object-contain mb-1" />
                <?php
else: ?>
                    <div class="h-20 sm:w-48"></div>
                <?php
endif; ?>
                <div class="font-bold uppercase text-sm border-t border-gray-800 pt-1 w-48 mx-auto">Dr. Adnan Jabbar</div>
                <div class="text-xs text-gray-600">Consultant Urologist / Andrologist</div>
            </div>

        </div>

    </div>

</body>
</html>
