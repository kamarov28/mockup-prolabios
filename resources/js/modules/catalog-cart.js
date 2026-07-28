/**
 * Catalog Navigation, Sidebar Accordion, Blog Filters, & Contact Form Controls
 */
import { isProductPath, sanitizeCategorySlug, getQueryParam, setTextContent } from './utils.js';

export function initCatalogCart() {
  initSidebarNavigation();
  initContactForm();
  initBlogCategoryFilter();
  initCopyCatalogCode();
  initAjaxAddToCart();
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

function applyInitialCategoryFromURL() {
  if (!isProductPath()) return;
  const urlCategory = getQueryParam('kategori') || getQueryParam('s');
  if (urlCategory) {
    setActiveSidebarForCategory(urlCategory);
    filterProductsByCategory(urlCategory);
  }
}

function filterProductsByCategory(rawCategory) {
  const category = sanitizeCategorySlug(rawCategory);
  const cards = getProductCards();
  if (!cards.length) return;

  cards.forEach(function (card) {
    const cardCategory = sanitizeCategorySlug(card.dataset.category || '');
    if (category === 'semua' || !category || cardCategory.includes(category) || category.includes(cardCategory)) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  });
}

export function initContactForm() {
  const form = document.querySelector('form.contact-form');
  if (!form) return;

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if (!btn) return;

    const originalText = btn.textContent;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';

    setTimeout(function () {
      btn.disabled = false;
      btn.textContent = originalText;
      showToast('Pesan Anda berhasil dikirim! Tim kami akan segera menghubungi Anda.', 'success');
      form.reset();
    }, 1200);
  });
}

export function initBlogCategoryFilter() {
  const categoryLinks = document.querySelectorAll('.blog-category-filter a');
  const blogCards = document.querySelectorAll('.blog-card');
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
        // Fallback
      });
    });
  });
}

export function initAjaxAddToCart() {
  document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!form || !form.action || !form.action.includes('/cart/add')) return;

    event.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn || submitBtn.disabled) return;

    const originalBtnHtml = submitBtn.innerHTML;
    const csrfInput = form.querySelector('input[name="_token"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    const formData = new FormData(form);

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Adding...';

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success) {
          // Update cart badge counts across navbar
          const badges = document.querySelectorAll('#cart-badge-count, .nav-cart-badge');
          badges.forEach(function (b) {
            b.textContent = data.cartCount;
            b.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
            b.style.transform = 'scale(1.4)';
            setTimeout(function () { b.style.transform = 'scale(1)'; }, 250);
          });

          // Show Button Success Feedback
          submitBtn.innerHTML = '<i class="bi bi-check2-circle me-1 text-success"></i> Added to RFQ';
          submitBtn.classList.remove('btn-outline-danger');
          submitBtn.classList.add('btn-success');

          showToast(data.message || 'Added to RFQ Cart!');

          setTimeout(function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-outline-danger');
          }, 2000);
        } else {
          showToast(data.message || 'Gagal menambahkan produk.', 'warning');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnHtml;
        }
      })
      .catch(function () {
        showToast('Terjadi kesalahan koneksi. Silakan coba lagi.', 'warning');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnHtml;
      });
  });
}

export function showToast(message, type = 'success') {
  let container = document.getElementById('prolabios-toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'prolabios-toast-container';
    container.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:100000;display:flex;flex-direction:column;gap:10px;pointer-events:none;';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = 'prolabios-toast-item';
  toast.style.cssText = 'background:#0f1015;color:#ffffff;border:1px solid rgba(255,73,80,0.35);border-radius:12px;padding:12px 20px;font-family:var(--font-body);font-size:0.88rem;box-shadow:0 14px 35px rgba(0,0,0,0.85);display:flex;align-items:center;gap:12px;opacity:0;transform:translateY(15px);transition:all 0.3s cubic-bezier(0.16,1,0.3,1);pointer-events:auto;';

  const iconHtml = type === 'success' 
    ? '<i class="bi bi-check-circle-fill" style="color: #2e7d32; font-size: 1.15rem;"></i>' 
    : '<i class="bi bi-exclamation-triangle-fill" style="color: #ed6c02; font-size: 1.15rem;"></i>';

  toast.innerHTML = iconHtml + '<span style="font-weight: 500;">' + message + '</span>';
  container.appendChild(toast);

  requestAnimationFrame(function () {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  });

  setTimeout(function () {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-10px)';
    setTimeout(function () {
      if (toast.parentElement) toast.parentElement.removeChild(toast);
    }, 300);
  }, 3200);
}
