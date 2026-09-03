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

      <!-- Quick Fast Stats Strip -->
      <div class="profil-stats-strip">
        <div class="profil-stat-box">
          <div class="profil-stat-num">Multi-Sektor</div>
          <div class="profil-stat-label">Farmasi, F&amp;B, Lingkungan &amp; Riset</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Standar ISO/USP</div>
          <div class="profil-stat-label">Kepatuhan Farmakope &amp; Regulasi Mutu</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Aplikasi Khusus</div>
          <div class="profil-stat-label">Rekomendasi Reagen &amp; Instrumen Terarah</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Dukungan Teknis</div>
          <div class="profil-stat-label">Konsultasi Spesifikasi &amp; RFQ Institusi</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sektor Content -->
  <section class="section-spacious nb-section" id="sektor-nav">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Sidebar (Left, Order 1 on Desktop to browse sectors easily) -->
        <div class="col-lg-4 col-md-5 order-2 order-md-1" id="sektor-sidebar">
          {{-- $activeSector is passed from PageController::sektor() --}}

          <!-- Sector Selector Card -->
          <div class="card p-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
            <h3 class="profil-sidebar-title mb-3">
              <i class="bi bi-grid-fill me-2 text-primary"></i> Pilih Sektor Industri
            </h3>
            <nav class="layanan-sidebar-nav">
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                  <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav"
                     class="layanan-sidebar-link {{ $activeSector == $sec['id'] ? 'is-active' : '' }}"
                     data-sector-id="{{ $sec['id'] }}">
                    <span>{{ $sec['name'] }}</span>
                    <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
                  </a>
                @endforeach
              @else
                <a href="#" class="layanan-sidebar-link is-active">
                  <span>Brewing</span>
                  <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
                </a>
              @endif
            </nav>
          </div>

          <!-- Sidebar Card 2: B2B Consultation CTA Box -->
          <div class="profil-cta-box p-4" style="background: var(--nb-primary, #A6171C); color: #FFFFFF; border: 2px solid #1E1E1E; border-radius: 8px; box-shadow: 4px 4px 0 #1E1E1E;">
            <span class="nb-badge mb-3" style="background: var(--nb-accent, #F1C045); color: #FFFFFF;">B2B CONSULTATION</span>
            <h3 class="profil-sidebar-title" style="color: #FFFFFF !important; border-bottom-color: rgba(255,255,255,0.3) !important;">Butuh Solusi Spesifik?</h3>
            <p style="font-size: 0.88rem; color: #FFFFFF !important; margin-bottom: 20px; line-height: 1.6;">Diskusikan alur pengujian laboratorium atau spesifikasi instrumen industri Anda dengan tim spesialis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="nb-btn nb-btn-ghost w-100 justify-content-center mb-2" style="background: var(--nb-accent, #F1C045); color: #1E1E1E !important;">
              Konsultasi Tim Teknis <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="background: #FFFFFF; color: #1E1E1E !important; font-size: 0.82rem;">
              <i class="bi bi-box-seam me-1"></i> Jelajahi Seluruh Katalog
            </a>
          </div>
        </div>

        <!-- Main Content (Right, Order 2 on Desktop) -->
        <div class="col-lg-8 col-md-7 order-1 order-md-2" id="sektor-main">
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
            <div class="card p-4 p-md-5 mb-5" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
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
                            <a href="{{ product_url($prod) }}" class="text-decoration-none fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">
                              {{ $prod['title'] }}
                            </a>
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

  @push('styles')
  <style>
    #sektor-main { position: relative; }
    #sektor-main.is-loading { pointer-events: none; }
    #sektor-main .ajax-loading-overlay {
      position: absolute; inset: 0; z-index: 6;
      display: none; align-items: flex-start; justify-content: center;
      padding-top: 80px;
      background: rgba(214, 208, 197, 0.65);
      border-radius: var(--nb-radius-lg, 8px);
    }
    #sektor-main.is-loading .ajax-loading-overlay { display: flex; }
    #sektor-main.is-loading > *:not(.ajax-loading-overlay) { opacity: 0.35; transition: opacity 0.15s; }
    .ajax-spinner {
      width: 40px; height: 40px;
      border: 3px solid rgba(30, 30, 30, 0.15);
      border-top-color: var(--nb-primary, #A6171C);
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
            overlay.innerHTML = '<div class="ajax-spinner" role="status" aria-label="Memuat"></div>';
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
