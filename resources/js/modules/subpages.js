/**
 * Subpages module: Client-side tabs for Layanan & AJAX navigator for Sektor
 */
import { ajaxHeaders } from './utils.js';
import { setCartBadgeCount } from './catalog-cart.js';

function initLayananTabs() {
  const serviceNav = document.getElementById('service-nav');
  if (!serviceNav) return;

  const sidebarLinks = serviceNav.querySelectorAll('.layanan-sidebar-link');
  if (!sidebarLinks.length) return;

  sidebarLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const urlObj = new URL(this.href);
      const serviceKey = urlObj.searchParams.get('s');
      if (!serviceKey) return;

      sidebarLinks.forEach(l => {
        const isMatch = (new URL(l.href).searchParams.get('s') === serviceKey);
        l.classList.toggle('is-active', isMatch);
        if (l.classList.contains('nb-btn')) {
          l.classList.toggle('nb-btn-primary', isMatch);
          l.classList.toggle('nb-btn-ghost', !isMatch);
        }
      });

      document.querySelectorAll('.service-content-block').forEach(block => block.classList.add('d-none'));
      const targetBlock = document.getElementById('service-content-' + serviceKey);
      if (targetBlock) {
        targetBlock.classList.remove('d-none');
        targetBlock.querySelectorAll('.animate-on-scroll').forEach(el => el.classList.add('is-visible'));
      }
      history.pushState(null, '', window.location.pathname + '?s=' + serviceKey);

      if (typeof window.initScrollAnimations === 'function') {
        window.initScrollAnimations();
      }
    });
  });

  window.addEventListener('popstate', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceKey = urlParams.get('s') || 'maintenance';
    sidebarLinks.forEach(link => {
      const urlObj = new URL(link.href);
      const isMatch = (urlObj.searchParams.get('s') === serviceKey);
      link.classList.toggle('is-active', isMatch);
      if (link.classList.contains('nb-btn')) {
        link.classList.toggle('nb-btn-primary', isMatch);
        link.classList.toggle('nb-btn-ghost', !isMatch);
      }
    });
    document.querySelectorAll('.service-content-block').forEach(block => block.classList.add('d-none'));
    const targetBlock = document.getElementById('service-content-' + serviceKey);
    if (targetBlock) targetBlock.classList.remove('d-none');
  });
}

function initSektorAjax() {
  const sektorMain = document.getElementById('sektor-main');
  const sektorSidebar = document.getElementById('sektor-sidebar');
  if (!sektorMain || !sektorSidebar) return;

  let fetchController = null;

  function setSektorLoading(on) {
    const main = document.getElementById('sektor-main');
    if (!main) return;
    main.classList.toggle('is-loading', !!on);
    main.setAttribute('aria-busy', on ? 'true' : 'false');
    let overlay = main.querySelector(':scope > .ajax-loading-overlay');
    if (on) {
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'ajax-loading-overlay';
        overlay.setAttribute('aria-hidden', 'false');
        overlay.innerHTML = '<div class="ajax-spinner" role="status" aria-label="Memuat"></div>';
        main.insertBefore(overlay, main.firstChild);
      } else {
        overlay.classList.add('is-visible');
        overlay.setAttribute('aria-hidden', 'false');
      }
    } else if (overlay) {
      overlay.remove();
    }
  }

  function loadSektorAjax(url, updateHistory) {
    if (fetchController) {
      fetchController.abort();
    }
    fetchController = new AbortController();

    setSektorLoading(true);

    fetch(url, {
      signal: fetchController.signal,
      headers: ajaxHeaders(null, 'text/html')
    })
      .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
      })
      .then(function (html) {
        const doc = new DOMParser().parseFromString(html, 'text/html');

        const newMain = doc.getElementById('sektor-main');
        const newSidebar = doc.getElementById('sektor-sidebar');
        const newMobileNav = doc.getElementById('sektor-mobile-nav');
        const curMain = document.getElementById('sektor-main');
        const curSidebar = document.getElementById('sektor-sidebar');
        const curMobileNav = document.getElementById('sektor-mobile-nav');

        if (curMain && newMain) {
          curMain.innerHTML = newMain.innerHTML;
          setSektorLoading(false);
        }
        if (curSidebar && newSidebar) {
          curSidebar.innerHTML = newSidebar.innerHTML;
        }
        if (curMobileNav && newMobileNav) {
          curMobileNav.innerHTML = newMobileNav.innerHTML;
        }

        if (updateHistory) {
          window.history.pushState({ url: url }, '', url);
        }

        if (typeof window.initScrollAnimations === 'function') {
          window.initScrollAnimations();
        }

        const section = document.getElementById('sektor-nav');
        if (section) {
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      })
      .catch(function (err) {
        if (err.name === 'AbortError') return;
        setSektorLoading(false);
        console.error('Sektor AJAX failed, full navigation:', err);
        window.location.href = url;
      });
  }

  document.addEventListener('click', function (e) {
    const link = e.target.closest('#sektor-sidebar a.layanan-sidebar-link, #sektor-mobile-nav a.sektor-pill-link, #sektor-main .pagination a');
    if (!link || !link.getAttribute('href') || link.getAttribute('href').startsWith('#')) {
      return;
    }

    try {
      const u = new URL(link.href, window.location.origin);
      if (u.origin !== window.location.origin) return;
      if (!u.pathname.replace(/\/$/, '').endsWith('/sektor') && u.pathname !== '/sektor') {
        if (!u.pathname.includes('sektor')) return;
      }
    } catch (err) {
      return;
    }

    e.preventDefault();
    loadSektorAjax(link.href, true);
  });

  window.addEventListener('popstate', function () {
    loadSektorAjax(window.location.href, false);
  });
}

