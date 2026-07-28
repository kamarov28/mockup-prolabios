/**
 * Helper Utility Functions
 */

export function prefersReducedMotion() {
  return document.documentElement.classList.contains('no-motion')
    || window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function debounce(fn, wait) {
  let timer = null;
  return function debounced() {
    const ctx = this;
    const args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      fn.apply(ctx, args);
    }, wait);
  };
}

export function onScrollThrottled(handler) {
  let ticking = false;
  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () {
      handler();
      ticking = false;
    });
  }, { passive: true });
}

export function isProductPath(pathname) {
  const path = pathname || window.location.pathname;
  return path === '/produk' || path.endsWith('/produk.php') || path.includes('/produk');
}

export function sanitizeCategorySlug(slug) {
  if (!slug) return '';
  return slug.replace(/[^a-zA-Z0-9\-_]/g, '');
}

export function getQueryParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

export function setTextContent(selector, text) {
  const element = document.querySelector(selector);
  if (element) {
    element.textContent = text;
  }
}
