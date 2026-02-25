<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? "IVF Experts | Advanced Fertility Treatment in Pakistan" ?></title>
<meta name="description" content="<?= $metaDescription ?? "IVF Experts provides advanced IVF, ICSI and infertility treatment services across Pakistan and for overseas Pakistanis." ?>">

<meta name="robots" content="index, follow">
<meta name="author" content="Dr. Adnan Jabbar">

<!-- Canonical -->
<link rel="canonical" href="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">

<!-- Open Graph (WhatsApp / Facebook) -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= $pageTitle ?? "IVF Experts" ?>">
<meta property="og:description" content="<?= $metaDescription ?? "" ?>">
<meta property="og:url" content="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">
<meta property="og:site_name" content="IVF Experts">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $pageTitle ?? "IVF Experts" ?>">
<meta name="twitter:description" content="<?= $metaDescription ?? "" ?>">

<!-- Performance -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Styles -->
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/app.js" defer></script>

<!-- Tailwind (Temporary – will remove later for performance optimization) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

</head>
<body>

<header class="fixed top-0 left-0 w-full bg-white shadow z-50">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
<a href="/" class="text-2xl font-bold text-teal-700">IVF Experts</a>
<nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-gray-700">

<a href="/" class="hover:text-teal-600 transition">Home</a>

<!-- MALE DROPDOWN -->
<div class="relative group">

<button class="hover:text-teal-600 transition focus:outline-none">
Male Infertility
</button>

<div class="absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<div class="bg-white shadow-2xl rounded-2xl p-6 w-72 border border-gray-100">

<a href="/male-infertility/" class="block py-2 hover:text-teal-600">Overview</a>
<a href="/male-infertility/low-sperm-count.php" class="block py-2 hover:text-teal-600">Low Sperm Count</a>
<a href="/male-infertility/azoospermia.php" class="block py-2 hover:text-teal-600">Azoospermia</a>
<a href="/male-infertility/dna-fragmentation.php" class="block py-2 hover:text-teal-600">DNA Fragmentation</a>
<a href="/male-infertility/varicocele.php" class="block py-2 hover:text-teal-600">Varicocele</a>

</div>
</div>
</div>

<!-- FEMALE DROPDOWN -->
<div class="relative group">

<button class="hover:text-teal-600 transition focus:outline-none">
Female Infertility
</button>

<div class="absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<div class="bg-white shadow-2xl rounded-2xl p-6 w-72 border border-gray-100">

<a href="/female-infertility/" class="block py-2 hover:text-teal-600">Overview</a>
<a href="/female-infertility/pcos.php" class="block py-2 hover:text-teal-600">PCOS</a>
<a href="/female-infertility/endometriosis.php" class="block py-2 hover:text-teal-600">Endometriosis</a>
<a href="/female-infertility/blocked-tubes.php" class="block py-2 hover:text-teal-600">Blocked Tubes</a>
<a href="/female-infertility/diminished-ovarian-reserve.php" class="block py-2 hover:text-teal-600">Low Ovarian Reserve</a>

</div>
</div>
</div>

<!-- ART DROPDOWN -->
<div class="relative group">

<button class="hover:text-teal-600 transition focus:outline-none">
ART Procedures
</button>

<div class="absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<div class="bg-white shadow-2xl rounded-2xl p-6 w-72 border border-gray-100">

<a href="/art-procedures/" class="block py-2 hover:text-teal-600">Overview</a>
<a href="/art-procedures/ivf.php" class="block py-2 hover:text-teal-600">IVF</a>
<a href="/art-procedures/icsi.php" class="block py-2 hover:text-teal-600">ICSI</a>
<a href="/art-procedures/iui.php" class="block py-2 hover:text-teal-600">IUI</a>
<a href="/art-procedures/pgt.php" class="block py-2 hover:text-teal-600">PGT</a>

</div>
</div>
</div>

<a href="/contact/" class="hover:text-teal-600 transition">Contact</a>

</nav>
<a href="https://wa.me/923111101483" class="btn-primary">WhatsApp</a>
</div>
</header>
<div class="h-24"></div>
