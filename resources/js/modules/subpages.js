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

export function initSubpages() {
  initLayananTabs();
  initSektorAjax();
}
