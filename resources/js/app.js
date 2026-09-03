/**
 * PROLABIOS Mockup - Main JavaScript Entrypoint (Modular ES6)
 */

import { initNavigation } from './modules/navigation.js';
import { initSearchModal } from './modules/search-modal.js';
import { initHeroSlideshow } from './modules/hero-slideshow.js';
import { initAnimations } from './modules/animations.js';
import { initCatalogCart } from './modules/catalog-cart.js';
import { revealHeroStatic } from './modules/typography-split.js';
import { initGSAPAnimations } from './modules/animations.js';
import { initBacteriaSwarm } from './modules/bacteria-swarm.js';

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

  // Bacteria swarm prototype: active on homepage
  if (document.querySelector('.home-hero') || document.querySelector('.hero-cinematic') || window.location.pathname === '/' || window.location.pathname === '') {
    safeInit('initBacteriaSwarm', initBacteriaSwarm);
  }
});

// Expose key global window helpers required by Blade views
window.initGSAPAnimations = initGSAPAnimations;
window.revealHeroStatic = revealHeroStatic;
