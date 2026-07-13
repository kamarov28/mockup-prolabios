@extends('layouts.app')

@section('title', isset($currentBlog) && $currentBlog ? $currentBlog['title'] . ' | PROLABIOS' : 'Berita & Kegiatan | PROLABIOS')
@section('meta_description', isset($currentBlog) && $currentBlog ? Str::limit(strip_tags($currentBlog['content']), 150) : 'Berita dan kegiatan terbaru Prolabios - Update event, training, artikel ilmiah, dan aktivitas perusahaan.')
@section('meta_keywords', isset($currentBlog) && $currentBlog ? 'berita, kegiatan, ' . $currentBlog['category'] . ', prolabios, artikel, training' : 'berita, kegiatan, event, training, artikel ilmiah, prolabios, aktivitas')
@section('canonical_url', isset($currentBlog) && $currentBlog ? url('/informasi?detail=' . $currentBlog['slug']) : url('/informasi'))

@if(isset($currentBlog) && $currentBlog)
  @section('og_title', $currentBlog['title'])
  @section('og_description', Str::limit(strip_tags($currentBlog['content']), 150))
  @section('og_image', $currentBlog['image'])
@endif

@section('preload')
  @if(isset($currentBlog) && $currentBlog)
    <link rel="preload" href="{{ $currentBlog['image'] }}" as="image">
  @else
    <link rel="preload" href="{{ $siteSettings['info_banner_image'] ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1920&q=80' }}" as="image">
  @endif
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['info_banner_image'] ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['info_title'] ?? 'Berita & Kegiatan' }}</h1>
      <p class="lead mb-0 text-light opacity-75">
        @if($currentBlog)
          Detail Artikel
        @else
          {{ $siteSettings['info_subtitle'] ?? 'Informasi terbaru, seminar, dan kegiatan teknis dari Prolabios' }}
        @endif
      </p>
    </div>
  </div>

  <!-- Informasi Content -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row">
        
        <!-- Main Content (Left) -->
        <div class="col-lg-8 col-md-7 mb-4">
          @if($currentBlog)
            <!-- Detail View -->
            <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 animate-on-scroll animate-slide-up">
              <a href="{{ url('/informasi') }}{{ $selectedCategory ? '?kategori=' . $selectedCategory : '' }}" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Berita
              </a>
              
              <div class="mb-3">
                <span class="badge bg-primary px-3 py-2 text-uppercase mb-2">{{ $currentBlog['category'] }}</span>
                <span class="text-muted ms-3 small"><i class="bi bi-calendar3 me-1"></i>{{ $currentBlog['date'] }}</span>
              </div>
              
              <h2 class="fw-bold mb-4" style="color: var(--color-secondary, #2b2d42);">{{ $currentBlog['title'] }}</h2>
              
              <img src="{{ $currentBlog['image'] }}" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height: 450px; object-fit: cover;" alt="{{ $currentBlog['title'] }}">
              
              <div class="lh-lg text-muted" style="text-align: justify;">
                {!! $currentBlog['content'] !!}
              </div>
            </div>
          @else
            <!-- List View -->
            @if(count($posts) > 0)
              <div class="row row-cols-1 row-cols-md-2 g-4 animate-on-scroll animate-slide-up">
                @foreach($posts as $post)
                  @php
                    $dateParts = explode(' ', $post['date']);
                    $day = isset($dateParts[0]) ? $dateParts[0] : '';
                    $month = isset($dateParts[1]) ? $dateParts[1] : '';
                  @endphp
                  <div class="col">
                    <div class="card h-100 blog-card position-relative animate-on-scroll animate-slide-up delay-{{ ($loop->index % 2 + 1) * 100 }}">
                      <div class="blog-card-img-wrap">
                        <img src="{{ $post['image'] }}" class="card-img-top" alt="{{ $post['title'] }}">
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
              @if($totalPages > 1)
                <nav aria-label="Page navigation" class="mt-5 animate-on-scroll animate-scale-in delay-200">
                  <ul class="pagination justify-content-center">
                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                      <a class="page-link" href="{{ url('/informasi') }}?page={{ $currentPage - 1 }}{{ $selectedCategory ? '&kategori=' . $selectedCategory : '' }}">Sebelumnya</a>
                    </li>
                    @for($i = 1; $i <= $totalPages; $i++)
                      <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ url('/informasi') }}?page={{ $i }}{{ $selectedCategory ? '&kategori=' . $selectedCategory : '' }}">{{ $i }}</a>
                      </li>
                    @endfor
                    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                      <a class="page-link" href="{{ url('/informasi') }}?page={{ $currentPage + 1 }}{{ $selectedCategory ? '&kategori=' . $selectedCategory : '' }}">Selanjutnya</a>
                    </li>
                  </ul>
                </nav>
              @endif
            @else
              <div class="bg-white p-5 rounded text-center shadow-sm border-0 animate-on-scroll animate-scale-in">
                <i class="bi bi-newspaper display-1 text-muted opacity-50 mb-3"></i>
                <h2 class="h4 fw-bold">Belum Ada Artikel</h2>
                <p class="text-muted mb-4">Tidak ada artikel yang ditemukan untuk kategori ini.</p>
                <a href="{{ url('/informasi') }}" class="btn btn-primary px-4">Lihat Semua Artikel</a>
              </div>
            @endif
          @endif
        </div>

        <!-- Sidebar (Right) -->
        <div class="col-lg-4 col-md-5 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-left">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-primary border-2">
              <h2 class="h5 fw-bold mb-0" style="color: var(--color-secondary, #2b2d42);">Kategori Artikel</h2>
              @if($selectedCategory)
                <a href="{{ url('/informasi') }}" class="text-primary text-decoration-none small fw-semibold"><i class="bi bi-x-circle me-1"></i>Reset</a>
              @endif
            </div>
            <div class="list-group list-group-flush">
              @foreach($categoryCounts as $catName => $count)
                @php
                  // Build category slugs
                  $catSlug = '';
                  if ($catName === 'Berita') $catSlug = 'berita';
                  elseif ($catName === 'Event') $catSlug = 'event';
                  elseif ($catName === 'Info Terkait') $catSlug = 'info';
                  elseif ($catName === 'IPTEK') $catSlug = 'iptek';
                  elseif ($catName === 'Kegiatan') $catSlug = 'kegiatan';
                @endphp
                <a href="{{ url('/informasi') }}?kategori={{ $catSlug }}" class="list-group-item list-group-item-action sector-sidebar-link d-flex justify-content-between align-items-center py-2 {{ $selectedCategory == $catSlug ? 'active' : '' }}">
                  {{ $catName }}
                  <span class="badge {{ $selectedCategory == $catSlug ? 'bg-light text-primary' : 'bg-primary' }} rounded-pill">{{ $count }}</span>
                </a>
              @endforeach
            </div>
          </div>
          
          <div class="bg-white p-4 rounded shadow-sm border-0 animate-on-scroll animate-slide-left delay-100">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Info Terkini</h2>
            
            @if(count($recentPosts) > 0)
              @foreach($recentPosts as $index => $rPost)
                <div class="mb-3 {{ $index === count($recentPosts) - 1 ? '' : 'pb-3 border-bottom' }}">
                  <div class="text-muted small mb-1"><i class="bi bi-calendar3 me-1"></i> {{ $rPost['date'] }}</div>
                  <h3 class="fs-6 fw-bold mb-0">
                    <a href="{{ url('/informasi') }}?detail={{ $rPost['slug'] }}" class="text-decoration-none text-dark hover-primary lh-base">{{ $rPost['title'] }}</a>
                  </h3>
                </div>
              @endforeach
            @else
              <p class="text-muted small mb-0">Belum ada info terkini.</p>
            @endif
            
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
