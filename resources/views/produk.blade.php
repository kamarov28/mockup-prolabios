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
    </div>
  </section>

  <!-- Product Content -->
  <section class="section-spacious nb-section" id="catalog-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">
        <!-- Sidebar / Filter Column (Order 1 on mobile/tablet for easy filtering, Order 2 on desktop) -->
        <div class="col-12 col-lg-4 order-1 order-lg-2">
          @include('partials.catalog-sidebar')
        </div>

        <!-- Main Product Content Column (Order 2 on mobile/tablet, Order 1 on desktop) -->
        <div class="col-12 col-lg-8 order-2 order-lg-1">
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
            <div class="row row-cols-1 row-cols-sm-2 g-3 g-md-4" id="product-container">
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
                        <span class="nb-badge-sm" style="font-size: 0.68rem; padding: 2px 6px;">
                          <i class="bi bi-building me-1 text-primary"></i>{{ $prod->principal->name }}
                        </span>
                      @endif
                    </div>
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                      <h3 class="card-title fs-6 fw-semibold mb-0 flex-grow-1" style="line-height: 1.4;">
                        <a href="{{ product_url($prod) }}" class="product-card-link">{{ $prod['title'] }}</a>
                      </h3>
                      @if(!empty($prod->principal) && !empty($prod->principal->logo))
                        <div class="product-principal-logo flex-shrink-0" title="Prinsipal: {{ $prod->principal->name }}" style="padding: 2px 4px; background: #FFFFFF; border: 1.5px solid var(--nb-ink); border-radius: 4px; box-shadow: 1.5px 1.5px 0 var(--nb-ink); max-height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                          <img src="{{ str_starts_with($prod->principal->logo, 'http') || str_starts_with($prod->principal->logo, '/') ? $prod->principal->logo : asset('storage/' . $prod->principal->logo) }}"
                               alt="Logo {{ $prod->principal->name }}"
                               loading="lazy"
                               style="max-height: 22px; max-width: 55px; width: auto; height: auto; object-fit: contain;">
                        </div>
                      @endif
                    </div>
                    <p class="product-card-desc mb-3 flex-grow-1">
                      {{ Str::limit(str_replace('-', ' ', $prod['sub_category'] ?? $prod['category'] ?? ''), 75) ?: 'Produk laboratorium' }}
                    </p>

                    <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between nb-card-foot" style="border-color: rgba(30,30,30,0.12) !important;">
                      <a href="{{ product_url($prod) }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.82rem; padding: 8px 14px; font-weight: 700;" aria-label="Detail dan spesifikasi {{ $prod['title'] }}">
                        Detail &amp; Spek <i class="bi bi-arrow-right ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="col-12 text-center p-5 card">
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
      </div>
    </div>
  </section>



  @push('scripts')
  @include('partials.gsap-loader')
  @endpush
@endsection
