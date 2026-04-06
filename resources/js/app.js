import "./bootstrap";

/* =============================================
   MARK-UP — Main JavaScript
   ============================================= */

document.addEventListener("DOMContentLoaded", () => {
    /* ===========================
     1. NAVBAR — Scroll Effect
     =========================== */
    const mainNav = document.getElementById("mainNav");

    function handleNavScroll() {
        if (window.scrollY > 60) {
            mainNav.classList.add("scrolled");
        } else {
            mainNav.classList.remove("scrolled");
        }
    }
    window.addEventListener("scroll", handleNavScroll, { passive: true });
    handleNavScroll();

    /* ===========================
     2. HERO PARTICLES
     =========================== */
    const particlesContainer = document.getElementById("particles");

    function createParticle() {
        if (!particlesContainer) return;
        const p = document.createElement("div");
        p.classList.add("particle");

        const size = Math.random() * 4 + 2;
        p.style.width = size + "px";
        p.style.height = size + "px";
        p.style.left = Math.random() * 100 + "vw";
        p.style.animationDuration = Math.random() * 10 + 8 + "s";
        p.style.animationDelay = Math.random() * 5 + "s";
        p.style.opacity = Math.random() * 0.5 + 0.1;

        particlesContainer.appendChild(p);

        // Remove after animation completes to avoid DOM bloat
        p.addEventListener("animationend", () => p.remove());
    }

    // Spawn particles periodically
    const particleInterval = setInterval(createParticle, 600);
    // Create initial burst
    for (let i = 0; i < 12; i++) createParticle();

    /* ===========================
     3. SCROLL REVEAL
     =========================== */
    const revealEls = document.querySelectorAll(".reveal");

    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    // Stagger siblings in the same row
                    const siblings =
                        entry.target.parentElement.querySelectorAll(".reveal");
                    let delay = 0;
                    siblings.forEach((sib, idx) => {
                        if (sib === entry.target) delay = idx * 80;
                    });

                    setTimeout(() => {
                        entry.target.classList.add("visible");
                    }, delay);

                    revealObserver.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12,
            rootMargin: "0px 0px -40px 0px",
        },
    );

    revealEls.forEach((el) => revealObserver.observe(el));

    /* ===========================
     4. COUNTER ANIMATION
     =========================== */
    const counters = document.querySelectorAll(".count");

    function animateCounter(el) {
        const target = parseInt(el.dataset.target, 10);
        const duration = 1800; // ms
        const start = performance.now();

        function step(timestamp) {
            const elapsed = timestamp - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out quad
            const eased = 1 - (1 - progress) * (1 - progress);
            el.textContent = Math.round(eased * target);
            if (progress < 1) requestAnimationFrame(step);
        }

        requestAnimationFrame(step);
    }

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 },
    );

    counters.forEach((c) => counterObserver.observe(c));

    /* ===========================
     5. BACK TO TOP BUTTON
     =========================== */
    const backToTop = document.getElementById("backToTop");

    window.addEventListener(
        "scroll",
        () => {
            if (window.scrollY > 400) {
                backToTop.classList.add("visible");
            } else {
                backToTop.classList.remove("visible");
            }
        },
        { passive: true },
    );

    backToTop.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    /* ===========================
     6. SMOOTH ACTIVE NAV LINK
     =========================== */
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

    const sectionObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    navLinks.forEach((link) => link.classList.remove("active"));
                    const active = document.querySelector(
                        `.navbar-nav a[href="#${entry.target.id}"]`,
                    );
                    if (active) active.classList.add("active");
                }
            });
        },
        { threshold: 0.4 },
    );

    sections.forEach((s) => sectionObserver.observe(s));

    /* ===========================
     7. PROGRAM CARD TILT EFFECT
     =========================== */
    const programCards = document.querySelectorAll(".program-card");

    programCards.forEach((card) => {
        card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const cx = rect.width / 2;
            const cy = rect.height / 2;
            const rotX = ((y - cy) / cy) * -6;
            const rotY = ((x - cx) / cx) * 6;

            card.style.transform = `perspective(800px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-8px)`;
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "";
        });
    });

    /* ===========================
     8. FEATURE CARD HOVER GLOW
     =========================== */
    const featureCards = document.querySelectorAll(".feature-card");

    featureCards.forEach((card) => {
        card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty("--mouse-x", x + "px");
            card.style.setProperty("--mouse-y", y + "px");
        });
    });

    /* ===========================
     9. TESTIMONIAL CARDS — AUTO CAROUSEL (Mobile)
     =========================== */
    function initTestiCarousel() {
        if (window.innerWidth > 767) return;

        const track = document.querySelector(".testimonials-section .row");
        const cards = document.querySelectorAll(".testi-card");
        if (!track || cards.length === 0) return;

        let current = 0;
        let autoPlay;

        cards.forEach((c, i) => {
            c.style.transition = "opacity 0.4s ease";
            if (i !== 0) c.style.opacity = "0.35";
        });

        function goTo(idx) {
            cards.forEach((c, i) => {
                c.style.opacity = i === idx ? "1" : "0.35";
                c.style.transform = i === idx ? "scale(1.02)" : "scale(0.98)";
            });
            current = idx;
        }

        autoPlay = setInterval(() => {
            goTo((current + 1) % cards.length);
        }, 3500);

        track.addEventListener("touchstart", () => clearInterval(autoPlay));
    }

    initTestiCarousel();

    /* ===========================
     10. LOGO MARQUEE — Pause on hover (already in CSS, reinforce via JS)
     =========================== */
    const logoTrack = document.querySelector(".logo-track");
    if (logoTrack) {
        logoTrack.addEventListener("mouseenter", () => {
            logoTrack.style.animationPlayState = "paused";
        });
        logoTrack.addEventListener("mouseleave", () => {
            logoTrack.style.animationPlayState = "running";
        });
    }

    /* ===========================
     11. NAVBAR MOBILE — Close on link click
     =========================== */
    const navbarCollapse = document.getElementById("navbarNav");
    const allNavLinks = document.querySelectorAll(
        "#navbarNav .nav-link:not(.dropdown-toggle)",
    );

    allNavLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (navbarCollapse.classList.contains("show")) {
                const bsCollapse =
                    bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) bsCollapse.hide();
            }
        });
    });

    /* ===========================
     12. STEP CARDS — Stagger on scroll
     =========================== */
    const stepCards = document.querySelectorAll(".step-card");

    const stepObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, idx) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = "1";
                        entry.target.style.transform = "translateY(0)";
                    }, idx * 120);
                    stepObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.2 },
    );

    stepCards.forEach((card) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        stepObserver.observe(card);
    });

    /* ===========================
     13. STATS CARD — Color pulse when visible
     =========================== */
    const statCards = document.querySelectorAll(".stat-card");

    const statObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add("pulse-once");
                    }, i * 150);
                    statObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 },
    );

    statCards.forEach((c) => statObserver.observe(c));

    /* ===========================
     14. CTA BUTTON — Ripple Effect
     =========================== */
    document
        .querySelectorAll(".btn-hero-primary, .btn-program, .btn-primary-nav")
        .forEach((btn) => {
            btn.addEventListener("click", function (e) {
                const ripple = document.createElement("span");
                ripple.style.cssText = `
        position:absolute;
        border-radius:50%;
        transform:scale(0);
        animation:ripple 0.6s linear;
        background:rgba(255,255,255,0.3);
        width:120px;height:120px;
        margin-top:-60px;margin-left:-60px;
        pointer-events:none;
      `;
                const rect = btn.getBoundingClientRect();
                ripple.style.left = e.clientX - rect.left + "px";
                ripple.style.top = e.clientY - rect.top + "px";

                if (getComputedStyle(btn).position === "static") {
                    btn.style.position = "relative";
                }
                btn.style.overflow = "hidden";
                btn.appendChild(ripple);

                ripple.addEventListener("animationend", () => ripple.remove());
            });
        });

    // Ripple keyframes
    const rippleStyle = document.createElement("style");
    rippleStyle.textContent = `
    @keyframes ripple {
      to { transform: scale(4); opacity: 0; }
    }
    .pulse-once {
      animation: statPulse 0.5s ease;
    }
    @keyframes statPulse {
      0%   { box-shadow: 0 0 0 0 rgba(249,201,34,0.4); }
      70%  { box-shadow: 0 0 0 12px rgba(249,201,34,0); }
      100% { box-shadow: 0 0 0 0 rgba(249,201,34,0); }
    }
  `;
    document.head.appendChild(rippleStyle);

    /* ===========================
     15. STOP PARTICLE SPAWN when tab hidden
     =========================== */
    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            clearInterval(particleInterval);
        }
    });

    // Done!
    console.log("🚀 MARK-UP scripts loaded successfully");
});
