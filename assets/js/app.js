document.addEventListener("DOMContentLoaded", function() {

const texts = [
"Advanced IVF & ICSI Treatment in Lahore",
"Personalized Fertility Care Across Pakistan",
"Male & Female Infertility Expertise",
"Teleconsultations for Overseas Pakistanis"
];
// Counter animation
const counters = document.querySelectorAll(".counter");

counters.forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute("data-target");
        const count = +counter.innerText;
        const increment = target / 50;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 40);
        } else {
            counter.innerText = target + "+";
        }
    };

    updateCount();
});
// Sticky shrink header
    const header = document.querySelector("header");

    window.addEventListener("scroll", function() {
        if (window.scrollY > 80) {
            header.classList.add("header-shrink");
        } else {
            header.classList.remove("header-shrink");
        }
    });

    // Fade in on scroll
    const faders = document.querySelectorAll(".fade-in");

    const appearOptions = {
        threshold: 0.15
    };

    const appearOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("appear");
            observer.unobserve(entry.target);
        });
    }, appearOptions);

    faders.forEach(fader => {
        appearOnScroll.observe(fader);
    });

});

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
