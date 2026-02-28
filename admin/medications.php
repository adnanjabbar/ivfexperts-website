<?php
$pageTitle = "Medication Arsenal";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';

// Handle Add Medication
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_med'])) {
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['med_type'] ?? 'Other');
    $vendor = trim($_POST['vendor'] ?? '');
    $price = !empty($_POST['price']) ? floatval($_POST['price']) : null;

    if (empty($name)) {
        $error = "Medication name is required.";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO medications (name, med_type, vendor, price) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssd", $name, $type, $vendor, $price);
            if ($stmt->execute()) {
                $success = "Medication added successfully!";
            }
            else {
                $error = "Failed to add medication: " . $stmt->error;
            }
        }
    }
}

// Fetch Medications
$meds = [];
try {
    $res = $conn->query("SELECT * FROM medications ORDER BY name ASC");
    if ($res) {
        while ($row = $res->fetch_assoc())
            $meds[] = $row;
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Add Form Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-teal-900 text-white">
                <h3 class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-pills text-teal-300"></i> Add to Arsenal
                </h3>
            </div>
            <div class="p-6">
                <?php if (!empty($error)): ?>
                    <div class="text-sm text-red-600 bg-red-50 p-3 rounded-lg mb-4 border border-red-100 flex gap-2">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i> <?php echo esc($error); ?>
                    </div>
                <?php
endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="text-sm text-emerald-700 bg-emerald-50 p-3 rounded-lg mb-4 border border-emerald-100 flex gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5"></i> <?php echo esc($success); ?>
                    </div>
                <?php
endif; ?>

                <form method="POST">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medication Name *</label>
                            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm" placeholder="e.g. Gonal-f, Letrozole" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select name="med_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 bg-white text-sm">
                                <option value="Injection">Injection</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Sachet">Sachet</option>
                                <option value="Syrup">Syrup</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor / Pharmacy</label>
                            <input type="text" name="vendor" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm" placeholder="Optional">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price / Cost (Rs)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm">Rs.</span>
                                <input type="number" step="0.01" name="price" class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 text-sm">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="add_med" class="w-full mt-6 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                        Add Medication
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Inventory List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Arsenal Inventory</h3>
                <span class="text-xs text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 font-medium"><?php echo count($meds); ?> total</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Vendor</th>
                            <th class="px-6 py-4 text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($meds)): ?>
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Your arsenal is currently empty. Add medications to build presets.</td></tr>
                        <?php
else:
    foreach ($meds as $m): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-bold text-gray-800"><?php echo esc($m['name']); ?></td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium">
                                        <?php echo esc($m['med_type']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-xs">
                                    <?php echo esc($m['vendor'] ?: 'N/A'); ?>
                                </td>
                                <td class="px-6 py-3 text-right font-mono text-teal-700">
                                    <?php echo $m['price'] ? 'Rs. ' . number_format($m['price'], 2) : '-'; ?>
                                </td>
                            </tr>
                        <?php
    endforeach;
endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
