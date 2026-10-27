/**
 * PROLABIOS Mockup - Main JavaScript Entrypoint (Modular ES6)
 */

import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';
import { createIcons, icons } from './lucide-icons.js';

window.bootstrap = bootstrap;
window.Swal = Swal;
window.lucide = {
  createIcons: (options = {}) => createIcons({ icons, attrs: { 'stroke-width': 1.75 }, ...options }),
  icons
};

import { initNavigation } from './modules/navigation.js';
import { initSearchModal } from './modules/search-modal.js';
import { initHeroSlideshow } from './modules/hero-slideshow.js';
import { initAnimations, initScrollAnimations, initGSAPAnimations } from './modules/animations.js';
import { initCatalogCart } from './modules/catalog-cart.js';
import { initBacteriaSwarm } from './modules/bacteria-swarm.js';
import { initSubpages } from './modules/subpages.js';
import { initCatalogAjax } from './modules/catalog-ajax.js';

document.addEventListener('DOMContentLoaded', function () {
  function safeInit(name, fn) {
    if (typeof fn === 'function') {
      try {
        fn();
      } catch (e) {
        console.error('Error in ' + name + ':', e);
      }
    }
  }

  safeInit('initNavigation', initNavigation);
  safeInit('initSearchModal', initSearchModal);
  safeInit('initHeroSlideshow', initHeroSlideshow);
  safeInit('initAnimations', initAnimations);
  safeInit('initCatalogCart', initCatalogCart);
  safeInit('initSubpages', initSubpages);
  safeInit('initCatalogAjax', initCatalogAjax);
  safeInit('initProductWatermarks', function () {
    document.querySelectorAll('.detail-product-desc img, .detail-product-page .profil-body-text img').forEach(function (img) {
      if (!img.closest('.product-watermark-wrap')) {
        const wrap = document.createElement('span');
        wrap.className = 'product-watermark-wrap';
        img.parentNode.insertBefore(wrap, img);
        wrap.appendChild(img);
        const overlay = document.createElement('span');
        overlay.className = 'product-watermark-overlay';
        overlay.setAttribute('aria-hidden', 'true');
        wrap.appendChild(overlay);
      }
    });
  });
  safeInit('lucide', () => window.lucide.createIcons());

  // Bacteria swarm prototype: active on homepage
  if (document.querySelector('.nb-hero') || document.querySelector('.page-home') || document.querySelector('.home-hero') || document.querySelector('.hero-cinematic') || window.location.pathname === '/' || window.location.pathname === '') {
    safeInit('initBacteriaSwarm', initBacteriaSwarm);
  }
});

// Expose key global window helpers required by Blade views and dynamic components
window.initScrollAnimations = initScrollAnimations;
window.initGSAPAnimations = initGSAPAnimations;
