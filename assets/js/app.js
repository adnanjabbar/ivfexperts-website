document.addEventListener("DOMContentLoaded", function () {

    /* ===============================
       ROTATING HERO TEXT
    =============================== */
    const texts = [
        "Advanced IVF & ICSI Treatment in Lahore",
        "Personalized Fertility Care Across Pakistan",
        "Male & Female Infertility Expertise",
        "Teleconsultations for Overseas Pakistanis"
    ];

    let index = 0;
    const rotating = document.getElementById("rotating-text");

    if (rotating) {
        setInterval(function () {
            rotating.style.opacity = 0;
            rotating.style.transform = "translateY(10px)";

            setTimeout(function () {
                index = (index + 1) % texts.length;
                rotating.innerText = texts[index];
                rotating.style.opacity = 1;
                rotating.style.transform = "translateY(0)";
            }, 500);
        }, 4500);
    }

    /* ===============================
       STICKY HEADER SHADOW
    =============================== */
    const header = document.querySelector("header");

    if (header) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 80) {
                header.classList.add("header-shrink");
            } else {
                header.classList.remove("header-shrink");
            }
        });
    }

    /* ===============================
       ADVANCED SCROLL REVEAL
    =============================== */
    const fadeElements = document.querySelectorAll(".fade-in");

    if ("IntersectionObserver" in window && fadeElements.length > 0) {
        const observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("appear");
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

        fadeElements.forEach(function (el) {
            observer.observe(el);
        });
    } else {
        fadeElements.forEach(el => el.classList.add("appear"));
    }

    /* ===============================
       COUNTER ANIMATION
    =============================== */
    const counters = document.querySelectorAll(".counter");

    counters.forEach(function (counter) {
        const target = parseInt(counter.getAttribute("data-target"));
        if (isNaN(target)) return;

        let started = false;

        const runCounter = function () {
            if (started) return;
            started = true;

            let current = 0;
            const increment = target / 60;

            const update = function () {
                current += increment;

                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(update);
                } else {
                    counter.innerText = target + "+";
                }
            };

            update();
        };

        if ("IntersectionObserver" in window) {
            const counterObserver = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        runCounter();
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counterObserver.observe(counter);
        } else {
            runCounter();
        }
    });

    /* ===============================
       PREMIUM CARD HOVER GLOW
    =============================== */
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        card.addEventListener("mousemove", e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            card.style.setProperty("--mouse-x", `${x}px`);
            card.style.setProperty("--mouse-y", `${y}px`);
        });
    });

});