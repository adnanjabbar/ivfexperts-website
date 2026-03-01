<!-- Sidebar -->
<aside :class="sidebarOpen ? 'w-64' : 'w-0 -ml-64 sm:ml-0 sm:w-20'" class="bg-teal-900 text-teal-100 flex flex-col transition-all duration-300 z-20 shrink-0 h-screen overflow-y-auto shadow-xl">
    
    <!-- Brand -->
    <div class="h-16 flex items-center justify-center border-b border-teal-800 px-4 shrink-0 bg-teal-950">
        <div class="font-bold text-lg tracking-wider text-white truncate flex items-center gap-2">
            <i class="fa-solid fa-dna text-teal-400"></i>
            <span x-show="sidebarOpen" class="transition-opacity">IVF Experts EMR</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-6 space-y-1">
        <?php
$current_page = basename($_SERVER['PHP_SELF']);

$nav_items = [
    ['url' => 'index.php', 'icon' => 'fa-solid fa-chart-pie', 'label' => 'Dashboard'],
    ['url' => 'patients.php', 'icon' => 'fa-solid fa-users', 'label' => 'Patient Registry'],
    ['url' => 'semen_analyses.php', 'icon' => 'fa-solid fa-microscope', 'label' => 'Semen Analysis'],
    ['url' => 'ultrasounds.php', 'icon' => 'fa-solid fa-image', 'label' => 'Ultrasounds'],
    ['url' => 'prescriptions.php', 'icon' => 'fa-solid fa-file-prescription', 'label' => 'Prescriptions'],
    ['url' => 'medications.php', 'icon' => 'fa-solid fa-pills', 'label' => 'Medications'],
    ['url' => 'hospitals.php', 'icon' => 'fa-regular fa-hospital', 'label' => 'Hospitals'],
    ['url' => 'financials.php', 'icon' => 'fa-solid fa-wallet', 'label' => 'Financials'],
    ['url' => 'settings.php', 'icon' => 'fa-solid fa-gear', 'label' => 'Settings']
];

foreach ($nav_items as $item) {
    $is_active = ($current_page == $item['url']) ? 'active bg-teal-800 text-white font-semibold' : 'text-teal-200 hover:text-white';
?>
            <a href="<?php echo htmlspecialchars($item['url']); ?>" class="sidebar-link flex items-center gap-3 px-3 py-3 rounded-lg <?php echo $is_active; ?>" title="<?php echo htmlspecialchars($item['label']); ?>">
                <i class="<?php echo $item['icon']; ?> w-6 text-center text-lg"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity"><?php echo htmlspecialchars($item['label']); ?></span>
            </a>
            <?php
}
?>
    </nav>
    
    <!-- Bottom user info -->
    <div class="p-4 border-t border-teal-800 text-xs text-teal-400/60 truncate" x-show="sidebarOpen">
        System v2.0 - EMR Edition
    </div>

</aside>
