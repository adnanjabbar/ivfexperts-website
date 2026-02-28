<?php
// contact/process.php - Form handler with reCAPTCHA v2 verification

// === CONFIG ===
$to_email = "hello@ivfexperts.pk";
$secret_key = "6LdVE3ksAAAAAOok8MKSLLfklIzGqyeipU5pKPkz"; // Your Secret Key
$redirect_url = "/contact/";

// === PROCESS ===
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: $redirect_url?error=invalid_request");
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    header("Location: $redirect_url?error=missing_fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: $redirect_url?error=invalid_email");
    exit;
}

// Verify reCAPTCHA
if (empty($recaptcha_response)) {
    header("Location: $redirect_url?error=captcha_missing");
    exit;
}

$verify_url = "https://www.google.com/recaptcha/api/siteverify";
$verify_data = [
    'secret' => $secret_key,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($verify_data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($verify_url, false, $context);
$response = json_decode($result);

if (!$response || !$response->success) {
    header("Location: $redirect_url?error=captcha");
    exit;
}

// === DATABASE INTEGRATION ===
// Connect to the DB to store the lead in the Admin Dashboard
require_once dirname(__DIR__) . '/config/db.php';

// Determine inquiry type (you could expand the form later to ask this explicitly)
$inquiry_type = "General Inquiry";
if (stripos($message, 'ivf') !== false) {
    $inquiry_type = "IVF";
}
elseif (stripos($message, 'icsi') !== false) {
    $inquiry_type = "ICSI";
}
elseif (stripos($message, 'gender') !== false || stripos($message, 'pgt') !== false) {
    $inquiry_type = "Gender Selection / PGT";
}

$utm_source = "Organic Website"; // Default for now, can be expanded if URL params exist

// Insert into `leads` table
$stmt = $conn->prepare("INSERT INTO leads (patient_name, phone_number, email, inquiry_type, message, utm_source) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $phone, $email, $inquiry_type, $message, $utm_source);

// Execute but don't stop the email dispatch if DB fails (ensure redundancy)
$stmt->execute();
$stmt->close();
// =============================

// Prepare and send email
$subject = "New Fertility Inquiry from $name";

$body = "=== New Contact Form Submission ===\n\n" .
    "Name:          $name\n" .
    "Email:         $email\n" .
    "Phone/WhatsApp: $phone\n" .
    "Message:\n$message\n\n" .
    "Submitted:     " . date('Y-m-d H:i:s') . "\n" .
    "IP:            " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";

$headers = "From: $email\r\n" .
    "Reply-To: $email\r\n" .
    "X-Mailer: PHP/" . phpversion();

if (mail($to_email, $subject, $body, $headers)) {
    header("Location: $redirect_url?success=1");
}
else {
    header("Location: $redirect_url?error=send_failed");
}

exit;
?>