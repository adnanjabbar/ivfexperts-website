<?php
$pageTitle = "Write Prescription";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';
$pre_patient_id = $_GET['patient_id'] ?? '';

// Generate hash for public portal verifying
$qrcode_hash = bin2hex(random_bytes(16));

// Removed patient loop memory overhead, using AJAX search API.

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
    $complaint = trim($_POST['presenting_complaint'] ?? '');

    // ICD / CPT / Revisit
    $icd_disease = trim($_POST['icd_disease'] ?? '');
    $icd_code = trim($_POST['icd_code'] ?? '');
    $cpt_procedure = trim($_POST['cpt_procedure'] ?? '');
    $cpt_code = trim($_POST['cpt_code'] ?? '');
    $revisit_date = !empty($_POST['revisit_date']) ? $_POST['revisit_date'] : null;

    $meds_array = $_POST['med_id'] ?? [];
    $dosages = $_POST['dosage'] ?? [];
    $usage_freqs = $_POST['usage_frequency'] ?? [];
    $durations = $_POST['duration'] ?? [];
    $instructions = $_POST['instructions'] ?? [];

    if (empty($patient_id) || empty($hospital_id)) {
        $error = "Patient and Hospital fields are required.";
    }
    else {
        $conn->begin_transaction();
        try {
            // Insert Prescript Master
            $stmt = $conn->prepare("INSERT INTO prescriptions (patient_id, hospital_id, qrcode_hash, presenting_complaint, icd_code, icd_disease, cpt_code, cpt_procedure, revisit_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssssss", $patient_id, $hospital_id, $qrcode_hash, $complaint, $icd_code, $icd_disease, $cpt_code, $cpt_procedure, $revisit_date, $notes);
            $stmt->execute();
            $rx_id = $conn->insert_id;

            // Insert Items
            if (!empty($meds_array)) {
                $stmt_items = $conn->prepare("INSERT INTO prescription_items (prescription_id, medication_id, dosage, usage_frequency, duration, instructions) VALUES (?, ?, ?, ?, ?, ?)");
                foreach ($meds_array as $k => $m_id) {
                    if (!empty($m_id)) {
                        $dos = trim($dosages[$k] ?? '');
                        $ufreq = trim($usage_freqs[$k] ?? '');
                        $dur = trim($durations[$k] ?? '');
                        $ins = trim($instructions[$k] ?? '');
                        $stmt_items->bind_param("iissss", $rx_id, $m_id, $dos, $ufreq, $dur, $ins);
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- AJAX Patient Search -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search Patient (MR / Phone / Name) *</label>
                        <input type="hidden" name="patient_id" :value="selectedPatientId" required>
                        <div class="relative">
                            <input type="text" x-model="patQuery" @input.debounce.300ms="searchPatient" placeholder="Type to search..." 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" 
                                :class="selectedPatientId ? 'bg-indigo-50 border-indigo-300 font-bold text-indigo-800 shadow-inner' : ''" autocomplete="off">
                            <div x-show="patLoading" class="absolute right-3 top-3.5 text-indigo-500">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </div>
                        </div>
                        
                        <!-- Dropdown -->
                        <div x-show="patResults.length > 0 && !selectedPatientId" @click.away="patResults = []" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-2xl overflow-hidden max-h-64 overflow-y-auto">
                            <template x-for="p in patResults" :key="p.id">
                                <div @click="selectPatient(p)" class="px-4 py-3 border-b border-gray-100 hover:bg-indigo-50 cursor-pointer transition-colors">
                                    <div class="font-bold text-gray-800 flex justify-between">
                                        <span x-text="p.first_name + ' ' + (p.last_name || '')"></span>
                                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded border border-gray-200" x-text="p.gender"></span>
                                    </div>
                                    <div class="text-xs text-gray-500 flex gap-3 mt-1.5">
                                        <span class="font-mono text-indigo-700 font-bold bg-indigo-100 px-1.5 py-0.5 rounded" x-text="'MR: ' + p.mr_number"></span>
                                        <span x-html="'<i class=\'fa-solid fa-phone text-[9px]\'></i> ' + (p.phone || 'N/A')" class="flex items-center gap-1"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedPatientId" class="text-xs text-red-500 mt-2 font-medium cursor-pointer hover:underline flex items-center gap-1 w-max" @click="clearPatient()">
                            <i class="fa-solid fa-times-circle"></i> Clear Selection
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hospital / Letterhead *</label>
                        <select name="hospital_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <?php foreach ($hospitals as $h): ?>
                                <option value="<?php echo $h['id']; ?>"><?php echo esc($h['name']); ?></option>
                            <?php
endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Select the hospital to accurately print on its specific letterhead margins.</p>
                    </div>
                </div>

                <!-- Clinical Details (Complaint & ICD Search) -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                    <h4 class="font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Clinical Assesment & Diagnosis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Presenting Complaint / History</label>
                            <textarea name="presenting_complaint" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:-ring-indigo-500 text-sm" placeholder="Patient presented with..."></textarea>
                        </div>
                        
                        <!-- ICD-10 Search via NIH API -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex justify-between">
                                <span>Diagnosis (ICD-10)</span>
                                <span class="text-[10px] text-gray-400 font-bold bg-white px-1 border rounded">LIVE NIH DB</span>
                            </label>
                            <input type="hidden" name="icd_code" :value="icdCode">
                            <div class="relative">
                                <input type="text" name="icd_disease" x-model="icdQuery" @input.debounce.400ms="searchIcd" placeholder="Search diagnosis..." 
                                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors text-sm" 
                                    :class="icdCode ? 'bg-emerald-50 border-emerald-300 font-semibold text-emerald-800' : ''" autocomplete="off">
                                <div x-show="icdLoading" class="absolute right-3 top-2.5 text-indigo-500">
                                    <i class="fa-solid fa-spinner fa-spin text-sm"></i>
                                </div>
                            </div>
                            
                            <!-- Dropdown -->
                            <div x-show="icdResults.length > 0 && !icdCode" @click.away="icdResults = []" class="absolute z-40 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden max-h-48 overflow-y-auto text-sm">
                                <template x-for="icd in icdResults" :key="icd[0]">
                                    <div @click="selectIcd(icd)" class="px-3 py-2 border-b border-gray-100 hover:bg-emerald-50 cursor-pointer flex gap-3">
                                        <span class="font-mono text-emerald-700 font-bold w-12 shrink-0" x-text="icd[0]"></span>
                                        <span class="text-gray-800" x-text="icd[1]"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="icdCode" class="text-[10px] text-red-500 mt-1 cursor-pointer hover:underline" @click="clearIcd()">
                                Clear ICD Assignment
                            </div>
                        </div>

                        <!-- CPT / Procedure -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Advised Procedure / SNOMED / CPT</label>
                            <div class="flex gap-2">
                                <input type="text" name="cpt_code" placeholder="Code (opt)" class="w-1/4 px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 text-sm font-mono" autocomplete="off">
                                <input type="text" name="cpt_procedure" placeholder="e.g. Scrotal Ultrasound" class="w-3/4 px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-indigo-500 text-sm" autocomplete="off">
                            </div>
                        </div>
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
                            <div class="flex flex-col md:flex-row gap-2 items-end bg-white p-3 rounded-lg border border-gray-200 shadow-sm relative pt-6 md:pt-3">
                                
                                <div class="absolute top-1 left-2 md:hidden text-[10px] font-bold text-gray-400" x-text="'Item #' + (index+1)"></div>
                                
                                <div class="w-full md:w-[35%]">
                                    <label class="block text-xs text-gray-500 mb-1">Medication *</label>
                                    <select :name="'med_id['+index+']'" class="w-full px-3 py-2 border border-gray-300 rounded text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                                        <option value="">Choose...</option>
                                        <?php foreach ($medications as $m): ?>
                                            <option value="<?php echo $m['id']; ?>"><?php echo esc($m['name'] . ' (' . $m['med_type'] . ')'); ?></option>
                                        <?php
endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="w-full md:w-[15%]">
                                    <label class="block text-xs text-gray-500 mb-1">Dosage</label>
                                    <input type="text" :name="'dosage['+index+']'" placeholder="500mg, 1 tab" class="w-full px-3 py-2 border border-gray-300 rounded text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>

                                <div class="w-full md:w-[20%]">
                                    <label class="block text-xs text-gray-500 mb-1">Usage Freq</label>
                                    <select :name="'usage_frequency['+index+']'" class="w-full px-3 py-2 border border-gray-300 rounded text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">--</option>
                                        <option value="OD">OD (1x a day)</option>
                                        <option value="BD">BD (2x a day)</option>
                                        <option value="TDS">TDS (3x a day)</option>
                                        <option value="QID">QID (4x a day)</option>
                                        <option value="SOS">SOS (When needed)</option>
                                        <option value="Stat">Stat (Immediately)</option>
                                    </select>
                                </div>

                                <div class="w-full md:w-[15%]">
                                    <label class="block text-xs text-gray-500 mb-1">Duration</label>
                                    <input type="text" :name="'duration['+index+']'" placeholder="5 Days" class="w-full px-3 py-2 border border-gray-300 rounded text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                
                                <div class="w-full md:w-[30%]">
                                    <label class="block text-xs text-gray-500 mb-1">Instructions</label>
                                    <input type="text" :name="'instructions['+index+']'" placeholder="e.g. After meals" class="w-full px-3 py-2 border border-gray-300 rounded text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                
                                <div class="w-full md:w-10 flex justify-end">
                                    <button type="button" @click="removeRow(index)" class="text-gray-400 hover:text-red-600 hover:bg-red-50 w-full md:w-10 h-9 rounded-md transition-colors border border-transparent md:border-gray-200 mt-2 md:mt-0" title="Remove row">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                
                            </div>
                        </template>
                        <div x-show="rows.length === 0" class="text-center py-6 text-gray-400 text-sm">
                            Click <strong class="text-indigo-600">"Add Medication"</strong> to assign drugs to this prescription.
                        </div>
                    </div>
                </div>

                <!-- Follow-up & Notes -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">General Advice / Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Follow-up exactly after 14 days for beta-hCG..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Revisit / Follow-up Date</label>
                        <input type="date" name="revisit_date" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-800">
                        <p class="text-[10px] text-gray-400 mt-2">Set this to automatically print the next visit date on the bottom of the prescription.</p>
                    </div>
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
        
        // --- Patient Search ---
        patQuery: '',
        patResults: [],
        patLoading: false,
        selectedPatientId: '',
        
        async searchPatient() {
            if (this.selectedPatientId) return;
            if (this.patQuery.length < 2) {
                this.patResults = []; return;
            }
            this.patLoading = true;
            try {
                let res = await fetch(`api_search_patients.php?q=${encodeURIComponent(this.patQuery)}`);
                this.patResults = await res.json();
            } catch (e) {
                console.error(e);
            }
            this.patLoading = false;
        },
        
        selectPatient(p) {
            this.selectedPatientId = p.id;
            this.patQuery = p.first_name + ' ' + (p.last_name || '') + ' (' + p.mr_number + ')';
            this.patResults = [];
        },
        
        clearPatient() {
            this.selectedPatientId = '';
            this.patQuery = '';
            this.patResults = [];
        },

        // --- ICD-10 Search (NIH API) ---
        icdQuery: '',
        icdResults: [],
        icdLoading: false,
        icdCode: '',

        async searchIcd() {
            if (this.icdCode) return;
            if (this.icdQuery.length < 2) {
                this.icdResults = []; return;
            }
            this.icdLoading = true;
            try {
                // NIH Clinical Tables API for ICD-10
                let url = `https://clinicaltables.nlm.nih.gov/api/icd10cm/v3/search?sf=code,name&terms=${encodeURIComponent(this.icdQuery)}&maxList=10`;
                let res = await fetch(url);
                let data = await res.json();
                // API format: [count, [codes], null, [[code, name], ...]]
                this.icdResults = data[3] || [];
            } catch (e) {
                console.error('ICD API Error:', e);
            }
            this.icdLoading = false;
        },

        selectIcd(icdArr) {
            this.icdCode = icdArr[0]; // e.g. N46
            this.icdQuery = icdArr[1]; // e.g. Male infertility
            this.icdResults = [];
        },

        clearIcd() {
            this.icdCode = '';
            this.icdQuery = '';
            this.icdResults = [];
        },

        // --- Rx Rows ---
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
