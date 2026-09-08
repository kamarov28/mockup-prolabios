/**
 * Catalog AJAX navigation, accordion toggle, live search, and dynamic pagination
 */

export function initCatalogAjax() {
  const catalogSection = document.getElementById('catalog-section');
  if (!catalogSection) return;

  let currentFetchController = null;

  function setProductLoading(on, isLiveSearch) {
    const wrap = document.getElementById('product-ajax-wrap');
    if (!wrap) return;
    wrap.classList.toggle('is-loading', !!on);
    wrap.setAttribute('aria-busy', on ? 'true' : 'false');
    const overlay = wrap.querySelector('.ajax-loading-overlay');
    if (overlay) overlay.setAttribute('aria-hidden', on ? 'false' : 'true');
  }

  function loadProductsAjax(url, updateHistory = true, isLiveSearch = false) {
    if (currentFetchController) {
      currentFetchController.abort();
    }
    currentFetchController = new AbortController();

    setProductLoading(true, isLiveSearch);
    const container = document.getElementById('product-container');
    if (container && isLiveSearch) {
      container.style.opacity = '0.7';
      container.style.transition = 'opacity 0.15s ease-in-out';
    }

    fetch(url, {
      signal: currentFetchController.signal,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const currentSidebar = document.querySelector('#catalog-section .col-lg-4');
        const newSidebar = doc.querySelector('#catalog-section .col-lg-4');
        if (currentSidebar && newSidebar && !isLiveSearch) {
          const collapseEl = document.getElementById('sidebarCollapse');
          const isCollapseOpen = collapseEl ? collapseEl.classList.contains('show') : false;
          currentSidebar.innerHTML = newSidebar.innerHTML;
          if (isCollapseOpen) {
            const newCollapseEl = document.getElementById('sidebarCollapse');
            if (newCollapseEl) newCollapseEl.classList.add('show');
          }
        }

        const currentTitle = document.getElementById('category-title');
        const newTitle = doc.getElementById('category-title');
        const currentSubtitle = document.getElementById('category-subtitle');
        const newSubtitle = doc.getElementById('category-subtitle');
        if (currentTitle && newTitle) currentTitle.innerHTML = newTitle.innerHTML;
        if (currentSubtitle && newSubtitle) currentSubtitle.innerHTML = newSubtitle.innerHTML;

        const currentGrid = document.getElementById('product-container');
        const newGrid = doc.getElementById('product-container');
        if (currentGrid && newGrid) {
          currentGrid.innerHTML = newGrid.innerHTML;
          currentGrid.className = newGrid.className;
          currentGrid.style.opacity = '1';
        }

        const currentPag = document.getElementById('dynamic-pagination');
        const newPag = doc.getElementById('dynamic-pagination');
        if (currentPag && newPag) currentPag.innerHTML = newPag.innerHTML;

        setProductLoading(false, isLiveSearch);

        if (updateHistory) {
          window.history.replaceState({ url: url }, '', url);
        }

        if (!isLiveSearch) {
          if (typeof window.initScrollAnimations === 'function') window.initScrollAnimations();
          if (typeof window.initGSAPAnimations === 'function') window.initGSAPAnimations();

          const sidebarCollapse = document.getElementById('sidebarCollapse');
          if (sidebarCollapse && window.innerWidth < 768 && window.bootstrap) {
            const bsCollapse = window.bootstrap.Collapse.getInstance(sidebarCollapse);
            if (bsCollapse) bsCollapse.hide();
            else sidebarCollapse.classList.remove('show');
          }

          const catalogSec = document.getElementById('catalog-section');
          if (catalogSec) catalogSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        const localSearch = document.getElementById('local-search-input');
        if (localSearch && document.activeElement !== localSearch) {
          const currentUrlObj = new URL(url, window.location.origin);
          localSearch.value = currentUrlObj.searchParams.get('s') || currentUrlObj.searchParams.get('q') || '';
        }
      })
      .catch(error => {
        if (error.name === 'AbortError') return;
        setProductLoading(false, isLiveSearch);
        console.error('AJAX Load Failed, falling back to full reload:', error);
        window.location.href = url;
      });
  }

  document.addEventListener('click', function (e) {
    const link = e.target.closest('#catalog-section .col-lg-3 a') || e.target.closest('#catalog-section .col-lg-4 a') || e.target.closest('#catalog-sidebar a') || e.target.closest('.pagination a');
    if (link && link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
      e.preventDefault();
      const linkUrl = new URL(link.href, window.location.origin);
      linkUrl.searchParams.delete('q');
      linkUrl.searchParams.delete('search');
      loadProductsAjax(linkUrl.toString(), true, false);
    }
  });

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.category-accordion-btn');
    if (btn) {
      e.preventDefault();
      const targetId = btn.getAttribute('data-target');
      const targetGroup = document.getElementById(targetId);
      if (targetGroup) {
        const isHidden = targetGroup.classList.contains('d-none');
        document.querySelectorAll('.sub-category-group').forEach(group => {
          if (group.id !== targetId) group.classList.add('d-none');
        });
        document.querySelectorAll('.category-accordion-btn').forEach(otherBtn => {
          if (otherBtn !== btn) {
            otherBtn.classList.remove('is-active');
            const otherChevron = otherBtn.querySelector('.chevron-icon');
            if (otherChevron) otherChevron.classList.replace('bi-chevron-down', 'bi-chevron-right');
          }
        });
        if (isHidden) {
          targetGroup.classList.remove('d-none');
          btn.classList.add('is-active');
          const chevron = btn.querySelector('.chevron-icon');
          if (chevron) chevron.classList.replace('bi-chevron-right', 'bi-chevron-down');
        } else {
          targetGroup.classList.add('d-none');
          btn.classList.remove('is-active');
          const chevron = btn.querySelector('.chevron-icon');
          if (chevron) chevron.classList.replace('bi-chevron-down', 'bi-chevron-right');
        }
      }
    }
  });

  window.addEventListener('popstate', function () {
    loadProductsAjax(window.location.href, false, false);
  });

  const localSearch = document.getElementById('local-search-input');
  const searchForm = document.getElementById('catalog-search-form');
  let searchDebounceTimer = null;

  if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const query = localSearch ? localSearch.value.trim() : '';
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.delete('q');
      currentUrl.searchParams.delete('search');
      if (query) currentUrl.searchParams.set('s', query);
      else currentUrl.searchParams.delete('s');
      currentUrl.searchParams.delete('page');
      loadProductsAjax(currentUrl.toString(), true, false);
    });
  }

  if (localSearch) {
    localSearch.addEventListener('input', function () {
      clearTimeout(searchDebounceTimer);
      const query = this.value.trim();
      searchDebounceTimer = setTimeout(() => {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('q');
        currentUrl.searchParams.delete('search');
        if (query) currentUrl.searchParams.set('s', query);
        else currentUrl.searchParams.delete('s');
        currentUrl.searchParams.delete('page');
        loadProductsAjax(currentUrl.toString(), true, true);
      }, 250);
    });
  }
}
