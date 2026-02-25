#!/bin/bash

echo "Starting Full IVFExperts Deployment..."

# Create core directories
mkdir -p assets/css assets/js includes
mkdir -p male-infertility female-infertility art-procedures about contact

########################################
# GLOBAL CSS
########################################
cat > assets/css/style.css <<'EOF'
body { font-family: 'Inter', sans-serif; background:#f8fafc; color:#1f2937; }
.section { padding:6rem 1rem; }
.card { background:white; padding:2rem; border-radius:1.5rem; border:1px solid #e5e7eb; transition:.3s; }
.card:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(0,0,0,0.08); }
.hero-gradient { background:linear-gradient(135deg,#0f172a,#0e7490,#1e3a8a); }
.btn-primary { background:#0e7490; color:white; padding:.8rem 1.8rem; border-radius:.75rem; font-weight:600; }
.btn-primary:hover { background:#155e75; }
.accordion-content { display:none; padding-top:10px; color:#4b5563; }
EOF

########################################
# GLOBAL JS
########################################
cat > assets/js/app.js <<'EOF'
document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll(".accordion-toggle").forEach(btn=>{
    btn.addEventListener("click",()=>{
      let content = btn.nextElementSibling;
      content.style.display = content.style.display === "block" ? "none" : "block";
    });
  });
});
EOF

########################################
# HEADER WITH MEGA MENU
########################################
cat > includes/header.php <<'EOF'
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
EOF

########################################
# FOOTER
########################################
cat > includes/footer.php <<'EOF'
<footer class="bg-gray-900 text-white py-16 mt-20">
<div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-10">
<div>
<h3 class="font-bold mb-4">IVF Experts</h3>
<p>Advanced fertility & IVF care serving patients across Pakistan and overseas Pakistanis.</p>
</div>
<div>
<h3 class="font-bold mb-4">Quick Links</h3>
<a href="/male-infertility/" class="block py-1">Male Infertility</a>
<a href="/female-infertility/" class="block py-1">Female Infertility</a>
<a href="/art-procedures/" class="block py-1">ART Procedures</a>
</div>
<div>
<h3 class="font-bold mb-4">Contact</h3>
<p>WhatsApp: +92 3 111 101 483</p>
</div>
</div>
<p class="text-center mt-10 text-gray-400">© 2026 IVF Experts</p>
</footer>
</body>
</html>
EOF

########################################
# MALE INFERTILITY PILLAR (EXPANDED)
########################################
cat > male-infertility/index.php <<'EOF'
<?php
$pageTitle = "Male Infertility Treatment in Pakistan | IVF Experts";
$metaDescription = "Comprehensive male infertility diagnosis and treatment including low sperm count, azoospermia, DNA fragmentation and ICSI.";
include("../includes/header.php");
?>

<section class="hero-gradient text-white text-center py-24">
<h1 class="text-5xl font-extrabold mb-6">Male Infertility Treatment in Pakistan</h1>
<p class="max-w-3xl mx-auto text-lg">
Male infertility contributes to nearly half of infertility cases globally. At IVF Experts, we provide comprehensive diagnosis, advanced reproductive treatments, and precision embryology solutions tailored to each couple.
</p>
</section>

<section class="section">
<div class="max-w-5xl mx-auto space-y-10">

<div class="card">
<h2 class="text-2xl font-bold mb-4">Understanding Male Infertility</h2>
<p>
Male infertility refers to reduced or absent sperm function preventing conception. Causes may involve sperm count, motility, morphology, hormonal imbalance, genetic conditions, varicocele, or lifestyle factors.
</p>
<p class="mt-4">
Detailed evaluation ensures appropriate management using medical therapy, surgical correction, IUI, IVF or ICSI depending on severity.
</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Common Conditions</h2>
<ul class="list-disc ml-6 space-y-2">
<li><a href="low-sperm-count.php" class="text-teal-600">Low Sperm Count (Oligospermia)</a></li>
<li><a href="azoospermia.php" class="text-teal-600">Azoospermia</a></li>
<li><a href="dna-fragmentation.php" class="text-teal-600">DNA Fragmentation</a></li>
<li><a href="varicocele.php" class="text-teal-600">Varicocele</a></li>
</ul>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Diagnostic Workup</h2>
<p>
Evaluation includes semen analysis, hormonal profile (FSH, LH, Testosterone), ultrasound imaging, genetic testing and sperm DNA integrity assessment.
</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Treatment Options</h2>
<p>
Mild cases may benefit from lifestyle optimization and medical therapy. Moderate to severe male factor infertility often requires IUI, IVF or ICSI.
</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Frequently Asked Questions</h2>

<button class="accordion-toggle font-semibold">Is male infertility treatable?</button>
<div class="accordion-content">Yes. Many causes can be treated medically or via assisted reproductive technologies.</div>

<button class="accordion-toggle font-semibold mt-4">When should evaluation be done?</button>
<div class="accordion-content">After 12 months of trying (6 months if female partner >35).</div>

</div>

</div>
</section>

<?php include("../includes/footer.php"); ?>
EOF

########################################
# SUBPAGE TEMPLATE GENERATOR
########################################
for page in low-sperm-count azoospermia dna-fragmentation varicocele
do
cat > male-infertility/$page.php <<EOF
<?php
\$pageTitle = ucfirst(str_replace("-", " ", "$page")) . " | IVF Experts";
\$metaDescription = "Detailed information about $page and its treatment options.";
include("../includes/header.php");
?>

<section class="hero-gradient text-white text-center py-24">
<h1 class="text-4xl font-bold mb-4">$(echo $page | sed 's/-/ /g' | sed 's/\b\(.\)/\u\1/g')</h1>
<p class="max-w-3xl mx-auto">Comprehensive diagnosis and advanced treatment solutions for $page.</p>
</section>

<section class="section">
<div class="max-w-5xl mx-auto space-y-8">

<div class="card">
<h2 class="text-2xl font-bold mb-4">Overview</h2>
<p>This page explains causes, symptoms, diagnosis and modern fertility treatment approaches.</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Treatment Strategy</h2>
<p>Management may include lifestyle changes, medical therapy, surgical options or IVF/ICSI.</p>
</div>

</div>
</section>

<?php include("../includes/footer.php"); ?>
EOF
done

########################################
# SITEMAP
########################################
cat > sitemap.php <<'EOF'
<?php
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>https://ivfexperts.pk/</loc></url>
<url><loc>https://ivfexperts.pk/male-infertility/</loc></url>
</urlset>
EOF

echo "Full deployment completed successfully."