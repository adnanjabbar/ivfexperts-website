<?php
$pageTitle = "Write Prescription";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';
$pre_patient_id = $_GET['patient_id'] ?? '';

// Generate hash for public portal verifying
$qrcode_hash = bin2hex(random_bytes(16));

// Fetch Patients
$patients = [];
try {
    $res = $conn->query("SELECT id, mr_number, first_name, last_name, phone FROM patients ORDER BY id DESC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $patients[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Hospitals
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

// Fetch Medications DB for Vue/Alpine data
$medications = [];
try {
    $res = $conn->query("SELECT id, name, med_type FROM medications ORDER BY name ASC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $medications[] = $row;
    }
}
catch (Exception $e) {
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_rx'])) {
    $patient_id = intval($_POST['patient_id'] ?? 0);
    $hospital_id = intval($_POST['hospital_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');
    $meds_array = $_POST['med_id'] ?? [];
    $dosages = $_POST['dosage'] ?? [];
    $instructions = $_POST['instructions'] ?? [];

    if (empty($patient_id) || empty($hospital_id)) {
        $error = "Patient and Hospital fields are required.";
    }
    else {
        $conn->begin_transaction();
        try {
            // Insert Prescript Master
            $stmt = $conn->prepare("INSERT INTO prescriptions (patient_id, hospital_id, qrcode_hash, notes) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $patient_id, $hospital_id, $qrcode_hash, $notes);
            $stmt->execute();
            $rx_id = $conn->insert_id;

            // Insert Items
            if (!empty($meds_array)) {
                $stmt_items = $conn->prepare("INSERT INTO prescription_items (prescription_id, medication_id, dosage, instructions) VALUES (?, ?, ?, ?)");
                foreach ($meds_array as $k => $m_id) {
                    if (!empty($m_id)) {
                        $dos = trim($dosages[$k] ?? '');
                        $ins = trim($instructions[$k] ?? '');
                        $stmt_items->bind_param("iiss", $rx_id, $m_id, $dos, $ins);
                        $stmt_items->execute();
                    }
                }
            }
            $conn->commit();
            header("Location: prescriptions.php?msg=rx_saved");
            exit;
        }
        catch (Exception $e) {
            $conn->rollback();
            $error = "Failed to save prescription: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-prescription text-indigo-600 mr-2"></i> New E-Prescription</h3>
        </div>
        
        <div class="p-6">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i> <?php echo esc($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST" x-data="prescriptionBuilder()">
                
                <!-- Setup Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Patient *</label>
                        <select name="patient_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                            <option value="">-- Choose Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo($pre_patient_id == $p['id']) ? 'selected' : ''; ?>>
                                    <?php echo esc($p['mr_number'] . ' | ' . $p['first_name'] . ' ' . $p['last_name']); ?>
                                </option>
                            <?php
endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hospital / Letterhead *</label>
                        <select name="hospital_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                            <?php foreach ($hospitals as $h): ?>
                                <option value="<?php echo $h['id']; ?>"><?php echo esc($h['name']); ?></option>
                            <?php
endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Select the hospital to accurately print on its specific letterhead margins.</p>
                    </div>
                </div>

                <!-- Dynamic Medication Builder -->
                <div class="border border-gray-200 rounded-xl overflow-hidden mb-6 mt-4">
                    <div class="bg-indigo-900 text-white px-4 py-3 flex justify-between items-center">
                        <span class="font-bold">Rx. Medications</span>
                        <button type="button" @click="addRow" class="bg-indigo-700 hover:bg-indigo-600 px-3 py-1 rounded text-sm transition-colors text-white border border-indigo-500 shadow-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Medication
                        </button>
                    </div>
                    
                    <div class="p-4 bg-gray-50 space-y-3">
                        <template x-for="(row, index) in rows" :key="row.id">
                            <div class="flex flex-col md:flex-row gap-3 items-end md:items-center bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                
                                <div class="w-full md:w-1/3">
                                    <label class="block text-xs text-gray-500 mb-1">Medication *</label>
                                    <select :name="'med_id['+index+']'" class="w-full px-3 py-2 border rounded-md text-sm outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500" required>
                                        <option value="">Choose...</option>
                                        <?php foreach ($medications as $m): ?>
                                            <option value="<?php echo $m['id']; ?>"><?php echo esc($m['name'] . ' (' . $m['med_type'] . ')'); ?></option>
                                        <?php
endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="w-full md:w-1/4">
                                    <label class="block text-xs text-gray-500 mb-1">Dosage</label>
                                    <input type="text" :name="'dosage['+index+']'" placeholder="e.g. 150 IU, 1 tab" class="w-full px-3 py-2 border rounded-md text-sm outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                                </div>
                                
                                <div class="w-full md:w-1/3">
                                    <label class="block text-xs text-gray-500 mb-1">Instructions</label>
                                    <input type="text" :name="'instructions['+index+']'" placeholder="e.g. Daily, after meals" class="w-full px-3 py-2 border rounded-md text-sm outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                                </div>
                                
                                <div class="w-full md:w-12 flex justify-end">
                                    <button type="button" @click="removeRow(index)" class="text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Remove row">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                
                            </div>
                        </template>
                        <div x-show="rows.length === 0" class="text-center py-6 text-gray-400 text-sm">
                            Click <strong class="text-indigo-600">"Add Medication"</strong> to begin building the prescription.
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clinical Notes & Advice</label>
                    <textarea name="notes" rows="4" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Follow-up exactly after 14 days for beta-hCG..."></textarea>
                </div>
                
                <div class="flex justify-end gap-3 mt-8">
                    <a href="patients.php" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-colors">Cancel</a>
                    <button type="submit" name="save_rx" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center gap-2">
                        <i class="fa-solid fa-file-signature"></i> Save & Generate Prescription
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('prescriptionBuilder', () => ({
        rows: [{id: 1}],
        nextId: 2,
        addRow() {
            this.rows.push({ id: this.nextId++ });
        },
        removeRow(index) {
            this.rows.splice(index, 1);
        }
    }))
})
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
