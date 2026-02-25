<?php
require("../config/db.php");
include("includes/header.php");

$totalPatients = $conn->query("SELECT COUNT(*) as c FROM patients")->fetch_assoc()['c'] ?? 0;
$totalReports = $conn->query("SELECT COUNT(*) as c FROM semen_reports")->fetch_assoc()['c'] ?? 0;
?>

<div class="grid grid-cols-2 gap-8">

<div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
<h3 class="text-gray-500 text-sm">Total Patients</h3>
<p class="text-4xl font-bold text-teal-600 mt-3"><?= $totalPatients ?></p>
</div>

<div class="bg-white p-8 rounded-2xl shadow hover:shadow-lg transition">
<h3 class="text-gray-500 text-sm">Total Semen Reports</h3>
<p class="text-4xl font-bold text-teal-600 mt-3"><?= $totalReports ?></p>
</div>

</div>

<?php include("includes/footer.php"); ?>