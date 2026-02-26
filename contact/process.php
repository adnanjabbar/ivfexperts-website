<?php
// contact/process.php - Form handler with reCAPTCHA v2 verification

// === CONFIG ===
$to_email     = "hello@ivfexperts.pk";
$secret_key   = "6LeiEHksAAAAAAUbfQYXy-PgJuGhdWMdEDcykKJk";   // Your Secret Key
$redirect_url = "/contact/";

// === PROCESS ===
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: $redirect_url?error=invalid_request");
    exit;
}

// Get form data
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
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
    'secret'   => $secret_key,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($verify_data)
    ]
];

$context  = stream_context_create($options);
$result   = file_get_contents($verify_url, false, $context);
$response = json_decode($result);

if (!$response || !$response->success) {
    header("Location: $redirect_url?error=captcha");
    exit;
}

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
} else {
    header("Location: $redirect_url?error=send_failed");
}

exit;
?>