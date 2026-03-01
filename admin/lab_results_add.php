<?php
require_once __DIR__ . '/includes/auth.php';
require_once dirname(__DIR__) . '/config/db.php';

$error = '';
$success = '';

// Check if directories exist
$upload_dir = dirname(__DIR__) . '/uploads/labs/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Fetch all defined tests
$tests = [];
$res = $conn->query("SELECT id, test_name, unit, reference_range FROM lab_tests_directory ORDER BY test_name ASC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $tests[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_lab_result'])) {

    $patient_id = intval($_POST['patient_id'] ?? 0);
    $test_id = intval($_POST['test_id'] ?? 0);
    $result_value = trim($_POST['result_value'] ?? '');
    $test_date = trim($_POST['test_date'] ?? date('Y-m-d'));

    $lab_name = trim($_POST['lab_name'] ?? '');
    $lab_city = trim($_POST['lab_city'] ?? '');
    $lab_mr_number = trim($_POST['lab_mr_number'] ?? '');

    if ($patient_id === 0 || $test_id === 0 || empty($result_value)) {
        $error = "Patient, Test, and Result Value are required.";
    }
    else {

        $file_path = null;

        // Handle File Upload
        if (isset($_FILES['scanned_report']) && $_FILES['scanned_report']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['scanned_report']['tmp_name'];
            $file_name = $_FILES['scanned_report']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $allowed_exts)) {
                $error = "Invalid file format. Only PDF, JPG, and PNG are allowed.";
            }
            else {
                $new_file_name = "lab_pt{$patient_id}_test{$test_id}_" . time() . '.' . $file_ext;
                $dest_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $dest_path)) {
                    $file_path = 'uploads/labs/' . $new_file_name;
                }
                else {
                    $error = "Failed to save the uploaded file.";
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $conn->prepare("INSERT INTO patient_lab_results 
                    (patient_id, lab_city, lab_name, lab_mr_number, test_date, test_id, result_value, scanned_report_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("isssisss",
                    $patient_id,
                    $lab_city,
                    $lab_name,
                    $lab_mr_number,
                    $test_date,
                    $test_id,
                    $result_value,
                    $file_path
                );

                if ($stmt->execute()) {
                    header("Location: lab_results.php?success=1");
                    exit;
                }
                else {
                    $error = "Database insertion failed: " . $stmt->error;
                }
            }
            catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}

$pageTitle = 'Record New Lab Result';
include __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="lab_results.php" class="text-sm text-gray-500 hover:text-indigo-600 font-medium flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Back to Results
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Add New Laboratory Result</h3>
        </div>
        
        <div class="p-6 md:p-8">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST" enctype="multipart/form-data">
                
                <!-- Patient Selection (AJAX component via Alpine) -->
                <div class="mb-8" x-data="patientSearch()">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Select Patient *</label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400"></i>
                        </div>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchPatients()" placeholder="Search by name, MR number, or phone..." class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-gray-50" autocomplete="off">
                        
                        <!-- Search Results Dropdown -->
                        <div x-show="results.length > 0 && showResults" @click.away="showResults = false" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto" x-cloak>
                            <template x-for="pt in results" :key="pt.id">
                                <div @click="selectPatient(pt)" class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                                    <div class="font-bold text-gray-800" x-text="pt.first_name + ' ' + (pt.last_name || '')"></div>
                                    <div class="text-xs text-gray-500 mt-1 flex gap-3">
                                        <span class="text-indigo-600 font-mono font-medium" x-text="pt.mr_number"></span>
                                        <span x-text="pt.phone || 'No phone'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Selected Patient Card -->
                    <div x-show="selectedPatient" x-cloak class="mt-4 bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex justify-between items-center transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-indigo-600 shadow-sm font-bold shadow-indigo-200">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 leading-tight" x-text="selectedPatient?.first_name + ' ' + (selectedPatient?.last_name || '')"></div>
                                <div class="text-xs text-indigo-700 font-mono mt-0.5" x-text="'MR: ' + selectedPatient?.mr_number"></div>
                            </div>
                        </div>
                        <button type="button" @click="clearSelection()" class="text-indigo-400 hover:text-indigo-600 text-sm font-medium px-2 py-1 hover:bg-indigo-100 rounded transition-colors">
                            Change
                        </button>
                    </div>

                    <input type="hidden" name="patient_id" :value="selectedPatient?.id || ''" required>
                </div>

                <hr class="border-gray-100 my-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Test Selection -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Select Laboratory Test *</label>
                        <select name="test_id" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <option value="">-- Choose Test --</option>
                            <?php foreach ($tests as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo((isset($_POST['test_id']) && $_POST['test_id'] == $t['id']) ? 'selected' : ''); ?>>
                                    <?php echo htmlspecialchars($t['test_name']); ?> 
                                    <?php if ($t['reference_range'])
        echo " (Ref: {$t['reference_range']} {$t['unit']})"; ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                    </div>

                    <!-- Result Value -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Result Value *</label>
                        <input type="text" name="result_value" value="<?php echo htmlspecialchars($_POST['result_value'] ?? ''); ?>" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-gray-50" placeholder="e.g. 2.45">
                    </div>

                    <!-- Test Date -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Test Performance Date *</label>
                        <input type="date" name="test_date" value="<?php echo htmlspecialchars($_POST['test_date'] ?? date('Y-m-d')); ?>" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                    </div>

                </div>

                <hr class="border-gray-100 my-8">

                <!-- Lab Details -->
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-building text-gray-400"></i> External Laboratory Details</h4>
                <p class="text-xs text-gray-500 mb-4">If the test was performed externally, record the details for correlation.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lab Name</label>
                        <input type="text" name="lab_name" value="<?php echo htmlspecialchars($_POST['lab_name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Chughtai Lab">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lab City</label>
                        <input type="text" name="lab_city" value="<?php echo htmlspecialchars($_POST['lab_city'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Lahore">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lab Patient MR / ID</label>
                        <input type="text" name="lab_mr_number" value="<?php echo htmlspecialchars($_POST['lab_mr_number'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="External lab tracking ID">
                    </div>
                </div>

                <hr class="border-gray-100 my-8">

                <!-- File Attachment -->
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 bg-gray-50 text-center hover:border-indigo-300 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-3"></i>
                    <h4 class="font-bold text-gray-800 mb-1">Attach Scanned Report</h4>
                    <p class="text-xs text-gray-500 mb-4">Upload the PDF, JPG, or PNG of the physical lab report (Optional).</p>
                    <input type="file" name="scanned_report" accept=".pdf, .jpg, .jpeg, .png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="lab_results.php" class="px-6 py-3 font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200 shadow-sm">
                        Cancel
                    </a>
                    <button type="submit" name="save_lab_result" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition-colors shadow-lg shadow-indigo-200 flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Lab Result
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Patient Search Script using Alpine.js -->
<script>
function patientSearch() {
    return {
        searchQuery: '',
        results: [],
        showResults: false,
        selectedPatient: null,
        
        async searchPatients() {
            if (this.searchQuery.length < 2) {
                this.results = [];
                this.showResults = false;
                return;
            }
            
            try {
                const response = await fetch(`ajax_search_patients.php?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                this.results = data;
                this.showResults = true;
            } catch (error) {
                console.error('Error searching patients:', error);
            }
        },
        
        selectPatient(pt) {
            this.selectedPatient = pt;
            this.searchQuery = '';
            this.showResults = false;
        },
        
        clearSelection() {
            this.selectedPatient = null;
            // Optionally, we could put the patient's name back in the search box
            setTimeout(() => { document.querySelector('input[x-model="searchQuery"]').focus(); }, 100);
        }
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
