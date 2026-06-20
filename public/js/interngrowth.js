/* InternGrowth — micro-animations & interactions */
(function () {
    'use strict';

    // ─── Scroll Reveal ───────────────────────────────────────────────
    const reveals = document.querySelectorAll('.ig-reveal');
    if (reveals.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e, idx) => {
                if (e.isIntersecting) {
                    const delay = e.target.dataset.revealDelay || (idx * 60);
                    e.target.style.transitionDelay = delay + 'ms';
                    e.target.classList.add('is-in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(el => io.observe(el));
    }

    // ─── Number Counter ──────────────────────────────────────────────
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length && 'IntersectionObserver' in window) {
        const co = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    animateCount(e.target);
                    co.unobserve(e.target);
                }
            });
        }, { threshold: 0.4 });
        counters.forEach(el => co.observe(el));
    }
    function animateCount(el) {
        const end = parseFloat(el.dataset.counter) || 0;
        const dur = parseInt(el.dataset.counterDur || 1200, 10);
        const suffix = el.dataset.counterSuffix || '';
        const prefix = el.dataset.counterPrefix || '';
        const decimals = parseInt(el.dataset.counterDecimals || 0, 10);
        const start = performance.now();
        function tick(now) {
            const p = Math.min(1, (now - start) / dur);
            const eased = 1 - Math.pow(1 - p, 3);
            const val = end * eased;
            el.textContent = prefix + val.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + suffix;
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    // ─── Magnetic buttons ────────────────────────────────────────────
    document.querySelectorAll('.ig-magnetic').forEach(btn => {
        const strength = 14;
        btn.addEventListener('mousemove', (e) => {
            const r = btn.getBoundingClientRect();
            const x = e.clientX - r.left - r.width / 2;
            const y = e.clientY - r.top  - r.height / 2;
            btn.style.transform = `translate(${x / r.width * strength}px, ${y / r.height * strength}px)`;
        });
        btn.addEventListener('mouseleave', () => { btn.style.transform = 'translate(0,0)'; });
    });

    // ─── Hero orb mouse follow (subtle) ──────────────────────────────
    const orbs = document.querySelectorAll('.ig-orb-follow');
    if (orbs.length) {
        document.addEventListener('mousemove', (e) => {
            const px = (e.clientX / window.innerWidth - 0.5);
            const py = (e.clientY / window.innerHeight - 0.5);
            orbs.forEach((o, i) => {
                const f = (i + 1) * 14;
                o.style.transform = `translate(${px * f}px, ${py * f}px)`;
            });
        }, { passive: true });
    }

    // ─── Typewriter ──────────────────────────────────────────────────
    document.querySelectorAll('[data-typewriter]').forEach(el => {
        const words = (el.dataset.typewriter || '').split('|');
        if (!words.length) return;
        let wi = 0, ci = 0, deleting = false;
        const speed = 90, pause = 1400;
        function tick() {
            const w = words[wi];
            if (!deleting) {
                el.textContent = w.slice(0, ++ci);
                if (ci === w.length) { deleting = true; setTimeout(tick, pause); return; }
            } else {
                el.textContent = w.slice(0, --ci);
                if (ci === 0) { deleting = false; wi = (wi + 1) % words.length; }
            }
            setTimeout(tick, deleting ? speed / 2 : speed);
        }
        tick();
    });

    // ─── Tilt card (gentle 3D on cursor) ─────────────────────────────
    document.querySelectorAll('[data-tilt]').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            const rx = (py - 0.5) * -4;
            const ry = (px - 0.5) * 4;
            card.style.transform = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg) translateZ(0)`;
        });
        card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });

    // ─── Smooth anchor scroll offset ─────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id.length > 1) {
                const t = document.querySelector(id);
                if (t) { e.preventDefault(); window.scrollTo({ top: t.offsetTop - 90, behavior: 'smooth' }); }
            }
        });
    });

})();