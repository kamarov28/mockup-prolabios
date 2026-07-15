@extends('layouts.app')

@section('title', 'Home | PROLABIOS Editorial')

@section('preload')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/experimental-typo.css') }}">
@endsection

@section('content')
  <!-- Hero Section (Typography Only) -->
  <section class="section-spacious typo-hero">
    <div class="container">
      <div class="gsap-reveal-fade">
        <div class="d-inline-block text-uppercase small fw-bold px-3 py-1 mb-4 text-white bg-primary rounded-1" style="width: fit-content; font-family: var(--font-headline); font-size: 0.75rem; letter-spacing: 2px;">
          {{ $siteSettings['company_name'] ?? 'Prolabios' }}
        </div>
        <h1 class="typo-hero-title">
          {!! str_replace('Terpercaya', '<span class="typo-outline">Terpercaya</span>', $homeData['hero_title']) !!}
        </h1>
        <p class="typo-lead">
          {{ $homeData['hero_subtitle'] }}
        </p>
        <div class="d-flex flex-wrap gap-4">
          <a href="{{ url('/profil') }}" class="typo-btn-link">
            Tentang Kami <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/produk') }}" class="typo-btn-link" style="border-color: rgba(255,255,255,0.3);">
            Katalog Produk <i class="bi bi-box-seam"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Continuous Running Text Marquee (Fira Code) -->
  <section class="typo-marquee">
    <div class="typo-marquee-content">
      PT PROLABIOS MITRA ANALITIKA &bull; PROFESSIONAL &bull; ROBUST &bull; OFFERING THE BEST &bull; MIKROBIOLOGI &bull; KULTUR &bull; INSTRUMEN PRESISI &bull; PT PROLABIOS MITRA ANALITIKA &bull; PROFESSIONAL &bull; ROBUST &bull; OFFERING THE BEST &bull; MIKROBIOLOGI &bull; KULTUR &bull; INSTRUMEN PRESISI &bull;
    </div>
    <div class="typo-marquee-content" aria-hidden="true">
      PT PROLABIOS MITRA ANALITIKA &bull; PROFESSIONAL &bull; ROBUST &bull; OFFERING THE BEST &bull; MIKROBIOLOGI &bull; KULTUR &bull; INSTRUMEN PRESISI &bull; PT PROLABIOS MITRA ANALITIKA &bull; PROFESSIONAL &bull; ROBUST &bull; OFFERING THE BEST &bull; MIKROBIOLOGI &bull; KULTUR &bull; INSTRUMEN PRESISI &bull;
    </div>
  </section>

  <!-- Sektor Fokus Section (Indeksal List) -->
  <section class="section-spacious">
    <div class="container">
      <div class="row mb-5 gsap-reveal-fade">
        <div class="col-lg-8">
          <h6 class="text-uppercase tracking-wider small mb-3 text-primary" style="font-family: var(--font-headline); font-size: 0.75rem; letter-spacing: 2px;">Value Chain</h6>
          <h2 class="display-5 fw-bold text-white mb-3" style="font-family: var(--font-headline); font-size: 2.2rem; letter-spacing: -1px;">{{ $homeData['focus_title'] }}</h2>
          <p class="text-muted" style="font-size: 1.05rem;">Fokus industri dan rantai nilai pelayanan kami untuk menyediakan solusi laboratorium berkualitas tinggi.</p>
        </div>
      </div>
      
      <div class="typo-index-list">
        @foreach($homeData['focus_cards'] as $index => $card)
          <div class="typo-index-item gsap-reveal-item" data-delay="{{ $index * 0.15 }}">
            <div class="typo-index-number">
              0{{ $index + 1 }}.
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

  <!-- Berita & Kegiatan Section (Clean Minimal List) -->
  <section class="section-spacious">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 gsap-reveal-fade">
        <div>
          <h6 class="text-uppercase tracking-wider small mb-3 text-primary" style="font-family: var(--font-headline); font-size: 0.75rem; letter-spacing: 2px;">Artikel &amp; Media</h6>
          <h2 class="fw-bold text-white" style="font-family: var(--font-headline); font-size: 2.2rem; letter-spacing: -1px;">Berita &amp; Kegiatan</h2>
          <p class="text-muted mb-0">Update terbaru tentang event, training, dan aktivitas Prolabios.</p>
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
              <div class="card typo-blog-card h-100 gsap-reveal-item" data-delay="{{ $loop->index * 0.15 }}">
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

  <!-- Append GSAP & ScrollTrigger Libraries via CDN -->
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
@endsection
