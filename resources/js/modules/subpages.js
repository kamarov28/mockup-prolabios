/**
 * Subpages module: Client-side tabs for Layanan & AJAX navigator for Sektor
 */

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

      sidebarLinks.forEach(l => l.classList.remove('is-active'));
      this.classList.add('is-active');

      document.querySelectorAll('.service-content-block').forEach(block => block.classList.add('d-none'));
      const targetBlock = document.getElementById('service-content-' + serviceKey);
      if (targetBlock) {
        targetBlock.classList.remove('d-none');
        targetBlock.querySelectorAll('.animate-on-scroll').forEach(el => el.classList.add('is-visible'));
      }
      history.pushState(null, '', window.location.pathname + '?s=' + serviceKey);

      if (typeof window.initGSAPAnimations === 'function') {
        window.initGSAPAnimations();
      }
    });
  });

  window.addEventListener('popstate', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceKey = urlParams.get('s') || 'maintenance';
    sidebarLinks.forEach(link => {
      const urlObj = new URL(link.href);
      link.classList.toggle('is-active', urlObj.searchParams.get('s') === serviceKey);
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
        overlay.style.display = 'flex';
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
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function (res) {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.text();
      })
      .then(function (html) {
        const doc = new DOMParser().parseFromString(html, 'text/html');

        const newMain = doc.getElementById('sektor-main');
        const newSidebar = doc.getElementById('sektor-sidebar');
        const curMain = document.getElementById('sektor-main');
        const curSidebar = document.getElementById('sektor-sidebar');

        if (curMain && newMain) {
          curMain.innerHTML = newMain.innerHTML;
          setSektorLoading(false);
        }
        if (curSidebar && newSidebar) {
          curSidebar.innerHTML = newSidebar.innerHTML;
        }

        if (updateHistory) {
          window.history.pushState({ url: url }, '', url);
        }

        if (typeof window.initGSAPAnimations === 'function') {
          window.initGSAPAnimations();
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
    const link = e.target.closest('#sektor-sidebar a.layanan-sidebar-link, #sektor-main .pagination a');
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
    if (mainImg) mainImg.src = src;
    if (lightboxImg) lightboxImg.src = src;
    document.querySelectorAll('.gallery-thumb').forEach(function (el) {
      el.classList.remove('active');
    });
    if (thumbEl) thumbEl.classList.add('active');
  };

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
    notice.style.display = (qty > stock) ? 'block' : 'none';
  }

  window.stepQty = function (amount) {
    let val = parseInt(qtyInput.value) || 1;
    val = Math.max(1, val + amount);
    qtyInput.value = val;
    toggleIndentNotice();
  };

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

  window.updateCartItemAjax = function (form) {
    const formData = new FormData(form);
    const itemCard = form.closest('.cart-item-card');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
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

        document.querySelectorAll('.nav-cart-badge').forEach(el => {
          el.textContent = data.cartCount;
          el.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
        });
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
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (itemCard) {
          itemCard.style.transition = 'all 0.3s ease';
          itemCard.style.opacity = '0';
          itemCard.style.transform = 'scale(0.95)';
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

        document.querySelectorAll('.nav-cart-badge').forEach(el => {
          el.textContent = data.cartCount;
          el.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
        });

        if (typeof window.Swal !== 'undefined') {
          window.Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: 'Item berhasil dihapus',
            showConfirmButton: false,
            timer: 2000,
            background: '#0f172a',
            color: '#ffffff'
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
      cancelButtonText: 'Batal'
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
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        executeRemoveAjax(form);
      }
    });
  };
}

export function initSubpages() {
  initLayananTabs();
  initSektorAjax();
  initProductDetail();
  initBeliProduk();
  initCartPage();
}
