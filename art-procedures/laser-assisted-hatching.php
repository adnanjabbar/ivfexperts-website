<?php
include("../includes/header.php");
?>

<!-- HERO SECTION -->
<section class="relative min-h-[75vh] flex items-center pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden bg-slate-900 text-white">
    <div class="absolute inset-0 bg-gradient-to-l from-slate-900 via-amber-900/20 to-slate-950 -z-20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjA1KSIvPjwvc3ZnPg==')] opacity-20 mix-blend-overlay -z-10"></div>
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-amber-500/10 blur-[100px] rounded-full -z-10 animate-pulse"></div>

    <div class="max-w-7xl mx-auto px-6 w-full grid lg:grid-cols-12 gap-16 items-center z-10">
        <!-- Narratives -->
        <div class="lg:col-span-12 md:col-span-8 md:col-start-3 lg:col-start-1 text-center lg:text-left fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-900/50 text-amber-200 text-sm font-semibold mb-6 border border-amber-700/50">
                <i class="ph-bold ph-crosshair text-amber-400"></i>
                Micromanipulation Technology
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white leading-[1.10] mb-6 tracking-tight" style="color:#ffffff;">
                Enhancing Implantation with <br class="hidden lg:block"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-orange-500" style="background:linear-gradient(to right,#fcd34d,#f97316);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    Laser-Assisted Hatching.
                </span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed font-light mx-auto lg:mx-0" style="color:#cbd5e1;">
                A beautiful embryo means nothing if it cannot break out of its shell to attach to the uterus. Using state-of-the-art laboratory lasers, we give your embryo the precise mechanical opening it needs to ensure a successful pregnancy.
            </p>

            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                <a href="https://wa.me/923111101483" class="btn-primary bg-amber-500 hover:bg-amber-400 text-slate-900 shadow-[0_8px_25px_rgba(245,158,11,0.3)] border-none px-8 py-4">
                    Maximize IVF Success
                </a>
            </div>
        </div>
    </div>
</section>

<!-- THE MECHANICS OF HATCHING -->
<section class="py-24 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
        <!-- Text -->
        <div class="fade-in">
            <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6">Breaking the Shell</h2>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                Every human embryo is surrounded by a protective protein shell called the <strong>Zona Pellucida</strong>. This shell helps protect the embryo as it travels down the fallopian tube. 
            </p>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                However, once the embryo reaches the uterus on Day 5 (Blastocyst stage), it must physically break out of this shell ("hatch") to embed itself into the uterine lining. <span class="text-amber-600 font-bold">If it cannot hatch, you cannot get pregnant.</span>
            </p>
            
            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                <h4 class="font-bold text-slate-900 mb-2">How the Laser Works</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Under a highly magnified microscope, the embryologist fires a microscopic, computer-calibrated laser beam at the zona pellucida. This creates a tiny, perfectly round hole in the shell, drastically lowering the physical barrier and allowing the embryo to easily escape and implant after it is transferred into the uterus.
                </p>
            </div>
        </div>
        
        <!-- Image representations -->
        <div class="grid grid-cols-2 gap-6 fade-in" style="transition-delay: 200ms;">
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 text-center shadow-inner pt-12">
                <div class="w-24 h-24 mx-auto border-[6px] border-slate-300 rounded-full flex items-center justify-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full animate-pulse-slow"></div>
                </div>
                <h4 class="font-bold text-slate-900">Thick Shell</h4>
                <p class="text-xs text-slate-500 mt-2">The embryo is trapped.</p>
            </div>
            <div class="bg-amber-50 p-6 rounded-3xl border border-amber-200 text-center shadow-lg transform -translate-y-4 pt-12 relative overflow-hidden">
                <div class="absolute top-0 right-1/2 translate-x-1/2 w-1 h-12 bg-gradient-to-b from-red-500 to-transparent shadow-[0_0_10px_red]"></div>
                <div class="w-24 h-24 mx-auto border-[6px] border-amber-400 border-t-transparent rounded-full flex items-center justify-center mb-6 relative">
                    <div class="w-16 h-16 bg-blue-100 rounded-full animate-bounce"></div> <!-- Represents escaping embryo -->
                </div>
                <h4 class="font-bold text-amber-900">Laser Assisted</h4>
                <p class="text-xs text-amber-700 mt-2">A precise opening is made.</p>
            </div>
        </div>
    </div>
</section>

<!-- WHO NEEDS IT? -->
<section class="py-24 bg-slate-50">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-slate-900 mb-12">Who Benefits from Laser Hatching?</h2>
        
        <div class="space-y-6 fade-in">
            <!-- Indicator 1 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-start gap-6 hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-hourglass-high text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Advanced Maternal Age</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">As women get older (typically 38 and above), the zona pellucida of their eggs naturally becomes thicker and much harder. The laser neutralizes this age-related barrier.</p>
                </div>
            </div>

            <!-- Indicator 2 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-start gap-6 hover:shadow-md transition">
                <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-snowflake text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Frozen Embryo Transfers (FET)</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">The <a href="/art-procedures/fertility-preservation.php" class="text-teal-600 font-semibold hover:underline">cryopreservation (freezing) process</a> causes the embryo's shell to harden chemically. We routinely use Laser Assisted Hatching on all thawed embryos to reverse this hardening prior to transfer.</p>
                </div>
            </div>

            <!-- Indicator 3 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-start gap-6 hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-arrow-counter-clockwise text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Repeated IVF Failures</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">If you have had multiple <a href="/art-procedures/ivf.php" class="text-rose-500 font-semibold hover:underline">IVF attempts</a> where beautiful embryos were transferred but failed to result in a pregnancy, shell hardening is a prime suspect. </p>
                </div>
            </div>
            
            <!-- Indicator 4 -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-start gap-6 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-dna text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Preimplantation Genetic Testing (PGT)</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">In order to perform <a href="/art-procedures/pgt.php" class="text-amber-600 font-semibold hover:underline">PGT-A</a>, the embryologist must extract a few cells from the embryo. The laser is used to safely create a microscopic opening to extract these testing cells without harming the embryo.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SCHEMA -->
<script type="application/ld+json">
[
  {
    "@context": "https://schema.org",
    "@type": "MedicalProcedure",
    "name": "Laser Assisted Hatching for IVF",
    "description": "Improve your embryo implantation rates with state-of-the-art Laser-Assisted Hatching during IVF processing in Lahore, Pakistan.",
    "url": "https://ivfexperts.pk/art-procedures/laser-assisted-hatching.php",
    "procedureType": "Assisted Reproductive Technology"
  }
]
</script>

<?php include("../includes/footer.php"); ?>
