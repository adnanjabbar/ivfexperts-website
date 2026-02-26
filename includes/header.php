<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle ?? 'IVF Experts – Fertility Specialist Lahore') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Expert IVF, ICSI, IUI & fertility treatment in Lahore by Dr. Adnan Jabbar – dual-trained Fertility Consultant & Clinical Embryologist.') ?>">

    <!-- Canonical & Robots -->
    <link rel="canonical" href="<?= $currentUrl ?? 'https://ivfexperts.pk' ?>">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'IVF Experts Lahore') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">
    <meta property="og:url" content="<?= $currentUrl ?? 'https://ivfexperts.pk' ?>">
    <meta property="og:site_name" content="IVF Experts">
    <meta property="og:image" content="https://ivfexperts.pk/assets/images/og-image.jpg"> <!-- add real OG image -->

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? '') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">
    <meta name="twitter:image" content="https://ivfexperts.pk/assets/images/og-image.jpg">

    <!-- Favicon (add your real icons) -->
    <link rel="icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Custom Tailwind Config (optional – extend if needed) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        teal: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Physician + Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "MedicalBusiness",
          "@id": "https://ivfexperts.pk/#organization",
          "name": "IVF Experts – Dr. Adnan Jabbar",
          "url": "https://ivfexperts.pk",
          "logo": "https://ivfexperts.pk/assets/images/logo.png",
          "telephone": "+923111101483",
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+923111101483",
            "contactType": "customer service",
            "areaServed": "PK",
            "availableLanguage": ["English", "Urdu"]
          },
          "medicalSpecialty": "Reproductive Medicine"
        },
        {
          "@type": "Physician",
          "@id": "https://ivfexperts.pk/#physician",
          "name": "Dr. Adnan Jabbar",
          "jobTitle": "Fertility Specialist & Clinical Embryologist",
          "medicalSpecialty": "Reproductive Endocrinology and Infertility",
          "worksFor": { "@id": "https://ivfexperts.pk/#organization" },
          "url": "https://ivfexperts.pk/about/"
        }
      ]
    }
    </script>

    <!-- Dynamic Breadcrumb Schema (if $breadcrumbs array exists) -->
    <?php if (!empty($breadcrumbs)): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
        {
          "@type": "ListItem",
          "position": <?= $index + 1 ?>,
          "name": "<?= htmlspecialchars($crumb['name']) ?>",
          "item": "<?= htmlspecialchars($crumb['url']) ?>"
        }<?= $index + 1 < count($breadcrumbs) ? ',' : '' ?>
        <?php endforeach; ?>
      ]
    }
    </script>
    <?php endif; ?>
</head>

<body class="bg-white text-gray-900 font-sans antialiased">

