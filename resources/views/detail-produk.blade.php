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
                  <div class="border rounded p-3 text-center bg-light overflow-hidden product-image-zoom-trigger" style="transition: all 0.3s ease; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageLightboxModal" title="Klik untuk memperbesar gambar">
                    <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="img-fluid rounded" style="max-height: 350px; object-fit: contain; transition: transform 0.3s ease;">
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

  @if($product)
  <!-- Image Lightbox Modal -->
  <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-transparent border-0 shadow-none position-relative">
        <!-- Close Button (Premium Floating Style) -->
        <button type="button" class="btn-close-lightbox" data-bs-dismiss="modal" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
        <div class="modal-body text-center p-0">
          <div class="lightbox-image-wrapper">
            <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format/crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="lightbox-img">
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <style>
    /* Hover Zoom on Product Detail Card */
    .product-image-zoom-trigger {
      position: relative;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .product-image-zoom-trigger:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
      border-color: var(--color-primary, #D32F2F) !important;
    }
    .product-image-zoom-trigger::after {
      content: '\F52A'; /* Bootstrap Icon Search / Zoom */
      font-family: 'bootstrap-icons';
      position: absolute;
      bottom: 12px;
      right: 12px;
      width: 34px;
      height: 34px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #64748b;
      font-size: 0.95rem;
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      z-index: 10;
    }
    .product-image-zoom-trigger:hover::after {
      opacity: 1;
      transform: scale(1);
    }
    
    [data-theme="dark"] .product-image-zoom-trigger::after {
      background: rgba(17, 24, 39, 0.95);
      color: #94a3b8;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .product-image-zoom-trigger:hover img {
      transform: scale(1.03);
    }

    /* Lightbox Wrapper */
    .lightbox-image-wrapper {
      padding: 10px;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.4);
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
      display: inline-block;
      max-width: 100%;
    }

    [data-theme="dark"] .lightbox-image-wrapper {
      background: rgba(17, 24, 39, 0.75);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }

    .lightbox-img {
      max-width: 100%;
      max-height: 75vh;
      object-fit: contain;
      border-radius: 10px;
      display: block;
    }

    /* Floating Close Button */
    .btn-close-lightbox {
      position: absolute;
      top: -24px;
      right: 0px;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #334155;
      font-size: 1.1rem;
      cursor: pointer;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      z-index: 1060;
    }

    [data-theme="dark"] .btn-close-lightbox {
      background: rgba(31, 41, 55, 0.95);
      color: #f8fafc;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-close-lightbox:hover {
      transform: scale(1.1) rotate(90deg);
      background: var(--color-primary, #D32F2F);
      color: #ffffff;
      border-color: var(--color-primary, #D32F2F);
    }

    /* Zoom Transition Animation overrides for Bootstrap Modal */
    .modal.fade .modal-dialog {
      transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      transform: scale(0.9);
    }
    .modal.show .modal-dialog {
      transform: scale(1);
    }
  </style>
@endsection
