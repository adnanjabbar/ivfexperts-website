<?php
if (!isset($pageTitle)) {
    $pageTitle = "IVF Experts | Advanced Fertility Treatment in Pakistan";
}
if (!isset($metaDescription)) {
    $metaDescription = "IVF Experts provides IVF, ICSI, IUI and advanced infertility treatments for patients across Pakistan and overseas Pakistanis.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<meta name="description" content="<?= $metaDescription ?>">
<meta name="keywords" content="IVF specialist Pakistan, fertility doctor Pakistan, ICSI treatment Pakistan, IUI procedure, male infertility treatment, female infertility specialist">

<link rel="canonical" href="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">

<meta property="og:title" content="<?= $pageTitle ?>">
<meta property="og:description" content="<?= $metaDescription ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

<style>
body { font-family: 'Inter', sans-serif; }
h1,h2,h3 { font-family: 'Poppins', sans-serif; }
</style>

</head>
<body class="bg-white text-gray-800">

<header class="bg-white shadow-md sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
<a href="/" class="text-2xl font-bold text-teal-700">IVF Experts</a>
<nav class="hidden md:flex space-x-6 text-sm font-medium">
<a href="/" class="hover:text-teal-600">Home</a>
<a href="/male-infertility/" class="hover:text-teal-600">Male Infertility</a>
<a href="/female-infertility/" class="hover:text-teal-600">Female Infertility</a>
<a href="/art-procedures/" class="hover:text-teal-600">ART Procedures</a>
<a href="/contact/" class="hover:text-teal-600">Contact</a>
</nav>
<a href="https://wa.me/923111101483" target="_blank" class="hidden md:inline-block bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
WhatsApp Now
</a>
</div>
</header>