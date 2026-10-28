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
});
