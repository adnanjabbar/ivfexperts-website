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

            setTimeout(function () {
                index = (index + 1) % texts.length;
                rotating.innerText = texts[index];
                rotating.style.opacity = 1;
            }, 400);

        }, 4000);
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
       FADE-IN ON SCROLL
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
        }, { threshold: 0.15 });

        fadeElements.forEach(function (el) {
            observer.observe(el);
        });

    } else {
        fadeElements.forEach(function (el) {
            el.classList.add("appear");
        });
    }

    /* ===============================
       COUNTER ANIMATION (SAFE)
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
            const increment = target / 40;

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
            }, { threshold: 0.6 });

            counterObserver.observe(counter);
        } else {
            runCounter();
        }

    });

});