@extends('layouts.app')

@section('title', 'Katalog Produk | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['products_banner_image'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  @include('partials.subpage-hero', [
    'badge' => '<i data-lucide="package" class="me-1"></i> KATALOG PRODUK',
    'title' => 'Produk & Instrumen Laboratorium',
    'subtitle' => $siteSettings['products_subtitle'] ?? 'Katalog lengkap instrumen analitika, media kultur mikrobiologi, dan perlengkapan pengujian bersertifikasi resmi Prolabios.'
  ])

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
                @include('partials.product-card', ['prod' => $prod])
              @endforeach
            @else
              <div class="col-12 text-center p-5 card">
                <i data-lucide="package" style="font-size: 2.5rem; color: var(--nb-muted); display: block; margin-bottom: 16px;"></i>
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
@endsection
