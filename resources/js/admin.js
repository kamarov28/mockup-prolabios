/**
 * PROLABIOS Mockup - Admin JavaScript Entrypoint
 */

import * as bootstrap from 'bootstrap';
import Swal from 'sweetalert2';
import { createIcons, icons } from './lucide-icons.js';

// Expose bootstrap and Swal globally for admin scripts
window.bootstrap = bootstrap;
window.Swal = Swal;

// Export lucide to window so any dynamic admin script can call createIcons()
window.lucide = {
  createIcons: (options = {}) => createIcons({ icons, attrs: { 'stroke-width': 1.75 }, ...options }),
  icons
};

document.addEventListener('DOMContentLoaded', () => {
  window.lucide.createIcons();

  // Keyboard shortcut '/' to focus admin search input
  document.addEventListener('keydown', (e) => {
    const activeEl = document.activeElement;
    const isTyping = activeEl && (['INPUT', 'TEXTAREA', 'SELECT'].includes(activeEl.tagName) || activeEl.isContentEditable);

    if (e.key === '/' && !isTyping && !e.ctrlKey && !e.metaKey) {
      const searchInput = document.querySelector('.admin-search-input');
      if (searchInput) {
        e.preventDefault();
        searchInput.focus();
        if (typeof searchInput.select === 'function') searchInput.select();
      }
    }
  });
});
