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
<header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/" class="text-3xl font-extrabold text-teal-700">
            IVF Experts
        </a>

        <nav class="flex items-center gap-10 text-lg font-medium">
            <a href="/" class="hover:text-teal-600">Home</a>
            <a href="/male-infertility/" class="hover:text-teal-600">Male Infertility</a>
            <a href="/female-infertility/" class="hover:text-teal-600">Female Infertility</a>
            <a href="/art-procedures/" class="hover:text-teal-600">ART Procedures</a>
            <a href="/contact/" class="hover:text-teal-600">Contact</a>
        </nav>

        <a href="https://wa.me/923111101483" class="bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700">
            WhatsApp
        </a>
    </div>
</header>

<div class="h-24"></div> <!-- spacer -->

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