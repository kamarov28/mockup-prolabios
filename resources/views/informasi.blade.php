@extends('layouts.app')

@section('title', isset($currentBlog) && $currentBlog ? $currentBlog['title'] . ' | PROLABIOS' : 'News & Events | PROLABIOS')

@if(isset($currentBlog) && $currentBlog)
  @section('og_title', $currentBlog['title'])
  @section('og_description', Str::limit(strip_tags($currentBlog['content']), 150))
  @section('og_image', $currentBlog['image'])
@endif

@section('content')
  @if(!$currentBlog)
    <!-- Editorial Page Header -->
    <div class="editorial-page-header">
      <div class="container">
        <span class="editorial-page-label">News & Articles</span>
        <h1 class="editorial-page-title">Information</h1>
        <p class="editorial-page-subtitle">Latest news and articles about laboratory and industry</p>
      </div>
    </div>
  @endif

  <!-- Informasi Content -->
  <section class="section-main {{ $currentBlog ? 'blog-detail-section' : '' }}" @if($currentBlog) style="padding-top: 140px !important;" @endif>
    <div class="container">
      <div class="row g-5">

        <!-- Main Content -->
        <div class="{{ $currentBlog ? 'col-lg-10 col-xl-8 mx-auto' : 'col-lg-8 col-md-7 order-last order-md-1' }}">
          @if($currentBlog)
            <!-- Detail View -->
            <div>
              <a href="{{ url('/informasi') }}{{ $selectedCategory ? '?kategori=' . $selectedCategory : '' }}" class="profil-cta-btn mb-5 d-inline-flex" style="color: var(--color-text-muted) !important; border-color: var(--color-border);">
                <i class="bi bi-arrow-left"></i> Back to News
              </a>

              <div style="margin-bottom: 20px; margin-top: 20px;">
                <span style="font-family: var(--font-headline); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--color-accent); border: 1px solid var(--color-accent); padding: 4px 10px;">{{ $currentBlog['category'] }}</span>
                <span style="font-size: 0.82rem; color: var(--color-text-muted); margin-left: 16px;"><i class="bi bi-calendar3 me-1"></i>{{ $currentBlog['date'] }}</span>
              </div>

              <h2 class="profil-section-title" style="font-size: 2.2rem !important; margin-bottom: 24px !important;">{{ $currentBlog['title'] }}</h2>

              <div class="profil-hero-img mb-5">
                <img src="{{ $currentBlog['image'] }}" class="w-100" style="max-height: 450px; object-fit: cover; display: block;" alt="{{ $currentBlog['title'] }}" loading="lazy" decoding="async">
              </div>

              <div class="profil-body-text" style="line-height: 1.9;">
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
                    <div class="card h-100 blog-card position-relative">
                      <div class="blog-card-img-wrap">
                        <img src="{{ $post['image'] }}" class="card-img-top" alt="{{ $post['title'] }}" loading="lazy" decoding="async">
                        <div class="blog-card-date">
                          <span class="day">{{ $day }}</span>
                          <span class="month">{{ $month }}</span>
                        </div>
                      </div>
                      <div class="blog-card-body">
                        <span class="blog-card-category">{{ $post['category'] }}</span>
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
                {{ $posts->links('pagination::bootstrap-5') }}
              </div>

            @else
              <div class="empty-state-card">
                <i class="bi bi-newspaper" style="font-size: 3rem; color: var(--color-text-muted); opacity: 0.4; display: block; margin-bottom: 20px;"></i>
                <h2 class="profil-section-title" style="font-size: 1.4rem !important;">No Articles Yet</h2>
                <p class="profil-body-text mb-4">No articles found for this category.</p>
                <a href="{{ url('/informasi') }}" class="profil-cta-btn">View All Articles <i class="bi bi-arrow-right"></i></a>
              </div>
            @endif
          @endif
        </div>

        @if(!$currentBlog)
          <!-- Sidebar (Right) -->
          <div class="col-lg-4 col-md-5 order-first order-md-2">

            <!-- Category Filter -->
            <div class="mb-5">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0;">
                <h3 class="profil-sidebar-title" style="margin-bottom: 0; flex: 1;">Article Categories</h3>
                @if($selectedCategory)
                  <a href="{{ url('/informasi') }}" style="font-family: var(--font-headline); font-size: 0.7rem; color: var(--color-accent); text-decoration: none; text-transform: uppercase; letter-spacing: 1px;"><i class="bi bi-x-circle me-1"></i>Reset</a>
                @endif
              </div>
              <div style="border-bottom: 1px solid var(--color-border); margin-bottom: 16px; padding-bottom: 12px;"></div>
              <nav class="layanan-sidebar-nav">
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
                     class="layanan-sidebar-link {{ $selectedCategory == $catSlug ? 'is-active' : '' }}"
                     style="display: flex; justify-content: space-between;">
                    {{ $catName }}
                    <span style="font-size: 0.72rem; color: var(--color-text-muted);">{{ $count }}</span>
                  </a>
                @endforeach
              </nav>
            </div>

            <!-- Recent Posts -->
            <div>
              <h3 class="profil-sidebar-title">Latest News</h3>
              @if(count($recentPosts) > 0)
                @foreach($recentPosts as $index => $rPost)
                  <div style="padding: 14px 0; {{ $index !== count($recentPosts) - 1 ? 'border-bottom: 1px solid var(--color-border);' : '' }}">
                    <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 6px; font-family: var(--font-headline); text-transform: uppercase; letter-spacing: 1px;">
                      <i class="bi bi-calendar3 me-1"></i> {{ $rPost['date'] }}
                    </div>
                    <h4 style="font-size: 0.88rem; line-height: 1.5; margin: 0;">
                      <a href="{{ url('/informasi') }}?detail={{ $rPost['slug'] }}" style="color: rgba(255,255,255,0.75); text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='var(--color-accent)'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">{{ $rPost['title'] }}</a>
                    </h4>
                  </div>
                @endforeach
              @else
                <p class="profil-body-text">No recent news available.</p>
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
