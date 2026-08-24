@extends('layouts.app')

@section('title', 'PT Prolabios Mitra Analitika | Precision Laboratory Solutions')

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

  <!-- 5. Bestseller Showcase -->
  <section class="section-spacious typo-products-section" style="border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <h2 class="typo-section-title">Featured Instruments &amp; Reagents</h2>
          <p class="typo-section-sub">High-reliability analytical devices and reagents designed to streamline your laboratory workflow.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/produk') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            View Full Product Catalog <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
          @foreach($featuredProducts as $prod)
            <div class="col">
              <div class="card h-100 product-card-premium border-0" style="view-transition-name: prod-card-{{ Str::slug($prod['title']) }};">
                <div class="img-wrap">
                  <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Laboratory Product &amp; Analytical Instrument" loading="lazy" decoding="async">
                </div>
                <div class="card-body p-4 d-flex flex-column">
                  @if(!empty($prod['catalog']))
                    <div class="product-cat-code mb-2">
                      CAT. {{ $prod['catalog'] }}
                    </div>
                  @endif
                  <h3 class="card-title fs-6 fw-semibold mb-2" style="line-height: 1.4;">
                    <a href="{{ url('/produk/detail') }}?id={{ $prod['id'] }}" class="product-card-link" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">{{ $prod['title'] }}</a>
                  </h3>
                  <div class="mt-auto pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ url('/produk/detail') }}?id={{ $prod['id'] }}" class="product-card-action text-decoration-none" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">View Specs <i class="bi bi-arrow-right ms-1"></i></a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">Featured products are currently being updated.</p>
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
        <span class="typo-pill-outline">{{ $homeData['cta_banner_badge'] ?? 'TECHNICAL PROCUREMENT SUPPORT' }}</span>
      </div>
      <h2 class="hitech-final-title">{{ $homeData['cta_banner_title'] ?? 'Require Custom Procurement or Project Quote?' }}</h2>
      <p class="hitech-final-sub">{{ $homeData['cta_banner_sub'] ?? 'Our application specialists and technical sales team assist with instrument specifications, bulk availability, and compliance documentation.' }}</p>
      
      <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mt-4">
        <a href="{{ url($homeData['cta_banner_btn_url'] ?? '/kontak') }}" class="typo-btn-link">
          {{ $homeData['cta_banner_btn_text'] ?? 'Contact Sales / Request Quote' }} <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Interactive Sector Finder Tab Logic
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
