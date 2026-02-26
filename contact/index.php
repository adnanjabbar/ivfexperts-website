<?php
$pageTitle = "Contact Dr. Adnan Jabbar | Fertility Specialist in Lahore | IVF & Infertility Consultation";
$metaDescription = "Schedule your fertility consultation in Lahore with Dr. Adnan Jabbar – dual-trained Fertility Consultant & Clinical Embryologist. In-person & teleconsultations for IVF, ICSI, male/female infertility. WhatsApp booking available for Pakistan & overseas patients.";
include("../includes/header.php");
?>

<!-- HERO -->
<section class="relative min-h-[70vh] flex items-center bg-gradient-to-br from-teal-50 via-white to-emerald-50/30 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-teal-100/20 to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-teal-200/25 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-16 lg:py-24 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold leading-tight text-gray-900 mb-6">
            Schedule Your Fertility Consultation
        </h1>
        <p class="text-lg lg:text-xl text-gray-700 leading-relaxed max-w-4xl mx-auto">
            Take the first step with clarity and confidence. Consultations focus on thorough evaluation, honest discussion, and a personalized, evidence-based plan — never rushed, always respectful of your journey.
        </p>

        <div class="flex flex-wrap justify-center gap-6 mt-10">
            <a href="https://wa.me/923111101483?text=Hello%20Dr.%20Adnan%20Jabbar,%20I'd%20like%20to%20book%20a%20consultation" 
               class="inline-flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white px-10 py-5 rounded-lg font-bold text-lg shadow-2xl transition">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/>
                </svg>
                Start on WhatsApp
            </a>
            <a href="#inquiry-form" class="bg-white text-teal-900 border-2 border-teal-900 hover:bg-teal-50 px-10 py-5 rounded-lg font-bold text-lg shadow-xl transition">
                Fill Inquiry Form
            </a>
        </div>
    </div>
</section>

<!-- CONTACT OPTIONS -->
<section class="section-lg bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-6 grid lg:grid-cols-2 gap-12 lg:gap-16 items-start">

        <!-- WhatsApp & Quick Info -->
        <div class="space-y-8">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                    Fastest Way to Connect
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed mb-6">
                    WhatsApp is the preferred and quickest way for most patients — especially overseas or busy families. Share your basic history, reports, and questions securely.
                </p>
                <a href="https://wa.me/923111101483?text=Hello%20Dr.%20Adnan%20Jabbar,%20I'm%20interested%20in%20a%20fertility%20consultation" 
                   class="inline-flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white px-10 py-5 rounded-lg font-bold text-lg shadow-2xl transition">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/>
                    </svg>
                    Message on WhatsApp Now
                </a>
                <p class="text-sm text-gray-600 mt-4">
                    Available for patients in Lahore, across Pakistan, and internationally.
                </p>
            </div>

            <div class="bg-teal-50/40 rounded-2xl p-8 border border-teal-100">
                <h3 class="text-2xl font-bold text-teal-800 mb-6">What to Prepare</h3>
                <ul class="space-y-4 text-gray-700">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Previous semen analysis / hormonal reports
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Ultrasound / HSG reports
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-teal-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Past treatment history (if any)
                    </li>
                </ul>
            </div>
        </div>

        <!-- Inquiry Form -->
        <div class="card bg-white/90 backdrop-blur-md border border-teal-100 shadow-2xl p-8 lg:p-10 rounded-3xl" id="inquiry-form">
            <h2 class="text-3xl font-bold text-teal-800 mb-8">
                Send Your Inquiry
            </h2>

            <form action="/contact/process.php" method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" required 
                           class="w-full border border-gray-300 rounded-lg px-5 py-4 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition"
                           placeholder="Your full name">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           class="w-full border border-gray-300 rounded-lg px-5 py-4 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition"
                           placeholder="your.email@example.com">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone / WhatsApp Number</label>
                    <input type="tel" id="phone" name="phone" 
                           class="w-full border border-gray-300 rounded-lg px-5 py-4 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition"
                           placeholder="+92 3XX XXXXXXX">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Fertility Concern / Message *</label>
                    <textarea id="message" name="message" rows="5" required 
                              class="w-full border border-gray-300 rounded-lg px-5 py-4 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent transition"
                              placeholder="Briefly describe your situation, previous tests, or questions..."></textarea>
                </div>

                <button type="submit" class="btn-primary w-full py-5 text-lg font-bold shadow-xl hover:shadow-2xl transition">
                    Submit Inquiry
                </button>
            </form>

            <p class="text-sm text-gray-500 mt-6 text-center">
                We usually respond within 24 hours (often faster via WhatsApp).
            </p>
        </div>
    </div>
