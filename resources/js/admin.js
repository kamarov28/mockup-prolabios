/**
 * PROLABIOS Mockup - Admin JavaScript Entrypoint
 */

import { createIcons, icons } from "./lucide-icons.js";

// Export lucide to window so any dynamic admin script can call createIcons()
window.lucide = {
  createIcons: (options = {}) => createIcons({ icons, attrs: { "stroke-width": 1.75 }, ...options }),
  icons
};

document.addEventListener("DOMContentLoaded", () => {
  window.lucide.createIcons();
});