<!-- HEADER -->
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300" id="header">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- LOGO -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-105 transition">
                    IV
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-teal-700">
                    IVF Experts
                </span>
            </a>

            <!-- DESKTOP NAV + MEGA MENUS -->
            <nav class="hidden lg:flex items-center space-x-10 text-sm font-medium text-gray-700">

                <a href="/" class="hover:text-teal-600 transition">Home</a>

                <!-- MALE INFERTILITY MEGA MENU -->
                <div class="relative group">
                    <a href="/male-infertility/" class="hover:text-teal-600 transition flex items-center gap-1">
                        Male Infertility
                        <svg class="w-4 h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>

                    <div class="absolute left-0 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pt-4">
                        <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="grid grid-cols-3 gap-10 p-10">
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Overview</h4>
                                    <p class="text-gray-600 text-sm mb-6">
                                        Comprehensive evaluation & treatment of male infertility in Lahore.
                                    </p>
                                    <a href="/male-infertility/" class="text-teal-600 font-semibold text-sm hover:underline">
                                        Explore Male Infertility →
                                    </a>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Common Conditions</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/male-infertility/low-sperm-count.php" class="hover:text-teal-600 transition">Low Sperm Count</a></li>
                                        <li><a href="/male-infertility/azoospermia.php" class="hover:text-teal-600 transition">Azoospermia</a></li>
                                        <li><a href="/male-infertility/varicocele.php" class="hover:text-teal-600 transition">Varicocele</a></li>
                                        <li><a href="/male-infertility/dna-fragmentation.php" class="hover:text-teal-600 transition">DNA Fragmentation</a></li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Advanced Care</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/art-procedures/icsi.php" class="hover:text-teal-600 transition">ICSI Treatment</a></li>
                                        <li><a href="/male-infertility/micro-tese.php" class="hover:text-teal-600 transition">Micro-TESE</a></li>
                                        <li><a href="/contact/" class="hover:text-teal-600 transition">Book Consultation</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FEMALE INFERTILITY MEGA MENU -->
                <div class="relative group">
                    <a href="/female-infertility/" class="hover:text-teal-600 transition flex items-center gap-1">
                        Female Infertility
                        <svg class="w-4 h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>

                    <div class="absolute left-0 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pt-4">
                        <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="grid grid-cols-3 gap-10 p-10">
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Overview</h4>
                                    <p class="text-gray-600 text-sm mb-6">
                                        Structured diagnosis & ART planning for female infertility.
                                    </p>
                                    <a href="/female-infertility/" class="text-teal-600 font-semibold text-sm hover:underline">
                                        Explore Female Infertility →
                                    </a>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Common Conditions</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/female-infertility/pcos.php" class="hover:text-teal-600 transition">PCOS</a></li>
                                        <li><a href="/female-infertility/endometriosis.php" class="hover:text-teal-600 transition">Endometriosis</a></li>
                                        <li><a href="/female-infertility/blocked-tubes.php" class="hover:text-teal-600 transition">Blocked Tubes</a></li>
                                        <li><a href="/female-infertility/diminished-ovarian-reserve.php" class="hover:text-teal-600 transition">Low Ovarian Reserve</a></li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Advanced Care</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/art-procedures/ivf.php" class="hover:text-teal-600 transition">IVF Treatment</a></li>
                                        <li><a href="/contact/" class="hover:text-teal-600 transition">Book Consultation</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ART PROCEDURES MEGA MENU -->
                <div class="relative group">
                    <a href="/art-procedures/" class="hover:text-teal-600 transition flex items-center gap-1">
                        ART Procedures
                        <svg class="w-4 h-4 transition group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>

                    <div class="absolute left-0 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pt-4">
                        <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="grid grid-cols-3 gap-10 p-10">
                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Overview</h4>
                                    <p class="text-gray-600 text-sm mb-6">
                                        Advanced assisted reproductive techniques in Lahore.
                                    </p>
                                    <a href="/art-procedures/" class="text-teal-600 font-semibold text-sm hover:underline">
                                        Explore ART →
                                    </a>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Core Procedures</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/art-procedures/iui.php" class="hover:text-teal-600 transition">IUI (Insemination)</a></li>
                                        <li><a href="/art-procedures/ivf.php" class="hover:text-teal-600 transition">IVF (Fertilization)</a></li>
                                        <li><a href="/art-procedures/icsi.php" class="hover:text-teal-600 transition">ICSI (Sperm Injection)</a></li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="font-bold text-lg mb-4 text-teal-800">Advanced Techniques</h4>
                                    <ul class="space-y-3 text-gray-700 text-sm">
                                        <li><a href="/art-procedures/pgt.php" class="hover:text-teal-600 transition">PGT (Genetic Testing)</a></li>
                                        <li><a href="/male-infertility/micro-tese.php" class="hover:text-teal-600 transition">Micro-TESE</a></li>
                                        <li><a href="/contact/" class="hover:text-teal-600 transition">Consultation</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="/contact/" class="hover:text-teal-600 transition">Contact</a>

            </nav>

            <!-- DESKTOP CTA -->
            <div class="hidden lg:flex items-center gap-6">
                <a href="tel:+923111101483" class="text-gray-700 hover:text-teal-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    +92 311 1101483
                </a>

                <a href="https://wa.me/923111101483?text=Hello%20Dr.%20Adnan%20Jabbar,%20I'd%20like%20to%20discuss%20fertility%20options"
                   class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/></svg>
                    WhatsApp
                </a>
            </div>

            <!-- MOBILE MENU BUTTON (placeholder – add full mobile nav JS later if needed) -->
            <button class="lg:hidden text-teal-700 focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

        </div>
    </div>
</header>

<!-- Spacer to prevent content overlap with fixed header -->
<div class="h-20 lg:h-24"></div>

<!-- Optional: Scroll shadow effect on header -->
<script>
    window.addEventListener('scroll', () => {
        const header = document.getElementById('header');
        if (window.scrollY > 10) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }
    });
</script>