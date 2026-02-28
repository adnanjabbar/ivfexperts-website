<?php
session_start();
if (isset($_SESSION['portal_patient_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IVF Experts - Patient Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-md w-full text-center">
        <img src="../assets/images/ivfexperts-logo.png" alt="IVF Experts" class="h-20 mx-auto mb-6">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Secure Patient Portal</h1>
        <p class="text-gray-500 mb-8 text-sm">Access your comprehensive EMR records, ultrasounds, prescriptions, and semen analyses directly from your device.</p>

        <div class="bg-sky-50 rounded-xl p-6 border border-sky-100 mb-6">
            <i class="fa-solid fa-qrcode text-5xl text-sky-400 mb-4 block"></i>
            <h2 class="font-bold text-sky-900 mb-1">Scan to Login</h2>
            <p class="text-xs text-sky-700">Open your smartphone camera and scan the QR code located at the bottom of any physical printout provided by our clinic.</p>
        </div>

        <div class="text-xs text-gray-400">
            Secured by IVF Experts 2FA EMR System &copy; <?php echo date('Y'); ?>
        </div>
    </div>

</body>
</html>
