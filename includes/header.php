<!DOCTYPE html>
<?php include("seo.php"); ?>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= $currentUrl ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta property="og:url" content="<?= $currentUrl ?>">
<meta property="og:site_name" content="<?= $siteName ?>">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">

<!-- Styles -->
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/app.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<!-- Physician + Organization Schema -->
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
      "worksFor": {
        "@id": "https://ivfexperts.pk/#organization"
      },
      "areaServed": "Pakistan"
    }
  ]
}
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "BreadcrumbList",
"itemListElement": [
<?php foreach($breadcrumbs as $index => $crumb): ?>
{
"@type": "ListItem",
"position": <?= $index + 1 ?>,
"name": "<?= $crumb['name'] ?>",
"item": "<?= $crumb['url'] ?>"
}<?= $index + 1 < count($breadcrumbs) ? "," : "" ?>
<?php endforeach; ?>
]
}
</script>
</head>

<body class="bg-white text-gray-800">

<header class="fixed top-0 left-0 w-full bg-white border-b border-gray-200 z-50">

<div class="relative max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<!-- LOGO -->
<a href="/" class="text-2xl font-extrabold tracking-tight text-teal-700">
IVF Experts
</a>

<!-- NAV -->
<nav class="hidden md:flex items-center space-x-10 text-sm font-semibold text-gray-700">

<a href="/" class="hover:text-teal-600 transition">Home</a>
<a href="/about/" class="hover:text-teal-600 transition">About</a>

<!-- ================= MALE ================= -->
<div class="group">
<a href="/male-infertility/" class="hover:text-teal-600 transition">
Male Infertility
</a>
<div class="absolute left-1/2 -translate-x-1/2 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">
<div class="bg-white shadow-2xl border border-gray-200 rounded-2xl mt-4">
<div class="grid grid-cols-3 gap-10 p-10">
<div>
<h4 class="font-bold mb-4">Overview</h4>
<p class="text-gray-600 text-sm mb-4">
Comprehensive male infertility evaluation in Lahore.
</p>
<a href="/male-infertility/" class="text-teal-600 text-sm font-semibold hover:underline">
Learn More →
</a>
</div>
<div>
<h4 class="font-bold mb-4">Common Conditions</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/male-infertility/low-sperm-count.php" class="hover:text-teal-600">Low Sperm Count</a></li>
<li><a href="/male-infertility/azoospermia.php" class="hover:text-teal-600">Azoospermia</a></li>
<li><a href="/male-infertility/varicocele.php" class="hover:text-teal-600">Varicocele</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Advanced Evaluation</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/male-infertility/dna-fragmentation.php" class="hover:text-teal-600">DNA Fragmentation</a></li>
<li><a href="/art-procedures/icsi.php" class="hover:text-teal-600">ICSI Strategy</a></li>
<li><a href="/contact/" class="hover:text-teal-600">Consultation</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<!-- ================= FEMALE ================= -->
<div class="group">
<a href="/female-infertility/" class="hover:text-teal-600 transition">
Female Infertility
</a>
<div class="absolute left-1/2 -translate-x-1/2 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">
<div class="bg-white shadow-2xl border border-gray-200 rounded-2xl mt-4">
<div class="grid grid-cols-3 gap-10 p-10">
<div>
<h4 class="font-bold mb-4">Overview</h4>
<p class="text-gray-600 text-sm mb-4">
Structured female infertility diagnosis and ART planning.
</p>
<a href="/female-infertility/" class="text-teal-600 text-sm font-semibold hover:underline">
Learn More →
</a>
</div>
<div>
<h4 class="font-bold mb-4">Common Conditions</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/female-infertility/pcos.php" class="hover:text-teal-600">PCOS</a></li>
<li><a href="/female-infertility/endometriosis.php" class="hover:text-teal-600">Endometriosis</a></li>
<li><a href="/female-infertility/blocked-tubes.php" class="hover:text-teal-600">Blocked Tubes</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Advanced Evaluation</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/female-infertility/diminished-ovarian-reserve.php" class="hover:text-teal-600">Low Ovarian Reserve</a></li>
<li><a href="/art-procedures/ivf.php" class="hover:text-teal-600">IVF Planning</a></li>
<li><a href="/contact/" class="hover:text-teal-600">Consultation</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<!-- ================= ART ================= -->
<div class="group">
<a href="/art-procedures/" class="hover:text-teal-600 transition">
ART Procedures
</a>
<div class="absolute left-1/2 -translate-x-1/2 top-full w-[900px] max-w-[95vw] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">
<div class="bg-white shadow-2xl border border-gray-200 rounded-2xl mt-4">
<div class="grid grid-cols-3 gap-10 p-10">
<div>
<h4 class="font-bold mb-4">Overview</h4>
<p class="text-gray-600 text-sm mb-4">
Advanced assisted reproductive techniques in Lahore.
</p>
<a href="/art-procedures/" class="text-teal-600 text-sm font-semibold hover:underline">
Learn More →
</a>
</div>
<div>
<h4 class="font-bold mb-4">Core Procedures</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/art-procedures/ivf.php" class="hover:text-teal-600">IVF</a></li>
<li><a href="/art-procedures/icsi.php" class="hover:text-teal-600">ICSI</a></li>
<li><a href="/art-procedures/iui.php" class="hover:text-teal-600">IUI</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Advanced Techniques</h4>
<ul class="space-y-2 text-gray-700 text-sm">
<li><a href="/art-procedures/pgt.php" class="hover:text-teal-600">PGT</a></li>
<li><a href="/male-infertility/dna-fragmentation.php" class="hover:text-teal-600">DNA Fragmentation</a></li>
<li><a href="/contact/" class="hover:text-teal-600">Consultation</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<a href="/contact/" class="hover:text-teal-600 transition">Contact</a>
</nav>
<!-- CTA -->
<a href="https://wa.me/923111101483"
class="hidden md:inline-block bg-teal-700 text-white px-6 py-2 rounded-md font-semibold hover:bg-teal-800 transition">
WhatsApp
</a>
</div>
</header>
<div class="h-24"></div>
