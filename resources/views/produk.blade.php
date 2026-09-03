@extends('layouts.app')

@section('title', 'Katalog Produk | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['products_banner_image'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header (Soft Neo-Brutalism Hero Banner, follows Profil page) -->
  <section class="profil-hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9">
          <span class="nb-badge">
            <i class="bi bi-box-seam me-1"></i> KATALOG PRODUK
          </span>
          <h1 class="profil-main-title">
            Produk &amp; Instrumen Laboratorium
          </h1>
          <p class="profil-main-subtitle">
            {{ $siteSettings['products_subtitle'] ?? 'Katalog lengkap instrumen analitika, media kultur mikrobiologi, dan perlengkapan pengujian bersertifikasi resmi Prolabios.' }}
          </p>
        </div>
      </div>

      <!-- Quick Fast Stats Strip -->
      <div class="profil-stats-strip">
        <div class="profil-stat-box">
          <div class="profil-stat-num">100%</div>
          <div class="profil-stat-label">Produk Original &amp; Bersertifikat COA</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Ready &amp; Indent</div>
          <div class="profil-stat-label">Jaminan Ketersediaan &amp; Pasokan</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Resmi &amp; Legal</div>
          <div class="profil-stat-label">Kepatuhan Regulasi &amp; AKL/AKD</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">B2B RFQ</div>
          <div class="profil-stat-label">Dukungan Penawaran Harga Institusi</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Product Content -->
  <section class="section-spacious nb-section" id="catalog-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">
        <!-- Main Content (Left, follows Profil page order-1) -->
        <div class="col-lg-8 col-md-7 order-1">
          <!-- Category Title Header -->
          <div class="mb-4 pb-2 border-bottom" style="border-color: rgba(30,30,30,0.15) !important;">
            <h2 class="produk-category-title mb-1" id="category-title">
              @if($activeCategory === 'all')
                Semua Produk
              @else
                {{ $categoriesStructure[$activeCategory]['name'] }}
                @if($activeSubCategory && $activeSubCategory !== 'all' && isset($categoriesStructure[$activeCategory]['subs'][$activeSubCategory]))
                  — {{ $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] }}
                @endif
              @endif
            </h2>
            <span class="produk-category-subtitle d-block" id="category-subtitle">
              @if($activeCategory === 'all')
                  Menampilkan seluruh katalog produk
              @else
                Menampilkan hasil untuk {{ $categoriesStructure[$activeCategory]['name'] }}
                @if($activeSubCategory && $activeSubCategory !== 'all' && isset($categoriesStructure[$activeCategory]['subs'][$activeSubCategory]))
                  ({{ $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] }})
                @endif
              @endif
            </span>
          </div>

          <div class="ajax-loading-wrap" id="product-ajax-wrap" aria-busy="false">
            <div class="ajax-loading-overlay" aria-hidden="true"><div class="ajax-spinner" role="status" aria-label="Memuat"></div></div>
            <div class="row row-cols-1 row-cols-md-2 g-4" id="product-container">
            @if(isset($products) && (is_array($products) || $products instanceof \Countable) && count($products) > 0)
              @foreach($products as $prod)
              <div class="col" data-category="{{ $prod['category'] ?? '' }} {{ $prod['sector'] ?? '' }}">
                <div class="card h-100 product-card border-0">
                  <div class="img-wrap">
                    <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Produk Laboratorium" loading="lazy" decoding="async" width="400" height="250">
                  </div>
                  <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex flex-wrap gap-1 align-items-center mb-2">
                      @if(!empty($prod['catalog']))
                        <div class="product-cat-code">
                          CAT. {{ $prod['catalog'] }}
                        </div>
                      @endif
                      @if(!empty($prod->principal))
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-1 px-2" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.5px;">
                          <i class="bi bi-building me-1" style="color: var(--color-accent);"></i>{{ $prod->principal->name }}
                        </span>
                      @endif
                    </div>
                    <h3 class="card-title fs-6 fw-semibold mb-2">
                      <a href="{{ product_url($prod) }}" class="product-card-link">{{ $prod['title'] }}</a>
                    </h3>
                    <p class="product-card-desc mb-3 flex-grow-1">
                      {{ Str::limit(str_replace('-', ' ', $prod['sub_category'] ?? $prod['category'] ?? ''), 75) ?: 'Produk laboratorium' }}
                    </p>

                    <div class="mt-auto pt-3 border-top" style="border-color: rgba(30,30,30,0.12) !important;">
                      <a href="{{ product_url($prod) }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.8rem; padding: 6px 12px;">
                        <i class="bi bi-eye me-1"></i> Lihat Detail Produk
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12 text-center p-5 card" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
                <i class="bi bi-box-seam" style="font-size: 2.5rem; color: var(--nb-muted); display: block; margin-bottom: 16px;"></i>
                <h3 class="fs-5 fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">Produk Tidak Ditemukan</h3>
                <p style="color: var(--nb-muted); margin-bottom: 0;">Belum ada produk spesifik di kategori atau kata kunci pencarian ini.</p>
              </div>
            @endif
          </div>
          </div><!-- /product-ajax-wrap -->

          <div class="mt-4" id="dynamic-pagination">
            {{ $products->links('partials.catalog-pagination') }}
          </div>
        </div>

        <!-- Sidebar / Right Column (follows Profil page layout order-2) -->
        <div class="col-lg-4 col-md-5 order-2">
          @include('partials.catalog-sidebar')
        </div>
      </div>
    </div>
  </section>



  @push('styles')
  <style>
    .ajax-loading-wrap { position: relative; min-height: 200px; }
    .ajax-loading-wrap.is-loading { pointer-events: none; }
    .ajax-loading-overlay {
      position: absolute; inset: 0; z-index: 5;
      display: none; align-items: flex-start; justify-content: center;
      padding-top: 48px;
      background: rgba(214, 208, 197, 0.65);
      border-radius: var(--nb-radius-lg, 8px);
    }
    .ajax-loading-wrap.is-loading .ajax-loading-overlay { display: flex; }
    .ajax-spinner {
      width: 40px; height: 40px;
      border: 3px solid rgba(30, 30, 30, 0.15);
      border-top-color: var(--nb-primary, #A6171C);
      border-radius: 50%;
      animation: ajax-spin 0.7s linear infinite;
    }
    @keyframes ajax-spin { to { transform: rotate(360deg); } }
    .ajax-loading-wrap.is-loading #product-container > .col.product-card,
    .ajax-loading-wrap.is-loading #product-container > .col-12 { visibility: hidden; height: 0; overflow: hidden; margin: 0; padding: 0; }
    .ajax-skel-card {
      background: var(--nb-card, #FFFFFF);
      border: var(--nb-border, 2px solid #1E1E1E);
      border-radius: var(--nb-radius-lg, 8px);
      box-shadow: var(--nb-shadow, 4px 4px 0 #1E1E1E);
      overflow: hidden;
      height: 100%;
    }
    .ajax-skel-img {
      aspect-ratio: 16/10;
      background: linear-gradient(90deg, #FEFEFE 25%, #E2DDD5 50%, #FEFEFE 75%);
      background-size: 200% 100%;
      animation: ajax-shimmer 1.2s ease-in-out infinite;
      border-bottom: 2px solid #1E1E1E;
    }
    .ajax-skel-line {
      height: 12px; border-radius: 4px; margin: 12px 16px;
      background: linear-gradient(90deg, #FEFEFE 25%, #E2DDD5 50%, #FEFEFE 75%);
      background-size: 200% 100%;
      animation: ajax-shimmer 1.2s ease-in-out infinite;
    }
    .ajax-skel-line.short { width: 40%; }
    .ajax-skel-line.med { width: 70%; }
    @keyframes ajax-shimmer {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
  </style>
  @endpush

  @push('scripts')
  @include('partials.gsap-loader')
  <script>
    let currentFetchController = null;

    function setProductLoading(on, isLiveSearch) {
      const wrap = document.getElementById('product-ajax-wrap');
      if (!wrap) return;
      wrap.classList.toggle('is-loading', !!on);
      wrap.setAttribute('aria-busy', on ? 'true' : 'false');
      const overlay = wrap.querySelector('.ajax-loading-overlay');
      if (overlay) overlay.setAttribute('aria-hidden', on ? 'false' : 'true');
      if (on && !isLiveSearch) {
        const grid = document.getElementById('product-container');
        if (grid && !grid.querySelector('.ajax-skel-col')) {
          const skel = document.createDocumentFragment();
          for (let i = 0; i < 6; i++) {
            const col = document.createElement('div');
            col.className = 'col ajax-skel-col';
            col.innerHTML = '<div class="ajax-skel-card"><div class="ajax-skel-img"></div><div class="ajax-skel-line short"></div><div class="ajax-skel-line med"></div><div class="ajax-skel-line"></div></div>';
            skel.appendChild(col);
          }
          grid.appendChild(skel);
        }
      }
      if (!on) {
        document.querySelectorAll('#product-container .ajax-skel-col').forEach(function (el) { el.remove(); });
      }
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
          if (typeof initScrollAnimations === 'function') initScrollAnimations();
          if (typeof initGSAPAnimations === 'function') initGSAPAnimations();

          const sidebarCollapse = document.getElementById('sidebarCollapse');
          if (sidebarCollapse && window.innerWidth < 768) {
            const bsCollapse = bootstrap.Collapse.getInstance(sidebarCollapse);
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

    document.addEventListener('DOMContentLoaded', function() {
      document.addEventListener('click', function(e) {
        const link = e.target.closest('#catalog-section .col-lg-3 a') || e.target.closest('.pagination a');
        if (link && link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
          e.preventDefault();
          const linkUrl = new URL(link.href, window.location.origin);
          linkUrl.searchParams.delete('q');
          linkUrl.searchParams.delete('search');
          loadProductsAjax(linkUrl.toString(), true, false);
        }
      });

      document.addEventListener('click', function(e) {
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

      window.addEventListener('popstate', function() {
        loadProductsAjax(window.location.href, false, false);
      });

      const localSearch = document.getElementById('local-search-input');
      const searchForm = document.getElementById('catalog-search-form');
      let searchDebounceTimer = null;

      if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
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
        localSearch.addEventListener('input', function() {
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
    });
  </script>
  @endpush
@endsection
