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
});

// Expose key global window helpers required by Blade views
window.initGSAPAnimations = initGSAPAnimations;
window.revealHeroStatic = revealHeroStatic;