function initProductDetail() {
  const detailWrap = document.querySelector('.detail-product-img-wrap');
  const thumbs = document.querySelectorAll('.gallery-thumb');
  if (!detailWrap && !thumbs.length) return;

  window.switchProductImage = function (src, thumbEl) {
    const mainImg = document.getElementById('main-product-image');
    const lightboxImg = document.getElementById('lightbox-product-image');
    if (mainImg && src) mainImg.src = src;
    if (lightboxImg && src) lightboxImg.src = src;
    document.querySelectorAll('.gallery-thumb').forEach(function (el) {
      el.classList.remove('active');
    });
    if (thumbEl) {
      thumbEl.classList.add('active');
    } else {
      const match = document.querySelector(`.gallery-thumb[data-img="${CSS.escape(src)}"]`);
      if (match) match.classList.add('active');
    }
  };

  document.addEventListener('click', function (e) {
    const thumb = e.target.closest('.gallery-thumb');
    if (thumb) {
      const src = thumb.getAttribute('data-img') || thumb.querySelector('img')?.getAttribute('src');
      if (src) {
        window.switchProductImage(src, thumb);
      }
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      const thumb = e.target.closest('.gallery-thumb');
      if (thumb) {
        e.preventDefault();
        const src = thumb.getAttribute('data-img') || thumb.querySelector('img')?.getAttribute('src');
        if (src) {
          window.switchProductImage(src, thumb);
        }
      }
    }
  });

  const lightboxModal = document.getElementById('imageLightboxModal');
  if (lightboxModal && lightboxModal.parentElement !== document.body) {
    document.body.appendChild(lightboxModal);
  }
}

function initBeliProduk() {
  const qtyInput = document.getElementById('qty-input');
  if (!qtyInput) return;

  function toggleIndentNotice() {
    const notice = document.getElementById('indent-notice');
    if (!qtyInput || !notice) return;
    const stock = parseInt(qtyInput.dataset.stock || '0', 10);
    const qty = parseInt(qtyInput.value || '1', 10);
    notice.classList.toggle('is-hidden', !(qty > stock));
  }

  function step(amount) {
    let val = parseInt(qtyInput.value, 10) || 1;
    val = Math.max(1, val + amount);
    qtyInput.value = val;
    toggleIndentNotice();
  }

  window.stepQty = step;

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.nb-stepper-btn');
    if (!btn || btn.closest('.cart-item-card')) return;
    const stepVal = btn.dataset.step ? parseInt(btn.dataset.step, 10) : 0;
    if (stepVal !== 0) {
      step(stepVal);
    } else {
      const isMinus = btn.getAttribute('aria-label')?.includes('Kurangi') || btn.querySelector('[data-lucide="minus"]');
      step(isMinus ? -1 : 1);
    }
  });

  qtyInput.addEventListener('input', toggleIndentNotice);
  toggleIndentNotice();
}

