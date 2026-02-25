<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === "admin" && $_POST['password'] === "admin123") {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IVF Experts Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-teal-900 to-slate-800 min-h-screen flex items-center justify-center">

<div class="bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-2xl w-96 border border-white/20">

<h2 class="text-2xl font-bold text-white mb-8 text-center">
IVF Experts Admin
</h2>

<form method="post" class="space-y-6">

<input type="text" name="username" placeholder="Username"
class="w-full p-3 rounded-xl bg-white/20 text-white placeholder-white/70 border border-white/30 focus:outline-none">

<input type="password" name="password" placeholder="Password"
class="w-full p-3 rounded-xl bg-white/20 text-white placeholder-white/70 border border-white/30 focus:outline-none">

<button class="w-full bg-teal-500 hover:bg-teal-600 text-white py-3 rounded-xl font-semibold transition">
Login
</button>

</form>

</div>

</body>
</html>