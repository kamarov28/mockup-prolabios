@extends('layouts.app')

@section('title', $product ? $product['title'] . ' - Prolabios' : 'Produk Tidak Ditemukan - Prolabios')

@if(isset($product) && $product)
  @section('og_title', $product['title'] . ' | PROLABIOS')
  @section('og_description', Str::limit(strip_tags($product['description']), 150))
  @section('og_image', $product['image'])
@endif

@section('preload')
  @if(isset($product) && $product)
    <link rel="preload" href="{{ $product['image'] }}" as="image">
  @else
    <link rel="preload" href="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80" as="image">
  @endif
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">Detail Produk</h1>
      <p class="lead mb-0 text-light opacity-75">Informasi lengkap mengenai spesifikasi produk kami</p>
    </div>
  </div>

  <section class="py-5 bg-light">
    <div class="container">
      <div class="row">
        
        <!-- Main Content (Left) -->
        <div class="col-lg-9 col-md-8 mb-4">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 animate-on-scroll animate-slide-up">
            @if($product)
              <!-- Title Area -->
              <div class="border-bottom pb-4 mb-4">
                <h2 class="fw-bold mb-2" style="color: var(--color-secondary, #2b2d42);">{{ $product['title'] }}</h2>
                @if(!empty($product['category']))
                <p class="text-muted mb-0 text-capitalize">
                  <span class="fw-bold" style="color: var(--color-primary, #e63946);">Kategori:</span> {{ str_replace('-', ' ', $product['category']) }}
                </p>
                @endif
              </div>

              <!-- Product Details Area -->
              <div class="row g-4">
                <!-- Product Image -->
                <div class="col-md-5 animate-on-scroll animate-scale-in delay-100">
                  <div class="border rounded p-3 text-center bg-light overflow-hidden" style="transition: box-shadow 0.3s ease;">
                    <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="img-fluid rounded" style="max-height: 350px; object-fit: contain;">
                  </div>
                </div>
                
                <!-- Product Info -->
                <div class="col-md-7 animate-on-scroll animate-slide-left delay-200">
                  @if(!empty($product['catalog']))
                  <div class="mb-3">
                    <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill">
                      Catalogue No: {{ $product['catalog'] }}
                    </span>
                  </div>
                  @endif
                  
                  <div class="product-description mt-4">
                    <h3 class="h5 fw-bold text-dark mb-3">Deskripsi / Aplikasi</h3>
                    <div class="lh-lg text-muted" style="text-align: justify;">
                      {!! $product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.' !!}
                    </div>
                  </div>
                  
                  <div class="d-flex flex-wrap gap-2 mt-4 pt-3">
                    <a href="{{ url('/kontak') }}?subjek=inquiry&produk={{ urlencode($product['title']) }}" class="btn btn-primary px-4 py-2 fw-semibold">
                      <i class="bi bi-envelope me-2"></i> Minta Penawaran
                    </a>
                    <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prolabios,%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product['title']) }}" target="_blank" rel="noopener noreferrer" class="btn btn-success px-4 py-2 fw-semibold" style="background-color: #25D366; border-color: #25D366; color: #ffffff;">
                      <i class="bi bi-whatsapp me-2"></i> Tanya via WhatsApp
                    </a>
                    <a href="{{ url('/produk') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                      Kembali ke Katalog
                    </a>
                  </div>
                </div>
              </div>
            @else
              <div class="text-center py-5">
                <div class="display-1 text-muted opacity-50 mb-4"><i class="bi bi-box-seam"></i></div>
                <h2 class="fw-bold">Produk Tidak Ditemukan</h2>
                <p class="text-muted">Maaf, produk yang Anda cari tidak tersedia atau URL tidak valid.</p>
                <a href="{{ url('/produk') }}" class="btn btn-primary mt-3 px-4 py-2 fw-semibold">Kembali ke Daftar Produk</a>
              </div>
            @endif
          </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 animate-on-scroll animate-slide-left delay-100">
            <h3 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Kategori Produk</h3>
            <div class="list-group list-group-flush">
              <a href="{{ url('/produk') }}" class="list-group-item list-group-item-action sector-sidebar-link py-2">Semua Produk</a>
              <a href="{{ url('/produk') }}?kategori=culture-media" class="list-group-item list-group-item-action sector-sidebar-link py-2">Culture Media</a>
              <a href="{{ url('/produk') }}?kategori=instruments" class="list-group-item list-group-item-action sector-sidebar-link py-2">Instruments</a>
              <a href="{{ url('/produk') }}?kategori=chemicals" class="list-group-item list-group-item-action sector-sidebar-link py-2">Chemicals & Reagents</a>
              <a href="{{ url('/produk') }}?kategori=consumables" class="list-group-item list-group-item-action sector-sidebar-link py-2">Consumables</a>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>
@endsection
