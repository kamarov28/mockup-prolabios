@extends('layouts.app')

@section('title', 'Home | PROLABIOS')

@section('preload')
  @foreach($homeData['hero_images'] as $img)
    @if(!empty($img))
      <link rel="preload" href="{{ $img }}" as="image">
      @break
    @endif
  @endforeach
@endsection

@section('content')
  <!-- Hero Full-Width Banner -->
  <section class="bg-light py-5 position-relative overflow-hidden">
    <!-- Decorative Glowing Blobs -->
    <div class="position-absolute top-0 start-0 w-100 h-100 hero-glow-1 pointer-events-none" style="z-index: 0;"></div>
    <div class="position-absolute top-0 start-0 w-100 h-100 hero-glow-2 pointer-events-none" style="z-index: 0;"></div>

    <div class="container py-md-5 position-relative" style="z-index: 1;">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 z-1">
          <h1 class="display-4 fw-bold mb-4 animate-on-scroll animate-slide-up hero-headline" style="color: var(--color-secondary, #2b2d42);">
            {!! str_replace('Terpercaya', '<span class="highlight-text">Terpercaya</span>', $homeData['hero_title']) !!}
          </h1>
          <p class="lead text-muted mb-4 animate-on-scroll animate-slide-up delay-100">
            {{ $homeData['hero_subtitle'] }}
          </p>
          <div class="d-inline-flex flex-wrap gap-3 animate-on-scroll animate-slide-up delay-200">
            <a href="{{ url('/profil') }}" class="btn btn-primary btn-hero px-4 py-3 fw-bold shadow-sm">
              Lihat Profil Kami <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <a href="{{ url('/produk') }}" class="btn btn-outline-secondary btn-hero-outline px-4 py-3 fw-bold shadow-sm">
              Katalog Produk <i class="bi bi-box-seam ms-2"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-6 position-relative z-1 animate-on-scroll animate-scale-in delay-300">
          <!-- Decorative dotted grid behind slider -->
          <div class="position-absolute translate-middle-y start-0 w-100 h-100 opacity-20 d-none d-md-block" style="background-image: radial-gradient(var(--color-primary, #D32F2F) 1.5px, transparent 1.5px); background-size: 20px 20px; z-index: -1; transform: translate(-25px, 25px); width: 120px; height: 160px;"></div>

          <div class="rounded-4 overflow-hidden position-relative bg-dark hero-carousel-wrapper" style="aspect-ratio: 4/3;">
            <div id="heroSlideshow" class="carousel slide carousel-card-swap h-100" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false" data-bs-touch="true">
              <div class="carousel-indicators" style="z-index: 20;">
                @php $indicatorCount = 0; @endphp
                @foreach($homeData['hero_images'] as $index => $img)
                  @if(!empty($img))
                    <button type="button" data-bs-target="#heroSlideshow" data-bs-slide-to="{{ $indicatorCount }}" class="{{ $indicatorCount === 0 ? 'active' : '' }}" aria-current="{{ $indicatorCount === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $indicatorCount + 1 }}"></button>
                    @php $indicatorCount++; @endphp
                  @endif
                @endforeach
              </div>
              <div class="carousel-inner h-100">
                @php $firstImg = true; @endphp
                @foreach($homeData['hero_images'] as $img)
                  @if(!empty($img))
                    <div class="carousel-item {{ $firstImg ? 'active' : '' }} h-100">
                      <img src="{{ $img }}" class="d-block w-100 h-100" style="object-fit: cover; pointer-events: none;" alt="Laboratorium Prolabios">
                    </div>
                    @php $firstImg = false; @endphp
                  @endif
                @endforeach
              </div>
              <!-- Carousel Controls -->
              <button class="carousel-control-prev" type="button" data-bs-target="#heroSlideshow" data-bs-slide="prev" style="z-index: 20;">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#heroSlideshow" data-bs-slide="next" style="z-index: 20;">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Value Chain / Sector Cards -->
  <section class="py-5 bg-white">
    <div class="container py-4">
      <h2 class="text-center fw-bold mb-5 animate-on-scroll animate-slide-up" style="color: var(--color-secondary, #2b2d42);">{{ $homeData['focus_title'] }}</h2>

      <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($homeData['focus_cards'] as $card)
          <div class="col">
            <div class="card h-100 border-0 shadow-sm overflow-hidden animate-on-scroll animate-slide-up delay-{{ ($loop->index + 1) * 100 }}">
              <div class="card-img-wrap" style="height: 200px;">
                <img src="{{ $card['image'] }}" class="w-100 h-100" alt="{{ $card['title'] }}" style="object-fit: cover;" loading="lazy" decoding="async">
              </div>
              <div class="card-body p-4 bg-light">
                <div class="text-primary small fw-semibold text-uppercase mb-2">Value Chain</div>
                <h3 class="card-title fw-bold h4">{{ $card['title'] }}</h3>
                <p class="card-text text-muted small mt-3 mb-4">{{ $card['description'] }}</p>
                <a href="{{ url('/sektor') }}" class="text-primary text-decoration-none fw-semibold">Selengkapnya &raquo;</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Info Section (Our Story & Hotline) -->
  <section class="py-5" style="background-color: var(--color-bg-gray, #f8f9fa);">
    <div class="container">
      <div class="row g-0 overflow-hidden rounded-4 shadow-sm align-items-stretch">
        <div class="col-md-7 p-4 p-md-5 bg-white d-flex flex-column justify-content-center animate-on-scroll animate-slide-right">
          <h2 class="fw-bold mb-4">{{ $homeData['about_title'] }}</h2>
          <p class="text-muted" style="line-height: 1.8;">{{ $homeData['about_description'] }}</p>
          <div><a href="{{ url('/profil') }}" class="btn btn-secondary mt-3 px-4 py-2">Baca Sejarah Kami</a></div>
        </div>
        <div class="col-md-5 p-4 p-md-5 text-white d-flex flex-column justify-content-center animate-on-scroll animate-slide-left delay-100" style="background-color: var(--color-primary, #e63946);">
          <p class="text-uppercase small fw-bold mb-2">{{ $homeData['hotline_label'] }}</p>
          <h2 class="display-5 fw-bold mb-3">{{ $homeData['hotline_number'] }}</h2>
          <p class="mb-0">{{ $homeData['hotline_description'] }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Principal Section (Infinite Horizontal Scroll Marquee) -->
  <section class="py-5 bg-white principal-section overflow-hidden">
    <div class="container-fluid px-0 py-4">
      <div class="container text-center mb-5">
        <h2 class="fw-bold" style="color: var(--color-secondary, #2b2d42);">Principal Kami</h2>
        <p class="text-muted">Kami bekerja sama dengan principal internasional terpercaya untuk menyediakan produk dan layanan berkualitas.</p>
      </div>

      <div class="marquee-container">
        <!-- Group 1 -->
        <div class="marquee-content">
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bc0ca791541599e58a4b7619ebfa72e4.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/72ca09c33675c45d2c94f351826e9425.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/8750771283bf378d2d4d34f33023f39e.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/4d8c80a7c6f31d10456d0838a4f76147.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/2584fc7d85c83d05914c130ab2da4180.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/9757f7f424d9fbbac1b3cfd10357ead4.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/631128ee29e2354e6685a44cad7560cf.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/7296b8cb17186abb8eca80a5ff1ad710.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/19f38e3ab01d54b5475f78cf5f252c3b.jpeg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bf629c714fe27d7216ea8bb97b5e46ae.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/af5b6334db64050af4eaa8127c84e2bf.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/e3e89f927bf1432531884f64eb64d99d.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ab86de253e7289bc84fc8ba013292496.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
        </div>
        <!-- Group 2 (Duplicate for infinite seamless scrolling loop) -->
        <div class="marquee-content" aria-hidden="true">
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bc0ca791541599e58a4b7619ebfa72e4.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/72ca09c33675c45d2c94f351826e9425.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/8750771283bf378d2d4d34f33023f39e.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/4d8c80a7c6f31d10456d0838a4f76147.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/2584fc7d85c83d05914c130ab2da4180.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/9757f7f424d9fbbac1b3cfd10357ead4.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/631128ee29e2354e6685a44cad7560cf.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/7296b8cb17186abb8eca80a5ff1ad710.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/19f38e3ab01d54b5475f78cf5f252c3b.jpeg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bf629c714fe27d7216ea8bb97b5e46ae.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/af5b6334db64050af4eaa8127c84e2bf.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/e3e89f927bf1432531884f64eb64d99d.png?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
          <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ab86de253e7289bc84fc8ba013292496.jpg?v=3') }}" alt="Principal" loading="lazy" decoding="async"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Berita & Kegiatan Section -->
  <section class="py-5 bg-light">
    <div class="container py-4">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 animate-on-scroll animate-slide-up">
        <div>
          <h2 class="fw-bold" style="color: var(--color-secondary, #2b2d42);">Berita & Kegiatan</h2>
          <p class="text-muted mb-0">Update terbaru tentang event, training, dan aktivitas Prolabios.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/informasi') }}" class="btn btn-outline-primary px-4 py-2 fw-semibold">Lihat Semua Info</a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-md-3 g-4">
        @if(count($recentPosts) > 0)
          @foreach($recentPosts as $post)
            @php
              $dateParts = explode(' ', $post['date']);
              $day = isset($dateParts[0]) ? $dateParts[0] : '';
              $month = isset($dateParts[1]) ? $dateParts[1] : '';
            @endphp
            <div class="col">
              <div class="card h-100 blog-card position-relative animate-on-scroll animate-slide-up delay-{{ ($loop->index + 1) * 100 }}">
                <div class="blog-card-img-wrap">
                  <!-- Floating premium date badge -->
                  <div class="blog-card-date">
                    <span class="day">{{ $day }}</span>
                    <span class="month">{{ $month }}</span>
                  </div>
                  <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" loading="lazy" decoding="async">
                </div>
                <div class="blog-card-body">
                  <span class="blog-card-category">{{ $post['category'] }}</span>
                  <h3 class="blog-card-title">
                    <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}">{{ $post['title'] }}</a>
                  </h3>
                  <p class="blog-card-text">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 120) }}</p>
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
