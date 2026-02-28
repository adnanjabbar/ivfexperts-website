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
                Learn More &rarr;
              </a>
            </div>
            <div>
              <h4 class="font-bold mb-4">Common Conditions</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/male-infertility/low-sperm-count.php" class="hover:text-teal-600">Low Sperm Count</a></li>
                <li><a href="/male-infertility/azoospermia.php" class="hover:text-teal-600">Azoospermia (Zero Sperm)</a></li>
                <li><a href="/male-infertility/varicocele.php" class="hover:text-teal-600">Varicocele</a></li>
                <li><a href="/male-infertility/erectile-ejaculatory-dysfunction.php" class="hover:text-teal-600">Erectile &amp; Ejaculatory Dysfunction</a></li>
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
                Learn More &rarr;
              </a>
            </div>
            <div>
              <h4 class="font-bold mb-4">Structural &amp; Hormonal</h4>
              <ul class="space-y-2 text-gray-700 text-sm">
                <li><a href="/female-infertility/pcos.php" class="hover:text-teal-600">PCOS</a></li>
                <li><a href="/female-infertility/endometriosis.php" class="hover:text-teal-600">Endometriosis</a></li>
                <li><a href="/female-infertility/blocked-tubes.php" class="hover:text-teal-600">Blocked Tubes</a></li>
                <li><a href="/female-infertility/uterine-fibroids-polyps.php" class="hover:text-teal-600">Uterine Fibroids &amp; Polyps</a></li>
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
                Learn More &rarr;
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
                <li><a href="/art-procedures/fertility-preservation.php" class="hover:text-teal-600">Egg &amp; Sperm Freezing</a></li>
                <li><a href="/art-procedures/ovarian-endometrial-prp.php" class="hover:text-teal-600">Ovarian &amp; Endometrial PRP</a></li>
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

  <!-- CTA (Desktop) -->
  <a href="https://wa.me/923111101483"
     class="hidden md:inline-block bg-teal-700 text-white px-6 py-2 rounded-md font-semibold hover:bg-teal-800 transition shadow-[0_4px_15px_rgba(15,118,110,0.3)] border-none">
    WhatsApp
  </a>

  <!-- HAMBURGER BUTTON (Mobile) -->
  <button id="mobile-menu-btn" aria-label="Open navigation menu" style="display:none;flex-direction:column;justify-content:center;align-items:center;width:40px;height:40px;border-radius:8px;background:transparent;border:none;cursor:pointer;">
    <span style="display:block;width:24px;height:2px;background:#374151;"></span>
    <span style="display:block;width:24px;height:2px;background:#374151;margin-top:6px;"></span>
    <span style="display:block;width:24px;height:2px;background:#374151;margin-top:6px;"></span>
  </button>
  <script>
    // Show hamburger on mobile
    (function(){
      var btn = document.getElementById('mobile-menu-btn');
      if(btn){
        function checkWidth(){ btn.style.display = window.innerWidth < 768 ? 'flex' : 'none'; }
        checkWidth();
        window.addEventListener('resize', checkWidth);
      }
    })();
  </script>

</div>
</header>

