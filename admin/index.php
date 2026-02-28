<?php
$pageTitle = "Dashboard Overview";
require_once __DIR__ . '/includes/auth.php';

// Safe queries for stats (will gracefully handle execution errors locally if DB is unavail)
$stats = [
    'patients' => 0,
    'semen_analyses' => 0,
    'prescriptions' => 0,
    'revenue' => 0
];

try {
    $res = $conn->query("SELECT COUNT(*) as c FROM patients");
    if ($res)
        $stats['patients'] = $res->fetch_assoc()['c'];

    $res = $conn->query("SELECT COUNT(*) as c FROM semen_analyses");
    if ($res)
        $stats['semen_analyses'] = $res->fetch_assoc()['c'];

    $res = $conn->query("SELECT COUNT(*) as c FROM prescriptions");
    if ($res)
        $stats['prescriptions'] = $res->fetch_assoc()['c'];

    $res = $conn->query("SELECT SUM(amount) as s FROM receipts");
    if ($res)
        $stats['revenue'] = $res->fetch_assoc()['s'] ?? 0;
}
catch (Exception $e) {
// Suppress error if DB not seeded yet
}

include __DIR__ . '/includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-2">
    <!-- Stat 1 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Patients</p>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['patients']); ?></h3>
        </div>
    </div>
    
    <!-- Stat 2 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-microscope"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Semen Analyses</p>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['semen_analyses']); ?></h3>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-file-prescription"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Prescriptions Issued</p>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['prescriptions']); ?></h3>
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
            <i class="fa-solid fa-wallet"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Revenue Tracking</p>
            <h3 class="text-2xl font-bold text-gray-800">Rs. <?php echo number_format($stats['revenue'], 2); ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="patients_add.php" class="flex flex-col items-center justify-center p-6 bg-teal-50 hover:bg-teal-100 rounded-xl transition-colors text-teal-700 font-medium group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-plus text-xl"></i>
                    </div>
                    Register Patient
                </a>
                
                <a href="semen_analyses_add.php" class="flex flex-col items-center justify-center p-6 bg-sky-50 hover:bg-sky-100 rounded-xl transition-colors text-sky-700 font-medium group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flask text-xl"></i>
                    </div>
                    New Semen Analysis
                </a>
                
                <a href="prescriptions_add.php" class="flex flex-col items-center justify-center p-6 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors text-indigo-700 font-medium group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-prescription-bottle-medical text-xl"></i>
                    </div>
                    Write Prescription
                </a>
                
                <a href="receipts_add.php" class="flex flex-col items-center justify-center p-6 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-colors text-emerald-700 font-medium group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                    </div>
                    Generate Receipt
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">System Information</h3>
        </div>
        <div class="p-6 h-full text-center flex flex-col items-center justify-center text-gray-500">
            <i class="fa-solid fa-server text-4xl mb-4 text-gray-300"></i>
            <p>Welcome to the custom IVF Experts EMR System.</p>
            <p class="text-sm mt-2">All backend data modules including patients, history, analytics, specific WHO-6th logic and portals are actively deployed.</p>
        </div>
    </div>
    
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>