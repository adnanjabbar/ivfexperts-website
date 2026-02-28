<?php
$pageTitle = "Register New Patient";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';

// Auto-generate MR Number proposal
$auto_mr = "IVF-" . date("ymd") . "-" . rand(1000, 9999);

// Fetch Hospitals for dropdown
$hospitals = [];
try {
    $res = $conn->query("SELECT id, name FROM hospitals ORDER BY name ASC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $hospitals[] = $row;
    }
}
catch (Exception $e) {
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mr_number = trim($_POST['mr_number']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $spouse_name = trim($_POST['spouse_name']);
    $cnic = trim($_POST['cnic']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $hospital_id = !empty($_POST['hospital_id']) ? $_POST['hospital_id'] : null;

    if (empty($mr_number) || empty($first_name) || empty($gender)) {
        $error = "MR Number, First Name, and Gender are required fields.";
    }
    else {
        try {
            $stmt = $conn->prepare("INSERT INTO patients (mr_number, first_name, last_name, spouse_name, cnic, phone, gender, referring_hospital_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssssssi", $mr_number, $first_name, $last_name, $spouse_name, $cnic, $phone, $gender, $hospital_id);
                if ($stmt->execute()) {
                    $new_id = $conn->insert_id;
                    header("Location: patients_view.php?id=" . $new_id . "&msg=created");
                    exit;
                }
                else {
                    $error = "Database Error: " . $stmt->error;
                }
            }
            else {
                $error = "Failed to prepare statement.";
            }
        }
        catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry
                $error = "MR Number '$mr_number' is already registered. Please use a unique MR Number.";
            }
            else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="patients.php" class="text-sm text-gray-500 hover:text-teal-600 font-medium flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Back to Registry
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Patient Registration Details</h3>
        </div>
        
        <div class="p-6 md:p-8">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo esc($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- MR Number -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1">MR Number *</label>
                        <div class="flex gap-2">
                            <input type="text" name="mr_number" id="mr_number" value="<?php echo esc($_POST['mr_number'] ?? $auto_mr); ?>" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 font-mono bg-gray-50" required>
                            <button type="button" onclick="document.getElementById('mr_number').value = 'IVF-' + new Date().toISOString().slice(2,10).replace(/-/g,'') + '-' + Math.floor(1000 + Math.random() * 9000)" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm shrink-0 transition-colors" title="Generate New Auto MR">
                                <i class="fa-solid fa-rotate-right"></i> Generate
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Leave as auto-generated or replace with external referring hospital MR Number.</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" value="<?php echo esc($_POST['first_name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo esc($_POST['last_name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>

                    <!-- Gender & Spouse -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gender *</label>
                        <select name="gender" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 bg-white" required>
                            <option value="Female" <?php echo(isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Male" <?php echo(isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Other" <?php echo(isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Spouse Name</label>
                        <input type="text" name="spouse_name" value="<?php echo esc($_POST['spouse_name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo esc($_POST['phone'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">CNIC / ID Number</label>
                        <input type="text" name="cnic" value="<?php echo esc($_POST['cnic'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 font-mono text-sm">
                    </div>

                    <!-- Referring Hospital -->
                    <div class="col-span-1 md:col-span-2 border-t border-gray-100 pt-6 mt-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Referring Clinic / Place of Consult</label>
                        <select name="hospital_id" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 bg-white">
                            <option value="">Direct to IVF Experts (Default)</option>
                            <?php foreach ($hospitals as $h): ?>
                                <option value="<?php echo $h['id']; ?>" <?php echo(isset($_POST['hospital_id']) && $_POST['hospital_id'] == $h['id']) ? 'selected' : ''; ?>>
                                    <?php echo esc($h['name']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">If the patient is registered under another hospital's MR, select the hospital to link them correctly.</p>
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-teal-500 flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save & Open File
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
