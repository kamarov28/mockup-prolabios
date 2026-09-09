@extends('layouts.app')

@section('title', isset($currentBlog) && $currentBlog ? $currentBlog['title'] . ' | PROLABIOS' : 'Berita & Informasi | PROLABIOS')

@if(isset($currentBlog) && $currentBlog)
  @section('og_title', $currentBlog['title'])
  @section('og_description', Str::limit(strip_tags($currentBlog['content']), 150))
  @section('og_image', $currentBlog['image'])
@endif

@section('content')
  @if(!$currentBlog)
    @include('partials.subpage-hero', [
      'badge' => '<i class="bi bi-newspaper me-1"></i> BERITA & ARTIKEL',
      'title' => 'Pusat Informasi & Wawasan Industri',
      'subtitle' => 'Update rilis regulasi laboratorium, wawasan analitika pengujian, inovasi teknologi instrumen, dan agenda kegiatan PT Prolabios Mitra Analitika.'
    ])
  @endif

  <!-- Informasi Content -->
  <section class="section-spacious nb-section {{ $currentBlog ? 'blog-detail-section' : '' }}" @if($currentBlog) style="padding-top: 130px !important;" @endif>
    <div class="container">
      <div class="row g-4 g-lg-5">

        <!-- Main Content -->
        <div class="{{ $currentBlog ? 'col-lg-10 col-xl-8 mx-auto' : 'col-lg-8 col-md-7 order-1' }}">
          @if($currentBlog)
            <!-- Detail View -->
            <div class="card p-4 p-md-5">
              <a href="{{ url('/informasi') }}{{ $selectedCategory ? '?kategori=' . $selectedCategory : '' }}" class="nb-btn nb-btn-ghost mb-4 d-inline-flex blog-back-btn">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Informasi
              </a>

              <div class="d-flex align-items-center gap-3 mb-3">
                <span class="blog-card-category mb-0">{{ $currentBlog['category'] }}</span>
                <span class="blog-card-date-inline"><i class="bi bi-calendar3 me-1"></i>{{ $currentBlog['date'] }}</span>
              </div>

              <h1 class="profil-main-title blog-detail-title">{{ $currentBlog['title'] }}</h1>

              <div class="profil-hero-img mb-4">
                <img src="{{ $currentBlog['image'] }}" class="w-100 blog-detail-img" alt="{{ $currentBlog['title'] }}" loading="lazy" decoding="async">
              </div>

              <div class="profil-body-text blog-detail-body">
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
              <div class="col-12 text-center p-5 card">
                <i class="bi bi-newspaper blog-empty-icon"></i>
                <h3 class="fs-5 fw-bold blog-empty-title">Belum Ada Artikel</h3>
                <p class="blog-empty-text">Tidak ada artikel untuk kategori yang Anda pilih.</p>
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
            <div class="card p-4 mb-4">
              <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom blog-sidebar-head">
                <h3 class="profil-sidebar-title mb-0 border-0 p-0"><i class="bi bi-tags me-2"></i>Kategori</h3>
                @if($selectedCategory)
                  <a href="{{ url('/informasi') }}" class="nb-badge blog-reset-badge">
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
                     class="profil-social-link justify-content-between {{ $selectedCategory == $catSlug ? 'is-active' : '' }}">
                    <span>{{ $catName }}</span>
                    <span class="blog-cat-count">{{ $count }}</span>
                  </a>
                @endforeach
              </nav>
            </div>

            <!-- Recent Posts -->
            <div class="profil-trust-box">
              <h3 class="profil-sidebar-title"><i class="bi bi-clock-history me-2"></i>Berita Terbaru</h3>
              @if(count($recentPosts) > 0)
                <div class="blog-recent-list">
                  @foreach($recentPosts as $index => $rPost)
                    <a href="{{ url('/informasi') }}?detail={{ $rPost['slug'] }}" class="blog-recent-item">
                      <div class="blog-recent-date">
                        <i class="bi bi-calendar3"></i> {{ $rPost['date'] }}
                      </div>
                      <h4 class="blog-recent-title">{{ $rPost['title'] }}</h4>
                    </a>
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
