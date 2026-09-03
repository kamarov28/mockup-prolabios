/**
 * GSAP ScrollTrigger & Reveal Animations Controller
 */
import { prefersReducedMotion } from './utils.js';
import { typoSplitWords, typoWrapLine, revealHeroStatic } from './typography-split.js';

let gsapSafetyTimer = null;

export function initAnimations() {
  initScrollAnimations();
  initGSAPAnimations();
}

export function initScrollAnimations() {
  const animateElements = document.querySelectorAll('.animate-on-scroll');
  if (!animateElements.length) return;

  if (prefersReducedMotion()) {
    animateElements.forEach(function (el) {
      el.classList.add('is-visible');
    });
    return;
  }

  const animationObserver = new IntersectionObserver(function (entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    root: null,
    rootMargin: '0px 0px -8% 0px',
    threshold: 0.08
  });

  animateElements.forEach(function (el) {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight * 0.92 && rect.bottom >= 0) {
      el.classList.add('is-visible');
    } else {
      animationObserver.observe(el);
    }
  });
}

export function initGSAPAnimations() {
  if (typeof window !== 'undefined' && 'history' in window && 'scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual';
  }

  if (prefersReducedMotion()) {
    revealHeroStatic();
    return;
  }

  if (typeof gsap === 'undefined') {
    if (!gsapSafetyTimer) {
      gsapSafetyTimer = setTimeout(function () {
        revealHeroStatic();
      }, 1800);
    }
    return;
  }

  if (gsapSafetyTimer) {
    clearTimeout(gsapSafetyTimer);
    gsapSafetyTimer = null;
  }

  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    ScrollTrigger.clearScrollMemory();
  }

  const EASE_EXPO = 'expo.out';
  const EASE_POWER = 'power3.out';

  // --------------------------------------------------------------------------
  // 1. Homepage Hero entrance animation
  // --------------------------------------------------------------------------
  const heroTextContainer = document.querySelector('.typo-hero-entrance');
  const activeHeroImg = document.querySelector('.hero-bg-slide.active');

  if (heroTextContainer && heroTextContainer.dataset.gsapDone !== '1') {
    const eyebrow = heroTextContainer.querySelector('.typo-eyebrow');
    const title = heroTextContainer.querySelector('.typo-hero-title');
    const lead = heroTextContainer.querySelector('.typo-lead');
    const ctas = heroTextContainer.querySelectorAll('.typo-btn-link');
    const ctaWrap = heroTextContainer.querySelector('.typo-hero-ctas');

    const titleWords = title ? typoSplitWords(title) : [];
    const leadWords = lead ? typoSplitWords(lead) : [];
    const eyebrowInner = eyebrow ? typoWrapLine(eyebrow) : null;

    gsap.set(heroTextContainer, { opacity: 1 });
    if (titleWords.length) gsap.set(titleWords, { yPercent: 115, rotate: 0.001 });
    if (leadWords.length) gsap.set(leadWords, { yPercent: 90, opacity: 0 });
    if (eyebrowInner) gsap.set(eyebrowInner, { yPercent: 110 });
    if (eyebrow) gsap.set(eyebrow, { letterSpacing: '0.55em', opacity: 0.35 });
    if (ctas.length) gsap.set(ctas, { y: 28, opacity: 0 });
    if (ctaWrap) gsap.set(ctaWrap, { opacity: 1 });

    const heroTl = gsap.timeline({
      defaults: { ease: EASE_EXPO },
      onComplete: function () {
        heroTextContainer.dataset.gsapDone = '1';
        gsap.set([titleWords, leadWords, eyebrowInner, ctas], { clearProps: 'willChange' });
      }
    });

    if (activeHeroImg) {
      gsap.set(activeHeroImg, { scale: 1.12 });
      heroTl.to(activeHeroImg, {
        scale: 1,
        duration: 2.2,
        ease: 'power2.out',
        clearProps: 'scale,transform'
      }, 0);
    }

    if (eyebrowInner) {
      heroTl.to(eyebrowInner, { yPercent: 0, duration: 0.95 }, 0.08);
    }
    if (eyebrow) {
      heroTl.to(eyebrow, {
        letterSpacing: '0.18em',
        opacity: 1,
        duration: 1.15,
        ease: EASE_POWER
      }, 0.08);
    }

    if (titleWords.length) {
      heroTl.to(titleWords, {
        yPercent: 0,
        duration: 1.2,
        stagger: 0.055,
        ease: EASE_EXPO
      }, 0.18);
    }

    if (leadWords.length) {
      heroTl.to(leadWords, {
        yPercent: 0,
        opacity: 1,
        duration: 0.85,
        stagger: 0.018,
        ease: EASE_POWER
      }, 0.48);
    }

    if (ctas.length) {
      heroTl.to(ctas, {
        y: 0,
        opacity: 1,
        duration: 0.75,
        stagger: 0.1,
        ease: EASE_EXPO
      }, 0.68);
    }
  }

  // --------------------------------------------------------------------------
  // 2. Editorial Subpage Header Entrance (Profil, Produk, Sektor, Layanan, etc.)
  // --------------------------------------------------------------------------
  const pageHeaders = document.querySelectorAll('.editorial-page-header');
  pageHeaders.forEach(function (pageHeader) {
    const label = pageHeader.querySelector('.editorial-page-label');
    const title = pageHeader.querySelector('.editorial-page-title');
    const subtitle = pageHeader.querySelector('.editorial-page-subtitle');
    const labelInner = label ? typoWrapLine(label) : null;
    const titleWords = title ? typoSplitWords(title) : [];

    gsap.set(pageHeader, { opacity: 1 });
    if (labelInner) gsap.set(labelInner, { yPercent: 110 });
    if (titleWords.length) gsap.set(titleWords, { yPercent: 110 });
    if (subtitle) gsap.set(subtitle, { y: 20, opacity: 0 });

    const headerTl = gsap.timeline({ defaults: { ease: EASE_EXPO } });
    if (labelInner) headerTl.to(labelInner, { yPercent: 0, duration: 0.8 }, 0.05);
    if (titleWords.length) {
      headerTl.to(titleWords, {
        yPercent: 0,
        duration: 1,
        stagger: 0.04,
        ease: EASE_EXPO
      }, 0.1);
    }
    if (subtitle) headerTl.to(subtitle, { y: 0, opacity: 1, duration: 0.7, ease: EASE_POWER }, 0.35);
  });

  // --------------------------------------------------------------------------
  // 3. Pin Section (Fokus Industri on Homepage)
  // --------------------------------------------------------------------------
  const pinSection = document.querySelector('.focus-section-pin');
  if (pinSection && typeof ScrollTrigger !== 'undefined') {
    const head = pinSection.querySelector('.typo-section-head');
    if (head) {
      const label = head.querySelector('.typo-section-label');
      const heading = head.querySelector('.typo-section-title');
      const sub = head.querySelector('.typo-section-sub');
      const labelInner = label ? typoWrapLine(label) : null;
      const headingWords = heading ? typoSplitWords(heading) : [];

      if (labelInner) gsap.set(labelInner, { yPercent: 110 });
      if (label) gsap.set(label, { letterSpacing: '0.45em', opacity: 0.4 });
      if (headingWords.length) gsap.set(headingWords, { yPercent: 110 });
      if (sub) gsap.set(sub, { y: 18, opacity: 0 });
      gsap.set(head, { opacity: 1 });

      const headTl = gsap.timeline({
        scrollTrigger: { trigger: head, start: 'top 85%', once: true }
      });
      if (labelInner) headTl.to(labelInner, { yPercent: 0, duration: 0.85, ease: EASE_EXPO }, 0);
      if (label) headTl.to(label, { letterSpacing: '0.18em', opacity: 1, duration: 1, ease: EASE_POWER }, 0);
      if (headingWords.length) {
        headTl.to(headingWords, { yPercent: 0, duration: 1.05, stagger: 0.05, ease: EASE_EXPO }, 0.08);
      }
      if (sub) headTl.to(sub, { y: 0, opacity: 1, duration: 0.7, ease: EASE_POWER }, 0.28);
    }

    pinSection.querySelectorAll('.typo-index-item').forEach(function (item, index) {
      const num = item.querySelector('.typo-index-number');
      const title = item.querySelector('.typo-index-title');
      const desc = item.querySelector('.typo-index-desc');
      const link = item.querySelector('.typo-index-link');
      const titleWords = title ? typoSplitWords(title) : [];

      gsap.set(item, { opacity: 1 });
      if (num) gsap.set(num, { x: -36, opacity: 0, letterSpacing: '0.2em' });
      if (titleWords.length) gsap.set(titleWords, { yPercent: 115 });
      if (desc) gsap.set(desc, { y: 22, opacity: 0 });
      if (link) gsap.set(link, { y: 12, opacity: 0 });

      const rowTl = gsap.timeline({
        scrollTrigger: { trigger: item, start: 'top 88%', once: true },
        delay: Math.min(index * 0.04, 0.16)
      });

      if (num) rowTl.to(num, { x: 0, opacity: 1, letterSpacing: '0.02em', duration: 0.9, ease: EASE_EXPO }, 0);
      if (titleWords.length) rowTl.to(titleWords, { yPercent: 0, duration: 1, stagger: 0.045, ease: EASE_EXPO }, 0.06);
      if (desc) rowTl.to(desc, { y: 0, opacity: 1, duration: 0.75, ease: EASE_POWER }, 0.28);
      if (link) rowTl.to(link, { y: 0, opacity: 1, duration: 0.55, ease: EASE_EXPO }, 0.38);
    });
  }

  // --------------------------------------------------------------------------
  // 4. Generic Section Heads Across All Pages
  // --------------------------------------------------------------------------
  if (typeof ScrollTrigger !== 'undefined') {
    document.querySelectorAll('.typo-section-head').forEach(function (head) {
      if (head.dataset.gsapAnimated) return;
      head.dataset.gsapAnimated = '1';

      gsap.fromTo(head,
        { y: 20, opacity: 0.2 },
        {
          y: 0,
          opacity: 1,
          duration: 0.6,
          ease: EASE_EXPO,
          scrollTrigger: { trigger: head, start: 'top 92%', once: true }
        }
      );
    });
  }

  // --------------------------------------------------------------------------
  // 5. Bento Cards & Grid Cards Animations (Homepage & Bento Grids)
  // --------------------------------------------------------------------------
  if (typeof ScrollTrigger !== 'undefined') {
    const bentoCards = document.querySelectorAll('.hitech-bento-card');
    if (bentoCards.length) {
      bentoCards.forEach(function (card, i) {
        const title = card.querySelector('.hitech-bento-title');
        const desc = card.querySelector('.hitech-bento-desc');
        const icon = card.querySelector('.hitech-bento-icon');
        const titleWords = title ? typoSplitWords(title) : [];

        if (titleWords.length) gsap.set(titleWords, { yPercent: 115 });
        if (desc) gsap.set(desc, { y: 14, opacity: 0 });
        if (icon) gsap.set(icon, { scale: 0.8, opacity: 0 });

        const bTl = gsap.timeline({
          scrollTrigger: { trigger: card, start: 'top 88%', once: true },
          delay: (i % 2) * 0.1
        });
        if (icon) bTl.to(icon, { scale: 1, opacity: 1, duration: 0.6, ease: EASE_EXPO }, 0);
        if (titleWords.length) bTl.to(titleWords, { yPercent: 0, duration: 0.95, stagger: 0.04, ease: EASE_EXPO }, 0.05);
        if (desc) bTl.to(desc, { y: 0, opacity: 1, duration: 0.65, ease: EASE_POWER }, 0.22);
      });
    }
  }

  // --------------------------------------------------------------------------
  // 6. Subpage Content Elements (Product Cards, Sector Cards, Blog Cards)
  // --------------------------------------------------------------------------
  if (typeof ScrollTrigger !== 'undefined') {

    // Catalog & Product Cards Stagger Reveal
    const productCards = document.querySelectorAll('.typo-product-card, .product-card, .catalog-item');
    if (productCards.length) {
      gsap.fromTo(productCards,
        { y: 40, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 0.85, stagger: 0.06, ease: EASE_EXPO,
          scrollTrigger: { trigger: productCards[0], start: 'top 90%', once: true }
        }
      );
    }

    // Sektor Cards
    const sektorCards = document.querySelectorAll('.sektor-card, .sektor-item, .sektor-detail-box');
    if (sektorCards.length) {
      gsap.fromTo(sektorCards,
        { y: 35, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 0.9, stagger: 0.08, ease: EASE_EXPO,
          scrollTrigger: { trigger: sektorCards[0], start: 'top 90%', once: true }
        }
      );
    }

    // Layanan Feature Cards & Boxes
    const layananBoxes = document.querySelectorAll('.layanan-feature-card, .layanan-box, .layanan-hero-box');
    if (layananBoxes.length) {
      gsap.fromTo(layananBoxes,
        { y: 30, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 0.85, stagger: 0.1, ease: EASE_POWER,
          scrollTrigger: { trigger: layananBoxes[0], start: 'top 88%', once: true }
        }
      );
    }

    // Blog & Information Cards
    const blogCards = document.querySelectorAll('.blog-card, .informasi-card, .news-card');
    if (blogCards.length) {
      gsap.fromTo(blogCards,
        { y: 35, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 0.9, stagger: 0.07, ease: EASE_EXPO,
          scrollTrigger: { trigger: blogCards[0], start: 'top 90%', once: true }
        }
      );
    }

    // Contact Form & Info Cards
    const contactForm = document.getElementById('contactForm');
    const contactInfoCards = document.querySelectorAll('.kontak-info-card, .kontak-sidebar-box');
    if (contactForm) {
      gsap.fromTo(contactForm,
        { y: 40, opacity: 0 },
        {
          y: 0, opacity: 1, duration: 1, ease: EASE_EXPO,
          scrollTrigger: { trigger: contactForm, start: 'top 85%', once: true }
        }
      );
    }
    if (contactInfoCards.length) {
      gsap.fromTo(contactInfoCards,
        { x: -30, opacity: 0 },
        {
          x: 0, opacity: 1, duration: 0.85, stagger: 0.1, ease: EASE_POWER,
          scrollTrigger: { trigger: contactInfoCards[0], start: 'top 88%', once: true }
        }
      );
    }
  }

  // --------------------------------------------------------------------------
  // 7. Refresh ScrollTrigger
  // --------------------------------------------------------------------------
  if (typeof ScrollTrigger !== 'undefined') {
    requestAnimationFrame(function () {
      ScrollTrigger.refresh();
    });
  }
}
