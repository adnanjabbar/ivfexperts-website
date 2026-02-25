#!/bin/bash

echo "Starting IVF Experts Platform V2 Deployment..."

mkdir -p assets/css assets/js assets/images
mkdir -p includes
mkdir -p male-infertility female-infertility art-procedures about contact
mkdir -p admin tcpdf config

############################################
# GLOBAL CSS
############################################
cat > assets/css/style.css <<'EOF'
body { font-family:'Inter',sans-serif; background:#f8fafc; color:#1f2937; }
.section { padding:6rem 1rem; }
.card { background:#fff; padding:2rem; border-radius:1.5rem; border:1px solid #e5e7eb; transition:.3s; }
.card:hover { transform:translateY(-5px); box-shadow:0 20px 40px rgba(0,0,0,0.08); }
.hero-gradient { background:linear-gradient(135deg,#0f172a,#0e7490,#1e3a8a); }
.btn-primary { background:#0e7490; color:white; padding:.8rem 1.6rem; border-radius:.75rem; font-weight:600; }
.btn-primary:hover { background:#155e75; }
.accordion-content { display:none; padding-top:10px; color:#4b5563; }
EOF

############################################
# GLOBAL JS
############################################
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

############################################
# DATABASE CONFIG
############################################
cat > config/db.php <<'EOF'
<?php
$host = "localhost";
$dbname = "u400207225_adnanjabbar";
$username = "u400207225_adnanjabbar";
$password = "4991701AdnanJabbar";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
EOF

############################################
# HEADER
############################################
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
<nav class="hidden md:flex space-x-8 text-sm font-semibold text-gray-700">
<a href="/">Home</a>
<a href="/male-infertility/">Male Infertility</a>
<a href="/female-infertility/">Female Infertility</a>
<a href="/art-procedures/">ART Procedures</a>
<a href="/contact/">Contact</a>
</nav>
<a href="https://wa.me/923111101483" class="btn-primary">WhatsApp</a>
</div>
</header>
<div class="h-24"></div>
EOF

############################################
# FOOTER
############################################
cat > includes/footer.php <<'EOF'
<footer class="bg-gray-900 text-white py-16 mt-20">
<div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-10">
<div>
<h3 class="font-bold mb-4">IVF Experts</h3>
<p>Advanced fertility & IVF care serving Pakistan and overseas Pakistanis.</p>
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

############################################
# MALE PILLAR (STRUCTURE READY FOR EXPANSION)
############################################
cat > male-infertility/index.php <<'EOF'
<?php
$pageTitle="Male Infertility Treatment in Pakistan | IVF Experts";
$metaDescription="Comprehensive male infertility diagnosis and ICSI treatment.";
include("../includes/header.php");
?>

<section class="hero-gradient text-white text-center py-24">
<h1 class="text-5xl font-extrabold mb-6">Male Infertility Treatment in Pakistan</h1>
<p class="max-w-3xl mx-auto text-lg">
Advanced diagnosis and treatment for low sperm count, azoospermia, varicocele and DNA fragmentation.
</p>
</section>

<section class="section">
<div class="max-w-5xl mx-auto space-y-10">

<div class="card">
<h2 class="text-2xl font-bold mb-4">Understanding Male Infertility</h2>
<p>Male infertility contributes to nearly 50% of infertility cases. Proper semen analysis and hormonal evaluation determine the treatment pathway.</p>
</div>

<div class="card">
<h2 class="text-2xl font-bold mb-4">Conditions We Treat</h2>
<ul class="list-disc ml-6 space-y-2">
<li><a href="low-sperm-count.php" class="text-teal-600">Low Sperm Count</a></li>
<li><a href="azoospermia.php" class="text-teal-600">Azoospermia</a></li>
<li><a href="dna-fragmentation.php" class="text-teal-600">DNA Fragmentation</a></li>
<li><a href="varicocele.php" class="text-teal-600">Varicocele</a></li>
</ul>
</div>

</div>
</section>

<?php include("../includes/footer.php"); ?>
EOF

############################################
# ADMIN LOGIN
############################################
cat > admin/login.php <<'EOF'
<?php
session_start();
if(isset($_POST['login'])){
  if($_POST['username']=="admin" && $_POST['password']=="password123"){
    $_SESSION['admin']=true;
    header("Location: dashboard.php");
  }
}
?>
<form method="post">
<input type="text" name="username" placeholder="Username"><br>
<input type="password" name="password" placeholder="Password"><br>
<button name="login">Login</button>
</form>
EOF

############################################
# WHO 6 INTERPRETATION ENGINE
############################################
cat > admin/who6.php <<'EOF'
<?php
function interpret($count,$motility,$morphology){
  if($count==0){ return "Azoospermia"; }
  if($count<15){ return "Oligospermia"; }
  if($motility<40){ return "Asthenozoospermia"; }
  if($morphology<4){ return "Teratozoospermia"; }
  return "Normozoospermia";
}
?>
EOF

echo "V2 Deployment Completed."