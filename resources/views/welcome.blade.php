@extends('layouts.app')

@section('title', 'Home | PROLABIOS Editorial')

@section('preload')
  @php
    $firstHero = null;
    if (!empty($homeData['hero_images']) && is_array($homeData['hero_images'])) {
      $firstHero = $homeData['hero_images'][0] ?? null;
    }
  @endphp
  @if($firstHero)
    <link rel="preload" as="image" href="{{ $firstHero }}" fetchpriority="high">
  @endif
@endsection

@section('content')
  <!-- Hero Section — typography-led -->
  <section class="section-spacious typo-hero">
    <div class="typo-hero-bg">
      @if(isset($homeData['hero_images']) && is_array($homeData['hero_images']))
        @foreach($homeData['hero_images'] as $index => $imgUrl)
          <img
            class="hero-bg-slide @if($index === 0) active @endif"
            src="{{ $imgUrl }}"
            alt=""
            decoding="async"
            @if($index === 0)
              fetchpriority="high"
            @else
              loading="lazy"
            @endif
          >
        @endforeach
      @else
        <img
          class="hero-bg-slide active"
          src="https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
          alt=""
          decoding="async"
          fetchpriority="high"
        >
      @endif
      <div class="typo-hero-overlay"></div>
    </div>
    <div class="container" style="position: relative; z-index: 2;">
      <div class="typo-hero-entrance" style="opacity: 0;">
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="typo-pill-accent">PROFESSIONAL</span>
          <span class="typo-pill-outline">ROBUST</span>
          <span class="typo-pill-accent">OFFERING THE BEST</span>
        </div>
        <h1 class="typo-hero-title">
            {!! $homeData['hero_title'] ?? '' !!}
        </h1>
        <p class="typo-lead">
          {{ $homeData['hero_subtitle'] }}
        </p>
        <div class="d-flex flex-wrap gap-4 typo-hero-ctas">
          <a href="{{ url('/profil') }}" class="typo-btn-link">
            About Us <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/produk') }}" class="typo-btn-link typo-btn-link--ghost">
            product catalog <i class="bi bi-box-seam"></i>
          </a>
        </div>
      </div>
      <!-- Manual Slider Controls -->
      @if(isset($homeData['hero_images']) && count($homeData['hero_images']) > 1)
        <div class="typo-hero-controls">
          <button id="hero-prev" class="typo-hero-ctrl-btn" aria-label="Previous Slide">
            <i class="bi bi-arrow-left"></i>
          </button>
          <button id="hero-next" class="typo-hero-ctrl-btn" aria-label="Next Slide">
            <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      @endif
    </div>
  </section>

  <!-- Sektor Fokus — index typography list -->
  <section class="section-spacious focus-section-pin">
    <div class="container">
      <div class="row mb-5 typo-section-head">
        <div class="col-lg-8">
          <span class="typo-section-label">Value Chain</span>
          <h2 class="typo-section-title">{{ $homeData['focus_title'] }}</h2>
          <p class="typo-section-sub text-muted">Our focus on the industry and the service value chain enables us to provide high-quality laboratory solutions.</p>
        </div>
      </div>

      <div class="typo-index-list">
        @foreach($homeData['focus_cards'] as $index => $card)
          <div class="typo-index-item gsap-reveal-item">
            <div class="typo-index-number">
              {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.
            </div>
            <div class="typo-index-content">
              <div class="typo-index-text">
                <h3 class="typo-index-title">{{ $card['title'] }}</h3>
                <p class="typo-index-desc">{{ $card['description'] }}</p>
              </div>
              <div>
                <a href="{{ url('/sektor') }}" class="typo-index-link">
                  Detail <i class="bi bi-arrow-up-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Editorial Principals Logo Marquee -->
  <section style="padding: 60px 0; border-bottom: 1px solid var(--color-border); background-color: #070708; overflow: hidden;">
    <div class="container mb-4">
      <div class="text-center">
        <span class="typo-section-label" style="margin-bottom: 0;">Authorized Principals &amp; Partners</span>
      </div>
    </div>
    <div class="marquee-container" style="position: relative; display: flex; overflow: hidden; user-select: none; padding: 20px 0;">
      <div class="marquee-content-single">
        <!-- Urutan asli (1 - 9) -->
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/liofilchem.png') }}" alt="Liofilchem" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bioendo.png') }}" alt="Bioendo" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/terragene.png') }}" alt="Terragene" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/biotool.png') }}" alt="Biotool" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ifm.png') }}" alt="IFM" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bnf_korea.png') }}" alt="BNF Korea" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/leadfluid.png') }}" alt="Leadfluid" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/meizheng.png') }}" alt="Meizheng" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ksl_pulse.png') }}" alt="KSL Pulse Scientific" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        
        <!-- Duplikat persis untuk loop (10 - 18) -->
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/liofilchem.png') }}" alt="Liofilchem" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bioendo.png') }}" alt="Bioendo" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/terragene.png') }}" alt="Terragene" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/biotool.png') }}" alt="Biotool" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ifm.png') }}" alt="IFM" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bnf_korea.png') }}" alt="BNF Korea" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/leadfluid.png') }}" alt="Leadfluid" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/meizheng.png') }}" alt="Meizheng" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ksl_pulse.png') }}" alt="KSL Pulse Scientific" style="height: 38px; filter: grayscale(100%) brightness(0.85); opacity: 0.6; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0%)'" onmouseout="this.style.opacity='0.6'; this.style.filter='grayscale(100%) brightness(0.85)'"></div>
      </div>
    </div>
    </div>
    </div>
  </section>

  <!-- Produk Unggulan — premium product showcase -->
  <section class="section-spacious typo-products-section" style="border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Recommendation</span>
          <h2 class="typo-section-title">Featured Products</h2>
          <p class="typo-section-sub text-muted mb-0">Discover our selection of the best instruments and reagents to support your laboratory activities.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/produk') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            Lihat Semua Produk <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
          @foreach($featuredProducts as $prod)
            <div class="col">
              <div class="card h-100 product-card-premium border-0">
                <div class="img-wrap">
                  <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                </div>
                <div class="card-body p-3">
                  @if(!empty($prod['catalog']))
                    <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-bottom: 6px; font-family: var(--font-headline); text-transform: uppercase; letter-spacing: 1px;">Cat. {{ $prod['catalog'] }}</div>
                  @endif
                  <h3 class="card-title fs-6 fw-bold">
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none" style="color: #fff;">{{ $prod['title'] }}</a>
                  </h3>
                  <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 6px; margin-bottom: 14px;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 80) }}</p>
                  <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="profil-cta-btn" style="font-size: 0.72rem;">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">No featured products have been displayed yet.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- Berita & Kegiatan — editorial columns -->
  <section class="section-spacious typo-news-section">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Articles &amp; Medias</span>
          <h2 class="typo-section-title">News &amp; activities</h2>
          <p class="typo-section-sub text-muted mb-0">The latest updates on Prolabios events, training sessions, and activities.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/informasi') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            Lihat Semua Info <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row g-4">
        @if(count($recentPosts) > 0)
          @foreach($recentPosts as $post)
            @php
              $dateParts = explode(' ', $post['date']);
              $day = isset($dateParts[0]) ? $dateParts[0] : '';
              $month = isset($dateParts[1]) ? $dateParts[1] : '';
            @endphp
            <div class="col-lg-4 col-md-12">
              <div class="card typo-blog-card h-100">
                <div class="card-body p-0">
                  <span class="typo-blog-card-meta">
                    {{ $post['category'] }} &bull; {{ $day }} {{ $month }}
                  </span>
                  <h3 class="typo-blog-card-title">
                    <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}">{{ $post['title'] }}</a>
                  </h3>
                  <p class="typo-blog-card-desc">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 140) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">Belum ada artikel terbaru.</p>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
@endpush
