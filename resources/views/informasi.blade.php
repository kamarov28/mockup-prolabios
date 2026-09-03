@extends('layouts.app')

@section('title', isset($currentBlog) && $currentBlog ? $currentBlog['title'] . ' | PROLABIOS' : 'Berita & Informasi | PROLABIOS')

@if(isset($currentBlog) && $currentBlog)
  @section('og_title', $currentBlog['title'])
  @section('og_description', Str::limit(strip_tags($currentBlog['content']), 150))
  @section('og_image', $currentBlog['image'])
@endif

@section('content')
  @if(!$currentBlog)
    <!-- Hero Banner (Soft Neo-Brutalism) -->
    <section class="profil-hero-banner">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-9">
            <span class="nb-badge">
              <i class="bi bi-newspaper me-1"></i> BERITA &amp; ARTIKEL
            </span>
            <h1 class="profil-main-title">
              Pusat Informasi &amp; Wawasan Industri
            </h1>
            <p class="profil-main-subtitle">
              Update rilis regulasi laboratorium, wawasan analitika pengujian, inovasi teknologi instrumen, dan agenda kegiatan PT Prolabios Mitra Analitika.
            </p>
          </div>
        </div>

        <!-- Quick Fast Stats Strip -->
        <div class="profil-stats-strip">
          <div class="profil-stat-box">
            <div class="profil-stat-num">Teknologi Lab</div>
            <div class="profil-stat-label">Inovasi Instrumen &amp; Reagensia</div>
          </div>
          <div class="profil-stat-box">
            <div class="profil-stat-num">Regulasi &amp; Mutu</div>
            <div class="profil-stat-label">Standar ISO 17025 &amp; Kepatuhan BPOM</div>
          </div>
          <div class="profil-stat-box">
            <div class="profil-stat-num">Event &amp; Pameran</div>
            <div class="profil-stat-label">Agenda Kegiatan &amp; Workshop Resmi</div>
          </div>
          <div class="profil-stat-box">
            <div class="profil-stat-num">Knowledge Base</div>
            <div class="profil-stat-label">Panduan Aplikasi Analitik Terkini</div>
          </div>
        </div>
      </div>
    </section>
  @endif

  <!-- Informasi Content -->
  <section class="section-spacious nb-section {{ $currentBlog ? 'blog-detail-section' : '' }}" @if($currentBlog) style="padding-top: 130px !important;" @endif>
    <div class="container">
      <div class="row g-4 g-lg-5">

        <!-- Main Content -->
        <div class="{{ $currentBlog ? 'col-lg-10 col-xl-8 mx-auto' : 'col-lg-8 col-md-7 order-1' }}">
          @if($currentBlog)
            <!-- Detail View -->
            <div class="card p-4 p-md-5" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
              <a href="{{ url('/informasi') }}{{ $selectedCategory ? '?kategori=' . $selectedCategory : '' }}" class="nb-btn nb-btn-ghost mb-4 d-inline-flex" style="padding: 6px 14px; font-size: 0.85rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Informasi
              </a>

              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="blog-card-category" style="margin-bottom: 0;">{{ $currentBlog['category'] }}</span>
                <span class="text-muted" style="font-family: var(--font-mono); font-size: 0.82rem; font-weight: 600;"><i class="bi bi-calendar3 me-1"></i>{{ $currentBlog['date'] }}</span>
              </div>

              <h1 class="profil-main-title" style="font-size: clamp(1.8rem, 3.5vw, 2.4rem) !important; margin-bottom: 24px !important;">{{ $currentBlog['title'] }}</h1>

              <div class="profil-hero-img mb-4">
                <img src="{{ $currentBlog['image'] }}" class="w-100" style="max-height: 480px; object-fit: cover; display: block;" alt="{{ $currentBlog['title'] }}" loading="lazy" decoding="async">
              </div>

              <div class="profil-body-text" style="line-height: 1.85;">
                {!! \App\Services\DataService::sanitizeHtml($currentBlog['content'] ?? '') !!}
              </div>
            </div>

          @else
            <!-- List View -->
            @if(count($posts) > 0)
              <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($posts as $post)
                  @php
                    $dateParts = explode(' ', $post['date']);
                    $day = isset($dateParts[0]) ? $dateParts[0] : '';
                    $month = isset($dateParts[1]) ? $dateParts[1] : '';
                  @endphp
                  <div class="col">
                    <div class="card h-100 blog-card">
                      <div class="blog-card-img-wrap">
                        <img src="{{ $post['image'] }}" class="card-img-top" alt="{{ $post['title'] }}" loading="lazy" decoding="async">
                      </div>
                      <div class="blog-card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <span class="blog-card-category mb-0">{{ $post['category'] }}</span>
                          <span class="blog-card-date-inline">
                            <i class="bi bi-calendar3 me-1"></i>{{ $post['date'] }}
                          </span>
                        </div>
                        <h3 class="blog-card-title">
                          <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}{{ $selectedCategory ? '&kategori=' . $selectedCategory : '' }}">{{ $post['title'] }}</a>
                        </h3>
                        <p class="blog-card-text">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 120) }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-5">
                {{ $posts->links('partials.catalog-pagination') }}
              </div>

            @else
              <div class="col-12 text-center p-5 card" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
                <i class="bi bi-newspaper" style="font-size: 2.5rem; color: var(--nb-muted); display: block; margin-bottom: 16px;"></i>
                <h3 class="fs-5 fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">Belum Ada Artikel</h3>
                <p style="color: var(--nb-muted); margin-bottom: 20px;">Tidak ada artikel untuk kategori yang Anda pilih.</p>
                <a href="{{ url('/informasi') }}" class="nb-btn nb-btn-primary d-inline-flex mx-auto">
                  Lihat Semua Artikel <i class="bi bi-arrow-right ms-1"></i>
                </a>
              </div>
            @endif
          @endif
        </div>

        @if(!$currentBlog)
          <!-- Sidebar (Right) -->
          <div class="col-lg-4 col-md-5 order-2">

            <!-- Category Filter -->
            <div class="card p-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
              <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom" style="border-color: rgba(30,30,30,0.15) !important;">
                <h3 class="profil-sidebar-title mb-0 border-0 p-0"><i class="bi bi-tags me-2"></i>Kategori</h3>
                @if($selectedCategory)
                  <a href="{{ url('/informasi') }}" class="nb-badge" style="text-decoration: none; font-size: 0.65rem;">
                    <i class="bi bi-x-circle me-1"></i>Reset
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
                     class="profil-social-link justify-content-between {{ $selectedCategory == $catSlug ? 'is-active' : '' }}"
                     style="{{ $selectedCategory == $catSlug ? 'background: var(--nb-accent) !important;' : '' }}">
                    <span>{{ $catName }}</span>
                    <span class="badge" style="background: var(--nb-bg-soft); color: var(--nb-ink); border: 1px solid #1E1E1E; font-family: var(--font-mono);">{{ $count }}</span>
                  </a>
                @endforeach
              </nav>
            </div>

            <!-- Recent Posts -->
            <div class="profil-trust-box">
              <h3 class="profil-sidebar-title"><i class="bi bi-clock-history me-2"></i>Berita Terbaru</h3>
              @if(count($recentPosts) > 0)
                <div class="d-flex flex-column gap-3">
                  @foreach($recentPosts as $index => $rPost)
                    <div class="p-3" style="background: var(--nb-card); border: 1.5px solid #1E1E1E; border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 #1E1E1E;">
                      <div class="d-flex align-items-center gap-2 mb-1" style="font-size: 0.72rem; color: var(--nb-muted); font-family: var(--font-mono); font-weight: 600;">
                        <i class="bi bi-calendar3"></i> {{ $rPost['date'] }}
                      </div>
                      <h4 style="font-size: 0.88rem; line-height: 1.45; margin: 0; font-family: var(--font-display); font-weight: 700;">
                        <a href="{{ url('/informasi') }}?detail={{ $rPost['slug'] }}" style="color: var(--nb-ink); text-decoration: none;" onmouseover="this.style.color='var(--nb-primary)'" onmouseout="this.style.color='var(--nb-ink)'">{{ $rPost['title'] }}</a>
                      </h4>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="profil-body-text mb-0">Belum ada berita terbaru.</p>
              @endif
            </div>

          </div>
        @endif
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
@endpush
