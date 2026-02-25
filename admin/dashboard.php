<?php
require("../config/db.php");
include("includes/header.php");

$totalPatients = $conn->query("SELECT COUNT(*) as c FROM patients")->fetch_assoc()['c'] ?? 0;
$totalReports = $conn->query("SELECT COUNT(*) as c FROM semen_reports")->fetch_assoc()['c'] ?? 0;
?>

<h1 class="text-3xl font-bold mb-8">Dashboard</h1>

<div class="grid grid-cols-2 gap-6">
<div class="bg-white p-6 rounded-xl shadow">
<h2 class="text-xl font-semibold">Total Patients</h2>
<p class="text-3xl mt-2"><?= $totalPatients ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<h2 class="text-xl font-semibold">Total Semen Reports</h2>
<p class="text-3xl mt-2"><?= $totalReports ?></p>
</div>
</div>

<?php include("includes/footer.php"); ?>
