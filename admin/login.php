<?php
session_start();
require_once("../config/db.php");

// === SECURITY: Start session & regenerate ID on every load (anti-fixation)
session_regenerate_id(true);

// === CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// === Handle Login POST
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verify CSRF token
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
                    // Login success
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];

                    // Regenerate session ID after successful login (security best practice)
                    session_regenerate_id(true);

                    // Clear CSRF token after use
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
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IVF Experts Clinical Admin Login</title>

    <!-- Tailwind CDN (temporary – move to local style.css later) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        }
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            outline: none;
            ring: 2px solid #0f766e;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-5xl login-card rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row">

    <!-- LEFT BRANDING PANEL -->
    <div class="w-full md:w-1/2 bg-gradient-to-br from-teal-800 to-teal-950 text-white p-10 lg:p-16 flex flex-col justify-center">
        <h1 class="text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">
            IVF Experts
        </h1>
        <p class="text-xl lg:text-2xl font-light mb-10 opacity-90">
            Clinical Management System
        </p>

        <ul class="space-y-4 text-base lg:text-lg opacity-90">
            <li class="flex items-center gap-3">
                <svg class="w-6 h-6 text-teal-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                WHO-compliant Semen Analysis
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-6 h-6 text-teal-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Secure Patient Records
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-6 h-6 text-teal-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Automated PDF Reports
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-6 h-6 text-teal-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Digital Signature Ready
            </li>
        </ul>

        <p class="mt-12 text-sm opacity-80">
            Secure access only. All sessions encrypted.
        </p>
    </div>

    <!-- RIGHT LOGIN FORM -->
    <div class="w-full md:w-1/2 p-10 lg:p-16 bg-white flex flex-col justify-center">

        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
            Admin Login
        </h2>
        <p class="text-gray-600 mb-10">
            Access the clinical management system
        </p>

        <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-r-xl">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div>
                <label class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" name="username" required autofocus
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition input-focus">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition input-focus">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" class="mr-2 rounded">
                    Remember me
                </label>

                <a href="#" class="text-sm text-teal-600 hover:text-teal-800 transition">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-4 rounded-xl transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-8">
            IVF Experts Clinical System — Secure Access Only
        </p>

    </div>

</div>

</body>
</html>