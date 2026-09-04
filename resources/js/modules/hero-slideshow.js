/**
 * Hero Slideshow — supports legacy .hero-bg-slide and Soft Neo .nb-hero-slide
 */
import { prefersReducedMotion } from './utils.js';

export function initHeroSlideshow() {
  initHeroBgSlideshow();
  initPrincipalSlider();
  initMarqueeVisibility();
}

export function initHeroBgSlideshow() {
  const slides = document.querySelectorAll('.nb-hero-slide, .hero-bg-slide');
  if (slides.length <= 1) return;

  const prevBtn = document.getElementById('hero-prev');
  const nextBtn = document.getElementById('hero-next');
  const heroSection = document.querySelector('.nb-hero, .typo-hero');
  const slideNumEl = document.getElementById('hero-slide-current');
  const slideTotalEl = document.getElementById('hero-slide-total');
  const progressFill = document.getElementById('hero-progress-fill');

  const SLIDE_DURATION = 5;
  const ACTIVE = slides[0].classList.contains('is-active') || slides[0].classList.contains('nb-hero-slide')
    ? 'is-active'
    : 'active';

  if (slideTotalEl) {
    slideTotalEl.textContent = slides.length < 10 ? '0' + slides.length : String(slides.length);
  }

  function updateCounter(idx) {
    if (slideNumEl) {
      slideNumEl.textContent = idx + 1 < 10 ? '0' + (idx + 1) : String(idx + 1);
    }
  }

  function startProgressAnim() {
    if (!progressFill) return;
    progressFill.classList.remove('running');
    progressFill.style.transform = 'scaleX(0)';
    void progressFill.offsetWidth;
    progressFill.style.transform = '';
    progressFill.classList.add('running');
  }

  const prefersReduced = prefersReducedMotion();
  const motionOff = document.documentElement.classList.contains('no-motion');

  let current = 0;
  let autoTimer = null;
  let isPaused = false;

  function getGsap() {
    return !prefersReduced && !motionOff && typeof window !== 'undefined' && window.gsap
      ? window.gsap
      : null;
  }

  function goTo(next) {
    if (next === current) return;
    const outSlide = slides[current];
    const inSlide = slides[next];
    const g = getGsap();

    let dir = next > current ? 1 : -1;
    if (current === slides.length - 1 && next === 0) dir = 1;
    if (current === 0 && next === slides.length - 1) dir = -1;

    if (g) {
      g.killTweensOf([outSlide, inSlide]);

      g.set(inSlide, { opacity: 1, xPercent: dir * 100, zIndex: 3 });
      g.set(outSlide, { zIndex: 2 });

      // Hard mechanical push (Neo-Brutalist snappy horizontal slide)
      g.to(outSlide, {
        xPercent: -dir * 30,
        opacity: 0.4,
        duration: 0.55,
        ease: 'power3.inOut',
        onComplete: function () {
          g.set(outSlide, { zIndex: 1, opacity: 0, xPercent: 0 });
        },
      });

      g.to(inSlide, {
        xPercent: 0,
        opacity: 1,
        duration: 0.55,
        ease: 'power3.inOut',
      });
    } else {
      // Native CSS fallback: Mechanical sliding classes
      outSlide.style.transition = 'transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.55s ease';
      inSlide.style.transition = 'transform 0.55s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.55s ease';

      inSlide.style.zIndex = '3';
      outSlide.style.zIndex = '2';

      inSlide.style.transform = `translateX(${dir * 100}%)`;
      inSlide.style.opacity = '1';
      void inSlide.offsetWidth; // force reflow

      inSlide.style.transform = 'translateX(0%)';
      outSlide.style.transform = `translateX(${-dir * 30}%)`;
      outSlide.style.opacity = '0';

      setTimeout(() => {
        outSlide.style.zIndex = '1';
        outSlide.style.transform = '';
        outSlide.style.transition = '';
        inSlide.style.transition = '';
      }, 550);
    }

    slides[current].classList.remove('active', 'is-active');
    slides[next].classList.add(ACTIVE);

    current = next;
    updateCounter(current);
    startProgressAnim();
    resetAutoTimer();
  }

  function advance() {
    if (!isPaused) {
      goTo((current + 1) % slides.length);
    }
  }

  function resetAutoTimer() {
    if (autoTimer) clearInterval(autoTimer);
    autoTimer = setInterval(advance, SLIDE_DURATION * 1000);
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function (e) {
      e.preventDefault();
      goTo((current - 1 + slides.length) % slides.length);
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function (e) {
      e.preventDefault();
      goTo((current + 1) % slides.length);
    });
  }

  updateCounter(0);
  startProgressAnim();
  resetAutoTimer();
}

export function initHeroKineticGrid() {
  const canvas = document.getElementById('hero-kinetic-canvas');
  if (!canvas || prefersReducedMotion()) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  let width = 0;
  let height = 0;
  const mouse = { x: -1000, y: -1000, active: false };
  const particles = [];
  const spacing = 45;
  let cols = 0;
  let rows = 0;

  function resize() {
    const parent = canvas.parentElement;
    width = canvas.width = parent ? parent.offsetWidth : window.innerWidth;
    height = canvas.height = parent ? parent.offsetHeight : window.innerHeight;
    cols = Math.ceil(width / spacing) + 1;
    rows = Math.ceil(height / spacing) + 1;

    particles.length = 0;
    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        particles.push({
          baseX: c * spacing,
          baseY: r * spacing,
          x: c * spacing,
          y: r * spacing,
          vx: 0,
          vy: 0,
        });
      }
    }
  }

  function onMouseMove(e) {
    const rect = canvas.getBoundingClientRect();
    mouse.x = e.clientX - rect.left;
    mouse.y = e.clientY - rect.top;
    mouse.active = true;
  }

  function onMouseLeave() {
    mouse.active = false;
    mouse.x = -1000;
    mouse.y = -1000;
  }

  const heroContainer = document.querySelector('.nb-hero, .typo-hero') || window;
  window.addEventListener('resize', resize);
  if (heroContainer.addEventListener) {
    heroContainer.addEventListener('mousemove', onMouseMove);
    heroContainer.addEventListener('mouseleave', onMouseLeave);
  }
  resize();

  function render() {
    ctx.clearRect(0, 0, width, height);
    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];
      const dx = mouse.x - p.x;
      const dy = mouse.y - p.y;
      const dist = Math.sqrt(dx * dx + dy * dy);

      if (dist < 120 && mouse.active) {
        const angle = Math.atan2(dy, dx);
        const force = (120 - dist) / 120;
        p.vx -= Math.cos(angle) * force * 1.5;
        p.vy -= Math.sin(angle) * force * 1.5;
      }

      p.vx += (p.baseX - p.x) * 0.05;
      p.vy += (p.baseY - p.y) * 0.05;
      p.vx *= 0.85;
      p.vy *= 0.85;
      p.x += p.vx;
      p.y += p.vy;

      ctx.fillStyle = 'rgba(166, 23, 28, 0.35)';
      ctx.beginPath();
      ctx.arc(p.x, p.y, 1.5, 0, Math.PI * 2);
      ctx.fill();
    }
    requestAnimationFrame(render);
  }

  render();
}

export function initPrincipalSlider() {}
export function initMarqueeVisibility() {}
