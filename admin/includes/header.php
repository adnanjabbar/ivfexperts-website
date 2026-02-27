<?php
/**
 * IVF Experts Admin - Sidebar Navigation
 * Shared left navigation for all admin pages
 * Include after header in pages
 */

// Get current page for active highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside class="bg-gray-800 text-white w-64 min-h-screen fixed top-16 lg:top-20 left-0 z-40 p-6 lg:p-8 shadow-2xl">
    <nav class="space-y-1">
        <a href="/admin/dashboard.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-gray-700 transition font-medium <?= $currentPage === 'dashboard.php' ? 'bg-gray-700 text-teal-300' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-3a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1m-4 0h4"/>
            </svg>
            Dashboard
        </a>

        <a href="/admin/patients/index.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-gray-700 transition font-medium <?= strpos($currentPage, 'patients') !== false ? 'bg-gray-700 text-teal-300' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM3 10a2 2 0 114 0 2 2 0 01-4 0z"/>
            </svg>
            Patients
        </a>

        <a href="/admin/reports/index.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-gray-700 transition font-medium <?= strpos($currentPage, 'reports') !== false ? 'bg-gray-700 text-teal-300' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Semen Reports
        </a>

        <!-- Add more links as needed -->
        <a href="/admin/ultrasound/index.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-gray-700 transition font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-7a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
            </svg>
            Ultrasound Reports
        </a>

        <a href="/admin/treatment/index.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-gray-700 transition font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Treatment Plans
        </a>

        <a href="/admin/logout.php" 
           class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-red-700/50 text-red-300 hover:text-red-100 transition font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </nav>
</aside>

<!-- Content Wrapper (shifts right for sidebar) -->
<div class="flex-1 ml-0 lg:ml-64 p-6 lg:p-8 bg-gray-50 min-h-[calc(100vh-5rem)]">