<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";

// === CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $csrf_token) {
        $error = "Invalid CSRF token. Please try again.";
    } else {
        $username = trim($_POST["username"] ?? "");
        $password = trim($_POST["password"] ?? "");

        if (empty($username) || empty($password)) {
            $error = "Both fields are required.";
        } else {
            $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();

                if (password_verify($password, $admin["password"])) {
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Invalid username.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IVF Experts Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0f766e, #115e59); }
        .login-card { background: rgba(255,255,255,0.97); backdrop-filter: blur(12px); box-shadow: 0 25px 50px rgba(0,0,0,0.35); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-5xl login-card rounded-3xl overflow-hidden flex flex-col md:flex-row">
        <!-- Branding -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-teal-800 to-teal-950 text-white p-12 flex flex-col justify-center">
            <h1 class="text-5xl font-extrabold mb-6">IVF Experts</h1>
            <p class="text-2xl font-light mb-10">Clinical Admin Portal</p>
            <ul class="space-y-4 text-lg opacity-90">
                <li>✔ Secure Patient Records</li>
                <li>✔ Semen Analysis Engine</li>
                <li>✔ Automated Reporting</li>
                <li>✔ Authorized Access Only</li>
            </ul>
        </div>

        <!-- Login Form -->
        <div class="w-full md:w-1/2 p-12 bg-white flex flex-col justify-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">Admin Login</h2>
            <p class="text-gray-600 mb-10">Access the clinical management system</p>

            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-xl">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Username</label>
                    <input type="text" name="username" required autofocus
                           class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                </div>

                <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-4 rounded-xl transition shadow-md hover:shadow-lg transform hover:-translate-y-1">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>