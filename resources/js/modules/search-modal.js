/**
 * Global Command Palette Search Modal Controller
 */
import { isProductPath, debounce } from './utils.js';

let cachedProductCards = null;
function getProductCards() {
  if (!cachedProductCards) {
    cachedProductCards = Array.from(document.querySelectorAll('.product-card'));
  }
  return cachedProductCards;
}

export function initSearchModal() {
  initSearch();
  initSearchOverlay();
}

export function initSearch() {
  const searchForm = document.querySelector('.search-form');
  if (!searchForm) return;

  const searchInput = searchForm.querySelector('input');
  const searchBtn = searchForm.querySelector('button');
  if (!searchInput || !searchBtn) return;

  const onProductPage = isProductPath();

  function filterProducts(query) {
    const q = query.toLowerCase();
    const cards = getProductCards();
    let hasResults = false;

    cards.forEach(function (card) {
      const text = card.textContent.toLowerCase();
      if (!q || text.includes(q)) {
        card.classList.remove('hidden-by-filter');
        card.style.display = '';
        hasResults = true;
      } else {
        card.classList.add('hidden-by-filter');
        card.style.display = 'none';
      }
    });

    const header = document.querySelector('.main-content h2');
    const subheader = document.querySelector('.main-content .text-muted');
    if (header) header.textContent = query ? 'Hasil Pencarian' : 'Semua Produk';
    if (subheader) {
      subheader.textContent = query
        ? (hasResults ? 'Menampilkan hasil untuk "' + query + '"' : 'Tidak ada hasil untuk "' + query + '"')
        : 'Menampilkan semua produk kami';
    }
  }

  function doSearch() {
    const query = searchInput.value.trim();
    if (onProductPage) {
      const newUrl = query ? '/produk?q=' + encodeURIComponent(query) : '/produk';
      window.history.pushState({ path: newUrl }, '', newUrl);
      filterProducts(query);
    } else if (query.length > 0) {
      window.location.href = '/produk?q=' + encodeURIComponent(query);
    }
  }

  if (onProductPage) {
    const initialQuery = new URLSearchParams(window.location.search).get('q');
    if (initialQuery) {
      searchInput.value = initialQuery;
      filterProducts(initialQuery);
    }

    searchInput.addEventListener('input', debounce(function () {
      filterProducts(this.value.trim());
    }, 180));
  }

  searchBtn.addEventListener('click', function (e) {
    e.preventDefault();
    doSearch();
  });
  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      doSearch();
    }
  });
}

export function initSearchOverlay() {
  const searchButtons = document.querySelectorAll('#nav-search-open, #mobile-search-open');
  const overlay = document.getElementById('search-overlay');
  const closeBtn = document.getElementById('search-close');
  const input = document.getElementById('search-overlay-input');

  if (!searchButtons.length || !overlay || !closeBtn || !input) return;

  function openOverlay(e) {
    if (e) e.preventDefault();
    overlay.classList.add('active');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(function () {
      input.focus();
    });
  }

  function closeOverlay() {
    overlay.classList.remove('active');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    input.value = '';
  }

  searchButtons.forEach(function (btn) {
    btn.addEventListener('click', openOverlay);
  });
  closeBtn.addEventListener('click', closeOverlay);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('active')) {
      closeOverlay();
    }
  });

  const backdrop = document.getElementById('search-close-backdrop');
  if (backdrop) {
    backdrop.addEventListener('click', closeOverlay);
  }

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay || e.target === backdrop) closeOverlay();
  });

  input.addEventListener('input', debounce(function () {
    const q = this.value.trim().toLowerCase();
    if (isProductPath()) {
      const cards = getProductCards();
      cards.forEach(function (card) {
        const text = card.textContent.toLowerCase();
        if (!q || text.includes(q)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    }
  }, 200));
}