function initCartPage() {
  const cartSection = document.querySelector('.cart-item-card') || document.querySelector('.cart-sidebar-panel');
  if (!cartSection) return;

  window.stepCartQty = function (btn, amount) {
    const form = btn.closest('form');
    if (!form) return;
    const input = form.querySelector('.cart-qty-input');
    if (!input) return;

    let val = parseInt(input.value) || 1;
    val = Math.max(1, val + amount);
    input.value = val;

    window.updateCartItemAjax(form);
  };

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.cart-item-card .nb-stepper-btn');
    if (!btn) return;
    const isMinus = btn.getAttribute('aria-label')?.includes('Kurangi') || btn.querySelector('[data-lucide="minus"]');
    window.stepCartQty(btn, isMinus ? -1 : 1);
  });

  document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('cart-qty-input')) {
      if (e.target.form) window.updateCartItemAjax(e.target.form);
    }
  });

  document.addEventListener('submit', function (e) {
    const removeForm = e.target.closest('form[action*="/cart/remove"]');
    if (removeForm) {
      e.preventDefault();
      window.removeCartItemAjax(removeForm);
    }
  });

  window.updateCartItemAjax = function (form) {
    const formData = new FormData(form);
    const itemCard = form.closest('.cart-item-card');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: ajaxHeaders()
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (itemCard) {
          const subtotalEl = itemCard.querySelector('.item-subtotal-val');
          if (subtotalEl) {
            subtotalEl.textContent = data.itemSubtotal;
          }
        }

        const totalUnitsEl = document.getElementById('sidebar-total-units');
        if (totalUnitsEl) {
          totalUnitsEl.textContent = data.cartCount + ' Unit';
        }

        const totalEstEl = document.getElementById('sidebar-total-estimate');
        if (totalEstEl) {
          totalEstEl.textContent = data.totalFormatted;
        }
        const mobileTotalEstEl = document.getElementById('mobile-total-estimate');
        if (mobileTotalEstEl) {
          mobileTotalEstEl.textContent = data.totalFormatted;
        }

        setCartBadgeCount(data.cartCount);
      }
    })
    .catch(err => console.error('Ajax Cart Error:', err));
  };

  function executeRemoveAjax(form) {
    const formData = new FormData(form);
    const itemCard = form.closest('.cart-item-card');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: ajaxHeaders()
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (itemCard) {
          itemCard.classList.add('is-removing');
          setTimeout(() => {
            itemCard.remove();
            if (data.cartCount === 0) {
              window.location.reload();
            }
          }, 300);
        }

        const totalUnitsEl = document.getElementById('sidebar-total-units');
        if (totalUnitsEl) {
          totalUnitsEl.textContent = data.cartCount + ' Unit';
        }

        const totalEstEl = document.getElementById('sidebar-total-estimate');
        if (totalEstEl) {
          totalEstEl.textContent = data.totalFormatted;
        }
        const mobileTotalEstEl = document.getElementById('mobile-total-estimate');
        if (mobileTotalEstEl) {
          mobileTotalEstEl.textContent = data.totalFormatted;
        }

        setCartBadgeCount(data.cartCount);

        if (typeof window.Swal !== 'undefined') {
          window.Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: 'Item berhasil dihapus',
            showConfirmButton: false,
            timer: 2000
          });
        }
      }
    })
    .catch(err => console.error('Ajax Remove Error:', err));
  }

  window.confirmClearCart = function (e, form) {
    e.preventDefault();
    if (typeof window.Swal === 'undefined') {
      if (confirm('Kosongkan semua item di keranjang?')) form.submit();
      return;
    }
    window.Swal.fire({
      title: 'Kosongkan keranjang pengajuan?',
      text: 'Seluruh item produk di dalam keranjang akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Kosongkan!',
      cancelButtonText: 'Batal',
      buttonsStyling: false,
      customClass: {
        confirmButton: 'nb-btn nb-btn-primary',
        cancelButton: 'nb-btn nb-btn-ghost',
        actions: 'gap-2'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  };

  window.removeCartItemAjax = function (form) {
    if (typeof window.Swal === 'undefined') {
      if (!confirm('Hapus item ini dari keranjang?')) return;
      executeRemoveAjax(form);
      return;
    }

    window.Swal.fire({
      title: 'Hapus Item Produk?',
      text: 'Item produk ini akan dihapus dari pengajuan penawaran.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      buttonsStyling: false,
      customClass: {
        confirmButton: 'nb-btn nb-btn-primary',
        cancelButton: 'nb-btn nb-btn-ghost',
        actions: 'gap-2'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        executeRemoveAjax(form);
      }
    });
  };
}

function initHitechTabs() {
  const tabBtns = Array.from(document.querySelectorAll('.hitech-tab-btn'));
  const tabPanels = document.querySelectorAll('.hitech-tab-panel');
  if (!tabBtns.length || !tabPanels.length) return;

  function activateTab(tab, setFocus) {
    if (!tab) return;
    const target = tab.getAttribute('data-target');
    tabBtns.forEach(b => {
      b.classList.remove('active');
      b.setAttribute('aria-selected', 'false');
      b.setAttribute('tabindex', '-1');
    });
    tabPanels.forEach(p => p.classList.remove('active'));

    tab.classList.add('active');
    tab.setAttribute('aria-selected', 'true');
    tab.setAttribute('tabindex', '0');
    if (setFocus) tab.focus();

    const activePanel = document.getElementById('panel-' + target);
    if (activePanel) activePanel.classList.add('active');
  }

  tabBtns.forEach((btn, idx) => {
    btn.addEventListener('click', function () {
      activateTab(this, false);
    });

    btn.addEventListener('keydown', function (e) {
      let targetIdx = null;
      if (e.key === 'ArrowRight') {
        targetIdx = (idx + 1) % tabBtns.length;
      } else if (e.key === 'ArrowLeft') {
        targetIdx = (idx - 1 + tabBtns.length) % tabBtns.length;
      } else if (e.key === 'Home') {
        targetIdx = 0;
      } else if (e.key === 'End') {
        targetIdx = tabBtns.length - 1;
      }
      if (targetIdx !== null) {
        e.preventDefault();
        activateTab(tabBtns[targetIdx], true);
      }
    });
  });
}

export function initSubpages() {
  initLayananTabs();
  initSektorAjax();
  initProductDetail();
  initBeliProduk();
  initCartPage();
  initHitechTabs();
}
