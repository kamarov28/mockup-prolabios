/**
 * Catalog Navigation, Sidebar Accordion, Blog Filters, & Contact Form Controls
 */
import { isProductPath, sanitizeCategorySlug, getQueryParam, setTextContent } from './utils.js';

export function initCatalogCart() {
  initSidebarNavigation();
  initContactForm();
  initBlogCategoryFilter();
  initCopyCatalogCode();
}

let cachedProductCards = null;
function getProductCards() {
  if (!cachedProductCards) {
    cachedProductCards = Array.from(document.querySelectorAll('.product-card'));
  }
  return cachedProductCards;
}

export function initSidebarNavigation() {
  const sidebarLinks = document.querySelectorAll('.nav-list .nav-item a, .list-group a.list-group-item');
  if (!sidebarLinks.length) return;

  sidebarLinks.forEach(function (link) {
    link.addEventListener('click', function (event) {
      handleSidebarClick(event, link);
    });
  });

  applyInitialCategoryFromURL();
}

function handleSidebarClick(event, link) {
  const href = link.getAttribute('href') || '';
  const isInternal = href === '#' || href.startsWith('javascript:');

  if (isInternal) {
    event.preventDefault();
  }

  if (isProductPath() && href.indexOf('?') !== -1 && (href.indexOf('kategori=') !== -1 || href.indexOf('s=') !== -1)) {
    event.preventDefault();
    const url = new URL(href, window.location.origin);
    const cat = url.searchParams.get('kategori') || url.searchParams.get('s');

    const newUrl = new URL(window.location.href);
    if (cat) {
      newUrl.searchParams.set('kategori', cat);
      newUrl.searchParams.delete('s');
    }
    window.history.pushState({}, '', newUrl.toString());

    updateSidebarSelection(link, cat);
    filterProductsByCategory(cat);
  }
}

function setActiveSidebarForCategory(category) {
  if (!category) return;
  const safeCategory = sanitizeCategorySlug(category);
  document.querySelectorAll('.nav-list, .list-group').forEach(function (list) {
    list.querySelectorAll('.nav-item, a.list-group-item').forEach(function (item) {
      item.classList.remove('active');
    });

    const match = list.querySelector("a[href*='=" + safeCategory + "']");
    if (match) {
      const parent = match.closest('.nav-item');
      if (parent) {
        parent.classList.add('active');
      } else {
        match.classList.add('active');
      }
    }
  });
}

function updateSidebarSelection(link, category) {
  const parentList = link.closest('.nav-list, .list-group');
  if (parentList) {
    parentList.querySelectorAll('.nav-item, a.list-group-item').forEach(function (item) {
      item.classList.remove('active');
    });
  }

  const selectedItem = link.closest('.nav-item');
  if (selectedItem) {
    selectedItem.classList.add('active');
  } else {
    link.classList.add('active');
  }

  const selectedText = link.textContent.trim();
  const cleanText = selectedText.replace(/\s*\d+\s*$/, '').trim();

  setTextContent('.main-content h2, #category-title', cleanText);
  setTextContent('.main-content .text-muted, #category-subtitle', 'Menampilkan hasil untuk ' + cleanText);
}

function filterProductsByCategory(category) {
  const productCards = getProductCards();
  if (!productCards || !productCards.length) return;

  if (!category) {
    productCards.forEach(function (card) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
    });
    return;
  }

  productCards.forEach(function (card) {
    const cat = card.getAttribute('data-category');
    if (!cat) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
      return;
    }

    const cats = cat.split(/[ ,]+/).map(function (c) {
      return c.trim().toLowerCase();
    });
    
    if (cats.includes(category.toLowerCase())) {
      card.classList.remove('hidden-by-filter');
      card.style.display = '';
    } else {
      card.classList.add('hidden-by-filter');
      card.style.display = 'none';
    }
  });
}

function applyInitialCategoryFromURL() {
  const category = getQueryParam('kategori') || getQueryParam('s');
  if (!category) return;

  const safeCategory = sanitizeCategorySlug(category);
  setActiveSidebarForCategory(safeCategory);
  filterProductsByCategory(safeCategory);
}

export function initContactForm() {
  if (window.handleContactForm) return;
  window.handleContactForm = function (e) {
    e.preventDefault();

    const form = document.getElementById('contactForm');
    const success = document.getElementById('formSuccess');
    if (!form || !success) return false;

    const requiredFields = form.querySelectorAll('[required]');
    let valid = true;

    requiredFields.forEach(function (field) {
      if (!field.value.trim()) {
        field.style.borderColor = 'var(--color-primary)';
        valid = false;
      } else {
        field.style.borderColor = 'var(--color-border)';
      }
    });

    if (valid) {
      form.style.display = 'none';
      success.style.display = 'block';
      success.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return false;
  };
}

export function initBlogCategoryFilter() {
  const blogCards = document.querySelectorAll('.blog-card');
  if (!blogCards.length) return;

  const categoryLinks = document.querySelectorAll('.sidebar .nav-list .nav-item a');
  if (!categoryLinks.length) return;

  categoryLinks.forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      const raw = link.textContent.trim();
      const categoryName = raw.replace(/\s*\d+\s*$/, '').trim().toLowerCase();

      const parentList = link.closest('.nav-list');
      if (parentList) {
        parentList.querySelectorAll('.nav-item').forEach(function (item) {
          item.classList.remove('active');
        });
      }

      const selectedItem = link.closest('.nav-item');
      if (selectedItem) selectedItem.classList.add('active');

      blogCards.forEach(function (card) {
        const meta = card.querySelector('.blog-meta');
        if (!meta) return;

        const metaText = meta.textContent.toLowerCase();
        card.style.display = metaText.includes(categoryName) || categoryName === 'semua' ? '' : 'none';
      });
    });
  });
}

export function initCopyCatalogCode() {
  const codes = document.querySelectorAll('.product-cat-code');
  if (!codes.length) return;

  codes.forEach(function (el) {
    el.style.cursor = 'pointer';
    el.setAttribute('title', 'Click to copy catalog code');
    el.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      const fullText = el.textContent.trim().replace(/^CAT\.\s*/i, '');
      if (!fullText) return;

      navigator.clipboard.writeText(fullText).then(function () {
        const originalText = el.textContent;
        el.innerHTML = '<i class="bi bi-check2 text-success me-1"></i> Copied!';
        el.style.borderColor = 'var(--color-accent)';

        setTimeout(function () {
          el.textContent = originalText;
          el.style.borderColor = '';
        }, 1800);
      }).catch(function () {
        // Fallback for older browsers if clipboard permission is blocked
      });
    });
  });
}

