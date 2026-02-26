document.addEventListener("DOMContentLoaded", function () {

    // 1. Rotating Hero Text (safe check)
    const rotating = document.getElementById("rotating-text");
    if (rotating) {
        const texts = [
            "Advanced IVF & ICSI Treatment in Lahore",
            "Personalized Fertility Care Across Pakistan",
            "Male & Female Infertility Expertise",
            "Teleconsultations for Overseas Pakistanis"
        ];
        let index = 0;

        setInterval(() => {
            rotating.style.opacity = "0";
            setTimeout(() => {
                index = (index + 1) % texts.length;
                rotating.textContent = texts[index];
                rotating.style.opacity = "1";
            }, 500); // increased fade time for smoothness
        }, 4500);
    }

    // 2. Sticky Header Shadow (safe)
    const header = document.querySelector("header");
    if (header) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 80) {
                header.classList.add("header-shrink");
            } else {
                header.classList.remove("header-shrink");
            }
        });
    }

    // 3. Fade-in on Scroll (safe + fallback)
    const fadeElements = document.querySelectorAll(".fade-in");
    if (fadeElements.length > 0) {
        if ("IntersectionObserver" in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("appear");
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            fadeElements.forEach(el => observer.observe(el));
        } else {
            // Fallback for old browsers
            fadeElements.forEach(el => el.classList.add("appear"));
        }
    }

    // 4. Counter Animation (very safe)
    document.querySelectorAll(".counter").forEach(counter => {
        const target = parseInt(counter.getAttribute("data-target"), 10);
        if (isNaN(target)) return;

        let started = false;

        const runCounter = () => {
            if (started) return;
            started = true;

            let current = 0;
            const increment = target / 60; // smoother
            const update = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(update);
                } else {
                    counter.textContent = target + (counter.getAttribute("data-suffix") || "+");
                }
            };
            update();
        };

        if ("IntersectionObserver" in window) {
            new IntersectionObserver((entries, obs) => {
                if (entries[0].isIntersecting) {
                    runCounter();
                    obs.disconnect();
                }
            }, { threshold: 0.5 }).observe(counter);
        } else {
            runCounter();
        }
    });

});