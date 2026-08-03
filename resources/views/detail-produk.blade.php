@extends('layouts.app')

@section('title', $product ? $product['title'] . ' - Prolabios' : 'Produk Tidak Ditemukan - Prolabios')

@if(isset($product) && $product)
  @section('og_title', $product['title'] . ' | PROLABIOS')
  @section('og_description', Str::limit(strip_tags($product['description']), 150))
  @section('og_image', $product['image'])
@endif

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Detail Produk</span>
      <p class="editorial-page-title">Produk &amp; Instrumen</p>
      <p class="editorial-page-subtitle">Informasi lengkap mengenai spesifikasi produk kami</p>
    </div>
  </div>

  <section class="section-main">
    <div class="container">
      <div class="row g-5">
        
        <!-- Main Content (Full Width) -->
        <div class="col-12">
          @if($product)
            <!-- Title Area -->
            <div style="border-bottom: 1px solid var(--color-border); padding-bottom: 24px; margin-bottom: 40px;">
              <h1 class="profil-section-title" style="font-size: 2.2rem !important; margin-bottom: 12px !important;">{{ $product['title'] }}</h1>
              @if(!empty($product['category']))
                <p class="profil-body-text mb-0 text-capitalize">
                  <span style="color: var(--color-accent); font-family: var(--font-headline); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; margin-right: 8px;">Kategori:</span> {{ str_replace('-', ' ', $product['category']) }}
                </p>
              @endif
            </div>

            <!-- Product Details Area -->
            <div class="row g-5">
              <!-- Product Image -->
              <div class="col-md-5">
                <div class="detail-product-img-wrap" data-bs-toggle="modal" data-bs-target="#imageLightboxModal" title="Klik untuk memperbesar gambar">
                  <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }} — Analytical Laboratory Instrument &amp; Reagent" class="w-100" style="object-fit: contain; max-height: 350px; display: block;" loading="lazy" decoding="async">
                </div>
              </div>
              
              <!-- Product Specs & Form -->
              <div class="col-md-7">
                @if(!empty($product['catalog']))
                  <div class="mb-4">
                    <span style="font-family: var(--font-headline); font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; color: var(--color-accent); border: 1px solid var(--color-accent); padding: 6px 14px; display: inline-block;">
                      Catalogue No: {{ $product['catalog'] }}
                    </span>
                  </div>
                @endif
                
                <!-- Price & Stock Info Box -->
                <div class="p-3 my-4 rounded border border-secondary border-opacity-20 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: rgba(255,255,255,0.02);">
                  <div>
                    <span class="text-muted small d-block">Harga Estimasi / Penawaran:</span>
                    <strong class="fs-4" style="color: var(--color-accent);">
                      {{ ($product['price'] ?? 0) > 0 ? 'Rp ' . number_format($product['price'], 0, ',', '.') : 'Hubungi Tim Penawaran' }}
                    </strong>
                  </div>
                  <div>
                    <span class="badge bg-success bg-opacity-20 text-success px-3 py-2">
                      <i class="bi bi-box-seam me-1"></i> Ready Stock
                    </span>
                  </div>
                </div>

                <div class="mt-4">
                  <h3 class="layanan-feature-title" style="font-size: 1rem !important; margin-bottom: 16px;">Deskripsi / Aplikasi</h3>
                  <div class="profil-body-text" style="line-height: 1.9;">
                    {!! \App\Services\DataService::sanitizeHtml($product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.') !!}
                  </div>
                </div>
                
                <form action="{{ route('cart.add') }}" method="POST" class="mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
                  @csrf
                  <input type="hidden" name="title" value="{{ $product['title'] }}">
                  
                  <div class="d-flex flex-wrap align-items-center gap-4">
                    {{-- Quantity Selector --}}
                    <div>
                      <label class="d-block text-uppercase fw-bold mb-2" style="font-size: 0.68rem; letter-spacing: 1.5px; color: var(--color-text-muted); font-family: var(--font-headline);">Jumlah Unit</label>
                      <div class="d-inline-flex align-items-center" style="border: 1px solid var(--color-border); background: var(--color-surface); height: 46px; border-radius: 4px;">
                        <button type="button" class="btn border-0 px-3 h-100 text-white-50 hover-white d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(-1)">
                          <i class="bi bi-dash-lg" style="font-size: 0.85rem;"></i>
                        </button>
                        <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="form-control text-center text-white bg-transparent border-0 fw-bold h-100 hide-spinner" style="width: 52px; font-size: 0.95rem; font-family: var(--font-headline); outline: none; box-shadow: none;">
                        <button type="button" class="btn border-0 px-3 h-100 text-white-50 hover-white d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(1)">
                          <i class="bi bi-plus-lg" style="font-size: 0.85rem;"></i>
                        </button>
                      </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-end gap-3 flex-grow-1" style="margin-top: 18px;">
                      <button type="submit" class="kontak-submit-btn border-0 cursor-pointer flex-grow-1" style="height: 46px; margin: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="bi bi-cart-plus me-2" style="font-size: 1.1rem;"></i> Tambah ke Keranjang RFQ
                      </button>
                      <a href="{{ url('/produk') }}" class="profil-cta-btn border-0 d-inline-flex align-items-center justify-content-center text-decoration-none" style="height: 46px; padding: 0 24px; font-size: 0.78rem;">
                        Kembali <i class="bi bi-arrow-right ms-2"></i>
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          @else
            <div class="empty-state-card">
              <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--color-text-muted); opacity: 0.4; display: block; margin-bottom: 20px;"></i>
              <h2 class="profil-section-title" style="font-size: 1.4rem !important;">Produk Tidak Ditemukan</h2>
              <p class="profil-body-text mb-4">Maaf, produk yang Anda cari tidak tersedia.</p>
              <a href="{{ url('/produk') }}" class="profil-cta-btn">Kembali ke Daftar Produk <i class="bi bi-arrow-right"></i></a>
            </div>
          @endif
        </div>
        
      </div>
    </div>
  </section>

  @if($product)
  <!-- Image Lightbox Modal -->
  <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-transparent border-0 shadow-none position-relative">
        <button type="button" class="btn-close-lightbox" data-bs-dismiss="modal" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
        <div class="modal-body text-center p-0">
          <div class="lightbox-image-wrapper">
            <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="lightbox-img" loading="lazy" decoding="async">
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <style>
    .detail-product-img-wrap {
      border: 1px solid var(--color-border);
      background-color: #070708;
      padding: 30px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    .detail-product-img-wrap img {
      filter: grayscale(30%);
      transition: all 0.4s ease;
    }
    .detail-product-img-wrap:hover img {
      filter: grayscale(0%) scale(1.02);
    }
    .detail-product-img-wrap::after {
      content: '\F52A';
      font-family: 'bootstrap-icons';
      position: absolute;
      bottom: 16px;
      right: 16px;
      width: 36px;
      height: 36px;
      background: rgba(7, 7, 8, 0.9);
      border: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,0.7);
      font-size: 0.95rem;
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .detail-product-img-wrap:hover::after {
      opacity: 1;
      transform: scale(1);
    }
    .lightbox-image-wrapper {
      padding: 12px;
      background: #070708;
      border: 1px solid var(--color-border);
      display: inline-block;
      max-width: 100%;
    }
    .lightbox-img {
      max-width: 100%;
      max-height: 75vh;
      object-fit: contain;
      display: block;
    }
    .btn-close-lightbox {
      position: absolute;
      top: -30px;
      right: 0px;
      width: 40px;
      height: 40px;
      background: #070708;
      border: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 1.1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 1060;
    }
    .btn-close-lightbox:hover {
      transform: scale(1.05) rotate(90deg);
      border-color: var(--color-accent);
      color: var(--color-accent);
    }
    .modal.fade .modal-dialog {
      transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1);
      transform: scale(0.95);
    }
    .modal.show .modal-dialog {
      transform: scale(1);
    }
  </style>
  <script>
    function stepQty(amount) {
      const input = document.getElementById('qty-input');
      if (input) {
        let val = parseInt(input.value) || 1;
        val = Math.max(1, val + amount);
        input.value = val;
      }
    }
  </script>
@endsection
