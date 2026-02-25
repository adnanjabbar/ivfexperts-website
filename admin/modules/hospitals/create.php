<?php
require_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT']."/admin/includes/auth.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/includes/header.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $license = $_POST['phc_license'];

    $doctor_name = $_POST['doctor_name'];
    $designation = $_POST['designation'];
    $license_number = $_POST['license_number'];
    $footer_note = $_POST['footer_note'];

    $logo_path = "";
    if (!empty($_FILES['logo']['name'])) {
        $target = "../../../uploads/logos/" . time() . "_" . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], $target);
        $logo_path = str_replace("../../../", "/", $target);
    }

    $conn->query("INSERT INTO hospitals 
        (name, city, address, phone, phc_license, logo_path, doctor_name, designation, license_number, footer_note)
        VALUES 
        ('$name','$city','$address','$phone','$license','$logo_path','$doctor_name','$designation','$license_number','$footer_note')");

    header("Location: list.php");
}
?>

<h1 class="text-2xl font-bold mb-6">Add Hospital</h1>

<form method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-8 rounded-xl shadow">

<input type="text" name="name" placeholder="Hospital Name" class="w-full border p-3 rounded">
<input type="text" name="city" placeholder="City" class="w-full border p-3 rounded">
<input type="text" name="phc_license" placeholder="PHC License" class="w-full border p-3 rounded">
<textarea name="address" placeholder="Address" class="w-full border p-3 rounded"></textarea>
<input type="text" name="phone" placeholder="Phone" class="w-full border p-3 rounded">

<hr class="my-4">

<h2 class="font-semibold text-lg">Doctor Signature Information</h2>

<input type="text" name="doctor_name" placeholder="Doctor Name" class="w-full border p-3 rounded">
<input type="text" name="designation" placeholder="Designation" class="w-full border p-3 rounded">
<input type="text" name="license_number" placeholder="License Number" class="w-full border p-3 rounded">
<textarea name="footer_note" placeholder="Footer Note (optional)" class="w-full border p-3 rounded"></textarea>

<hr class="my-4">

<label class="block font-medium">Hospital Logo</label>
<input type="file" name="logo" class="w-full">

<button class="bg-teal-600 text-white px-6 py-3 rounded-lg mt-4">
Save Hospital
</button>

</form>

<?php include("../../includes/footer.php"); ?>