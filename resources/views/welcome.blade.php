@extends('layouts.app')

@section('title', 'PT Prolabios Mitra Analitika | Solusi Laboratorium Terpercaya')

@section('preload')
  @php
    $firstHero = $homeData['hero_images'][0] ?? 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
  @endphp
  <link rel="preload" as="image" href="{{ $firstHero }}" fetchpriority="high">
@endsection

@section('content')
  <!-- 1. Hero Section -->
  @include('partials.home-hero')

  <!-- 2. Trusted Principals Marquee -->
  @include('partials.home-principals')

  <!-- 3. Value Pillars Grid (Bento) -->
  @include('partials.home-bento')

  <!-- 4. Interactive Sector Finder -->
  @include('partials.home-focus')

  <!-- 5. Bestseller Showcase (Asymmetric Lead Product Grid) -->
  <section class="section-spacious typo-products-section" style="border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <h2 class="typo-section-title">Produk &amp; Reagen Unggulan</h2>
          <p class="typo-section-sub">Instrumen teruji dan media kultur standar farmakope siap pakai untuk kebutuhan pengujian lab.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/produk') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            Lihat Semua Produk <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 align-items-stretch">
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
          @foreach($featuredProducts as $idx => $prod)
            @php $isLead = ($idx === 0); @endphp
            <div class="col">
              <div class="card h-100 product-card border-0 @if($isLead) position-relative @endif"
                   style="view-transition-name: prod-card-{{ Str::slug($prod['title']) }}; @if($isLead) border: 1px solid rgba(255, 73, 80, 0.4) !important; background: linear-gradient(180deg, rgba(255,73,80,0.06) 0%, rgba(18,18,20,0.98) 100%); @endif">

                @if($isLead)
                  <div class="position-absolute top-0 start-0 m-3 z-2">
                    <span class="badge bg-danger text-white px-2 py-1 font-monospace" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                      <i class="bi bi-star-fill me-1"></i> REKOMENDASI QC
                    </span>
                  </div>
                @endif

                <div class="img-wrap">
                  <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Produk laboratorium" loading="lazy" decoding="async">
                </div>

                <div class="card-body p-4 d-flex flex-column">
                  <div class="d-flex flex-wrap gap-1 align-items-center mb-2">
                    @if(!empty($prod['catalog']))
                      <div class="product-cat-code">
                        CAT. {{ $prod['catalog'] }}
                      </div>
                    @endif
                    @if(!empty($prod->principal))
                      <span class="badge bg-secondary bg-opacity-25 text-white-50 border border-secondary border-opacity-25 py-1 px-2" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.5px;">
                        <i class="bi bi-building me-1" style="color: var(--color-accent);"></i>{{ $prod->principal->name }}
                      </span>
                    @endif
                  </div>

                  <h3 class="card-title fs-6 fw-semibold mb-2" style="line-height: 1.4;">
                    <a href="{{ product_url($prod) }}" class="product-card-link" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">{{ $prod['title'] }}</a>
                  </h3>

                  <p class="product-card-desc mb-3 flex-grow-1 text-white-50" style="font-size: 0.82rem; line-height: 1.5;">
                    {{ Str::limit(str_replace('-', ' ', $prod['sub_category'] ?? $prod['category'] ?? ''), 65) ?: 'Instrumen dan reagen analitika standar pengujian laboratorium' }}
                  </p>

                  <div class="mt-auto pt-3 border-top border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                    <a href="{{ product_url($prod) }}" class="product-card-action text-decoration-none fw-medium" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">
                      Detail &amp; Spek <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <span class="text-white-50 small font-monospace"><i class="bi bi-file-earmark-check text-accent"></i> COA</span>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">Produk unggulan sedang diperbarui.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- 6. Technical Insights & Articles -->
  @include('partials.home-news')

  <!-- 7. Bottom Conversion Banner -->
  <section class="hitech-final-banner">
    <div class="container text-center py-2">
      <div class="mb-3">
        <span class="typo-pill-outline">{{ $homeData['cta_banner_badge'] ?? 'DUKUNGAN PENGADAAN' }}</span>
      </div>
      <h2 class="hitech-final-title">{{ $homeData['cta_banner_title'] ?? 'Butuh penawaran khusus atau proyek pengadaan?' }}</h2>
      <p class="hitech-final-sub">{{ $homeData['cta_banner_sub'] ?? 'Tim sales kami siap membantu spesifikasi alat, ketersediaan stok, dan dokumen pendukung.' }}</p>
      
      <div class="d-flex flex-column align-items-center gap-3 mt-4">
        <a href="{{ url($homeData['cta_banner_btn_url'] ?? '/kontak') }}" class="typo-btn-link">
          {{ $homeData['cta_banner_btn_text'] ?? 'Hubungi Sales / Minta Penawaran' }} <i class="bi bi-arrow-right ms-2"></i>
        </a>
        <div class="d-flex align-items-center gap-2 text-white-50 mt-1" style="font-size: 0.8rem;">
          <span>Sudah memilih produk?</span>
          <a href="{{ url('/cart') }}" class="text-accent text-decoration-none fw-medium">
            <i class="bi bi-cart3 me-1"></i> Buka Keranjang RFQ
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tabBtns = document.querySelectorAll('.hitech-tab-btn');
      const tabPanels = document.querySelectorAll('.hitech-tab-panel');

      tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const target = this.getAttribute('data-target');

          tabBtns.forEach(b => b.classList.remove('active'));
          tabPanels.forEach(p => p.classList.remove('active'));

          this.classList.add('active');
          const activePanel = document.getElementById('panel-' + target);
          if (activePanel) {
            activePanel.classList.add('active');
          }
        });
      });
    });
  </script>
@endpush
