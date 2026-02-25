#!/bin/bash

echo "Deploying IVF Experts Clinical System V1..."

mkdir -p admin/includes

############################################
# ADMIN LAYOUT HEADER
############################################

cat > admin/includes/header.php <<'EOF'
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
EOF

############################################
# ADMIN FOOTER
############################################

cat > admin/includes/footer.php <<'EOF'
</div>
</div>
</body>
</html>
EOF

############################################
# AUTH
############################################

cat > admin/includes/auth.php <<'EOF'
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
EOF

############################################
# LOGOUT
############################################

cat > admin/logout.php <<'EOF'
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
EOF

############################################
# DASHBOARD
############################################

cat > admin/dashboard.php <<'EOF'
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
EOF

############################################
# PATIENTS TABLE
############################################

cat > admin/patients.php <<'EOF'
<?php
require("../config/db.php");
include("includes/header.php");

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM patients";
if($search){
$query .= " WHERE mr_number LIKE '%$search%' OR phone LIKE '%$search%' OR cnic LIKE '%$search%'";
}

$result = $conn->query($query);
?>

<h1 class="text-2xl font-bold mb-6">Patients</h1>

<form method="get" class="mb-6">
<input type="text" name="search" placeholder="Search MR / Phone / CNIC" class="border p-2">
<button class="bg-teal-600 text-white px-4 py-2">Search</button>
</form>

<table class="w-full bg-white shadow rounded">
<tr class="bg-gray-200">
<th class="p-2">MR</th>
<th>Name</th>
<th>Phone</th>
<th>CNIC</th>
</tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td class="p-2"><?= $row['mr_number'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['cnic'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php include("includes/footer.php"); ?>
EOF

echo "Clinical system deployed."