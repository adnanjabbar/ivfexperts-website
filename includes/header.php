<?php
if (!isset($pageTitle)) {
    $pageTitle = "IVF Experts | Premium IVF & Fertility Specialist in Pakistan";
}
if (!isset($metaDescription)) {
    $metaDescription = "Advanced IVF, ICSI, IUI and infertility treatments across Pakistan and for overseas Pakistanis. Expert fertility care led by clinical embryology precision.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>
<meta name="description" content="<?= $metaDescription ?>">
<link rel="canonical" href="https://ivfexperts.pk<?= $_SERVER['REQUEST_URI']; ?>">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
body { font-family: 'Inter', sans-serif; }

/* Animated gradient */
.hero-bg {
background: linear-gradient(-45deg, #0f172a, #0e7490, #1e3a8a, #0f172a);
background-size: 400% 400%;
animation: gradientMove 15s ease infinite;
}

@keyframes gradientMove {
0% {background-position: 0% 50%;}
50% {background-position: 100% 50%;}
100% {background-position: 0% 50%;}
}

/* Dot pattern */
.pattern {
background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
background-size: 20px 20px;
}

/* Glass */
.glass {
background: rgba(255,255,255,0.08);
backdrop-filter: blur(14px);
border: 1px solid rgba(255,255,255,0.15);
}

.card {
transition: all 0.3s ease;
}
.card:hover {
transform: translateY(-8px);
box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
</style>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="absolute w-full z-50">
<div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center text-white">
<a href="/" class="text-2xl font-bold tracking-tight">IVF Experts</a>
<nav class="hidden md:flex space-x-8 text-sm font-medium">
<a href="/" class="hover:text-teal-300">Home</a>
<a href="/male-infertility/" class="hover:text-teal-300">Male Infertility</a>
<a href="/female-infertility/" class="hover:text-teal-300">Female Infertility</a>
<a href="/art-procedures/" class="hover:text-teal-300">ART Procedures</a>
<a href="/contact/" class="hover:text-teal-300">Contact</a>
</nav>
<a href="https://wa.me/923111101483" target="_blank"
class="bg-green-500 text-white px-5 py-2 rounded-lg text-sm font-semibold shadow-lg hover:scale-105 transition">
WhatsApp
</a>
</div>
</header>