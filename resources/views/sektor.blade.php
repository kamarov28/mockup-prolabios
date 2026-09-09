@extends('layouts.app')

@section('title', 'Sektor Industri | PROLABIOS')
@section('meta_description', 'Sektor industri yang dilayani Prolabios - Farmasi, Food & Beverage, Mikrobiologi, dan berbagai industri lain dengan solusi laboratorium terpercaya.')
@section('meta_keywords', 'sektor industri, farmasi, food beverage, mikrobiologi, industri, solusi laboratorium, prolabios')
@section('canonical_url', url('/sektor'))

@section('content')
  <!-- Page Header (Soft Neo-Brutalism Hero Banner, follows Profil & Katalog) -->
  <section class="profil-hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9">
          <span class="nb-badge">
            <i class="bi bi-diagram-3 me-1"></i> SEKTOR INDUSTRI
          </span>
          <h1 class="profil-main-title">
            Solusi Pengujian &amp; Analisis Lintas Sektor
          </h1>
          <p class="profil-main-subtitle">
            Mendukung akurasi kendali mutu (QC/QA), riset aplikasi, dan kepatuhan regulasi di industri farmasi, makanan &amp; minuman, agrikultur, hingga pengolahan air di seluruh Indonesia.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Sektor Content -->
  <section class="section-spacious nb-section" id="sektor-nav">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Sidebar (Left, Order 1 on Desktop to browse sectors easily, Order 2 on Mobile/Tablet) -->
        <div class="col-12 col-lg-4 order-2 order-lg-1" id="sektor-sidebar">
          {{-- $activeSector is passed from PageController::sektor() --}}

          <!-- Sector Selector Card -->
          <div class="card p-4 mb-4">
            <h3 class="profil-sidebar-title mb-3">
              <i class="bi bi-grid-fill me-2 text-primary"></i> Pilih Sektor Industri
            </h3>
            <nav class="layanan-sidebar-nav">
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                  <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav"
                     class="layanan-sidebar-link d-flex align-items-center justify-content-between {{ $activeSector == $sec['id'] ? 'is-active' : '' }}"
                     data-sector-id="{{ $sec['id'] }}">
                    <span>{{ $sec['name'] }}</span>
                    <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
                  </a>
                @endforeach
              @else
                <a href="#" class="layanan-sidebar-link d-flex align-items-center justify-content-between is-active">
                  <span>Brewing</span>
                  <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
                </a>
              @endif
            </nav>
          </div>

          <!-- Sidebar Card 2: B2B Consultation CTA Box -->
          <div class="profil-cta-box p-4">
            <span class="nb-badge mb-3">B2B CONSULTATION</span>
            <h3 class="profil-sidebar-title">Butuh Solusi Spesifik?</h3>
            <p>Diskusikan alur pengujian laboratorium atau spesifikasi instrumen industri Anda dengan tim spesialis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="nb-btn nb-btn-primary w-100 justify-content-center mb-2">
              Konsultasi Tim Teknis <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.82rem;">
              <i class="bi bi-box-seam me-1"></i> Jelajahi Seluruh Katalog
            </a>
          </div>
        </div>

        <!-- Main Content (Right, Order 2 on Desktop, Order 1 on Mobile/Tablet) -->
        <div class="col-12 col-lg-8 order-1 order-lg-2" id="sektor-main">
          @php
            $currentData = null;
            if (isset($sectors) && count($sectors) > 0) {
                foreach ($sectors as $sec) {
                    if ($sec['id'] == $activeSector) {
                        $currentData = $sec;
                        break;
                    }
                }
                if (!$currentData) {
                    $currentData = $sectors[0];
                    $activeSector = $currentData['id'];
                }
            }

            $descriptionParagraphs = $currentData['description'] ?? [];
            if (empty($descriptionParagraphs)) {
                $descriptionParagraphs = [
                    "Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>" . ($currentData['name'] ?? '') . "</strong>. Seluruh produk kami dikembangkan dengan standar kualitas tertinggi guna menjamin keandalan, akurasi, dan kepatuhan terhadap standar industri terkini.",
                    "Jelajahi rangkaian produk spesifik yang kami tawarkan, mulai dari reagen, instrumen analitik, hingga media kultur yang dirancang khusus untuk memenuhi kebutuhan pengujian harian laboratorium maupun lini produksi Anda."
                ];
            }

            $defaultImage = 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
            $currentImage = $currentData['image'] ?? $defaultImage;
            if ($currentImage && strpos($currentImage, '/') === 0) {
                $currentImage = asset($currentImage);
            }
          @endphp

          @if($currentData)
            <!-- Sector Detail Card -->
            <div class="card p-4 p-md-5 mb-5">
              <!-- Sector Hero Image -->
              <div class="profil-hero-img mb-4">
                <img src="{{ $currentImage }}" alt="{{ $currentData['name'] }} Sector" class="w-100" style="aspect-ratio: 16/9; width: 100%; height: auto; object-fit: cover; display: block; max-height: 440px;" loading="lazy" decoding="async">
              </div>

              <!-- Sector Title & Description -->
              <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <span class="nb-badge-sm">
                  <i class="bi bi-tag-fill me-1 text-primary"></i> Sektor Terpilih
                </span>
                <span class="nb-mono text-muted small">AKREDITASI &amp; REGULASI INDUSTRI</span>
              </div>

              <h2 class="profil-section-title mb-3">{{ $currentData['name'] }}</h2>
              @foreach($descriptionParagraphs as $desc)
                <p class="profil-body-text mb-3" style="color: var(--nb-ink); line-height: 1.7;">{!! \App\Services\DataService::sanitizeHtml($desc) !!}</p>
              @endforeach

              <div class="pt-3 border-top d-flex flex-wrap gap-2 mt-4" style="border-color: rgba(30,30,30,0.12) !important;">
                <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Instrumen Terkalibrasi</span>
                <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Jaminan COA &amp; MSDS</span>
                <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Penanganan Rantai Dingin</span>
              </div>
            </div>

            <!-- Product Table Card -->
            <div class="mb-5">
              <div class="mb-4 pb-2 border-bottom" style="border-color: rgba(30,30,30,0.15) !important;">
                <h3 class="profil-section-title mb-1" style="font-size: 1.45rem !important;">
                  Daftar Produk &amp; Instrumen Sektor {{ $currentData['name'] }}
                </h3>
                <span class="text-muted d-block" style="font-family: var(--font-body); font-size: 0.9rem;">
                  Rangkaian instrumen, reagen, dan perlengkapan khusus untuk operasional sektor ini.
                </span>
              </div>

              <!-- Mobile Swipe Indicator -->
              <div class="d-md-none text-end mb-2">
                <span class="nb-badge-sm" style="background: var(--nb-accent); color: var(--nb-ink);">
                  <i class="bi bi-arrow-left-right me-1"></i> Geser Tabel
                </span>
              </div>

              <div class="table-responsive" id="sektor-product-table-wrap">
                <table class="table custom-table align-middle mb-0" style="min-width: 650px;">
                  <thead>
                    <tr>
                      <th style="width: 22%;">Katalog</th>
                      <th style="width: 38%;">Produk</th>
                      <th style="width: 40%;">Aplikasi &amp; Fungsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $hasProducts = false; @endphp
                    @if(isset($products) && count($products) > 0)
                      @foreach($products as $prod)
                        @php $hasProducts = true; @endphp
                        <tr>
                          <td>
                            @if(!empty($prod['catalog']))
                              <span class="product-cat-code" style="font-size: 0.72rem;">CAT. {{ $prod['catalog'] }}</span>
                            @else
                              <span class="text-muted small">-</span>
                            @endif
                          </td>
                          <td>
                            <div class="d-flex align-items-center justify-content-between gap-2">
                              <a href="{{ product_url($prod) }}" class="text-decoration-none fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">
                                {{ $prod['title'] }}
                              </a>
                              @if(!empty($prod->principal) && !empty($prod->principal->logo))
                                <div class="flex-shrink-0" title="Prinsipal: {{ $prod->principal->name }}" style="padding: 1px 3px; background: #FFFFFF; border: 1px solid var(--nb-ink); border-radius: 3px;">
                                  <img src="{{ str_starts_with($prod->principal->logo, 'http') || str_starts_with($prod->principal->logo, '/') ? $prod->principal->logo : asset('storage/' . $prod->principal->logo) }}"
                                       alt="Logo {{ $prod->principal->name }}"
                                       loading="lazy"
                                       style="max-height: 18px; max-width: 45px; width: auto; height: auto; object-fit: contain;">
                                </div>
                              @endif
                            </div>
                          </td>
                          <td style="color: var(--nb-muted); font-size: 0.88rem; line-height: 1.5;">
                            {{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 140) }}
                          </td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>

              <div id="sektor-pagination-or-empty">
                @if(!$hasProducts)
                  <div class="text-center p-5 card mt-3" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow-sm);">
                    <i class="bi bi-inbox fs-1 text-muted mb-2"></i>
                    <p class="mb-2 fw-semibold" style="color: var(--nb-ink);">Belum ada produk terdaftar untuk sektor ini.</p>
                    <p class="text-muted small mb-3">Silakan hubungi tim kami untuk ketersediaan katalog indent atau jelajahi katalog utama.</p>
                    <div>
                      <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost" style="font-size: 0.82rem;">
                        <i class="bi bi-box-seam me-1"></i> Buka Katalog Utama
                      </a>
                    </div>
                  </div>
                @else
                  <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->except('page'))->fragment('sektor-nav')->links('partials.catalog-pagination') }}
                  </div>
                @endif
              </div>
            </div>

            <!-- Related Products Section -->
            <div class="mt-5 pt-3">
              <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 pb-2 border-bottom" style="border-color: rgba(30,30,30,0.15) !important;">
                <div>
                  <h3 class="profil-section-title mb-1" style="font-size: 1.45rem !important;">
                    Rekomendasi Produk Pilihan
                  </h3>
                  <span class="text-muted d-block" style="font-family: var(--font-body); font-size: 0.88rem;">
                    Instrumen dan reagen yang sering digunakan untuk kebutuhan industri ini.
                  </span>
                </div>
                <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost" style="font-size: 0.82rem; padding: 6px 14px;">
                  Semua Produk <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>

              <div class="row row-cols-1 row-cols-md-3 g-4" id="sektor-related">
                @php
                  $related = $relatedProducts ?? collect();
                  if ($related->isEmpty() && isset($products) && count($products) > 0) {
                      $related = collect($products)->take(3);
                  }
                @endphp
                @foreach($related as $prod)
                  <div class="col">
                    <div class="card h-100 product-card border-0">
                      <div class="img-wrap">
                        <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Produk Sektor" loading="lazy" decoding="async">
                      </div>
                      <div class="card-body p-3 d-flex flex-column">
                        @if(!empty($prod['catalog']))
                          <div class="product-cat-code mb-2" style="font-size: 0.65rem;">CAT. {{ $prod['catalog'] }}</div>
                        @endif
                        <h4 class="card-title fs-6 fw-bold mb-2">
                          <a href="{{ product_url($prod) }}" class="text-decoration-none" style="color: var(--nb-ink);">{{ $prod['title'] }}</a>
                        </h4>
                        <p class="mb-3 flex-grow-1" style="font-size: 0.8rem; color: var(--nb-muted); line-height: 1.5;">
                          {{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 75) ?: 'Instrumen dan solusi laboratorium resmi.' }}
                        </p>
                        <div class="mt-auto pt-2 border-top" style="border-color: rgba(30,30,30,0.12) !important;">
                          <a href="{{ product_url($prod) }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.78rem; padding: 6px 10px;">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  @include('partials.gsap-loader')
  @endpush
@endsection
