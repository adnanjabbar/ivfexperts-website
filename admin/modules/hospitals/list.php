<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";

$result = $conn->query("SELECT * FROM hospitals");
?>

<h1 class="text-2xl font-bold mb-6">Hospitals</h1>

<a href="create.php" class="bg-teal-600 text-white px-4 py-2 rounded-lg mb-4 inline-block">+ Add Hospital</a>

<table class="w-full bg-white shadow rounded-lg">
<tr class="bg-gray-200">
<th class="p-3 text-left">Name</th>
<th>City</th>
<th>PHC License</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr class="border-b">
<td class="p-3"><?= $row['name'] ?></td>
<td><?= $row['city'] ?></td>
<td><?= $row['phc_license'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php include("../../includes/footer.php"); ?>