@extends('layouts.app')

@section('title', 'Sektor Industri | PROLABIOS')
@section('meta_description', 'Sektor industri yang dilayani Prolabios - Farmasi, Food & Beverage, Mikrobiologi, dan berbagai industri lain dengan solusi laboratorium terpercaya.')
@section('meta_keywords', 'sektor industri, farmasi, food beverage, mikrobiologi, industri, solusi laboratorium, prolabios')
@section('canonical_url', url('/sektor'))

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Industrial Sector</span>
      <h1 class="editorial-page-title">Focus Sectors</h1>
      <p class="editorial-page-subtitle">Serving various industrial sectors with cutting-edge analytics solutions</p>
    </div>
  </div>

  <!-- Sektor Content -->
  <section class="section-main" id="sektor-nav">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4" id="sektor-sidebar">
          {{-- $activeSector is passed from PageController::sektor() --}}

          <div class="mb-5">
            <h3 class="profil-sidebar-title">Select a Sector</h3>
            <nav class="layanan-sidebar-nav">
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                  <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav"
                     class="layanan-sidebar-link {{ $activeSector == $sec['id'] ? 'is-active' : '' }}"
                     data-sector-id="{{ $sec['id'] }}">
                    {{ $sec['name'] }}
                  </a>
                @endforeach
              @else
                <a href="#" class="layanan-sidebar-link is-active">Brewing</a>
              @endif
            </nav>
          </div>

          <div class="profil-cta-box d-none d-md-block">
            <h3 class="profil-sidebar-title">Looking for tailored solutions?</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Discuss your industry-specific needs with our technical team.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Free Consultation <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8" id="sektor-main">
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
            <!-- Sector Hero Image -->
            <div class="profil-hero-img mb-5">
              <img src="{{ $currentImage }}" alt="{{ $currentData['name'] }} Sector" class="w-100" style="aspect-ratio: 16/9; width: 100%; height: auto; object-fit: cover; display: block; max-height: 480px;" loading="lazy" decoding="async">
            </div>

            <!-- Sector Title & Description -->
            <h2 class="profil-section-title">{{ $currentData['name'] }}</h2>
            @foreach($descriptionParagraphs as $desc)
              <p class="profil-body-text mb-4">{!! \App\Services\DataService::sanitizeHtml($desc) !!}</p>
            @endforeach

            <hr style="border-color: var(--color-border); margin: 48px 0;">

            <!-- Product Table -->
            <h3 class="profil-section-title" style="font-size: 1.4rem !important;">Discover Our Products</h3>
            <p class="profil-body-text mb-4">We offer a wide variety of specialized products designed to support operational activities, research, and analysis in the <strong style="color: rgba(255,255,255,0.85);">{{ $currentData['name'] }}</strong> sector.</p>

            <!-- Mobile Swipe Indicator -->
            <div class="d-md-none text-end mb-2">
              <span class="badge" style="background: rgba(255, 73, 80, 0.08); color: var(--color-accent); border: 1px solid rgba(255, 73, 80, 0.2); font-size: 0.68rem; font-family: var(--font-headline); letter-spacing: 0.5px; padding: 6px 12px; border-radius: 100px;">
                <i class="bi bi-arrow-left-right me-1"></i> Geser Tabel
              </span>
            </div>

            <div class="table-responsive mt-2" id="sektor-product-table-wrap">
              <table class="table custom-table align-middle" style="min-width: 650px;">
                <thead>
                  <tr>
                    <th>Catalogue</th>
                    <th>Product</th>
                    <th>Application</th>
                  </tr>
                </thead>
                <tbody>
                  @php $hasProducts = false; @endphp
                  @if(isset($products) && count($products) > 0)
                    @foreach($products as $prod)
                      @php $hasProducts = true; @endphp
                      <tr>
                        <td style="color: var(--color-text-muted); font-size: 0.82rem;">{{ $prod['catalog'] ?? '-' }}</td>
                        <td>
                          <a href="{{ product_url($prod) }}" class="text-decoration-none fw-semibold" style="color: var(--color-accent);">
                            {{ $prod['title'] }}
                          </a>
                        </td>
                        <td style="color: var(--color-text-muted); font-size: 0.88rem;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 150) }}</td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            <div id="sektor-pagination-or-empty">
              @if(!$hasProducts)
                <p style="color: var(--color-text-muted); font-size: 0.9rem; padding: 16px 0; border-top: 1px solid var(--color-border);">
                  Belum ada produk spesifik untuk sektor ini. <a href="{{ url('/produk') }}" style="color: var(--color-accent);">Lihat semua produk kami</a>.
                </p>
              @else
                <div class="d-flex justify-content-center mt-4">
                  {{ $products->appends(request()->except('page'))->fragment('sektor-nav')->links('pagination::bootstrap-5') }}
                </div>
              @endif
            </div>

            <hr style="border-color: var(--color-border); margin: 48px 0;">

            <!-- Related Products -->
            <h3 class="profil-section-title" style="font-size: 1.4rem !important;">Related Product</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2" id="sektor-related">
              @php
                $related = $relatedProducts ?? collect();
                if ($related->isEmpty() && isset($products) && count($products) > 0) {
                    $related = collect($products)->take(3);
                }
              @endphp
              @foreach($related as $prod)
                <div class="col">
                  <div class="card h-100 product-card-premium border-0">
                    <div class="img-wrap">
                      <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }} — Sector Product & Analytical Instrument" loading="lazy" decoding="async">
                    </div>
                    <div class="card-body p-3">
                      @if(!empty($prod['catalog']))
                        <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-bottom: 6px; font-family: var(--font-headline); text-transform: uppercase; letter-spacing: 1px;">Cat. {{ $prod['catalog'] }}</div>
                      @endif
                      <h4 class="card-title fs-6 fw-bold">
                        <a href="{{ product_url($prod) }}" class="text-decoration-none" style="color: #fff;">{{ $prod['title'] }}</a>
                      </h4>
                      <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 8px; margin-bottom: 16px;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 80) }}</p>
                      <a href="{{ product_url($prod) }}" class="profil-cta-btn" style="font-size: 0.72rem;">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Mobile-only CTA Box -->
            <div class="profil-cta-box d-md-none mt-5">
              <h3 class="profil-sidebar-title">Looking for tailored solutions?</h3>
              <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Discuss your industry-specific needs with our technical team.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Free Consultation <i class="bi bi-arrow-right"></i></a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @push('styles')
  <style>
    #sektor-main { position: relative; }
    #sektor-main.is-loading { pointer-events: none; }
    #sektor-main .ajax-loading-overlay {
      position: absolute; inset: 0; z-index: 6;
      display: none; align-items: flex-start; justify-content: center;
      padding-top: 80px;
      background: rgba(0,0,0,0.4);
      backdrop-filter: blur(1px);
      border-radius: 8px;
    }
    #sektor-main.is-loading .ajax-loading-overlay { display: flex; }
    #sektor-main.is-loading > *:not(.ajax-loading-overlay) { opacity: 0.3; transition: opacity 0.15s; }
    .ajax-spinner {
      width: 36px; height: 36px;
      border: 2px solid rgba(255,73,80,0.25);
      border-top-color: var(--color-accent, #ff4950);
      border-radius: 50%;
      animation: ajax-spin 0.7s linear infinite;
    }
    @keyframes ajax-spin { to { transform: rotate(360deg); } }
  </style>
  @endpush

  @push('scripts')
  @include('partials.gsap-loader')
  <script>
    (function () {
      let fetchController = null;

      function setSektorLoading(on) {
        const main = document.getElementById('sektor-main');
        if (!main) return;
        main.classList.toggle('is-loading', !!on);
        main.setAttribute('aria-busy', on ? 'true' : 'false');
        let overlay = main.querySelector(':scope > .ajax-loading-overlay');
        if (on) {
          if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'ajax-loading-overlay';
            overlay.setAttribute('aria-hidden', 'false');
            overlay.innerHTML = '<div class="ajax-spinner" role="status" aria-label="Loading"></div>';
            main.insertBefore(overlay, main.firstChild);
          } else {
            overlay.style.display = 'flex';
            overlay.setAttribute('aria-hidden', 'false');
          }
        } else if (overlay) {
          overlay.remove();
        }
      }

      function loadSektorAjax(url, updateHistory) {
        if (fetchController) {
          fetchController.abort();
        }
        fetchController = new AbortController();

        setSektorLoading(true);

        fetch(url, {
          signal: fetchController.signal,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
          })
          .then(function (html) {
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newMain = doc.getElementById('sektor-main');
            const newSidebar = doc.getElementById('sektor-sidebar');
            const curMain = document.getElementById('sektor-main');
            const curSidebar = document.getElementById('sektor-sidebar');

            if (curMain && newMain) {
              curMain.innerHTML = newMain.innerHTML;
              setSektorLoading(false);
            }
            if (curSidebar && newSidebar) {
              curSidebar.innerHTML = newSidebar.innerHTML;
            }

            if (updateHistory) {
              window.history.pushState({ url: url }, '', url);
            }

            if (typeof initGSAPAnimations === 'function') {
              initGSAPAnimations();
            }

            const section = document.getElementById('sektor-nav');
            if (section) {
              section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
          })
          .catch(function (err) {
            if (err.name === 'AbortError') return;
            setSektorLoading(false);
            console.error('Sektor AJAX failed, full navigation:', err);
            window.location.href = url;
          });
      }

      document.addEventListener('click', function (e) {
        const link = e.target.closest('#sektor-sidebar a.layanan-sidebar-link, #sektor-main .pagination a');
        if (!link || !link.getAttribute('href') || link.getAttribute('href').startsWith('#')) {
          return;
        }

        try {
          const u = new URL(link.href, window.location.origin);
          if (u.origin !== window.location.origin) return;
          if (!u.pathname.replace(/\/$/, '').endsWith('/sektor') && u.pathname !== '/sektor') {
            if (!u.pathname.includes('sektor')) return;
          }
        } catch (err) {
          return;
        }

        e.preventDefault();
        loadSektorAjax(link.href, true);
      });

      window.addEventListener('popstate', function () {
        loadSektorAjax(window.location.href, false);
      });

      document.addEventListener('DOMContentLoaded', function () {
        if (typeof initGSAPAnimations === 'function') {
          initGSAPAnimations();
        }
      });
    })();
  </script>
  @endpush
@endsection