<!-- MOBILE MENU OVERLAY -->
<div id="mobile-menu-overlay" style="display:none;position:fixed;inset:0;z-index:9999;">
  <!-- Backdrop -->
  <div id="mobile-menu-backdrop" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);transition:opacity 0.3s;opacity:0;"></div>
  
  <!-- Slide-in Panel -->
  <div id="mobile-menu-panel" style="position:absolute;top:0;right:0;bottom:0;width:85%;max-width:400px;background:#fff;transform:translateX(100%);transition:transform 0.3s ease;overflow-y:auto;box-shadow:-4px 0 25px rgba(0,0,0,0.15);">
    
    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #e5e7eb;">
      <a href="/" style="font-size:1.35rem;font-weight:800;color:#0f766e;text-decoration:none;">IVF Experts</a>
      <button id="mobile-menu-close" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:8px;background:transparent;cursor:pointer;border:none;font-size:24px;color:#374151;" aria-label="Close menu">&#x2715;</button>
    </div>

    <!-- Navigation Links -->
    <nav style="padding:16px 0;">
      <a href="/" style="display:block;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;text-decoration:none;border-bottom:1px solid #f1f5f9;">Home</a>
      <a href="/about/" style="display:block;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;text-decoration:none;border-bottom:1px solid #f1f5f9;">About</a>

      <!-- Male Infertility Accordion -->
      <div class="mobile-accordion" style="border-bottom:1px solid #f1f5f9;">
        <button class="mobile-accordion-toggle" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;background:transparent;border:none;cursor:pointer;text-align:left;">
          Male Infertility
          <svg class="mobile-accordion-arrow" style="width:18px;height:18px;transition:transform 0.3s;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="mobile-accordion-content" style="display:none;padding:0 24px 12px 24px;background:#f8fafc;">
          <a href="/male-infertility/" style="display:block;padding:10px 16px;font-size:14px;color:#0f766e;font-weight:600;text-decoration:none;border-radius:8px;">Overview &rarr;</a>
          <a href="/male-infertility/low-sperm-count.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Low Sperm Count</a>
          <a href="/male-infertility/azoospermia.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Azoospermia (Zero Sperm)</a>
          <a href="/male-infertility/varicocele.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Varicocele</a>
          <a href="/male-infertility/erectile-ejaculatory-dysfunction.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Erectile &amp; Ejaculatory Dysfunction</a>
          <a href="/male-infertility/dna-fragmentation.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">DNA Fragmentation</a>
          <a href="/male-infertility/unexplained-male-infertility.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Unexplained Male Infertility</a>
          <a href="/male-infertility/klinefelters-syndrome.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Klinefelter's Syndrome</a>
        </div>
      </div>

      <!-- Female Infertility Accordion -->
      <div class="mobile-accordion" style="border-bottom:1px solid #f1f5f9;">
        <button class="mobile-accordion-toggle" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;background:transparent;border:none;cursor:pointer;text-align:left;">
          Female Infertility
          <svg class="mobile-accordion-arrow" style="width:18px;height:18px;transition:transform 0.3s;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="mobile-accordion-content" style="display:none;padding:0 24px 12px 24px;background:#f8fafc;">
          <a href="/female-infertility/" style="display:block;padding:10px 16px;font-size:14px;color:#0f766e;font-weight:600;text-decoration:none;border-radius:8px;">Overview &rarr;</a>
          <a href="/female-infertility/pcos.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">PCOS</a>
          <a href="/female-infertility/endometriosis.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Endometriosis</a>
          <a href="/female-infertility/blocked-tubes.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Blocked Tubes</a>
          <a href="/female-infertility/uterine-fibroids-polyps.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Uterine Fibroids &amp; Polyps</a>
          <a href="/female-infertility/adenomyosis.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Adenomyosis</a>
          <a href="/female-infertility/diminished-ovarian-reserve.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Low Ovarian Reserve (AMH)</a>
          <a href="/female-infertility/recurrent-pregnancy-loss.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Recurrent Miscarriages</a>
          <a href="/female-infertility/unexplained-infertility.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Unexplained Infertility</a>
        </div>
      </div>

      <!-- ART Procedures Accordion -->
      <div class="mobile-accordion" style="border-bottom:1px solid #f1f5f9;">
        <button class="mobile-accordion-toggle" style="display:flex;align-items:center;justify-content:space-between;width:100%;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;background:transparent;border:none;cursor:pointer;text-align:left;">
          ART Procedures
          <svg class="mobile-accordion-arrow" style="width:18px;height:18px;transition:transform 0.3s;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="mobile-accordion-content" style="display:none;padding:0 24px 12px 24px;background:#f8fafc;">
          <a href="/art-procedures/" style="display:block;padding:10px 16px;font-size:14px;color:#0f766e;font-weight:600;text-decoration:none;border-radius:8px;">Overview &rarr;</a>
          <a href="/art-procedures/ivf.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">IVF Treatment</a>
          <a href="/art-procedures/icsi.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">ICSI Treatment</a>
          <a href="/art-procedures/iui.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">IUI Insemination</a>
          <a href="/art-procedures/pgt.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">PGT / Gender Selection</a>
          <a href="/art-procedures/fertility-preservation.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Egg &amp; Sperm Freezing</a>
          <a href="/art-procedures/ovarian-endometrial-prp.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Ovarian &amp; Endometrial PRP</a>
          <a href="/art-procedures/surgical-sperm-retrieval.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Surgical Sperm Retrieval</a>
          <a href="/art-procedures/laser-assisted-hatching.php" style="display:block;padding:8px 16px;font-size:13px;color:#475569;text-decoration:none;">Laser-Assisted Hatching</a>
        </div>
      </div>

      <a href="/contact/" style="display:block;padding:14px 24px;font-size:15px;font-weight:600;color:#1e293b;text-decoration:none;border-bottom:1px solid #f1f5f9;">Contact</a>
    </nav>

    <!-- WhatsApp CTA -->
    <div style="padding:16px 24px 32px;">
      <a href="https://wa.me/923111101483" style="display:flex;align-items:center;justify-content:center;gap:10px;background:#0f766e;color:#fff;padding:14px 24px;border-radius:12px;font-weight:700;font-size:15px;text-decoration:none;box-shadow:0 4px 15px rgba(15,118,110,0.3);">
        <svg style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/></svg>
        WhatsApp Consultation
      </a>
    </div>

  </div>
</div>

<div class="h-24" style="height:6rem;"></div>

