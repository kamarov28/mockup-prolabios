/**
 * Native High-Performance Scroll & Reveal Animations Controller
 * Replaces heavy external GSAP & ScrollTrigger with native CSS keyframes & IntersectionObserver
 */
import { prefersReducedMotion } from './utils.js';

export function initAnimations() {
  initScrollAnimations();
}

export function revealHeroStatic() {
  const heroTextContainer = document.querySelector('.nb-hero-immersive-content, .typo-hero-entrance');
  if (heroTextContainer) {
    heroTextContainer.style.opacity = '1';
    heroTextContainer.dataset.gsapDone = '1';
  }
}

export function initScrollAnimations() {
  const animateElements = document.querySelectorAll(
    '.animate-on-scroll, .hitech-bento-card, .b2b-usecase-card, .layanan-feature-card, .profil-mission-card, .profil-value-card'
  );
  if (!animateElements.length) return;

  if (prefersReducedMotion()) {
    animateElements.forEach(function (el) {
      el.classList.add('is-visible');
    });
    return;
  }

  // Auto-apply animate-on-scroll class and subtle stagger delay to cards if not present
  const containers = document.querySelectorAll('.row, .hitech-bento-grid, .b2b-usecase-grid');
  containers.forEach(function (container) {
    const cards = container.querySelectorAll(
      '.hitech-bento-card, .b2b-usecase-card, .layanan-feature-card, .profil-mission-card, .profil-value-card'
    );
    cards.forEach(function (card, index) {
      if (!card.classList.contains('animate-on-scroll')) {
        card.classList.add('animate-on-scroll');
        card.style.transitionDelay = Math.min((index % 4) * 80, 240) + 'ms';
      }
    });
  });

  const animationObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    root: null,
    rootMargin: '0px 0px -6% 0px',
    threshold: 0.08
  });

  const windowHeight = window.innerHeight;
  animateElements.forEach(function (el) {
    const rect = el.getBoundingClientRect();
    if (rect.top < windowHeight * 0.94 && rect.bottom >= 0) {
      el.classList.add('is-visible');
    } else {
      animationObserver.observe(el);
    }
  });
}

// Backward-compatible export for any legacy Blade references
export function initGSAPAnimations() {
  // Pure CSS keyframes & IntersectionObserver handles motion without external scripts
}
