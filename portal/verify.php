<?php
session_start();
require_once dirname(__DIR__) . '/config/db.php';

$hash = trim($_GET['hash'] ?? '');
if (empty($hash)) {
    die("Invalid or missing Security Hash. Please scan the QR code from your document natively.");
}

// 1. Find the document and the associated patient_id
$patient_id = null;
$doc_type = null;

// Check Prescriptions
$stmt = $conn->prepare("SELECT patient_id FROM prescriptions WHERE qrcode_hash = ?");
$stmt->bind_param("s", $hash);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $patient_id = $res->fetch_assoc()['patient_id'];
    $doc_type = 'prescription';
}

// Check Ultrasounds
if (!$patient_id) {
    $stmt = $conn->prepare("SELECT patient_id FROM patient_ultrasounds WHERE qrcode_hash = ?");
    $stmt->bind_param("s", $hash);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $patient_id = $res->fetch_assoc()['patient_id'];
        $doc_type = 'ultrasound';
    }
}

// Check Semen Analysis
if (!$patient_id) {
    $stmt = $conn->prepare("SELECT patient_id FROM semen_analyses WHERE qrcode_hash = ?");
    $stmt->bind_param("s", $hash);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $patient_id = $res->fetch_assoc()['patient_id'];
        $doc_type = 'semen';
    }
}

// Check Receipts
if (!$patient_id) {
    $stmt = $conn->prepare("SELECT patient_id FROM receipts WHERE qrcode_hash = ?");
    $stmt->bind_param("s", $hash);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $patient_id = $res->fetch_assoc()['patient_id'];
        $doc_type = 'receipt';
    }
}

if (!$patient_id) {
    die("This document hash could not be found in our secure registry. It may have been revoked.");
}

// If already logged in as THIS patient, proceed
if (isset($_SESSION['portal_patient_id']) && $_SESSION['portal_patient_id'] == $patient_id) {
    header("Location: dashboard.php");
    exit;
}

// Optional: fetch partial phone to show as hint
$stmt = $conn->prepare("SELECT phone, cnic, first_name FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify'])) {
    $phone_mr = trim($_POST['phone_mr'] ?? '');
    $cnic_raw = trim($_POST['cnic'] ?? '');
    $cnic_clean = preg_replace('/[^0-9]/', '', $cnic_raw);

    $patient_cnic_clean = preg_replace('/[^0-9]/', '', $patient['cnic'] ?? '');

    $phone_match = (!empty($patient['phone']) && $phone_mr === $patient['phone']);
    $mr_match = (!empty($patient['mr_number']) && $phone_mr === $patient['mr_number']);
    $cnic_match = (!empty($patient_cnic_clean) && $cnic_clean === $patient_cnic_clean);

    if (($phone_match || $mr_match) && $cnic_match) {
        $_SESSION['portal_patient_id'] = $patient_id;
        $_SESSION['portal_patient_name'] = $patient['first_name'];
        header("Location: dashboard.php");
        exit;
    }
    else {
        $error = "The provided details do not match our secure records for this document.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - IVF Experts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-sky-100 text-sky-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Verify Identity</h1>
            <p class="text-sm text-gray-500">Document registry located. To unlock and view your medical records, please complete the 2-Factor Authentication.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg mb-4 border border-red-100 text-center">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php
endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Mobile Number OR MR Number</label>
                <input type="text" name="phone_mr" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" placeholder="e.g. 03001234567 or IVF-2310..." required autofocus value="<?php echo htmlspecialchars($_POST['phone_mr'] ?? ''); ?>">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">CNIC Number</label>
                <input type="text" name="cnic" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono" placeholder="13 digits (dashes will be ignored)" required value="<?php echo htmlspecialchars($_POST['cnic'] ?? ''); ?>">
            </div>
            
            <button type="submit" name="verify" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 rounded-lg transition-colors shadow-md">
                Unlock Records
            </button>
        </form>

    </div>

</body>
</html>
