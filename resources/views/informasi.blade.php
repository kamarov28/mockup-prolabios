@extends('layouts.app')

@section('title', isset($currentBlog) && $currentBlog ? $currentBlog['title'] . ' | PROLABIOS' : 'Berita & Informasi | PROLABIOS')
@section('canonical', isset($currentBlog) && $currentBlog ? url('/informasi/' . $currentBlog['slug']) : url('/informasi'))
@section('meta_description', isset($currentBlog) && $currentBlog ? Str::limit(strip_tags($currentBlog['content'] ?? ''), 155) : 'Update rilis regulasi laboratorium, wawasan analitika pengujian, inovasi teknologi instrumen, dan agenda kegiatan PT Prolabios Mitra Analitika.')
@section('og_title', !empty($currentBlog['title']) ? $currentBlog['title'] : 'Berita & Informasi | PROLABIOS')
@section('og_description', !empty($currentBlog['content']) ? Str::limit(strip_tags($currentBlog['content']), 150) : 'Pusat Informasi & Wawasan Industri')
@section('og_image', !empty($currentBlog['image']) ? $currentBlog['image'] : asset('images/logo-prolabios.png'))

@section('content')
  @if($currentBlog)
    {{-- Editorial Page Header (Top Spacing to clear fixed navbar & standard layout) --}}
    <div class="editorial-page-header">
      <div class="container">
        <div class="d-flex align-items-center gap-2 mb-2">
          <a href="{{ url('/informasi') }}" class="nb-mono small text-muted text-decoration-none">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px; vertical-align: -2px;"></i> Berita &amp; Artikel
          </a>
          @if(!empty($currentBlog['category']))
            <span class="text-muted small">/</span>
            <span class="nb-mono small text-muted text-capitalize">{{ $currentBlog['category'] }}</span>
          @endif
        </div>
        <h1 class="editorial-page-title" style="text-wrap: balance;">{{ $currentBlog['title'] }}</h1>
        <div class="d-flex align-items-center gap-3 mt-2 flex-wrap text-muted small">
          <span class="badge" style="background: rgba(166, 23, 28, 0.08); color: #A6171C; font-weight: 600; font-size: 0.75rem; padding: 4px 8px;">
            {{ $currentBlog['category'] }}
          </span>
          <span class="d-inline-flex align-items-center gap-1">
            <i data-lucide="calendar" style="width: 14px; height: 14px;"></i> {{ $currentBlog['date'] }}
          </span>
          <span>&bull;</span>
          <span class="d-inline-flex align-items-center gap-1">
            <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
            {{ max(1, (int) round(str_word_count(strip_tags($currentBlog['content'] ?? '')) / 200)) }} menit baca
          </span>
          <span>&bull;</span>
          <span class="d-inline-flex align-items-center gap-1">
            <i data-lucide="building" style="width: 14px; height: 14px;"></i> PT. Prolabios Mitra Analitika
          </span>
        </div>
      </div>
    </div>
  @else
    <x-subpage-hero
      badge='<i data-lucide="newspaper" class="me-1"></i> BERITA &amp; ARTIKEL'
      title="Pusat Informasi & Wawasan Industri"
      subtitle="Update rilis regulasi laboratorium, wawasan analitika pengujian, inovasi teknologi instrumen, dan agenda kegiatan PT Prolabios Mitra Analitika."
    />
  @endif

  <!-- Informasi Content (Full 2-Column Responsive Layout) -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row g-4 g-lg-5">

        <!-- Main Content (Left: col-12 col-lg-8) -->
        <div class="col-12 col-lg-8 order-1">
          @if($currentBlog)
            <!-- Detail View -->
            <article class="card p-4 p-md-5 blog-detail-card">
              @if(!empty($currentBlog['image']))
                <div class="profil-hero-img mb-4">
                  <img src="{{ $currentBlog['image'] }}" class="w-100 blog-detail-img" alt="{{ $currentBlog['title'] }}" loading="lazy" decoding="async">
                </div>
              @endif

              <div class="profil-body-text blog-detail-body">
                {!! \App\Helpers\HtmlSanitizer::clean($currentBlog['content'] ?? '') !!}
              </div>

              {{-- Social Share & Quick Back bar --}}
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-4 mt-5 border-top" style="border-color: #E5E7EB !important;">
                <div class="d-flex align-items-center gap-2">
                  <span class="small fw-semibold text-muted">Bagikan:</span>
                  <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded py-1 px-2" style="font-size: 0.78rem;" onclick="copyArticleUrl(this)">
                    <i data-lucide="copy" style="width: 13px; height: 13px;"></i> <span class="copy-label">Salin Tautan</span>
                  </button>
                </div>
                <a href="{{ url('/informasi') }}" class="small fw-semibold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--color-accent, #A6171C);">
                  <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Semua Artikel
                </a>
              </div>

              {{-- Recommendation / Procurement CTA Box --}}
              <div class="p-4 mt-4 rounded" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 4px solid var(--color-accent, #A6171C);">
                <div class="d-flex align-items-start gap-3">
                  <i data-lucide="beaker" class="flex-shrink-0 mt-1" style="color: var(--color-accent, #A6171C); width: 22px; height: 22px;"></i>
                  <div>
                    <strong class="d-block mb-1" style="font-family: var(--font-display); color: var(--nb-ink); font-size: 0.98rem;">Dukungan Pengadaan &amp; Instrumen Laboratorium</strong>
                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                      PT. Prolabios Mitra Analitika melayani suplai media mikrobiologi siap pakai, reagen analitika (USP/EP), indikator sterilisasi, serta instrumen preparasi lab bergaransi resmi prinsipal dengan fasilitas cold-chain di Indonesia.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                      <a href="{{ url('/produk') }}" class="nb-btn nb-btn-primary py-1 px-3 text-decoration-none" style="font-size: 0.8rem; border-radius: 4px;">
                        <i data-lucide="package" style="width: 13px; height: 13px;"></i> Jelajahi Katalog Produk
                      </a>
                      <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-ghost py-1 px-3 text-decoration-none" style="font-size: 0.8rem; border-radius: 4px;">
                        <i data-lucide="phone" style="width: 13px; height: 13px;"></i> Kontak Dukungan Teknis
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </article>

          @else
            <!-- List View -->
            @if(count($posts) > 0)
              <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($posts as $post)
                  <div class="col">
                    <div class="card h-100 blog-card">
                      <div class="blog-card-img-wrap">
                        <img src="{{ $post['image'] ?: asset('images/placeholder.svg') }}" class="card-img-top" alt="{{ $post['title'] }}" loading="lazy" decoding="async">
                      </div>
                      <div class="blog-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <span class="blog-card-category mb-0">{{ $post['category'] }}</span>
                          <span class="blog-card-date-inline">
                            <i data-lucide="calendar" class="me-1"></i>{{ $post['date'] }}
                          </span>
                        </div>
                        <h3 class="blog-card-title">
                          <a href="{{ url('/informasi/' . $post['slug']) }}">{{ $post['title'] }}</a>
                        </h3>
                        <p class="blog-card-text">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 120) }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-5">
                {{ $posts->links('partials.catalog-pagination', ['itemLabel' => 'artikel']) }}
              </div>

            @else
              <div class="col-12 text-center p-5 card">
                <i data-lucide="newspaper" class="blog-empty-icon"></i>
                <h3 class="fs-5 fw-bold blog-empty-title">Belum Ada Artikel</h3>
                <p class="blog-empty-text">Tidak ada artikel untuk kategori yang Anda pilih.</p>
                <a href="{{ url('/informasi') }}" class="nb-btn nb-btn-primary d-inline-flex mx-auto">
                  Lihat Semua Artikel <i data-lucide="arrow-right" class="ms-1"></i>
                </a>
              </div>
            @endif
          @endif
        </div>

        <!-- Sidebar Column (Right: col-12 col-lg-4 order-2 - Always present for visual balance) -->
        <div class="col-12 col-lg-4 order-2">
          <div class="blog-sidebar-sticky">

            <!-- Category Filter -->
            <div class="card p-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom blog-sidebar-head">
                <h3 class="profil-sidebar-title mb-0 border-0 p-0"><i data-lucide="tags" class="me-2"></i>Kategori</h3>
                @if($selectedCategory)
                  <a href="{{ url('/informasi') }}" class="nb-badge blog-reset-badge">
                    <i data-lucide="x-circle" class="me-1"></i>Reset
                  </a>
                @endif
              </div>
              <nav class="layanan-sidebar-nav d-flex flex-column gap-2">
                @foreach($categoryCounts as $catName => $count)
                  @php
                    $catSlug = '';
                    if ($catName === 'Berita') $catSlug = 'berita';
                    elseif ($catName === 'Event') $catSlug = 'event';
                    elseif ($catName === 'Info Terkait') $catSlug = 'info';
                    elseif ($catName === 'IPTEK') $catSlug = 'iptek';
                    elseif ($catName === 'Kegiatan') $catSlug = 'kegiatan';
                  @endphp
                  <a href="{{ url('/informasi') }}?kategori={{ $catSlug }}"
                     class="profil-social-link justify-content-between {{ $selectedCategory == $catSlug ? 'is-active' : '' }}">
                    <span>{{ $catName }}</span>
                    <span class="blog-cat-count {{ (int)$count === 0 ? 'is-zero' : '' }}">{{ $count }}</span>
                  </a>
                @endforeach
              </nav>
            </div>

            <!-- Recent Posts -->
            <div class="profil-trust-box mb-4">
              <h3 class="profil-sidebar-title"><i data-lucide="history" class="me-2"></i>Berita Terbaru</h3>
              @if(count($recentPosts) > 0)
                <div class="blog-recent-list">
                  @foreach($recentPosts as $index => $rPost)
                    @if(!isset($currentBlog) || $currentBlog['slug'] !== $rPost['slug'])
                      <a href="{{ url('/informasi/' . $rPost['slug']) }}" class="blog-recent-item">
                        <div class="blog-recent-date">
                          <i data-lucide="calendar"></i> {{ $rPost['date'] }}
                        </div>
                        <h4 class="blog-recent-title">{{ $rPost['title'] }}</h4>
                      </a>
                    @endif
                  @endforeach
                </div>
              @else
                <p class="profil-body-text mb-0">Belum ada berita terbaru.</p>
              @endif
            </div>

            <!-- Official Office Landline Info Box (Corporate Policy Compliance) -->
            <div class="card p-4 border-0" style="background: #FFFFFF; border: 1px solid #E5E7EB !important; border-radius: var(--nb-radius-lg);">
              <h3 class="profil-sidebar-title mb-2" style="font-size: 0.95rem;">
                <i data-lucide="headphones" class="me-2 text-primary"></i>Layanan Resmi Kantor
              </h3>
              <p class="small text-muted mb-3" style="font-size: 0.8rem; line-height: 1.5;">
                Hubungi saluran resmi telepon kantor atau email marketing untuk permohonan katalog dan penawaran teknis.
              </p>
              <div class="d-flex flex-column gap-2 small">
                <div class="d-flex align-items-center gap-2">
                  <i data-lucide="phone" style="width: 14px; height: 14px; color: var(--color-accent, #A6171C);"></i>
                  <span class="fw-semibold">021-3874-1447</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <i data-lucide="phone" style="width: 14px; height: 14px; color: var(--color-accent, #A6171C);"></i>
                  <span class="fw-semibold">021-8792-9433</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <i data-lucide="mail" style="width: 14px; height: 14px; color: var(--color-accent, #A6171C);"></i>
                  <a href="mailto:marketing@prolabios.com" class="text-decoration-none" style="color: var(--nb-ink);">marketing@prolabios.com</a>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

  @if($currentBlog)
    <script @nonce>
      function copyArticleUrl(btn) {
        if (navigator.clipboard) {
          navigator.clipboard.writeText(window.location.href).then(function() {
            var label = btn.querySelector('.copy-label');
            if (label) {
              var orig = label.textContent;
              label.textContent = 'Tersalin!';
              setTimeout(function() { label.textContent = orig; }, 2000);
            }
          });
        }
      }
    </script>
  @endif
@endsection
