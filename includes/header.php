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

<link rel="canonical" href="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">

<meta property="og:type" content="website">
<meta property="og:title" content="<?= $pageTitle ?? "IVF Experts" ?>">
<meta property="og:description" content="<?= $metaDescription ?? "" ?>">
<meta property="og:url" content="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">
<meta property="og:site_name" content="IVF Experts">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $pageTitle ?? "IVF Experts" ?>">
<meta name="twitter:description" content="<?= $metaDescription ?? "" ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/app.js" defer></script>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
body { font-family: 'Inter', sans-serif; }
</style>

</head>
<body class="bg-white text-gray-800">

<header class="fixed top-0 left-0 w-full bg-white border-b border-gray-200 z-50">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<!-- LOGO -->
<a href="/" class="text-2xl font-extrabold tracking-tight text-teal-700">
IVF Experts
</a>

<!-- NAV -->
<nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-gray-700">

<a href="/" class="hover:text-teal-600 transition">Home</a>

<a href="/about/" class="hover:text-teal-600 transition">About</a>

<!-- MALE -->
<div class="relative group">

<a href="/male-infertility/" 
class="hover:text-teal-600 transition">
Male Infertility
</a>

<div class="absolute left-0 top-full bg-white shadow-xl rounded-xl p-5 w-64 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<a href="/male-infertility/" class="block py-2 hover:text-teal-600 font-medium">Overview</a>
<a href="/male-infertility/low-sperm-count.php" class="block py-2 hover:text-teal-600">Low Sperm Count</a>
<a href="/male-infertility/azoospermia.php" class="block py-2 hover:text-teal-600">Azoospermia</a>
<a href="/male-infertility/dna-fragmentation.php" class="block py-2 hover:text-teal-600">DNA Fragmentation</a>
<a href="/male-infertility/varicocele.php" class="block py-2 hover:text-teal-600">Varicocele</a>

</div>
</div>

<!-- FEMALE -->
<div class="relative group">

<a href="/female-infertility/" 
class="hover:text-teal-600 transition">
Female Infertility
</a>

<div class="absolute left-0 top-full bg-white shadow-xl rounded-xl p-5 w-64 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<a href="/female-infertility/" class="block py-2 hover:text-teal-600 font-medium">Overview</a>
<a href="/female-infertility/pcos.php" class="block py-2 hover:text-teal-600">PCOS</a>
<a href="/female-infertility/endometriosis.php" class="block py-2 hover:text-teal-600">Endometriosis</a>
<a href="/female-infertility/blocked-tubes.php" class="block py-2 hover:text-teal-600">Blocked Tubes</a>
<a href="/female-infertility/diminished-ovarian-reserve.php" class="block py-2 hover:text-teal-600">Low Ovarian Reserve</a>

</div>
</div>

<!-- ART -->
<div class="relative group">

<a href="/art-procedures/" 
class="hover:text-teal-600 transition">
ART Procedures
</a>

<div class="absolute left-0 top-full bg-white shadow-xl rounded-xl p-5 w-64 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200">

<a href="/art-procedures/" class="block py-2 hover:text-teal-600 font-medium">Overview</a>
<a href="/art-procedures/ivf.php" class="block py-2 hover:text-teal-600">IVF</a>
<a href="/art-procedures/icsi.php" class="block py-2 hover:text-teal-600">ICSI</a>
<a href="/art-procedures/iui.php" class="block py-2 hover:text-teal-600">IUI</a>
<a href="/art-procedures/pgt.php" class="block py-2 hover:text-teal-600">PGT</a>

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