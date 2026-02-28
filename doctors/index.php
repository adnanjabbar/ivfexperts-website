<?php
$pageTitle = "Our Expert Team | IVF Experts Pakistan | Dr. Adnan Jabbar";
$metaDescription = "Meet the expert doctors at IVF Experts Pakistan. Our multi-disciplinary team provides world-class fertility, andrology, and reproductive medicine services in Lahore.";
include("../includes/header.php");
?>

<!-- HERO -->
<section class="relative py-24 lg:py-32 overflow-hidden bg-slate-950 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-950 via-slate-900 to-slate-950 -z-20"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-teal-500/10 rounded-full blur-[100px] -z-10"></div>
    <div class="max-w-5xl mx-auto px-6 text-center z-10">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-900/40 text-teal-300 text-sm font-semibold mb-6 border border-teal-800/50 backdrop-blur-md">
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
            Our Team
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white leading-[1.10] mb-6 tracking-tight" style="color:#fff;">
            The Experts Behind <br/><span class="text-teal-400">Your Journey.</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-light" style="color:#cbd5e1;">
            A world-class, multi-disciplinary team of specialists in reproductive medicine, andrology, embryology, and regenerative science — united under the leadership of Dr. Adnan Jabbar.
        </p>
    </div>
</section>

<!-- DOCTORS GRID -->
<section class="py-24 bg-soft">
    <div class="max-w-7xl mx-auto px-6">

        <!-- ========== LEAD DOCTOR ========== -->
        <div class="mb-20 fade-in">
            <div class="text-center mb-12">
                <p class="text-sm font-semibold text-teal-600 uppercase tracking-wider mb-2">Lead Consultant</p>
                <h2 class="text-3xl font-bold text-slate-900">Leadership</h2>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <!-- DOCTOR CARD: Dr. Adnan Jabbar -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-slate-100 hover:shadow-2xl transition-shadow">
                    <div class="md:flex">
                        <div class="md:w-1/3 bg-slate-100 flex items-center justify-center p-8">
                            <!-- Replace src with actual photo -->
                            <div class="w-48 h-48 rounded-full bg-teal-50 border-4 border-teal-200 flex items-center justify-center overflow-hidden">
                                <img src="/assets/images/doctors/dr-adnan-jabbar.jpg" alt="Dr. Adnan Jabbar" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full bg-teal-100 text-teal-600\'><svg class=\'w-20 h-20\' fill=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z\'/></svg></div>'">
                            </div>
                        </div>
                        <div class="md:w-2/3 p-8">
                            <h3 class="text-2xl font-bold text-slate-900 mb-1">Dr. Adnan Jabbar</h3>
                            <p class="text-teal-600 font-semibold mb-4">Lead Consultant — Reproductive Medicine, Andrology & Regenerative Fertility</p>
                            <p class="text-sm text-slate-600 mb-3"><strong>Place of Work:</strong> IVF Experts, Lahore | Sakina International Hospital</p>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Pakistan's leading reproductive medicine specialist. Pioneering stem cell-based fertility treatments in collaboration with Sakina International Hospital and The University of Lahore. Expert in male & female infertility, IVF/ICSI, surgical sperm retrieval, and regenerative reproductive medicine.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- END DOCTOR CARD -->
            </div>
        </div>

        <!-- ========== TEAM MEMBERS ========== -->
        <div class="fade-in">
            <div class="text-center mb-12">
                <p class="text-sm font-semibold text-teal-600 uppercase tracking-wider mb-2">Our Specialists</p>
                <h2 class="text-3xl font-bold text-slate-900">Team Members</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!--
                ==========================================================
                TEMPLATE: Copy this block for each new doctor.
                Replace the placeholders (NAME, DESIGNATION, ROLE, PLACE, DETAIL, PHOTO) with actual data.
                ==========================================================
                -->

                <!-- DOCTOR CARD TEMPLATE (copy this) -->
                <!--
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="p-6 text-center">
                        <div class="w-28 h-28 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center overflow-hidden mx-auto mb-4">
                            <img src="/assets/images/doctors/PHOTO.jpg" alt="NAME" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full bg-slate-50 text-slate-400\'><svg class=\'w-12 h-12\' fill=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z\'/></svg></div>'">
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">NAME</h3>
                        <p class="text-teal-600 text-sm font-semibold mb-2">DESIGNATION</p>
                        <p class="text-xs text-slate-500 mb-3"><strong>Role:</strong> ROLE</p>
                        <p class="text-xs text-slate-500 mb-3"><strong>Place:</strong> PLACE</p>
                        <p class="text-slate-600 text-sm leading-relaxed">DETAIL</p>
                    </div>
                </div>
                -->
                <!-- END DOCTOR CARD TEMPLATE -->

                <!-- EXAMPLE PLACEHOLDER CARDS (remove when adding real data) -->
                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-dashed border-slate-300 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="p-8 text-center flex flex-col items-center justify-center min-h-[280px]">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <p class="text-slate-400 font-semibold">Coming Soon</p>
                        <p class="text-xs text-slate-300 mt-2">New team member to be added</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-dashed border-slate-300 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="p-8 text-center flex flex-col items-center justify-center min-h-[280px]">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <p class="text-slate-400 font-semibold">Coming Soon</p>
                        <p class="text-xs text-slate-300 mt-2">New team member to be added</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden shadow-lg border border-dashed border-slate-300 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="p-8 text-center flex flex-col items-center justify-center min-h-[280px]">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <p class="text-slate-400 font-semibold">Coming Soon</p>
                        <p class="text-xs text-slate-300 mt-2">New team member to be added</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- JSON-LD -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MedicalOrganization",
  "name": "IVF Experts Pakistan",
  "url": "https://ivfexperts.pk/doctors/",
  "medicalSpecialty": "Reproductive Medicine",
  "address": { "@type": "PostalAddress", "addressLocality": "Lahore", "addressCountry": "PK" },
  "member": [
    { "@type": "Physician", "name": "Dr. Adnan Jabbar", "medicalSpecialty": "Reproductive Endocrinology and Infertility" }
  ]
}
</script>

<!-- CTA -->
<section class="py-20 bg-white border-t border-slate-200">
    <div class="max-w-4xl mx-auto px-6 text-center fade-in">
        <h2 class="text-3xl font-bold text-slate-900 mb-6">Meet the team that never gives up.</h2>
        <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto">Every member of IVF Experts is dedicated to one mission: helping you build your family.</p>
        <a href="https://wa.me/923111101483" class="bg-teal-600 text-white px-10 py-5 rounded-xl font-bold shadow-xl hover:bg-teal-700 hover:scale-105 transition-all text-lg inline-flex items-center gap-3">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/></svg>
            Contact Us
        </a>
    </div>
</section>

<?php include("../includes/footer.php"); ?>
