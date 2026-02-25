#!/bin/bash

echo "Deploying Female + ART + Expanded Male..."

############################################
# FEMALE INFERTILITY PILLAR (EXPANDED)
############################################

cat > female-infertility/index.php <<'EOF'
<?php
$pageTitle="Female Infertility Treatment in Pakistan | IVF Experts";
$metaDescription="Comprehensive female infertility diagnosis and advanced IVF treatment options.";
include("../includes/header.php");
?>

<section class="hero-gradient text-white text-center py-24">
<h1 class="text-5xl font-extrabold mb-6">Female Infertility Treatment in Pakistan</h1>
<p class="max-w-3xl mx-auto text-lg">
Comprehensive evaluation and advanced reproductive treatments for PCOS, endometriosis, blocked tubes, low ovarian reserve and age-related infertility.
</p>
</section>

<section class="section">
<div class="max-w-5xl mx-auto space-y-12">

<div class="card">
<h2 class="text-2xl font-bold mb-4">Understanding Female Infertility</h2>
<p>
Female infertility may arise due to ovulatory disorders, tubal blockage, uterine abnormalities, hormonal imbalance, or diminished ovarian reserve. Early diagnosis significantly improves treatment outcomes.
</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Common Conditions</h2>
<ul class="list-disc ml-6 space-y-2">
<li><a href="pcos.php" class="text-teal-600">PCOS</a></li>
<li><a href="endometriosis.php" class="text-teal-600">Endometriosis</a></li>
<li><a href="blocked-tubes.php" class="text-teal-600">Blocked Fallopian Tubes</a></li>
<li><a href="diminished-ovarian-reserve.php" class="text-teal-600">Low Ovarian Reserve</a></li>
</ul>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Treatment Options</h2>
<p>
Treatment may involve ovulation induction, IUI, IVF or ICSI depending on diagnosis, age and ovarian response.
</p>
</div>

</div>
</section>

<?php include("../includes/footer.php"); ?>
EOF

############################################
# ART HUB
############################################

cat > art-procedures/index.php <<'EOF'
<?php
$pageTitle="ART Procedures | IVF, ICSI, IUI, PGT | IVF Experts";
$metaDescription="Advanced assisted reproductive technologies including IVF, ICSI, IUI and PGT.";
include("../includes/header.php");
?>

<section class="hero-gradient text-white text-center py-24">
<h1 class="text-5xl font-extrabold mb-6">Assisted Reproductive Technology (ART)</h1>
<p class="max-w-3xl mx-auto text-lg">
Advanced fertility procedures tailored to maximize pregnancy outcomes.
</p>
</section>

<section class="section">
<div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10">

<div class="card">
<h2 class="text-xl font-bold mb-3">IVF</h2>
<p>In vitro fertilization is a structured ovarian stimulation and embryo transfer process.</p>
<a href="ivf.php" class="text-teal-600">Learn More</a>
</div>

<div class="card">
<h2 class="text-xl font-bold mb-3">ICSI</h2>
<p>Intracytoplasmic sperm injection for severe male infertility cases.</p>
<a href="icsi.php" class="text-teal-600">Learn More</a>
</div>

<div class="card">
<h2 class="text-xl font-bold mb-3">IUI</h2>
<p>Intrauterine insemination for mild infertility conditions.</p>
<a href="iui.php" class="text-teal-600">Learn More</a>
</div>

<div class="card">
<h2 class="text-xl font-bold mb-3">PGT</h2>
<p>Preimplantation genetic testing for chromosomal screening.</p>
<a href="pgt.php" class="text-teal-600">Learn More</a>
</div>

</div>
</section>

<?php include("../includes/footer.php"); ?>
EOF

echo "Phase 1 content deployed."