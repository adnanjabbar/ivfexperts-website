<?php
require_once __DIR__ . '/includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0)
    die("Invalid Receipt ID");

// Fetch Receipt Data
$rx = null;
try {
    $stmt = $conn->prepare("
        SELECT r.*, p.first_name, p.last_name, p.mr_number, p.phone, p.cnic, 
               h.name as hospital_name, h.logo_path, h.digital_signature_path, h.address, h.phone as hospital_phone
        FROM receipts r 
        JOIN patients p ON r.patient_id = p.id 
        JOIN hospitals h ON r.hospital_id = h.id 
        WHERE r.id = ?
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
    die("Receipt not found.");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?php echo $id; ?></title>
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page { size: A4; margin: 15mm 15mm 15mm 15mm; }
        body { background-color: #f3f4f6; -webkit-print-color-adjust: exact; color: #000; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .a4-container {
            width: 210mm; min-height: 297mm; background: #fff; margin: 0 auto; position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 15mm 15mm 15mm 15mm; box-sizing: border-box;
        }
        @media print {
            body { background: #fff; }
            .a4-container { width: auto; min-height: auto; box-shadow: none; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="py-10 print:py-0">

    <div class="fixed top-4 right-4 flex gap-2 no-print">
        <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg shadow-lg font-bold">
            <i class="fa-solid fa-print"></i> Print Receipt
        </button>
        <button onclick="window.close()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg">Close</button>
    </div>

    <!-- The Actual Document -->
    <div class="a4-container flex flex-col relative pb-32">
        
        <!-- Header Logos -->
        <div class="flex justify-between items-center mb-8 border-b-2 border-emerald-900 pb-4">
            <div class="w-1/3">
                <img src="../assets/images/ivfexperts-logo.png" alt="IVF Experts" class="h-20 object-contain" onerror="this.style.display='none';">
            </div>
            
            <div class="w-1/3 text-center">
                <h1 class="font-extrabold text-3xl uppercase tracking-widest text-emerald-900">Receipt</h1>
                <p class="text-xs text-gray-500 tracking-widest">PATIENT COPY</p>
            </div>
            
            <div class="w-1/3 flex justify-end">
                <?php if (!empty($rx['logo_path']) && $rx['logo_path'] !== 'assets/images/ivfexperts-logo.png'): ?>
                    <img src="../<?php echo esc($rx['logo_path']); ?>" alt="<?php echo esc($rx['hospital_name']); ?>" class="h-20 object-contain">
                <?php
endif; ?>
            </div>
        </div>

        <!-- Receipt Meta -->
        <div class="flex justify-between mb-8">
            <div>
                <p class="text-sm font-bold text-gray-600 uppercase mb-1">Receipt To:</p>
                <p class="text-lg font-bold text-gray-900"><?php echo esc($rx['first_name'] . ' ' . $rx['last_name']); ?></p>
                <p class="text-sm text-gray-700 font-mono">MR #: <?php echo esc($rx['mr_number']); ?></p>
                <?php if ($rx['phone'])
    echo '<p class="text-sm text-gray-700">Phone: ' . esc($rx['phone']) . '</p>'; ?>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-600 uppercase mb-1">Details:</p>
                <p class="text-xl font-bold font-mono text-gray-900">RCPT-<?php echo str_pad($id, 6, '0', STR_PAD_LEFT); ?></p>
                <p class="text-md text-gray-700">Date: <?php echo date('d M Y', strtotime($rx['receipt_date'])); ?></p>
            </div>
        </div>

        <!-- Line Items -->
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300">
                    <th class="text-left py-3 px-4 font-bold uppercase text-xs tracking-wider text-gray-700 w-2/3">Description</th>
                    <th class="text-right py-3 px-4 font-bold uppercase text-xs tracking-wider text-gray-700 w-1/3">Amount (Rs)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-200">
                    <td class="py-5 px-4 text-lg font-medium text-gray-800">
                        <?php echo esc($rx['procedure_name']); ?>
                        <?php if (!empty($rx['notes'])): ?>
                            <div class="text-sm text-gray-500 font-normal mt-1 italic"><?php echo esc($rx['notes']); ?></div>
                        <?php
endif; ?>
                    </td>
                    <td class="py-5 px-4 text-right text-xl font-mono text-gray-900 font-bold">
                        <?php echo number_format($rx['amount'], 2); ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end mb-12">
            <div class="w-1/2">
                <div class="flex justify-between border-t-2 border-emerald-900 pt-3">
                    <span class="text-lg font-bold uppercase text-emerald-900">Total Paid:</span>
                    <span class="text-2xl font-mono font-bold text-emerald-900">Rs. <?php echo number_format($rx['amount'], 2); ?></span>
                </div>
                <div class="text-right text-xs text-gray-500 mt-2 uppercase tracking-wide">Thank you for trusting IVF Experts.</div>
            </div>
        </div>

        <!-- Footer / QR -->
        <div class="absolute bottom-6 left-0 right-0 flex justify-between items-end border-t border-gray-300 pt-4">
            
            <div class="flex items-center gap-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://ivfexperts.pk/portal/verify.php?hash=<?php echo urlencode($rx['qrcode_hash']); ?>" alt="QR Code" class="w-16 h-16 border p-1" />
                <div class="text-[10px] text-gray-500 w-48 leading-snug">
                    Scan to download Digital PDF Receipt.
                </div>
            </div>

            <div class="text-right text-xs text-gray-600">
                <p class="font-bold text-emerald-900 mb-1"><?php echo esc($rx['hospital_name']); ?></p>
                <p><?php echo esc($rx['address']); ?></p>
                <p>Tel: <?php echo esc($rx['hospital_phone']); ?></p>
                <p class="mt-2 text-[10px] text-gray-400">Generated by Custom EMR | IVF Experts Network</p>
            </div>

        </div>

    </div>

</body>
</html>
