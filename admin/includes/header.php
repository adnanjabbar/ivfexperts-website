<?php require("auth.php"); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IVF Experts Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-slate-900 text-white flex flex-col">

<div class="p-6 border-b border-slate-700">
<h2 class="text-xl font-bold">IVF Experts</h2>
<p class="text-sm text-slate-400">Clinical System</p>
</div>

<nav class="flex-1 p-6 space-y-4 text-sm">

<a href="dashboard.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700 transition">
Dashboard
</a>

<a href="patients.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700 transition">
Patients
</a>

<a href="create_semen_report.php" class="block px-4 py-3 rounded-xl hover:bg-slate-700 transition">
New Semen Report
</a>

<a href="logout.php" class="block px-4 py-3 rounded-xl hover:bg-red-600 transition">
Logout
</a>

</nav>

</aside>

<!-- Main Content -->
<div class="flex-1">

<!-- Topbar -->
<div class="bg-white shadow px-10 py-6 flex justify-between items-center">
<h1 class="text-2xl font-semibold text-gray-800">Admin Panel</h1>
</div>

<div class="p-10">