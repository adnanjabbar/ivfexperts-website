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
<div class="w-64 bg-gray-900 text-white p-6">
<h2 class="text-xl font-bold mb-8">IVF Admin</h2>
<nav class="space-y-3">
<a href="dashboard.php" class="block hover:text-teal-400">Dashboard</a>
<a href="patients.php" class="block hover:text-teal-400">Patients</a>
<a href="create_semen_report.php" class="block hover:text-teal-400">New Semen Report</a>
<a href="logout.php" class="block hover:text-red-400">Logout</a>
</nav>
</div>

<div class="flex-1 p-10">
