@extends('layouts.app')

@section('title', 'Katalog Produk | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['products_banner_image'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['products_banner_image'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['products_title'] ?? 'Produk & Instrumen' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['products_subtitle'] ?? 'Katalog lengkap produk laboratorium dari Prolabios' }}</p>
    </div>
  </div>

  <!-- Product Content -->
  <section class="py-5 bg-light" id="catalog-section">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
          <!-- Mobile Filter Toggle Button -->
          <button class="btn btn-danger w-100 d-md-none mb-3 d-flex align-items-center justify-content-between py-2 px-3 rounded shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse" style="font-size: 0.95rem;">
            <span class="fw-bold"><i class="bi bi-funnel-fill me-2"></i>Filter & Kategori</span>
            <i class="bi bi-chevron-down"></i>
          </button>
          
          <!-- Collapsible Content for Mobile, always open on Medium+ screens -->
          <div class="collapse d-md-block" id="sidebarCollapse">
            <!-- BOX 1: KATEGORI UTAMA -->
            <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
              <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Kategori Produk</h2>
              <div class="list-group list-group-flush" id="produk-sidebar">
                <a href="{{ url('/produk') }}?category=all#catalog-section" 
                   class="list-group-item list-group-item-action sector-sidebar-link {{ $activeCategory === 'all' ? 'active' : '' }}">
                    Semua Kategori (All)
                </a>
                @foreach($categoriesStructure as $catKey => $catData)
                  <a href="{{ url('/produk') }}?category={{ $catKey }}#catalog-section" 
                     class="list-group-item list-group-item-action sector-sidebar-link {{ $activeCategory === $catKey ? 'active' : '' }}">
                      {{ $catData['name'] }}
                  </a>
                @endforeach
              </div>
            </div>

            <!-- BOX 2: SUB-KATEGORI (Otomatis berganti isi & menyusut sesuai Kategori Utama yang active) -->
            @if($activeCategory !== 'all' && !empty($categoriesStructure[$activeCategory]['subs']))
            <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right delay-100">
              <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">
                {{ $categoriesStructure[$activeCategory]['name'] }} — Subkategori
              </h2>
              <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                <a href="{{ url('/produk') }}?category={{ $activeCategory }}&subcategory=all#catalog-section" 
                   class="list-group-item list-group-item-action sector-sidebar-link py-2 {{ !$activeSubCategory || $activeSubCategory === 'all' ? 'active' : '' }}">
                  Semua Subkategori (All)
                </a>
                @foreach($categoriesStructure[$activeCategory]['subs'] as $subKey => $subName)
                  <a href="{{ url('/produk') }}?category={{ $activeCategory }}&subcategory={{ $subKey }}#catalog-section" 
                     class="list-group-item list-group-item-action sector-sidebar-link py-2 {{ $activeSubCategory === $subKey ? 'active' : '' }}">
                    {{ $subName }}
                  </a>
                @endforeach
              </div>
            </div>
            @endif

            <div class="p-4 rounded shadow-sm text-center animate-on-scroll animate-slide-right delay-200 sidebar-cta-box mb-4">
              <h2 class="h5 fw-bold mb-3">Butuh Bantuan?</h2>
              <p class="small mb-3">Konsultasikan kebutuhan product Anda dengan tim teknis kami.</p>
              <a href="{{ url('/kontak') }}?subjek=inquiry" class="btn w-100 fw-bold shadow-sm mb-2">Tanya Produk</a>
              <a href="{{ $siteSettings['catalog_pdf_url'] ?? 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs' }}" target="_blank" rel="noopener noreferrer" class="btn w-100 btn-outline-light btn-sm fw-bold border-2"><i class="bi bi-download me-1"></i> Unduh Katalog PDF</a>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
              <h2 class="mb-0 fw-bold" id="category-title" style="color: var(--color-secondary, #2b2d42);">
                @if($activeCategory === 'all')
                  Semua Produk (All)
                @else
                  {{ $categoriesStructure[$activeCategory]['name'] }}
                  @if($activeSubCategory && $activeSubCategory !== 'all' && isset($categoriesStructure[$activeCategory]['subs'][$activeSubCategory]))
                    — {{ $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] }}
                  @endif
                @endif
              </h2>
              <span class="text-muted small" id="category-subtitle">
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
            <div class="ms-md-auto" style="width: 100%; max-width: 320px;">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="local-search-input" class="form-control border-start-0" placeholder="Cari produk di katalog ini..." aria-label="Cari produk" value="{{ request()->query('q') ?? request()->query('s') }}">
              </div>
            </div>
          </div>
          
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 animate-on-scroll animate-slide-up" id="product-container">
            @if(isset($products) && count($products) > 0)
              @foreach($products as $prod)
              <div class="col product-card animate-on-scroll animate-slide-up delay-{{ ($loop->index % 3 + 1) * 100 }}" data-category="{{ $prod['category'] ?? '' }} {{ $prod['sector'] ?? '' }}">
                <div class="card h-100 product-card-premium border-0">
                  <div class="img-wrap">
                    <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                  </div>
                  <div class="card-body p-3">
                    @if(!empty($prod['catalog']))
                    <div class="text-muted small mb-2 fw-semibold" style="font-size: 0.75rem;">Cat. {{ $prod['catalog'] }}</div>
                    @endif
                    <h3 class="card-title fs-6 fw-bold">
                      <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none text-dark hover-primary">{{ $prod['title'] }}</a>
                    </h3>
                    <p class="card-text text-muted small mt-2 mb-3" style="font-size: 0.78rem;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 80) }}</p>
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">Lihat Detail</a>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
                <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-0">Belum ada produk spesifik di kategori ini.</p>
              </div>
            @endif
          </div>

          <div class="d-flex justify-content-center mt-5" id="dynamic-pagination">
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
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
          currentSidebar.innerHTML = newSidebar.innerHTML;
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

        // 6. Reset search input
        const localSearch = document.getElementById('local-search-input');
        if (localSearch) {
          localSearch.value = '';
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
          loadProductsAjax(link.href);
        }
      });

      // 2. Popstate listener to handle browser back & forward buttons instantly
      window.addEventListener('popstate', function() {
        loadProductsAjax(window.location.href, false);
      });

      // 3. Keep local instant search filter logic working
      const localSearch = document.getElementById('local-search-input');
      if (localSearch) {
        localSearch.addEventListener('input', function() {
          const query = this.value.trim();
          const navSearchInput = document.querySelector('.search-form input');
          if (navSearchInput) {
            navSearchInput.value = query;
          }
          if (typeof filterProducts === 'function') {
            filterProducts(query);
          } else {
            const cards = document.querySelectorAll('.product-card');
            const q = query.toLowerCase();
            cards.forEach(card => {
              if (card.textContent.toLowerCase().includes(q)) {
                card.classList.remove('hidden-by-filter');
                card.style.display = 'block';
              } else {
                card.classList.add('hidden-by-filter');
                card.style.display = 'none';
              }
            });
            if (typeof applyPagination === 'function') applyPagination(1);
          }
        });
        
        const urlParams = new URLSearchParams(window.location.search);
        const q = urlParams.get('q');
        if (q) {
          localSearch.value = q;
        }
      }
    });
  </script>
  @endpush
@endsection