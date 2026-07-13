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
          
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Kategori Produk</h2>
            <div class="list-group list-group-flush" id="produk-sidebar">
              <a href="{{ url('/produk') }}?kategori=microbiology#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link active">Microbiology</a>
              <a href="{{ url('/produk') }}?kategori=reference-standards#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Reference Standards</a>
              <a href="{{ url('/produk') }}?kategori=device#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Device</a>
              <a href="{{ url('/produk') }}?kategori=instruments#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Instruments</a>
            </div>
          </div>

          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right delay-100">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Microbiology — Subkategori</h2>
            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
              <a href="{{ url('/produk') }}?kategori=food-safety#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Food Safety</a>
              <a href="{{ url('/produk') }}?kategori=antimicrobial-susceptibility-testing#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Antimicrobial Susceptibility Testing</a>
              <a href="{{ url('/produk') }}?kategori=microbiological-identification#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microbiological Identification</a>
              <a href="{{ url('/produk') }}?kategori=bactobank#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microorganisms Preservation System (BactoBank)</a>
              <a href="{{ url('/produk') }}?kategori=microbial-staining-and-fixatives#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microbial Staining &amp; Fixatives</a>
              <a href="{{ url('/produk') }}?kategori=consumables#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Consumables</a>
              <a href="{{ url('/produk') }}?kategori=mic-test-strip#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">MIC Test Strip</a>
              <a href="{{ url('/produk') }}?kategori=qc-organisms#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">QC Organisms</a>
              <a href="{{ url('/produk') }}?kategori=dip-slide#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Dip-slide</a>
              <a href="{{ url('/produk') }}?kategori=chemical-indicator#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Chemical Indicator</a>
              <a href="{{ url('/produk') }}?kategori=latex-agglutination-kits#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Latex Agglutination Kits</a>
              <a href="{{ url('/produk') }}?kategori=ready-to-use-culture-media#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Ready To Use Culture Media</a>
              <a href="{{ url('/produk') }}?kategori=biological-indicators#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Biological Indicators</a>
              <a href="{{ url('/produk') }}?kategori=dehydrated-culture-media#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Dehydrated Culture Media</a>
              <a href="{{ url('/produk') }}?kategori=immunology#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Immunology</a>
              <a href="{{ url('/produk') }}?kategori=endotoxin#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Endotoxin</a>
            </div>
          </div>

          <div class="p-4 rounded shadow-sm text-center animate-on-scroll animate-slide-right delay-200 sidebar-cta-box">
            <h2 class="h5 fw-bold mb-3">Butuh Bantuan?</h2>
            <p class="small mb-3">Konsultasikan kebutuhan product Anda dengan tim teknis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=inquiry" class="btn w-100 fw-bold shadow-sm">Tanya Produk</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
              <h2 class="mb-0 fw-bold" id="category-title" style="color: var(--color-secondary, #2b2d42);">Microbiology</h2>
              <span class="text-muted small" id="category-subtitle">Menampilkan hasil untuk Microbiology</span>
            </div>
            <div class="ms-md-auto" style="width: 100%; max-width: 320px;">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="local-search-input" class="form-control border-start-0" placeholder="Cari produk di katalog ini..." aria-label="Cari produk">
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
                    <p class="card-text text-muted small mt-2 mb-3" style="font-size: 0.78rem;">{{ Str::limit($prod['description'] ?? '', 80) }}</p>
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">Lihat Detail</a>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12">
                <p class="text-muted">Sedang mengambil data produk, silakan muat ulang halaman ini dalam beberapa saat...</p>
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
    document.addEventListener('DOMContentLoaded', function() {
      const localSearch = document.getElementById('local-search-input');
      if (localSearch) {
        localSearch.addEventListener('input', function() {
          const query = this.value.trim();
          const navSearchInput = document.querySelector('.search-form input');
          if (navSearchInput) {
            navSearchInput.value = query;
          }
          // Call the global filter function in js/app.js if it is ready
          if (typeof filterProducts === 'function') {
            filterProducts(query);
          } else {
            // Fallback: manually loop cards if global function is loading
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
        
        // Auto-populate local search from URL query parameter
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

@extends('layouts.app')

@section('title', 'Katalog Produk | PROLABIOS')
@section('meta_description', 'Katalog lengkap produk laboratorium Prolabios - Media kultur, instrumen, chemicals, dan consumables untuk kebutuhan analitika dan mikrobiologi.')
@section('meta_keywords', 'katalog produk, laboratorium, media kultur, instrumen, chemicals, consumables, prolabios, alat lab')
@section('canonical_url', url('/produk'))

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
          
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Kategori Produk</h2>
            <div class="list-group list-group-flush" id="produk-sidebar">
              <a href="{{ url('/produk') }}?kategori=microbiology#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link active">Microbiology</a>
              <a href="{{ url('/produk') }}?kategori=reference-standards#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Reference Standards</a>
              <a href="{{ url('/produk') }}?kategori=device#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Device</a>
              <a href="{{ url('/produk') }}?kategori=instruments#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link">Instruments</a>
            </div>
          </div>

          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right delay-100">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Microbiology — Subkategori</h2>
            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
              <a href="{{ url('/produk') }}?kategori=food-safety#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Food Safety</a>
              <a href="{{ url('/produk') }}?kategori=antimicrobial-susceptibility-testing#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Antimicrobial Susceptibility Testing</a>
              <a href="{{ url('/produk') }}?kategori=microbiological-identification#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microbiological Identification</a>
              <a href="{{ url('/produk') }}?kategori=bactobank#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microorganisms Preservation System (BactoBank)</a>
              <a href="{{ url('/produk') }}?kategori=microbial-staining-and-fixatives#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Microbial Staining &amp; Fixatives</a>
              <a href="{{ url('/produk') }}?kategori=consumables#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Consumables</a>
              <a href="{{ url('/produk') }}?kategori=mic-test-strip#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">MIC Test Strip</a>
              <a href="{{ url('/produk') }}?kategori=qc-organisms#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">QC Organisms</a>
              <a href="{{ url('/produk') }}?kategori=dip-slide#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Dip-slide</a>
              <a href="{{ url('/produk') }}?kategori=chemical-indicator#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Chemical Indicator</a>
              <a href="{{ url('/produk') }}?kategori=latex-agglutination-kits#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Latex Agglutination Kits</a>
              <a href="{{ url('/produk') }}?kategori=ready-to-use-culture-media#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Ready To Use Culture Media</a>
              <a href="{{ url('/produk') }}?kategori=biological-indicators#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Biological Indicators</a>
              <a href="{{ url('/produk') }}?kategori=dehydrated-culture-media#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Dehydrated Culture Media</a>
              <a href="{{ url('/produk') }}?kategori=immunology#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Immunology</a>
              <a href="{{ url('/produk') }}?kategori=endotoxin#catalog-section" class="list-group-item list-group-item-action sector-sidebar-link py-2">Endotoxin</a>
            </div>
          </div>

          <div class="p-4 rounded shadow-sm text-center animate-on-scroll animate-slide-right delay-200 sidebar-cta-box">
            <h2 class="h5 fw-bold mb-3">Butuh Bantuan?</h2>
            <p class="small mb-3">Konsultasikan kebutuhan product Anda dengan tim teknis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=inquiry" class="btn w-100 fw-bold shadow-sm">Tanya Produk</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
              <h2 class="mb-0 fw-bold" id="category-title" style="color: var(--color-secondary, #2b2d42);">Microbiology</h2>
              <span class="text-muted small" id="category-subtitle">Menampilkan hasil untuk Microbiology</span>
            </div>
            <div class="ms-md-auto" style="width: 100%; max-width: 320px;">
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" id="local-search-input" class="form-control border-start-0" placeholder="Cari produk di katalog ini..." aria-label="Cari produk">
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
                    <p class="card-text text-muted small mt-2 mb-3" style="font-size: 0.78rem;">{{ Str::limit($prod['description'] ?? '', 80) }}</p>
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">Lihat Detail</a>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12">
                <p class="text-muted">Sedang mengambil data produk, silakan muat ulang halaman ini dalam beberapa saat...</p>
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
    document.addEventListener('DOMContentLoaded', function() {
      const localSearch = document.getElementById('local-search-input');
      if (localSearch) {
        localSearch.addEventListener('input', function() {
          const query = this.value.trim();
          const navSearchInput = document.querySelector('.search-form input');
          if (navSearchInput) {
            navSearchInput.value = query;
          }
          // Call the global filter function in js/app.js if it is ready
          if (typeof filterProducts === 'function') {
            filterProducts(query);
          } else {
            // Fallback: manually loop cards if global function is loading
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
        
        // Auto-populate local search from URL query parameter
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
