<footer class="bg-gray-950 text-gray-300 pt-20 pb-10 mt-24">

<div class="max-w-7xl mx-auto px-6">

    <!-- TOP GRID -->
    <div class="grid md:grid-cols-4 gap-12">

        <!-- BRAND -->
        <div>
            <h3 class="text-white font-semibold text-lg mb-6">
                IVF Experts
            </h3>
            <p class="text-sm leading-relaxed text-gray-400">
                Fertility Specialist Consultant & Clinical Embryologist providing structured, evidence-based IVF, ICSI and infertility treatment in Lahore, across Pakistan and for overseas patients.
            </p>

            <p class="text-xs text-gray-500 mt-6">
                Serving Lahore • Pakistan • Teleconsultations Worldwide
            </p>
        </div>

        <!-- CORE SECTIONS -->
        <div>
            <h4 class="text-white font-semibold mb-6">
                Core Areas
            </h4>

            <a href="/male-infertility/" class="block py-2 text-sm hover:text-white transition">
                Male Infertility
            </a>
            <a href="/female-infertility/" class="block py-2 text-sm hover:text-white transition">
                Female Infertility
            </a>
            <a href="/art-procedures/" class="block py-2 text-sm hover:text-white transition">
                ART Procedures
            </a>
            <a href="/about/" class="block py-2 text-sm hover:text-white transition">
                About Dr. Adnan Jabbar
            </a>
        </div>

        <!-- ART LINKS -->
        <div>
            <h4 class="text-white font-semibold mb-6">
                ART Treatments
            </h4>

            <a href="/art-procedures/ivf.php" class="block py-2 text-sm hover:text-white transition">
                IVF Treatment
            </a>
            <a href="/art-procedures/icsi.php" class="block py-2 text-sm hover:text-white transition">
                ICSI Treatment
            </a>
            <a href="/art-procedures/iui.php" class="block py-2 text-sm hover:text-white transition">
                IUI Procedure
            </a>
            <a href="/art-procedures/pgt.php" class="block py-2 text-sm hover:text-white transition">
                Genetic Testing (PGT)
            </a>
        </div>

        <!-- CONTACT -->
        <div>
            <h4 class="text-white font-semibold mb-6">
                Consultation
            </h4>

            <p class="text-sm text-gray-400 mb-3">
                WhatsApp Consultation:
            </p>

            <a href="https://wa.me/923111101483"
               class="inline-block bg-teal-700 text-white px-5 py-3 rounded-md text-sm font-semibold hover:bg-teal-800 transition">
                +92 311 1101483
            </a>

            <p class="text-xs text-gray-500 mt-6 leading-relaxed">
                For appointment scheduling and fertility case discussion.
            </p>
        </div>

    </div>

    <!-- DIVIDER -->
    <div class="border-t border-gray-800 mt-16 pt-8 text-center text-sm text-gray-500">

        <p>
            Evidence-based reproductive medicine integrating clinical and embryology expertise.
        </p>

        <p class="mt-4">
            © <?php echo date("Y"); ?> IVF Experts. All Rights Reserved.
        </p>

    </div>

</div>

</footer>


<!-- FLOATING WHATSAPP BUTTON -->
<a href="https://wa.me/923111101483"
   class="fixed bottom-6 right-6 bg-teal-700 text-white px-6 py-3 rounded-full shadow-2xl hover:bg-teal-800 transition z-50 text-sm font-semibold">
   WhatsApp Consultation
</a>


<!-- STRUCTURED DATA -->
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
      "areaServed": {
        "@type": "Country",
        "name": "Pakistan"
      },
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
      "knowsAbout": [
        "IVF",
        "ICSI",
        "IUI",
        "Male Infertility",
        "Female Infertility",
        "PGT",
        "Embryology"
      ]
    }
  ]
}
</script>

<!-- Breadcrumb Schema -->
<?php if(isset($faqSchema)): ?>
<script type="application/ld+json">
{
"@context":"https://schema.org",
"@type":"FAQPage",
"mainEntity":[
<?php foreach($faqSchema as $index => $faq): ?>
{
"@type":"Question",
"name":"<?= $faq['question'] ?>",
"acceptedAnswer":{
"@type":"Answer",
"text":"<?= $faq['answer'] ?>"
}
}<?= $index + 1 < count($faqSchema) ? "," : "" ?>
<?php endforeach; ?>
]
}
</script>
<?php endif; ?>
<!-- End of Breadcrumb Schema -->
</body>
</html>