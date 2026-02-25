<?php
require("../../../config/db.php");
require("../../includes/auth.php");
include("../../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_FILES['signature']['name'])) {
        $target = "../../../uploads/signatures/" . time() . "_" . $_FILES['signature']['name'];
        move_uploaded_file($_FILES['signature']['tmp_name'], $target);

        $path = str_replace("../../../", "/", $target);

        $conn->query("DELETE FROM settings");
        $conn->query("INSERT INTO settings (signature_path) VALUES ('$path')");
    }
}
?>

<h1 class="text-2xl font-bold mb-6">Digital Signature</h1>

<form method="POST" enctype="multipart/form-data">
<input type="file" name="signature">
<button class="bg-teal-600 text-white px-6 py-2 rounded-lg">Upload</button>
</form>

<?php include("../../includes/footer.php"); ?>