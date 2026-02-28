<?php
$pageTitle = "Log Expense";
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $title = trim($_POST['title'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $category = trim($_POST['category'] ?? 'General');
    $date = $_POST['expense_date'] ?? date('Y-m-d');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($title) || $amount <= 0) {
        $error = "Title and a valid Amount are required.";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO expenses (title, amount, expense_date, category, notes) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sdsss", $title, $amount, $date, $category, $notes);
            if ($stmt->execute()) {
                header("Location: financials.php?msg=exp_saved");
                exit;
            }
            else {
                $error = "DB Error: " . $stmt->error;
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="financials.php" class="text-sm text-gray-500 hover:text-red-600 font-medium flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Back to Financials
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-900 text-white flex justify-between items-center">
            <h3 class="font-bold"><i class="fa-solid fa-minus-circle text-red-300 mr-2"></i> Log New Expense</h3>
        </div>
        
        <div class="p-6 md:p-8">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 flex gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i> <?php echo esc($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST">
                <div class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expense Title / Item *</label>
                        <input type="text" name="title" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500" placeholder="e.g. IVF Media, Needles, IUI kit..." required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (Rs) *</label>
                            <input type="number" step="0.01" name="amount" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 font-mono" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <input type="date" name="expense_date" value="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                            <option value="IVF Products">IVF Products</option>
                            <option value="Medications">Medications</option>
                            <option value="Lab Consumables">Lab Consumables</option>
                            <option value="Office / Utility">Office / Utility</option>
                            <option value="Other" selected>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes / Vendor details</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500"></textarea>
                    </div>

                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" name="save" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none w-full">
                        Log Expense Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
