<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/auth.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";

$pageTitle = "Add New Patient - IVF Experts Admin";

$error = "";
$success = "";
$formData = $_POST; // repopulate on error

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mr_number = trim($_POST['mr_number'] ?? '');
    $name      = trim($_POST['name'] ?? '');
    $age       = !empty($_POST['age']) ? (int)$_POST['age'] : null;
    $phone     = trim($_POST['phone'] ?? '');
    $cnic      = trim($_POST['cnic'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $email     = trim($_POST['email'] ?? '');

    // Validation
    if (empty($mr_number) || empty($name)) {
        $error = "MR Number and Name are required.";
    } else {
        // Check duplicate MR
        $stmt = $conn->prepare("SELECT id FROM patients WHERE mr_number = ?");
        $stmt->bind_param("s", $mr_number);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "MR Number already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO patients (mr_number, name, age, phone, cnic, address, email) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssissss", $mr_number, $name, $age, $phone, $cnic, $address, $email);

            if ($stmt->execute()) {
                $success = "Patient added successfully!";
                $formData = []; // clear form
            } else {
                $error = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}

include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/header.php";
?>

<div class="p-6 lg:p-10 max-w-4xl mx-auto">
    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Add New Patient</h1>
    <p class="text-gray-600 mb-10">Enter patient details to register</p>

    <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 mb-8 rounded-xl">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-6 mb-8 rounded-xl">
            <?= htmlspecialchars($success) ?>
            <a href="list.php" class="underline ml-4 font-medium">View All Patients</a>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-8 bg-white rounded-2xl shadow-lg p-8 lg:p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">MR Number <span class="text-red-600">*</span></label>
                <input type="text" name="mr_number" value="<?= htmlspecialchars($formData['mr_number'] ?? '') ?>" required
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Full Name <span class="text-red-600">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Age</label>
                <input type="number" name="age" value="<?= htmlspecialchars($formData['age'] ?? '') ?>" min="1"
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">CNIC / NIC</label>
                <input type="text" name="cnic" value="<?= htmlspecialchars($formData['cnic'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                       class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Address</label>
                <textarea name="address" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition"><?= htmlspecialchars($formData['address'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="list.php" class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-medium transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-semibold transition shadow-md hover:shadow-lg">
                Save Patient
            </button>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/footer.php"; ?>