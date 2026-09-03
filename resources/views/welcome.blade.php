@extends('layouts.app')

@section('title', 'PT Prolabios Mitra Analitika | Solusi Laboratorium Terpercaya')

@section('preload')
  @php
    $firstHero = $homeData['hero_images'][0] ?? 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
  @endphp
  <link rel="preload" as="image" href="{{ $firstHero }}" fetchpriority="high">
@endsection

@section('content')
  @include('partials.home-hero')
  @include('partials.home-principals')
  @include('partials.home-bento')
  @include('partials.home-focus')

  <!-- Products -->
  <section class="section-spacious typo-products-section nb-section">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <h2 class="typo-section-title">Produk & Reagen Unggulan</h2>
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
            <div class="col">
              <div class="card h-100 product-card"
                   style="view-transition-name: prod-card-{{ Str::slug($prod['title']) }};">
                <div class="img-wrap">
                  <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Produk laboratorium" loading="lazy" decoding="async">
                </div>
                <div class="card-body p-4 d-flex flex-column">
                  <div class="d-flex flex-wrap gap-1 align-items-center mb-2">
                    @if(!empty($prod['catalog']))
                      <div class="product-cat-code">CAT. {{ $prod['catalog'] }}</div>
                    @endif
                    @if(!empty($prod->principal))
                      <span class="nb-badge-sm">{{ $prod->principal->name }}</span>
                    @endif
                  </div>
                  <h3 class="card-title fs-6 fw-semibold mb-2" style="line-height: 1.4;">
                    <a href="{{ product_url($prod) }}" class="product-card-link" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">{{ $prod['title'] }}</a>
                  </h3>
                  <p class="product-card-desc mb-3 flex-grow-1 text-muted" style="font-size: 0.82rem; line-height: 1.5;">
                    {{ Str::limit(str_replace('-', ' ', $prod['sub_category'] ?? $prod['category'] ?? ''), 65) ?: 'Instrumen dan reagen analitika standar pengujian laboratorium' }}
                  </p>
                  <div class="mt-auto pt-3 d-flex align-items-center justify-content-between nb-card-foot">
                    <a href="{{ product_url($prod) }}" class="product-card-action text-decoration-none fw-medium" data-vt-target="prod-card-{{ Str::slug($prod['title']) }}">
                      Detail & Spek <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <span class="nb-mono small"><i class="bi bi-file-earmark-check"></i> COA</span>
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

  @include('partials.home-news')

  <!-- RFQ giant callout -->
  <section class="nb-rfq-section">
    <div class="container">
      <div class="nb-rfq-box">
        <span class="nb-badge">{{ $homeData['cta_banner_badge'] ?? 'B2B PROCUREMENT' }}</span>
        <h2 class="nb-rfq-title">{{ $homeData['cta_banner_title'] ?? 'Need a formal quotation for your laboratory?' }}</h2>
        <p class="nb-rfq-sub">{{ $homeData['cta_banner_sub'] ?? 'Submit an RFQ with your product list — our sales team will follow up with pricing, bulk availability, and compliance documentation.' }}</p>
        <div class="nb-rfq-actions">
          <a href="{{ url($homeData['cta_banner_btn_url'] ?? '/kontak') }}" class="nb-btn nb-btn-primary">
            {{ $homeData['cta_banner_btn_text'] ?? 'Contact Sales / Request Quote' }}
            <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/cart') }}" class="nb-btn nb-btn-ghost">
            <i class="bi bi-cart3"></i> Buka Keranjang RFQ
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
          if (activePanel) activePanel.classList.add('active');
        });
      });
    });
  </script>
@endpush
