<?php
session_start();
if (!isset($_SESSION['portal_patient_id'])) {
    die("Unauthorized Access. Please login via the Patient Portal.");
}

$type = $_GET['type'] ?? '';
$hash = $_GET['hash'] ?? '';
$patient_id = intval($_SESSION['portal_patient_id']);

require_once dirname(__DIR__) . '/config/db.php';

// Route and Verify
$doc_id = 0;
$script = '';

if ($type === 'sa') {
    $stmt = $conn->prepare("SELECT id FROM semen_analyses WHERE qrcode_hash = ? AND patient_id = ?");
    $stmt->bind_param("si", $hash, $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $doc_id = $res->fetch_assoc()['id'];
        $script = '../admin/semen_analyses_print.php';
    }
}
elseif ($type === 'usg') {
    $stmt = $conn->prepare("SELECT id FROM patient_ultrasounds WHERE qrcode_hash = ? AND patient_id = ?");
    $stmt->bind_param("si", $hash, $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $doc_id = $res->fetch_assoc()['id'];
        $script = '../admin/ultrasounds_print.php';
    }
}
elseif ($type === 'rx') {
    $stmt = $conn->prepare("SELECT id FROM prescriptions WHERE qrcode_hash = ? AND patient_id = ?");
    $stmt->bind_param("si", $hash, $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $doc_id = $res->fetch_assoc()['id'];
        $script = '../admin/prescriptions_print.php';
    }
}
elseif ($type === 'receipt') {
    $stmt = $conn->prepare("SELECT id FROM receipts WHERE qrcode_hash = ? AND patient_id = ?");
    $stmt->bind_param("si", $hash, $patient_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $doc_id = $res->fetch_assoc()['id'];
        $script = '../admin/receipts_print.php';
    }
}

if ($doc_id > 0 && !empty($script)) {
    // Setup environment for the admin script
    define('BYPASS_AUTH', true);
    $_GET['id'] = $doc_id; // Fake the ID parameter

    // Execute the layout script
    include $script;
}
else {
    die("Document not found or you do not have permission to view it.");
}
