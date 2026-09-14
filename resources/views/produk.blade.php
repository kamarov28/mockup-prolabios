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
          <!-- Category Title Header & View Switcher -->
          <div class="mb-4 pb-3 border-bottom" style="border-color: rgba(30,30,30,0.12) !important;">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
              <div>
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

              <!-- View Switcher (Grid vs Tabel) -->
              <div class="catalog-view-switcher mt-1" role="group" aria-label="Mode Tampilan Katalog">
                <button type="button" class="catalog-view-toggle active" data-view="grid" id="btn-view-grid" title="Tampilan Kotak (Grid)" aria-label="Grid View">
                  <i data-lucide="layout-grid"></i>
                  <span class="d-none d-sm-inline ms-1">Grid</span>
                </button>
                <button type="button" class="catalog-view-toggle" data-view="table" id="btn-view-table" title="Tampilan Tabel (Table View)" aria-label="Table View">
                  <i data-lucide="table"></i>
                  <span class="d-none d-sm-inline ms-1">Tabel</span>
                </button>
              </div>
            </div>
          </div>

          <div class="ajax-loading-wrap" id="product-ajax-wrap" aria-busy="false">
            <div class="ajax-loading-overlay" aria-hidden="true"><div class="ajax-spinner" role="status" aria-label="Memuat"></div></div>
            
            <div id="product-container">
            @if(isset($products) && (is_array($products) || $products instanceof \Countable) && count($products) > 0)
              <!-- 1. Grid Cards View -->
              <div class="catalog-grid-panel row row-cols-1 row-cols-sm-2 g-3 g-md-4">
                @foreach($products as $prod)
                  @include('partials.product-card', ['prod' => $prod])
                @endforeach
              </div>

              <!-- 2. High-Density Laboratory Catalog Table -->
              <div class="catalog-table-panel d-none">
                <div class="table-responsive card border-0 shadow-none" style="border: var(--nb-border) !important; border-radius: var(--nb-radius-lg); overflow: hidden; background: var(--nb-card);">
                  <table class="table table-hover align-middle mb-0 catalog-precision-table">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 130px;" class="table-col-catalog">KODE CAT.</th>
                        <th scope="col">PRODUK &amp; SPESIFIKASI</th>
                        <th scope="col" class="d-none d-md-table-cell" style="width: 170px;">KATEGORI</th>
                        <th scope="col" style="width: 140px;">STATUS</th>
                        <th scope="col" class="text-end" style="width: 140px;">AKSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($products as $prod)
                        @php
                          $stockVal = (int)($prod['stock'] ?? 0);
                          $cardUrl = product_url($prod);
                          $cardImage = !empty($prod['image']) ? $prod['image'] : asset('images/placeholder.svg');
                          $cleanDesc = !empty($prod['description']) ? strip_tags(html_entity_decode($prod['description'])) : '';
                        @endphp
                        <tr>
                          <td class="table-col-catalog">
                            @if(!empty($prod['catalog']))
                              <span class="product-cat-code" title="Klik untuk menyalin">CAT. {{ $prod['catalog'] }}</span>
                            @else
                              <span class="text-muted small">—</span>
                            @endif
                          </td>
                          <td>
                            <div class="d-flex align-items-center gap-3">
                              <img src="{{ $cardImage }}" 
                                   alt="{{ $prod['title'] }}" 
                                   class="d-none d-sm-block rounded" 
                                   style="width: 44px; height: 44px; object-fit: cover; background: var(--nb-bg-soft); border: 1px solid #E5E7EB; flex-shrink: 0;"
                                   loading="lazy">
                              <div>
                                <a href="{{ $cardUrl }}" class="catalog-table-title text-decoration-none d-block mb-1">
                                  {{ $prod['title'] }}
                                </a>
                                <div class="d-flex align-items-center gap-2 flex-wrap text-muted small" style="font-size: 0.78rem;">
                                  @if(!empty($prod->principal))
                                    <span class="fw-semibold text-secondary">{{ $prod->principal->name }}</span>
                                    <span>·</span>
                                  @endif
                                  <span>{{ Str::limit($cleanDesc, 60) ?: 'Spesifikasi standar lab' }}</span>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="d-none d-md-table-cell">
                            <span class="text-muted small text-capitalize">
                              {{ str_replace('-', ' ', $prod['category'] ?? 'Umum') }}
                            </span>
                          </td>
                          <td>
                            @if($stockVal > 0)
                              <span class="nb-badge-stock" title="Stok siap kirim">
                                Siap ({{ $stockVal }})
                              </span>
                            @else
                              <span class="nb-badge-stock nb-badge-stock--empty" title="Pesanan khusus / indent">
                                Indent
                              </span>
                            @endif
                          </td>
                          <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                              <form action="{{ route('cart.add') }}" method="POST" class="m-0 d-inline-block">
                                @csrf
                                <input type="hidden" name="id" value="{{ $prod['id'] ?? '' }}">
                                <input type="hidden" name="title" value="{{ $prod['title'] }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="nb-btn nb-btn-primary py-1 px-2" style="font-size: 0.78rem; border-radius: 6px;" title="Tambahkan ke Pengajuan Penawaran">
                                  <i data-lucide="plus" style="width: 13px; height: 13px;"></i> RFQ
                                </button>
                              </form>
                              <a href="{{ $cardUrl }}" class="nb-btn nb-btn-ghost py-1 px-2 text-decoration-none" style="font-size: 0.78rem; border-radius: 6px;" title="Lihat Spesifikasi Lengkap">
                                <i data-lucide="arrow-right" style="width: 13px; height: 13px;"></i>
                              </a>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @else
              <div class="col-12 text-center p-5 card border-0" style="border: var(--nb-border) !important; border-radius: var(--nb-radius-lg);">
                <i data-lucide="package-search" style="font-size: 2.5rem; color: var(--nb-muted); display: block; margin-bottom: 16px;"></i>
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
