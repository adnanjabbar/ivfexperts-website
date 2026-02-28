<?php
$pageTitle = "Financial Dashboard";
require_once __DIR__ . '/includes/auth.php';

// Quick metrics
$revenue = 0;
$expenses = 0;

try {
    $resR = $conn->query("SELECT SUM(amount) as r FROM receipts");
    if ($resR)
        $revenue = floatval($resR->fetch_assoc()['r'] ?? 0);

    $resE = $conn->query("SELECT SUM(amount) as e FROM expenses");
    if ($resE)
        $expenses = floatval($resE->fetch_assoc()['e'] ?? 0);
}
catch (Exception $e) {
}

$net_profit = $revenue - $expenses;
$profit_margin = $revenue > 0 ? round(($net_profit / $revenue) * 100, 1) : 0;

// Fetch Recent Income (Receipts)
$recent_receipts = [];
try {
    $stmt = $conn->query("SELECT r.id, r.receipt_date, r.procedure_name, r.amount, p.first_name, p.last_name FROM receipts r JOIN patients p ON r.patient_id = p.id ORDER BY r.receipt_date DESC LIMIT 10");
    if ($stmt) {
        while ($row = $stmt->fetch_assoc())
            $recent_receipts[] = $row;
    }
}
catch (Exception $e) {
}

// Fetch Recent Expenses
$recent_expenses = [];
try {
    $stmt = $conn->query("SELECT * FROM expenses ORDER BY expense_date DESC LIMIT 10");
    if ($stmt) {
        while ($row = $stmt->fetch_assoc())
            $recent_expenses[] = $row;
    }
}
catch (Exception $e) {
}

include __DIR__ . '/includes/header.php';
?>

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">Financial Overview</h2>
    <div class="flex gap-2">
        <a href="expenses_add.php" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-minus cursor-pointer"></i> Log Expense
        </a>
        <a href="receipts_add.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus cursor-pointer"></i> Generate Receipt
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 mt-2">
    <!-- Revenue -->
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center opacity-50">
            <i class="fa-solid fa-money-bill-wave text-3xl text-emerald-300 ml-4 mt-4"></i>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
        <h3 class="text-3xl font-bold text-gray-800">Rs. <?php echo number_format($revenue, 2); ?></h3>
    </div>
    
    <!-- Expenses -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full flex items-center justify-center opacity-50">
            <i class="fa-solid fa-chart-line text-3xl text-red-300 ml-4 mt-4"></i>
        </div>
        <p class="text-sm font-medium text-gray-500 mb-1">Total Expenses (IVF, IUI, Meds)</p>
        <h3 class="text-3xl font-bold text-gray-800">Rs. <?php echo number_format($expenses, 2); ?></h3>
    </div>

    <!-- Net Profit -->
    <div class="bg-white rounded-2xl shadow-sm border <?php echo $net_profit >= 0 ? 'border-teal-200 bg-teal-50' : 'border-amber-200 bg-amber-50'; ?> p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium <?php echo $net_profit >= 0 ? 'text-teal-700' : 'text-amber-700'; ?> mb-1">Net Profit</p>
                <h3 class="text-3xl font-bold <?php echo $net_profit >= 0 ? 'text-teal-900' : 'text-amber-900'; ?>">Rs. <?php echo number_format($net_profit, 2); ?></h3>
            </div>
            <div class="<?php echo $net_profit >= 0 ? 'bg-teal-100 text-teal-800' : 'bg-amber-100 text-amber-800'; ?> text-xs font-bold px-2 py-1 rounded">
                <?php echo $profit_margin; ?>% margin
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Income Stream -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-arrow-trend-up text-emerald-600 mr-2"></i> Recent Income</h3>
        </div>
        <div class="p-0">
            <table class="w-full text-left text-sm text-gray-600">
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($recent_receipts)): ?>
                        <tr><td class="p-6 text-center text-gray-400">No income recorded.</td></tr>
                    <?php
else:
    foreach ($recent_receipts as $r): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?php echo esc($r['procedure_name']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo esc($r['first_name'] . ' ' . $r['last_name']); ?> • <?php echo date('d M', strtotime($r['receipt_date'])); ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-emerald-600">+ Rs. <?php echo number_format($r['amount'], 2); ?></span>
                                <div class="text-xs text-gray-400 mt-1">
                                    <a href="#" class="hover:text-emerald-700 underline" onclick="alert('Print engine loading')">Print Receipt</a>
                                </div>
                            </td>
                        </tr>
                    <?php
    endforeach;
endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Expense Stream -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-50/50">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-arrow-trend-down text-red-600 mr-2"></i> Recent Expenses</h3>
        </div>
        <div class="p-0">
            <table class="w-full text-left text-sm text-gray-600">
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($recent_expenses)): ?>
                        <tr><td class="p-6 text-center text-gray-400">No expenses recorded.</td></tr>
                    <?php
else:
    foreach ($recent_expenses as $e): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800"><?php echo esc($e['title']); ?></div>
                                <div class="text-xs text-gray-500">Cat: <?php echo esc($e['category'] ?: 'Uncategorized'); ?> • <?php echo date('d M', strtotime($e['expense_date'])); ?></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-red-600">- Rs. <?php echo number_format($e['amount'], 2); ?></span>
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

<?php include __DIR__ . '/includes/footer.php'; ?>
