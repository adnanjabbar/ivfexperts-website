<?php
/**
 * IVF Experts Admin Dashboard
 * Main overview page for clinical system
 */

session_start();

// === Security: Require authentication
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/auth.php";

// === Database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/db.php";

// === Fetch dashboard statistics (safe with prepared statements)
try {
    // Total Patients
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM patients");
    $stmt->execute();
    $totalPatients = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    // Total Semen Reports
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM semen_reports");
    $stmt->execute();
    $totalReports = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    // Pending Reports (example – adjust table/column names as needed)
    $stmt = $conn->prepare("SELECT COUNT(*) as pending FROM semen_reports WHERE status = 'pending'");
    $stmt->execute();
    $pendingReports = $stmt->get_result()->fetch_assoc()['pending'] ?? 0;
    $stmt->close();

    // Recent Activity (last 5 reports – placeholder)
    $stmt = $conn->prepare("SELECT id, patient_id, created_at, status 
                            FROM semen_reports 
                            ORDER BY created_at DESC 
                            LIMIT 5");
    $stmt->execute();
    $recentReports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

} catch (Exception $e) {
    // In production: log error, show friendly message
    error_log("Dashboard stats error: " . $e->getMessage());
    $error = "Unable to load dashboard statistics. Please try again later.";
}

include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/header.php";
?>

<!-- Page Header -->
<div class="mb-10">
    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">
        Welcome to IVF Experts Clinical Dashboard
    </h1>
    <p class="text-gray-600 mt-2">
        Overview of patients, semen reports, and system activity
    </p>
</div>

<?php if (isset($error)): ?>
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-10">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">

    <!-- Total Patients -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-teal-600 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Total Patients
                    </h3>
                    <p class="text-4xl lg:text-5xl font-extrabold text-gray-900 mt-3">
                        <?= number_format($totalPatients) ?>
                    </p>
                </div>
                <div class="bg-teal-100 p-4 rounded-full">
                    <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM6 5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Registered fertility patients
            </p>
        </div>
    </div>

    <!-- Total Semen Reports -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-blue-600 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Semen Reports
                    </h3>
                    <p class="text-4xl lg:text-5xl font-extrabold text-gray-900 mt-3">
                        <?= number_format($totalReports) ?>
                    </p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Generated semen analysis reports
            </p>
        </div>
    </div>

    <!-- Pending Reports -->
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-purple-600 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">
                        Pending Reports
                    </h3>
                    <p class="text-4xl lg:text-5xl font-extrabold text-gray-900 mt-3">
                        <?= number_format($pendingReports) ?>
                    </p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Reports awaiting review/signature
            </p>
        </div>
    </div>

</div>

<!-- Recent Activity Section -->
<div class="bg-white rounded-2xl shadow-lg p-8 lg:p-10">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">
            Recent Semen Reports
        </h2>
        <a href="reports.php" class="text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-2">
            View All Reports →
        </a>
    </div>

    <?php if (empty($recentReports)): ?>
        <div class="text-center py-12 text-gray-500">
            No recent reports found.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Report ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($recentReports as $report): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($report['id']) ?>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600">
                                <?= htmlspecialchars($report['patient_id']) ?>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600">
                                <?= date('d M Y H:i', strtotime($report['created_at'])) ?>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $report['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= ucfirst(htmlspecialchars($report['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm">
                                <a href="report_view.php?id=<?= $report['id'] ?>" class="text-teal-600 hover:text-teal-900">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/admin/includes/footer.php"; ?>