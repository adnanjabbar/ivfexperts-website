<?php
require("../../../config/db.php");
require("../../includes/auth.php");
include("../../includes/header.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $license = $_POST['phc_license'];

    $logo_path = "";
    if (!empty($_FILES['logo']['name'])) {
        $target = "../../../uploads/logos/" . time() . "_" . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $target);
        $logo_path = str_replace("../../../", "/", $target);
    }

    $conn->query("INSERT INTO hospitals (name, city, address, phone, phc_license, logo_path)
                  VALUES ('$name','$city','$address','$phone','$license','$logo_path')");

    header("Location: list.php");
}
?>

<h1 class="text-2xl font-bold mb-6">Add Hospital</h1>

<form method="POST" enctype="multipart/form-data" class="space-y-4">

<input type="text" name="name" placeholder="Hospital Name" class="w-full border p-3 rounded">
<input type="text" name="city" placeholder="City" class="w-full border p-3 rounded">
<input type="text" name="phc_license" placeholder="PHC License" class="w-full border p-3 rounded">
<textarea name="address" placeholder="Address" class="w-full border p-3 rounded"></textarea>
<input type="text" name="phone" placeholder="Phone" class="w-full border p-3 rounded">

<label>Logo</label>
<input type="file" name="logo" class="w-full">

<button class="bg-teal-600 text-white px-6 py-3 rounded-lg">Save</button>

</form>

<?php include("../../includes/footer.php"); ?>