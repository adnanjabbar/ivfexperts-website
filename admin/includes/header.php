<?php require("auth.php"); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IVF Experts Clinical System</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 font-sans">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-72 bg-slate-900 text-white flex flex-col shadow-xl">

<div class="p-6 border-b border-slate-700">
<h2 class="text-2xl font-bold">IVF Experts</h2>
<p class="text-sm text-slate-400">Clinical Dashboard</p>
</div>

<nav class="flex-1 p-6 space-y-3 text-sm">

<a href="dashboard.php" class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
📊 Dashboard
</a>

<a href="patients.php" class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
👤 Patients
</a>

<a href="create_semen_report.php" class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
🧪 Semen Reports
</a>

<a href="settings.php" class="block px-4 py-3 rounded-lg hover:bg-slate-700 transition">
⚙ Settings
</a>

<a href="logout.php" class="block px-4 py-3 rounded-lg hover:bg-red-600 transition">
🚪 Logout
</a>

</nav>

<div class="p-6 border-t border-slate-700 text-xs text-slate-400">
© <?= date("Y") ?> IVF Experts
</div>

</aside>

<!-- MAIN AREA -->
<div class="flex-1">

<!-- TOPBAR -->
<header class="bg-white shadow px-10 py-6 flex justify-between items-center">

<div>
<h1 class="text-2xl font-semibold text-slate-800">
Admin Panel
</h1>
</div>

<div class="text-sm text-slate-600">
Logged in as Admin
</div>

</header>

<div class="p-10">