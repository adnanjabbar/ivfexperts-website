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
