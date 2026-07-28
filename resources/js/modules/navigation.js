/**
 * Navigation, Header Scroll, Hamburger & Scroll-to-top Controls
 */
import { onScrollThrottled } from './utils.js';

export function initNavigation() {
  initHeaderScrollEffect();
  initMobileMenu();
  initScrollToTop();
  initAnchorSmoothScroll();
  initMotionToggleSync();
  initViewTransitions();
}

export function initHeaderScrollEffect() {
  const elements = document.querySelectorAll('.header, .navbar');
  if (!elements.length) return;

  onScrollThrottled(function () {
    const isScrolled = window.scrollY > 50;
    elements.forEach(function (el) {
      el.classList.toggle('header-scrolled', isScrolled);
    });
  });
}

export function initMobileMenu() {
  const menuToggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('header nav');
  if (!menuToggle || !nav) return;

  menuToggle.addEventListener('click', function () {
    nav.classList.toggle('nav-open');
    this.classList.toggle('menu-active');
  });
}

export function initScrollToTop() {
  const scrollBtn = document.getElementById('scroll-to-top');
  if (!scrollBtn) return;

  onScrollThrottled(function () {
    if (window.scrollY > 400) {
      scrollBtn.style.opacity = '1';
      scrollBtn.style.visibility = 'visible';
    } else {
      scrollBtn.style.opacity = '0';
      scrollBtn.style.visibility = 'hidden';
    }
  });

  scrollBtn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

export function initAnchorSmoothScroll() {
  document.querySelectorAll('a[href*="#"]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      const href = link.getAttribute('href');
      if (!href || !href.startsWith('#') || href.length <= 1) return;

      const target = document.querySelector(href);
      if (target) {
        event.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}

export function initMotionToggleSync() {
  const motionToggle = document.getElementById('motion-toggle');
  if (!motionToggle) return;

  motionToggle.addEventListener('click', function () {
    document.documentElement.classList.toggle('no-motion');
    const isNoMotion = document.documentElement.classList.contains('no-motion');
    localStorage.setItem('prolabios_motion', isNoMotion ? 'off' : 'on');

    document.dispatchEvent(new CustomEvent('prolabios:motion-change', {
      detail: { motion: isNoMotion ? 'off' : 'on' }
    }));
  });
}

export function initViewTransitions() {
  if (!('startViewTransition' in document)) return;
  document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])').forEach(function (link) {
    link.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (!href || href.startsWith('mailto:') || href.startsWith('tel:')) return;
    });
  });
}
