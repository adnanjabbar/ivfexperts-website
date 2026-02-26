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
