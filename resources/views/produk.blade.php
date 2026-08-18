@extends('layouts.app')

@section('title', 'Katalog Produk | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['products_banner_image'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">catalog</span>
      <h1 class="editorial-page-title">Products & Instruments</h1>
      <p class="editorial-page-subtitle">{{ $siteSettings['products_subtitle'] ?? 'Prolabios Complete Catalog of Laboratory Products' }}</p>
    </div>
  </div>

  <!-- Product Content -->
  <section class="section-main" id="catalog-section">
    <div class="container">
      <div class="row g-5">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          <!-- Mobile Filter Toggle Button -->
          <button class="catalog-filter-toggle-btn w-100 d-md-none mb-4 d-flex align-items-center justify-content-between py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
            <span><i class="bi bi-funnel me-2"></i>Filter &amp; Kategori</span>
            <i class="bi bi-chevron-down"></i>
          </button>

          <!-- Collapsible Content for Mobile, always open on Medium+ screens -->
          <div class="collapse d-md-block" id="sidebarCollapse">
            <!-- Categories -->
            <div class="mb-5">
              <h3 class="profil-sidebar-title">Kategori Produk</h3>
              <nav class="layanan-sidebar-nav" id="produk-sidebar">
                <a href="{{ url('/produk') }}?category=all#catalog-section"
                   class="layanan-sidebar-link {{ $activeCategory === 'all' ? 'is-active' : '' }}">
                  All Categories
                </a>
                @foreach($categoriesStructure as $catKey => $catData)
                  @if(!empty($catData['subs']))
                    <!-- Category with subcategories: acts as accordion toggle -->
                    <a href="#"
                       class="layanan-sidebar-link d-flex justify-content-between align-items-center category-accordion-btn {{ $activeCategory === $catKey ? 'is-active' : '' }}"
                       role="button"
                       aria-expanded="{{ $activeCategory === $catKey ? 'true' : 'false' }}"
                       aria-controls="sub-group-{{ $catKey }}"
                       data-target="sub-group-{{ $catKey }}">
                      <span>{{ $catData['name'] }}</span>
                      <i class="bi bi-chevron-{{ $activeCategory === $catKey ? 'down' : 'right' }} chevron-icon" style="font-size: 0.7rem; opacity: 0.6;"></i>
                    </a>

                    <!-- Subcategories container -->
                    <div id="sub-group-{{ $catKey }}" class="sub-category-group ps-3 mb-3 {{ $activeCategory === $catKey ? '' : 'd-none' }}" style="max-height: 350px; overflow-y: auto; border-left: 1px solid var(--color-border); margin-left: 8px;">
                      <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory=all#catalog-section"
                         class="sub-category-link {{ $activeCategory === $catKey && (!$activeSubCategory || $activeSubCategory === 'all') ? 'is-active' : '' }}">
                        Semua {{ $catData['name'] }}
                      </a>
                      @foreach($catData['subs'] as $subKey => $subName)
                        <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory={{ $subKey }}#catalog-section"
                           class="sub-category-link {{ $activeCategory === $catKey && $activeSubCategory === $subKey ? 'is-active' : '' }}">
                          {{ $subName }}
                        </a>
                      @endforeach
                    </div>
                  @else
                    <!-- Category without subcategories: direct filter link -->
                    <a href="{{ url('/produk') }}?category={{ $catKey }}#catalog-section"
                       class="layanan-sidebar-link {{ $activeCategory === $catKey ? 'is-active' : '' }}">
                      {{ $catData['name'] }}
                    </a>
                  @endif
                @endforeach
              </nav>
            </div>

            <div class="profil-cta-box">
              <h3 class="profil-sidebar-title">Need Help?</h3>
              <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Discuss your product needs with our technical team.</p>
              <a href="{{ url('/kontak') }}?subjek=inquiry" class="profil-cta-btn d-block mb-3">Ask About a Product <i class="bi bi-arrow-right"></i></a>
              <a href="{{ !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf') }}" target="_blank" rel="noopener noreferrer" class="profil-social-link"><i class="bi bi-download"></i> Download the PDF Catalog</a>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <!-- Category Title Header -->
          <div class="mb-4">
            <h2 class="produk-category-title mb-1" id="category-title">
              @if($activeCategory === 'all')
                All Products
              @else
                {{ $categoriesStructure[$activeCategory]['name'] }}
                @if($activeSubCategory && $activeSubCategory !== 'all' && isset($categoriesStructure[$activeCategory]['subs'][$activeSubCategory]))
                  — {{ $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] }}
                @endif
              @endif
            </h2>
            <span class="produk-category-subtitle d-block" id="category-subtitle">
              @if($activeCategory === 'all')
                  Displaying the entire product catalog
              @else
                Showing results for {{ $categoriesStructure[$activeCategory]['name'] }}
                @if($activeSubCategory && $activeSubCategory !== 'all' && isset($categoriesStructure[$activeCategory]['subs'][$activeSubCategory]))
                  ({{ $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] }})
                @endif
              @endif
            </span>
          </div>

          <!-- Search Input Box Below Title -->
          <form action="{{ url('/produk') }}" method="GET" id="catalog-search-form" class="produk-search-wrap w-100 mb-5" style="max-width: 480px;">
            @if(request()->query('category'))
              <input type="hidden" name="category" value="{{ request()->query('category') }}">
            @endif
            @if(request()->query('subcategory'))
              <input type="hidden" name="subcategory" value="{{ request()->query('subcategory') }}">
            @endif
            <i class="bi bi-search" style="cursor: pointer;" onclick="document.getElementById('catalog-search-form').submit();"></i>
            <input type="text" id="local-search-input" name="s" placeholder="Search reagents or CAT. code..." aria-label="Cari produk" value="{{ request()->query('s') ?? request()->query('q') }}">
          </form>

          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="product-container">
            @if(isset($products) && (is_array($products) || $products instanceof \Countable) && count($products) > 0)
              @foreach($products as $prod)
              <div class="col product-card" data-category="{{ $prod['category'] ?? '' }} {{ $prod['sector'] ?? '' }}">
                <div class="card h-100 product-card-premium border-0">
                  <div class="img-wrap">
                    <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Laboratory Product &amp; Analytical Instrument" loading="lazy" decoding="async">
                  </div>
                  <div class="card-body p-4 d-flex flex-column">
                    @if(!empty($prod['catalog']))
                      <div class="product-cat-code mb-2">
                        CAT. {{ $prod['catalog'] }}
                      </div>
                    @endif
                    <h3 class="card-title fs-6 fw-semibold mb-2">
                      <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="product-card-link">{{ $prod['title'] }}</a>
                    </h3>
                    <p class="product-card-desc mb-3 flex-grow-1">
                      {{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 75) }}
                    </p>

                    <div class="mt-auto pt-3 border-top border-secondary border-opacity-10">
                      <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-outline-danger btn-sm w-100 fw-semibold text-decoration-none">
                        <i class="bi bi-eye me-1"></i> Lihat Detail Produk
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12" style="padding: 60px 0; border: 1px solid var(--color-border); text-align: center;">
                <i class="bi bi-box-seam" style="font-size: 2.5rem; color: var(--color-text-muted); display: block; margin-bottom: 16px;"></i>
                <p style="color: var(--color-text-muted);">There are no specific products in this category yet.</p>
              </div>
            @endif
          </div>

          <div class="d-flex justify-content-center mt-5" id="dynamic-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>
  </section>



  @push('scripts')
  @include('partials.gsap-loader')
  <script>
    // AJAX Dynamic Loader for Catalog Navigation
    function loadProductsAjax(url, updateHistory = true) {
      const container = document.getElementById('product-container');
      if (container) {
        container.style.opacity = '0.4';
        container.style.transition = 'opacity 0.2s ease-in-out';
      }

      fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // 1. Update Left Sidebar (Box 1 Categories and Box 2 Subcategories)
        const currentSidebar = document.querySelector('#catalog-section .col-lg-3');
        const newSidebar = doc.querySelector('#catalog-section .col-lg-3');
        if (currentSidebar && newSidebar) {
          const collapseEl = document.getElementById('sidebarCollapse');
          const isCollapseOpen = collapseEl ? collapseEl.classList.contains('show') : false;

          currentSidebar.innerHTML = newSidebar.innerHTML;

          if (isCollapseOpen) {
            const newCollapseEl = document.getElementById('sidebarCollapse');
            if (newCollapseEl) {
              newCollapseEl.classList.add('show');
            }
          }
        }

        // 2. Update Category Header Title and Subtitle
        const currentTitle = document.getElementById('category-title');
        const newTitle = doc.getElementById('category-title');
        const currentSubtitle = document.getElementById('category-subtitle');
        const newSubtitle = doc.getElementById('category-subtitle');

        if (currentTitle && newTitle) currentTitle.innerHTML = newTitle.innerHTML;
        if (currentSubtitle && newSubtitle) currentSubtitle.innerHTML = newSubtitle.innerHTML;

        // 3. Update Product Cards Grid Container
        const currentGrid = document.getElementById('product-container');
        const newGrid = doc.getElementById('product-container');
        if (currentGrid && newGrid) {
          currentGrid.innerHTML = newGrid.innerHTML;
          currentGrid.className = newGrid.className;
          currentGrid.style.opacity = '1';
        }

        // 4. Update History state
        if (updateHistory) {
          window.history.pushState({ url: url }, '', url);
        }

        // 5. Re-initialize scroll entrance reveal animations on new elements
        if (typeof initScrollAnimations === 'function') {
          initScrollAnimations();
        }
        if (typeof initGSAPAnimations === 'function') {
          initGSAPAnimations();
        }

        // Close mobile filter/sidebar collapse after selection
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        if (sidebarCollapse && window.innerWidth < 768) {
          const bsCollapse = bootstrap.Collapse.getInstance(sidebarCollapse);
          if (bsCollapse) {
            bsCollapse.hide();
          } else {
            sidebarCollapse.classList.remove('show');
          }
        }

        // 6. Preserve active search query in search input
        const localSearch = document.getElementById('local-search-input');
        if (localSearch) {
          const currentUrlObj = new URL(url, window.location.origin);
          const activeSearchQuery = currentUrlObj.searchParams.get('s') || currentUrlObj.searchParams.get('q') || '';
          localSearch.value = activeSearchQuery;
          if (activeSearchQuery && document.activeElement === localSearch) {
            const len = activeSearchQuery.length;
            localSearch.setSelectionRange(len, len);
          }
        }

        // Smooth scroll to catalog section top
        const catalogSec = document.getElementById('catalog-section');
        if (catalogSec) {
          catalogSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      })
      .catch(error => {
        console.error('AJAX Load Failed, falling back to full reload:', error);
        window.location.href = url;
      });
    }

    document.addEventListener('DOMContentLoaded', function() {


      // 1. Intercept sidebar catalog links clicks to load instantly via AJAX
      document.addEventListener('click', function(e) {
        const link = e.target.closest('#catalog-section .col-lg-3 a');
        if (link && link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
          e.preventDefault();
          const linkUrl = new URL(link.href, window.location.origin);
          linkUrl.searchParams.delete('q');
          linkUrl.searchParams.delete('search');
          linkUrl.searchParams.delete('s');
          loadProductsAjax(linkUrl.toString());
        }
      });

      // Accordion Toggle for Categories
      document.addEventListener('click', function(e) {
        const btn = e.target.closest('.category-accordion-btn');
        if (btn) {
          e.preventDefault();
          const targetId = btn.getAttribute('data-target');
          const targetGroup = document.getElementById(targetId);
          if (targetGroup) {
            const isHidden = targetGroup.classList.contains('d-none');
            
            // Tutup semua sub-kategori lain yang sedang terbuka
            document.querySelectorAll('.sub-category-group').forEach(group => {
              if (group.id !== targetId) {
                group.classList.add('d-none');
              }
            });
            document.querySelectorAll('.category-accordion-btn').forEach(otherBtn => {
              if (otherBtn !== btn) {
                otherBtn.classList.remove('is-active');
                const otherChevron = otherBtn.querySelector('.chevron-icon');
                if (otherChevron) {
                  otherChevron.classList.replace('bi-chevron-down', 'bi-chevron-right');
                }
              }
            });

            // Toggle status active pada kategori yang diklik
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

      // 2. Popstate listener to handle browser back & forward buttons instantly
      window.addEventListener('popstate', function() {
        loadProductsAjax(window.location.href, false);
      });

      // 3. Dynamic Server-Side Search (AJAX Debounced Search across full Database)
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
          if (query) {
            currentUrl.searchParams.set('s', query);
          } else {
            currentUrl.searchParams.delete('s');
          }
          currentUrl.searchParams.delete('page');
          loadProductsAjax(currentUrl.toString());
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
            if (query) {
              currentUrl.searchParams.set('s', query);
            } else {
              currentUrl.searchParams.delete('s');
            }
            currentUrl.searchParams.delete('page');
            loadProductsAjax(currentUrl.toString());
          }, 350);
        });

        // Set cursor to end of input if search parameter active
        if (localSearch.value && document.activeElement === localSearch) {
          const len = localSearch.value.length;
          localSearch.setSelectionRange(len, len);
        }
      }
    });
  </script>
  @endpush
@endsection