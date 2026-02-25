<?php
session_start();
require("../config/db.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin"] = $admin["id"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IVF Experts Clinical Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-teal-900 to-blue-900">

<div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex">

<!-- LEFT PANEL -->
<div class="w-1/2 bg-gradient-to-br from-teal-700 to-blue-800 text-white p-12 flex flex-col justify-center">

<h2 class="text-3xl font-bold mb-6">
IVF Experts Clinical System
</h2>

<p class="text-lg mb-8">
Secure fertility reporting & patient management platform.
</p>

<ul class="space-y-3 text-sm opacity-90">
<li>• WHO 6 Semen Analysis Engine</li>
<li>• Secure Patient Records</li>
<li>• Automated PDF Reports</li>
<li>• Digital Signature Integration</li>
</ul>

</div>

<!-- RIGHT PANEL -->
<div class="w-1/2 p-12">

<h2 class="text-2xl font-bold mb-6">Welcome Back</h2>

<?php if($error): ?>
<div class="bg-red-100 text-red-600 p-3 rounded mb-4">
<?= $error ?>
</div>
<?php endif; ?>

<form method="POST" class="space-y-6">

<div>
<label class="block text-sm mb-2">Username</label>
<input type="text" name="username" required
class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
</div>

<div>
<label class="block text-sm mb-2">Password</label>
<input type="password" name="password" required
class="w-full border p-3 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
</div>

<button class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-semibold transition">
Sign In
</button>

</form>

</div>
</div>

</body>
</html>