</section>

<!-- PRACTICE LOCATIONS -->
<section class="section-lg bg-teal-50/30 border-t border-teal-100">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold mb-12 text-gray-900">
            Where We Consult in Lahore
        </h2>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition border border-teal-100">
                <svg class="w-10 h-10 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m0 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="font-semibold text-lg text-gray-900">Healthnox Medical Center</p>
                <p class="text-gray-600 mt-2">Lahore</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition border border-teal-100">
                <svg class="w-10 h-10 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m0 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="font-semibold text-lg text-gray-900">Skina International Hospital</p>
                <p class="text-gray-600 mt-2">Lahore</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition border border-teal-100">
                <svg class="w-10 h-10 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m0 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="font-semibold text-lg text-gray-900">AQ Ortho & Gynae Complex</p>
                <p class="text-gray-600 mt-2">Lahore</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition border border-teal-100">
                <svg class="w-10 h-10 mx-auto mb-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m0 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="font-semibold text-lg text-gray-900">Latif Hospital</p>
                <p class="text-gray-600 mt-2">Lahore</p>
            </div>
        </div>
    </div>
</section>

<!-- CONSULTATION FAQ -->
<section class="section-lg bg-white border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-6">
        <h2 class="text-3xl lg:text-4xl font-bold text-center mb-12 text-gray-900">
            Common Consultation Questions
        </h2>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="card p-8 rounded-2xl">
                <h3 class="text-xl font-semibold mb-4 text-teal-800">What should I bring to the consultation?</h3>
                <p class="text-gray-700">
                    Previous reports (semen analysis, hormones, ultrasound/HSG, AMH, etc.), treatment history, and any questions you'd like discussed.
                </p>
            </div>

            <div class="card p-8 rounded-2xl">
                <h3 class="text-xl font-semibold mb-4 text-teal-800">Do you offer teleconsultations?</h3>
                <p class="text-gray-700">
                    Yes — especially convenient for overseas Pakistanis or patients outside Lahore. WhatsApp video calls work well for initial discussions.
                </p>
            </div>

            <div class="card p-8 rounded-2xl">
                <h3 class="text-xl font-semibold mb-4 text-teal-800">How long until I get a treatment plan?</h3>
                <p class="text-gray-700">
                    We aim for clarity after the first detailed consultation. Some cases need additional tests before finalizing the plan.
                </p>
            </div>

            <div class="card p-8 rounded-2xl">
                <h3 class="text-xl font-semibold mb-4 text-teal-800">Is the consultation confidential?</h3>
                <p class="text-gray-700">
                    Absolutely — all discussions and records are strictly private and handled with the highest ethical standards.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FINAL CTA -->
<section class="section-lg bg-gradient-to-br from-teal-700 to-teal-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-6 py-16">
        <h2 class="text-3xl lg:text-4xl font-bold mb-6">
            Ready to Move Forward?
        </h2>
        <p class="text-lg lg:text-xl mb-10 opacity-90">
            One structured conversation can bring clarity and hope. We're here to guide you — in Lahore or from anywhere in the world.
        </p>
        <div class="flex flex-wrap justify-center gap-6">
            <a href="https://wa.me/923111101483?text=Hello%20Dr.%20Adnan%20Jabbar,%20I'm%20ready%20to%20schedule%20a%20consultation" 
               class="inline-flex items-center gap-3 bg-green-500 hover:bg-green-600 px-12 py-6 rounded-lg font-bold text-xl shadow-2xl transition">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.766 0 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.767-5.766-.001-3.187-2.575-5.77-5.764-5.771z"/>
                </svg>
                WhatsApp Now
            </a>
            <a href="#inquiry-form" class="bg-white text-teal-900 px-12 py-6 rounded-lg font-bold text-xl shadow-2xl hover:bg-gray-100 transition">
                Use Contact Form
            </a>
        </div>
    </div>
</section>

<?php include("../includes/footer.php"); ?>