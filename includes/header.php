<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? "IVF Experts" ?></title>
<meta name="description" content="<?= $metaDescription ?? "" ?>">
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/app.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>

<header class="fixed top-0 left-0 w-full bg-white shadow z-50">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
<a href="/" class="text-2xl font-bold text-teal-700">IVF Experts</a>

<nav class="hidden md:flex space-x-6 text-sm font-semibold">
<a href="/" class="hover:text-teal-600">Home</a>

<div class="relative group">
<span class="cursor-pointer hover:text-teal-600">Male Infertility</span>
<div class="absolute hidden group-hover:block bg-white shadow-xl p-6 mt-2 rounded-xl w-64">
<a href="/male-infertility/" class="block py-2 hover:text-teal-600">Overview</a>
<a href="/male-infertility/low-sperm-count.php" class="block py-2 hover:text-teal-600">Low Sperm Count</a>
<a href="/male-infertility/azoospermia.php" class="block py-2 hover:text-teal-600">Azoospermia</a>
<a href="/male-infertility/dna-fragmentation.php" class="block py-2 hover:text-teal-600">DNA Fragmentation</a>
<a href="/male-infertility/varicocele.php" class="block py-2 hover:text-teal-600">Varicocele</a>
</div>
</div>

<a href="/female-infertility/" class="hover:text-teal-600">Female Infertility</a>
<a href="/art-procedures/" class="hover:text-teal-600">ART Procedures</a>
<a href="/contact/" class="hover:text-teal-600">Contact</a>
</nav>

<a href="https://wa.me/923111101483" class="btn-primary">WhatsApp</a>
</div>
</header>

<div class="h-24"></div>
