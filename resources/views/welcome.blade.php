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
        <h1 class="typo-hero-title">
          {!! str_replace('Terpercaya', '<span class="typo-outline">Terpercaya</span>', e($homeData['hero_title'] ?? '')) !!}
        </h1>
        <p class="typo-lead">
          {{ $homeData['hero_subtitle'] }}
        </p>
        <div class="d-flex flex-wrap gap-4 typo-hero-ctas">
          <a href="{{ url('/profil') }}" class="typo-btn-link">
            Tentang Kami <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/produk') }}" class="typo-btn-link typo-btn-link--ghost">
            Katalog Produk <i class="bi bi-box-seam"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Continuous Running Text Marquee -->
  <section class="typo-marquee" aria-hidden="true">
    <div class="typo-marquee-content">
      PT PROLABIOS MITRA ANALITIKA &bull; <span class="typo-marquee-accent">PROFESSIONAL</span> &bull; <span class="typo-marquee-outline">ROBUST</span> &bull; <span class="typo-marquee-accent">OFFERING THE BEST</span> &bull; PT PROLABIOS MITRA ANALITIKA &bull; <span class="typo-marquee-outline">PROFESSIONAL</span> &bull; <span class="typo-marquee-accent">ROBUST</span> &bull; <span class="typo-marquee-outline">OFFERING THE BEST</span> &bull;
    </div>
    <div class="typo-marquee-content">
      PT PROLABIOS MITRA ANALITIKA &bull; <span class="typo-marquee-outline">PROFESSIONAL</span> &bull; <span class="typo-marquee-accent">ROBUST</span> &bull; <span class="typo-marquee-outline">OFFERING THE BEST</span> &bull; PT PROLABIOS MITRA ANALITIKA &bull; <span class="typo-marquee-accent">PROFESSIONAL</span> &bull; <span class="typo-marquee-outline">ROBUST</span> &bull; <span class="typo-marquee-accent">OFFERING THE BEST</span> &bull;
    </div>
  </section>

  <!-- Sektor Fokus — index typography list -->
  <section class="section-spacious focus-section-pin">
    <div class="container">
      <div class="row mb-5 typo-section-head">
        <div class="col-lg-8">
          <span class="typo-section-label">Value Chain</span>
          <h2 class="typo-section-title">{{ $homeData['focus_title'] }}</h2>
          <p class="typo-section-sub text-muted">Fokus industri dan rantai nilai pelayanan kami untuk menyediakan solusi laboratorium berkualitas tinggi.</p>
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
    <div class="marquee-container" style="position: relative; display: flex; overflow: hidden; user-select: none; gap: 40px; padding: 20px 0;">
      <div class="marquee-content" style="display: flex; flex-shrink: 0; justify-content: space-around; min-width: 100%; gap: 60px; animation: scroll-marquee 25s linear infinite;">
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
      <div class="marquee-content" aria-hidden="true" style="display: flex; flex-shrink: 0; justify-content: space-around; min-width: 100%; gap: 60px; animation: scroll-marquee 25s linear infinite;">
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
  </section>

  <!-- Berita & Kegiatan — editorial columns -->
  <section class="section-spacious typo-news-section">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Artikel &amp; Media</span>
          <h2 class="typo-section-title">Berita &amp; Kegiatan</h2>
          <p class="typo-section-sub text-muted mb-0">Update terbaru tentang event, training, dan aktivitas Prolabios.</p>
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

{{-- GSAP: load ASAP on homepage (not idle) so typography timeline owns first impression --}}
@push('scripts')
  <script>
    (function () {
      if (document.documentElement.classList.contains('no-motion')) return;
      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

      function loadScript(src) {
        return new Promise(function (resolve, reject) {
          var s = document.createElement('script');
          s.src = src;
          s.async = true;
          s.onload = resolve;
          s.onerror = reject;
          document.head.appendChild(s);
        });
      }

      var boot = function () {
        loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js')
          .then(function () {
            return loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js');
          })
          .then(function () {
            if (typeof initGSAPAnimations === 'function') {
              initGSAPAnimations();
            }
          })
          .catch(function () {
            if (typeof revealHeroStatic === 'function') revealHeroStatic();
            else {
              var hero = document.querySelector('.typo-hero-entrance');
              if (hero) hero.style.opacity = '1';
            }
          });
      };

      // Start load immediately — hero type should not wait for idle
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
      } else {
        boot();
      }
    })();
  </script>
@endpush
