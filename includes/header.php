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
<!-- Dynamic Page Schema -->
<?php if(isset($schemaType) && ($schemaType == 'MedicalCondition' || $schemaType == 'MedicalProcedure')): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "<?= $schemaType ?>",
  "name": "<?= htmlspecialchars($pageTitle) ?>",
  "description": "<?= htmlspecialchars($metaDescription) ?>",
  "url": "<?= $currentUrl ?>",
  "relevantSpecialty": {
    "@type": "MedicalSpecialty",
    "name": "<?= $medicalSpecialty ?>"
  }
  <?php if($schemaType == 'MedicalProcedure'): ?>
  ,"provider": {
    "@id": "https://ivfexperts.pk/#physician"
  }
  <?php elseif($schemaType == 'MedicalCondition'): ?>
  ,"possibleTreatment": {
    "@type": "MedicalTherapy",
    "name": "Consultation with Dr. Adnan Jabbar"
  }
  <?php endif; ?>
}
</script>
<?php endif; ?>

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

<!-- JavaScript for dynamic mega menu positioning -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const groups = document.querySelectorAll('nav .group');
  groups.forEach(group => {
    const dropdown = group.querySelector('.mega-dropdown');
    if (!dropdown) return;

    group.addEventListener('mouseenter', () => {
      const triggerRect = group.getBoundingClientRect();
      const dropdownWidth = dropdown.offsetWidth;
      const viewportWidth = window.innerWidth;

      let idealLeft = triggerRect.left + (triggerRect.width / 2) - (dropdownWidth / 2);

      // Clamp to viewport with margins
      const margin = 16;
      if (idealLeft < margin) {
        idealLeft = margin;
      }
      if (idealLeft + dropdownWidth > viewportWidth - margin) {
        idealLeft = viewportWidth - dropdownWidth - margin;
      }

      // Set left relative to the relative parent
      dropdown.style.left = `${idealLeft - triggerRect.left}px`;
    });
  });
});
</script>

</head>

<body class="bg-white text-gray-800 font-inter">

<header class="fixed top-0 left-0 w-full bg-white border-b border-gray-200 z-50">

<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

  <!-- LOGO -->
  <a href="/" class="text-2xl font-extrabold tracking-tight text-teal-700">
    IVF Experts
  </a>

  <!-- NAV -->
  <nav class="hidden md:flex items-center gap-x-10 text-sm font-semibold text-gray-700">

    <a href="/" class="hover:text-teal-600 transition">Home</a>
    <a href="/about/" class="hover:text-teal-600 transition">About</a>

    <!-- ================= MALE INFERTILITY ================= -->
    <div class="relative group inline-block">
      <a href="/male-infertility/" class="hover:text-teal-600 transition">
        Male Infertility
      </a>
      <div class="mega-dropdown absolute top-full left-0 w-[900px] max-w-[calc(100vw-2rem)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 pointer-events-none group-hover:pointer-events-auto z-50">
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
                <li><a href="/male-infertility/azoospermia.php" class="hover:text-teal-600">Azoospermia (Zero Sperm)</a></li>
                <li><a href="/male-infertility/varicocele.php" class="hover:text-teal-600">Varicocele</a></li>
                <li><a href="/male-infertility/erectile-ejaculatory-dysfunction.php" class="hover:text-teal-600">Erectile & Ejaculatory Dysfunction</a></li>
              </ul>
            </div>
            <div>
              <h4 class="font-bold mb-4">Advanced Evaluation</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/male-infertility/dna-fragmentation.php" class="hover:text-teal-600">DNA Fragmentation</a></li>
                <li><a href="/male-infertility/unexplained-male-infertility.php" class="hover:text-teal-600">Unexplained Male Infertility</a></li>
                <li><a href="/male-infertility/klinefelters-syndrome.php" class="hover:text-teal-600">Klinefelter's Syndrome</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= FEMALE INFERTILITY ================= -->
    <div class="relative group inline-block">
      <a href="/female-infertility/" class="hover:text-teal-600 transition">
        Female Infertility
      </a>
      <div class="mega-dropdown absolute top-full left-0 w-[900px] max-w-[calc(100vw-2rem)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 pointer-events-none group-hover:pointer-events-auto z-50">
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
              <h4 class="font-bold mb-4">Structural & Hormonal</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/female-infertility/pcos.php" class="hover:text-teal-600">PCOS</a></li>
                <li><a href="/female-infertility/endometriosis.php" class="hover:text-teal-600">Endometriosis</a></li>
                <li><a href="/female-infertility/blocked-tubes.php" class="hover:text-teal-600">Blocked Tubes</a></li>
                <li><a href="/female-infertility/uterine-fibroids-polyps.php" class="hover:text-teal-600">Uterine Fibroids & Polyps</a></li>
                <li><a href="/female-infertility/adenomyosis.php" class="hover:text-teal-600">Adenomyosis</a></li>
              </ul>
            </div>
            <div>
              <h4 class="font-bold mb-4">Complex Diagnoses</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/female-infertility/diminished-ovarian-reserve.php" class="hover:text-teal-600">Low Ovarian Reserve (AMH)</a></li>
                <li><a href="/female-infertility/recurrent-pregnancy-loss.php" class="hover:text-teal-600">Recurrent Miscarriages</a></li>
                <li><a href="/female-infertility/unexplained-infertility.php" class="hover:text-teal-600">Unexplained Infertility</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= ART PROCEDURES ================= -->
    <div class="relative group inline-block">
      <a href="/art-procedures/" class="hover:text-teal-600 transition">
        ART Procedures
      </a>
      <div class="mega-dropdown absolute top-full left-0 w-[900px] max-w-[calc(100vw-2rem)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 pointer-events-none group-hover:pointer-events-auto z-50">
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
                <li><a href="/art-procedures/ivf.php" class="hover:text-teal-600">IVF Treatment</a></li>
                <li><a href="/art-procedures/icsi.php" class="hover:text-teal-600">ICSI Treatment</a></li>
                <li><a href="/art-procedures/iui.php" class="hover:text-teal-600">IUI Insemination</a></li>
                <li><a href="/art-procedures/pgt.php" class="hover:text-teal-600">PGT / Gender Selection</a></li>
              </ul>
            </div>
            <div>
              <h4 class="font-bold mb-4">Advanced Laboratory</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/art-procedures/fertility-preservation.php" class="hover:text-teal-600">Egg & Sperm Freezing</a></li>
                <li><a href="/art-procedures/ovarian-endometrial-prp.php" class="hover:text-teal-600">Ovarian & Endometrial PRP</a></li>
                <li><a href="/art-procedures/surgical-sperm-retrieval.php" class="hover:text-teal-600">Surgical Sperm Retrieval</a></li>
                <li><a href="/art-procedures/laser-assisted-hatching.php" class="hover:text-teal-600">Laser-Assisted Hatching</a></li>
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
     class="hidden md:inline-block bg-teal-700 text-white px-6 py-2 rounded-md font-semibold hover:bg-teal-800 transition shadow-[0_4px_15px_rgba(15,118,110,0.3)] border-none">
    WhatsApp
  </a>

</div>
</header>

<div class="h-24"></div>
