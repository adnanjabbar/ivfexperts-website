<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Page Title (set in each page) -->
    <title><?= htmlspecialchars($pageTitle ?? 'IVF Experts Admin Panel') ?></title>

    <!-- Tailwind CSS (CDN for quick dev – move to local admin.css later) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin Custom Styles -->
    <link rel="stylesheet" href="/admin/assets/css/admin.css?v=<?= time() ?>">

    <!-- Favicon (update path) -->
    <link rel="icon" href="/favicon.ico">
</head>

<body class="bg-gray-50 font-sans antialiased">

<!-- Top Bar / Header -->
<header class="bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-full mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-20">
            <!-- Logo / Brand -->
            <div class="flex items-center gap-4">
                <a href="/admin/dashboard.php" class="text-2xl lg:text-3xl font-extrabold text-teal-700 tracking-tight hover:text-teal-600 transition">
                    IVF Experts Admin
                </a>
                <span class="hidden md:inline text-sm text-gray-500 font-medium">
                    Clinical Management System
                </span>
            </div>

            <!-- User Info & Logout -->
            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold">
                        <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                    </div>
                    <span class="text-gray-700 font-medium">
                        <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
                    </span>
                </div>

                <a href="/admin/logout.php" 
                   class="bg-red-50 text-red-700 hover:bg-red-100 px-5 py-2.5 rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Spacer to prevent content overlap with fixed header -->
<div class="h-16 lg:h-20"></div>

<!-- Main Content Wrapper (for sidebar + page content) -->
<div class="flex min-h-[calc(100vh-80px)]">