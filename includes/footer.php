<footer class="bg-gray-900 text-gray-300 py-16">
<div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-12">

<div>
<h3 class="text-white text-lg font-semibold mb-4">IVF Experts</h3>
<p>Advanced fertility & IVF care serving patients across Pakistan and overseas Pakistanis.</p>
</div>

<div>
<h4 class="text-white font-semibold mb-4">Quick Links</h4>
<ul class="space-y-2">
<li><a href="/male-infertility/" class="hover:text-white">Male Infertility</a></li>
<li><a href="/female-infertility/" class="hover:text-white">Female Infertility</a></li>
<li><a href="/art-procedures/" class="hover:text-white">ART Procedures</a></li>
</ul>
</div>

<div>
<h4 class="text-white font-semibold mb-4">Contact</h4>
<p>WhatsApp: +92 3 111 101 483</p>
</div>

</div>

<div class="text-center text-sm mt-12 text-gray-500">
© <?= date("Y") ?> IVF Experts. All rights reserved.
</div>
</footer>

<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "MedicalBusiness",
"name": "IVF Experts",
"url": "https://ivfexperts.pk",
"medicalSpecialty": "Reproductive Medicine",
"areaServed": "Pakistan",
"telephone": "+923111101483",
"sameAs": []
}
</script>
<script>
/* Counter Animation */
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
const updateCount = () => {
const target = +counter.getAttribute('data-target');
const count = +counter.innerText;
const increment = target / 200;
if(count < target){
counter.innerText = Math.ceil(count + increment);
setTimeout(updateCount, 10);
} else {
counter.innerText = target;
}
};
updateCount();
});

/* Testimonial Slider */
let index = 0;
const items = document.querySelectorAll('.testimonial-item');
setInterval(() => {
items[index].classList.add('hidden');
index = (index + 1) % items.length;
items[index].classList.remove('hidden');
}, 4000);
</script>

<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "MedicalBusiness",
"name": "IVF Experts",
"url": "https://ivfexperts.pk",
"telephone": "+923111101483",
"areaServed": "Pakistan",
"medicalSpecialty": "Reproductive Medicine"
}
</script>

</body>
</html>