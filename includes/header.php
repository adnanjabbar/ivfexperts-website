<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fallback if variables not defined -->
    <title><?= htmlspecialchars($pageTitle ?? 'IVF Experts – Fertility Specialist Lahore') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Expert IVF, ICSI, IUI, PGT & infertility treatment in Lahore by Dr. Adnan Jabbar.') ?>">

    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($currentUrl ?? 'https://ivfexperts.pk' . $_SERVER['REQUEST_URI']) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'IVF Experts Lahore') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl ?? 'https://ivfexperts.pk') ?>">
    <meta property="og:site_name" content="IVF Experts">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? '') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time() ?>">

    <!-- Favicon (update paths) -->
    <link rel="icon" href="/favicon.ico">

    <!-- Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "MedicalBusiness",
          "@id": "https://ivfexperts.pk/#organization",
          "name": "IVF Experts",
          "url": "https://ivfexperts.pk",
          "telephone": "+923111101483",
          "medicalSpecialty": "Reproductive Medicine"
        },
        {
          "@type": "Physician",
          "@id": "https://ivfexperts.pk/#physician",
          "name": "Dr. Adnan Jabbar",
          "jobTitle": "Fertility Specialist & Clinical Embryologist",
          "medicalSpecialty": "Reproductive Medicine",
          "worksFor": { "@id": "https://ivfexperts.pk/#organization" }
        }
      ]
    }
    </script>

    <!-- Breadcrumbs (safe fallback) -->
    <?php if (!empty($breadcrumbs)): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
        {
          "@type": "ListItem",
          "position": <?= $i + 1 ?>,
          "name": "<?= htmlspecialchars($crumb['name']) ?>",
          "item": "<?= htmlspecialchars($crumb['url']) ?>"
        }<?= $i + 1 < count($breadcrumbs) ? ',' : '' ?>
        <?php endforeach; ?>
      ]
    }
    </script>
    <?php endif; ?>
</head>

<body class="bg-white text-gray-900 font-sans antialiased">

<!-- HEADER -->
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300" id="main-header">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- LOGO -->
            <a href="/" class="flex items-center gap-3 group">
                <span class="text-3xl font-extrabold tracking-tight text-teal-700 group-hover:text-teal-600 transition">
                    IVF Experts
                </span>
            </a>

            <!-- DESKTOP NAV -->
            <nav class="hidden lg:flex items-center space-x-10 text-base font-medium text-gray-700">

                <a href="/" class="hover:text-teal-600 transition">Home</a>

                <!-- Male Infertility Mega -->
                <div class="relative group">
                    <a href="/male-infertility/" class="hover:text-teal-600 transition flex items-center gap-1">
                        Male Infertility
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </a>
                    <div class="absolute left-0 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pt-4 pointer-events-none group-hover:pointer-events-auto">
                        <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="grid grid-cols-3 gap-10 p-10">
                                <!-- Overview -->
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Overview</h4>
                                    <p class="text-gray-600 text-sm mb-6">
                                        Expert diagnosis & treatment of male infertility.
                                    </p>
                                    <a href="/male-infertility/" class="text-teal-600 font-semibold text-sm hover:underline">
                                        Explore →
                                    </a>
                                </div>
                                <!-- Conditions -->
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Conditions</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/male-infertility/low-sperm-count.php" class="hover:text-teal-600">Low Sperm Count</a></li>
                                        <li><a href="/male-infertility/azoospermia.php" class="hover:text-teal-600">Azoospermia</a></li>
                                        <li><a href="/male-infertility/varicocele.php" class="hover:text-teal-600">Varicocele</a></li>
                                        <li><a href="/male-infertility/dna-fragmentation.php" class="hover:text-teal-600">DNA Fragmentation</a></li>
                                    </ul>
                                </div>
                                <!-- Advanced -->
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Advanced Care</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/art-procedures/icsi.php" class="hover:text-teal-600">ICSI</a></li>
                                        <li><a href="/contact/" class="hover:text-teal-600">Consultation</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repeat similar structure for Female Infertility and ART -->

                <!-- ... (add Female and ART mega menus here – same pattern as above) ... -->

                <a href="/contact/" class="hover:text-teal-600 transition">Contact</a>
            </nav>

            <!-- CTA Buttons -->
            <div class="hidden lg:flex items-center gap-6">
                <a href="https://wa.me/923111101483?text=Hello%20Dr.%20Adnan%20Jabbar,%20I'd%20like%20to%20discuss%20fertility%20options"
                   class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md animate-pulse-slow">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/>
                    </svg>
                    WhatsApp
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden text-teal-700 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

        </div>
    </div>
</header>

<!-- Spacer -->
<div class="header-spacer"></div>

<!-- Pulse animation for WhatsApp -->
<style>
    @keyframes pulse-slow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(45, 212, 191, 0.5); }
        50% { box-shadow: 0 0 0 15px rgba(45, 212, 191, 0); }
    }
    .animate-pulse-slow {
        animation: pulse-slow 2.5s infinite;
    }
</style>