document.addEventListener("DOMContentLoaded", () => {
    // Rotating Hero Text
    const rotating = document.getElementById("rotating-text");
    if (rotating) {
        const texts = [
            "Advanced IVF & ICSI Treatment in Lahore",
            "Personalized Fertility Care Across Pakistan",
            "Male & Female Infertility Expertise",
            "Teleconsultations for Overseas Pakistanis"
        ];
        let index = 0;

        const rotate = () => {
            rotating.style.opacity = "0";
            setTimeout(() => {
                index = (index + 1) % texts.length;
                rotating.textContent = texts[index];
                rotating.style.opacity = "1";
            }, 600);
        };

        setInterval(rotate, 5000);
        rotate(); // initial call
    }

    // Sticky Header Shadow
    const header = document.querySelector("header");
    if (header) {
        window.addEventListener("scroll", () => {
            header.classList.toggle("header-shrink", window.scrollY > 80);
        });
    }

    // Fade-in Observer (with fallback)
    const fadeElements = document.querySelectorAll(".fade-in");
    if (fadeElements.length > 0) {
        if ("IntersectionObserver" in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("appear");
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            fadeElements.forEach(el => observer.observe(el));
        } else {
            fadeElements.forEach(el => el.classList.add("appear"));
        }
    }

    // Counter Animation (very safe)
    document.querySelectorAll(".counter").forEach(counter => {
        const target = parseInt(counter.dataset.target, 10);
        if (isNaN(target)) return;

        let started = false;
        const run = () => {
            if (started) return;
            started = true;

            let current = 0;
            const step = target / 60;
            const tick = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(tick);
                } else {
                    counter.textContent = target + (counter.dataset.suffix || "+");
                }
            };
            tick();
        };

        if ("IntersectionObserver" in window) {
            new IntersectionObserver(([entry]) => {
                if (entry.isIntersecting) {
                    run();
                }
            }, { threshold: 0.5 }).observe(counter);
        } else {
            run();
        }
    });
});