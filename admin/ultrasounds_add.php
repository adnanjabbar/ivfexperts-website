<?php
$pageTitle = "Write Ultrasound Report";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';
$pre_patient_id = $_GET['patient_id'] ?? '';

// Generate QR hash
$qrcode_hash = bin2hex(random_bytes(16));

// Fetch required data
$patients = [];
$res = $conn->query("SELECT id, mr_number, first_name, last_name FROM patients ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $patients[] = $row;
}

$hospitals = [];
$res = $conn->query("SELECT id, name FROM hospitals ORDER BY name ASC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $hospitals[] = $row;
}

$templates = [];
$res = $conn->query("SELECT id, title, body FROM ultrasound_templates ORDER BY title ASC");
if ($res) {
    while ($row = $res->fetch_assoc())
        $templates[] = $row;
}

// Save Ultrasound
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_usg'])) {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    $hospital_id = intval($_POST['hospital_id'] ?? 0);
    $report_title = trim($_POST['report_title'] ?? 'Ultrasound Report');
    $content = trim($_POST['content'] ?? '');

    if (empty($patient_id) || empty($hospital_id) || empty($content)) {
        $error = "Patient, Hospital, and Report Content are required.";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO patient_ultrasounds (patient_id, hospital_id, qrcode_hash, report_title, content) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iisss", $patient_id, $hospital_id, $qrcode_hash, $report_title, $content);
            if ($stmt->execute()) {
                header("Location: ultrasounds.php?msg=saved");
                exit;
            }
            else {
                $error = "Database Error: " . $stmt->error;
            }
        }
    }
}

// Map templates to JS
$templates_json = json_encode(array_column($templates, 'body', 'id'));

$extraHead = '<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>';
$extraHead .= '<script>
  tinymce.init({
    selector: "#usg_content",
    plugins: "lists link table code preview",
    toolbar: "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | table | preview",
    menubar: false,
    height: 600
  });
</script>';

include __DIR__ . '/includes/header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-image text-sky-600 mr-2"></i> Patient Ultrasound Report</h3>
        </div>
        
        <div class="p-6">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i> <?php echo esc($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Patient -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Patient *</label>
                        <select name="patient_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                            <option value="">-- Choose Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo($pre_patient_id == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo esc($p['mr_number'] . ' | ' . $p['first_name'] . ' ' . $p['last_name']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                    </div>

                    <!-- Hospital -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hospital (Print Location) *</label>
                        <select name="hospital_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                            <?php foreach ($hospitals as $h): ?>
                                <option value="<?php echo $h['id']; ?>"><?php echo esc($h['name']); ?></option>
                            <?php
endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Load Template -->
                    <div class="lg:col-span-1 border-l pl-6 border-gray-100 hidden md:block">
                        <label class="block text-sm font-bold text-sky-700 mb-1">Load Template</label>
                        <select id="template_loader" onchange="loadTemplate(this.value)" class="w-full px-4 py-3 bg-sky-50 border border-sky-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 text-sky-800">
                            <option value="">-- Start from Scratch --</option>
                            <?php foreach ($templates as $t): ?>
                                <option value="<?php echo $t['id']; ?>"><?php echo esc($t['title']); ?></option>
                            <?php
endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-2">Loading a template will overwrite current editor contents.</p>
                    </div>
                </div>

                <!-- Report Title -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Title *</label>
                    <input type="text" name="report_title" id="report_title" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="e.g. Scrotal Ultrasound / Penile Doppler" required>
                </div>

                <!-- WYSIWYG Editor -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clinical Findings & Report *</label>
                    <textarea name="content" id="usg_content"></textarea>
                </div>
                
                <div class="flex justify-end gap-3 mt-8">
                    <a href="ultrasounds.php" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-colors">Cancel</a>
                    <button type="submit" name="save_usg" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none flex items-center gap-2">
                        <i class="fa-solid fa-file-signature"></i> Finalize Report
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
const templatesData = <?php echo $templates_json; ?>;

function loadTemplate(id) {
    if (!id) return;
    if (confirm("Loading this template will replace any text currently in the editor. Proceed?")) {
        const body = templatesData[id];
        if (tinymce.get('usg_content')) {
            tinymce.get('usg_content').setContent(body);
        }
        
        // Auto-fill title
        const select = document.getElementById('template_loader');
        const text = select.options[select.selectedIndex].text;
        document.getElementById('report_title').value = text;
    } else {
        document.getElementById('template_loader').value = "";
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
