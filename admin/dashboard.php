<?php
session_start();

// Require admin auth (protects this page)
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/auth.php";

// Database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";

// Page title
$pageTitle = "Admin Dashboard - IVF Experts Clinical System";

// Admin header  and footer (isolated from public)
include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/footer.php";

// Fetch stats safely
$totalPatients = $conn->query("SELECT COUNT(*) as c FROM patients")->fetch_assoc()['c'] ?? 0;
$totalReports  = $conn->query("SELECT COUNT(*) as c FROM semen_reports")->fetch_assoc()['c'] ?? 0;
$pendingReports = $conn->query("SELECT COUNT(*) as c FROM semen_reports WHERE status = 'pending'")->fetch_assoc()['c'] ?? 0;
?>

<div class="p-6 lg:p-10">
    <!-- Welcome & Stats -->
    <div class="mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">
            Welcome, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
        </h1>
        <p class="text-lg text-gray-600">
            Quick overview of the IVF Experts clinical system
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Total Patients -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-teal-600 hover:shadow-xl transition">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Patients</h3>
            <p class="text-5xl font-extrabold text-gray-900 mt-4"><?= number_format($totalPatients) ?></p>
            <p class="text-sm text-gray-500 mt-2">Registered fertility patients</p>
        </div>

        <!-- Total Reports -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-blue-600 hover:shadow-xl transition">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Semen Reports</h3>
            <p class="text-5xl font-extrabold text-gray-900 mt-4"><?= number_format($totalReports) ?></p>
            <p class="text-sm text-gray-500 mt-2">Generated analysis reports</p>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-purple-600 hover:shadow-xl transition">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Pending Reports</h3>
            <p class="text-5xl font-extrabold text-gray-900 mt-4"><?= number_format($pendingReports) ?></p>
            <p class="text-sm text-gray-500 mt-2">Awaiting review/signature</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="/admin/patients/list.php" class="bg-teal-50 hover:bg-teal-100 p-8 rounded-xl text-center transition shadow hover:shadow-lg">
            <div class="text-5xl mb-4">👥</div>
            <h3 class="text-xl font-semibold text-teal-800">Manage Patients</h3>
        </a>

        <a href="/admin/reports/add.php" class="bg-blue-50 hover:bg-blue-100 p-8 rounded-xl text-center transition shadow hover:shadow-lg">
            <div class="text-5xl mb-4">📊</div>
            <h3 class="text-xl font-semibold text-blue-800">New Semen Report</h3>
        </a>

        <!-- Add more quick cards as needed -->
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/footer.php"; ?>