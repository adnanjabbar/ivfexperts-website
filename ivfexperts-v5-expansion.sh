#!/bin/bash

echo "Starting IVFExperts V5 Elite Expansion..."

# =========================
# GLOBAL STYLE UPGRADE
# =========================
cat > assets/css/style.css << 'EOF'
body {
    font-family: 'Inter', sans-serif;
    background: #ffffff;
    color: #1f2937;
}

.section {
    padding: 120px 0;
}

.wave {
    position: relative;
}

.wave::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 100px;
    background: url("data:image/svg+xml;utf8,<svg viewBox='0 0 1440 320' xmlns='http://www.w3.org/2000/svg'><path fill='%23f3f4f6' d='M0,160L80,170.7C160,181,320,203,480,213.3C640,224,800,224,960,213.3C1120,203,1280,181,1360,170.7L1440,160V320H0Z'></path></svg>") no-repeat;
    background-size: cover;
}

.card {
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    padding: 40px;
    transition: 0.3s ease;
}

.card:hover {
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    transform: translateY(-5px);
}

.btn-primary {
    background: #0f766e;
    color: white;
    padding: 14px 30px;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #115e59;
}
EOF


# =========================
# GLOBAL JS (ROTATION + COUNTER + TESTIMONIAL)
# =========================
cat > assets/js/app.js << 'EOF'
document.addEventListener("DOMContentLoaded", function() {

const texts = [
"Advanced IVF & ICSI Treatment in Lahore",
"Personalized Fertility Care Across Pakistan",
"Male & Female Infertility Expertise",
"Teleconsultations for Overseas Pakistanis"
];

let index = 0;
const rotating = document.getElementById("rotating-text");
if(rotating){
setInterval(() => {
rotating.style.opacity = 0;
setTimeout(() => {
index = (index + 1) % texts.length;
rotating.innerText = texts[index];
rotating.style.opacity = 1;
}, 400);
}, 4000);
}

const counters = document.querySelectorAll(".counter");
counters.forEach(counter => {
counter.innerText = "0";
const target = +counter.getAttribute("data-target");
const updateCounter = () => {
const value = +counter.innerText;
if(value < target){
counter.innerText = Math.ceil(value + target/100);
setTimeout(updateCounter, 20);
} else {
counter.innerText = target + "+";
}
};
updateCounter();
});

});
EOF


# =========================
# HOMEPAGE UPGRADE
# =========================
cat > index.php << 'EOF'
<?php include("includes/header.php"); ?>

<section class="section text-center">
<h1 style="font-size:56px; font-weight:800;">Your Journey to Parenthood Begins Here</h1>
<p id="rotating-text" style="font-size:22px; color:#0f766e;">Advanced IVF & ICSI Treatment in Lahore</p>
<br>
<a href="/contact/" class="btn-primary">Book Consultation</a>
</section>

<section class="section wave text-center">
<h2>Every Couple Deserves the Chance to Hold Their Child</h2>
<p style="max-width:800px;margin:auto;">
Infertility can bring uncertainty and emotional strain. At IVF Experts, care combines compassion with structured, evidence-based reproductive medicine.
</p>
</section>

<section class="section text-center">
<div class="card">
<h3 class="counter" data-target="1000">0</h3>
<p>Fertility Cases Managed</p>
</div>
</section>

<section class="section text-center">
<h2>Affiliated Hospitals</h2>
<div class="card">Healthnox Medical Center</div>
<div class="card">Skina International Hospital</div>
<div class="card">AQ Ortho & Gynae Complex</div>
<div class="card">Latif Hospital</div>
</section>

<?php include("includes/footer.php"); ?>
EOF


# =========================
# MALE INFERTILITY EXPANSION
# =========================
cat > male-infertility/index.php << 'EOF'
<?php include("../includes/header.php"); ?>
<section class="section">
<h1>Male Infertility Treatment in Lahore</h1>
<p>Comprehensive evaluation of low sperm count, azoospermia, DNA fragmentation and hormonal imbalance with structured ART planning.</p>
</section>
<?php include("../includes/footer.php"); ?>
EOF


# =========================
# FEMALE INFERTILITY EXPANSION
# =========================
cat > female-infertility/index.php << 'EOF'
<?php include("../includes/header.php"); ?>
<section class="section">
<h1>Female Infertility Specialist in Lahore</h1>
<p>PCOS, diminished ovarian reserve, endometriosis and ovulatory disorder management with personalized IVF protocols.</p>
</section>
<?php include("../includes/footer.php"); ?>
EOF


# =========================
# ART PROCEDURES EXPANSION
# =========================
cat > art-procedures/index.php << 'EOF'
<?php include("../includes/header.php"); ?>
<section class="section">
<h1>IVF, ICSI & Assisted Reproductive Technology</h1>
<p>Advanced IVF, ICSI, IUI and PGT procedures for couples across Pakistan and overseas Pakistanis.</p>
</section>
<?php include("../includes/footer.php"); ?>
EOF


# =========================
# CONTACT PAGE UPGRADE
# =========================
cat > contact/index.php << 'EOF'
<?php include("../includes/header.php"); ?>
<section class="section text-center">
<h1>Contact IVF Experts</h1>
<p>WhatsApp: +923111101483</p>
<p>Serving Lahore, Pakistan & International Patients</p>
</section>
<?php include("../includes/footer.php"); ?>
EOF


# =========================
# ABOUT PAGE LUXURY UPGRADE
# =========================
cat > about/index.php << 'EOF'
<?php include("../includes/header.php"); ?>
<section class="section text-center">
<h1>About Dr. Adnan Jabbar</h1>
<p>Fertility Specialist Consultant & Clinical Embryologist with background in Family and Emergency Medicine.</p>
</section>
<?php include("../includes/footer.php"); ?>
EOF


echo "V5 Expansion Complete."
echo "Now run:"
echo "git add ."
echo "git commit -m 'IVFExperts V5 Elite Expansion'"
echo "git push"