@extends('layouts.app')

@php
  $seoTitle = $product
    ? ($product['title'] . (!empty($product['catalog']) ? ' (' . $product['catalog'] . ')' : '') . ' | PROLABIOS')
    : 'Produk Tidak Ditemukan | PROLABIOS';
  $seoDesc = $product
    ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($product['description'] ?? ''))), 155)
    : 'Produk laboratorium Prolabios tidak ditemukan.';
  if ($product && $seoDesc === '') {
    $seoDesc = 'Spesifikasi dan penawaran ' . $product['title'] . ' dari PT. Prolabios Mitra Analitika.';
  }
  $seoImage = '';
  if ($product && !empty($product['image'])) {
    $seoImage = str_starts_with($product['image'], 'http')
      ? $product['image']
      : url($product['image']);
  }
  $seoKeywords = $product
    ? implode(', ', array_filter([
        $product['title'] ?? null,
        $product['catalog'] ?? null,
        !empty($product['category']) ? str_replace('-', ' ', $product['category']) : null,
        'prolabios',
        'alat laboratorium',
      ]))
    : 'prolabios, alat laboratorium';
  $seoCanonical = $product ? product_url($product) : url('/produk');
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', $seoKeywords)
@section('canonical', $seoCanonical)

{{-- OG / Twitter (layout yields these) --}}
@section('og_type', 'product')
@section('og_title', $seoTitle)
@section('og_description', $seoDesc)
@section('og_image', $seoImage)

@section('content')
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Detail Produk</span>
      <p class="editorial-page-title">Produk & Instrumen</p>
      <p class="editorial-page-subtitle">Informasi lengkap mengenai spesifikasi produk kami</p>
    </div>
  </div>

  <section class="section-main">
    <div class="container">
      <div class="row g-5">
        <div class="col-12">
          @if($product)
            @php
              $galleryImages = !empty($product['gallery_images']) ? $product['gallery_images'] : [];
              $mainImage = $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
              $allImages = array_values(array_unique(array_merge([$mainImage], $galleryImages)));
            @endphp

            <div style="border-bottom: 2px solid var(--nb-ink); padding-bottom: 24px; margin-bottom: 40px;">
              <h1 class="profil-section-title" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem) !important; margin-bottom: 12px !important;">{{ $product['title'] }}</h1>
              @if(!empty($product['category']))
                <p class="profil-body-text mb-0 text-capitalize">
                  <span class="nb-badge-sm me-2">Kategori</span>
                  <span class="fw-semibold text-dark">{{ str_replace('-', ' ', $product['category']) }}</span>
                </p>
              @endif
            </div>

            <div class="row g-5">
              <div class="col-md-5">
                <div class="detail-product-img-wrap" data-bs-toggle="modal" data-bs-target="#imageLightboxModal" title="Klik untuk memperbesar gambar">
                  <img id="main-product-image" src="{{ $mainImage }}" alt="{{ $product['title'] }} — Instrumen & Reagen Laboratorium" class="w-100" style="object-fit: contain; max-height: 350px; display: block;" loading="lazy" decoding="async">
                </div>
                @if(count($allImages) > 1)
                  <div class="d-flex gap-2 mt-3 flex-wrap product-gallery-thumbs">
                    @foreach($allImages as $imgPath)
                      <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-img="{{ $imgPath }}" onclick="switchProductImage('{{ $imgPath }}', this)">
                        <img src="{{ $imgPath }}" alt="Foto produk {{ $loop->iteration }}" loading="lazy" decoding="async">
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="col-md-7">
                <div class="mb-4 d-flex flex-wrap gap-2 align-items-center">
                  @if(!empty($product['catalog']))
                    <div class="product-cat-code" style="font-size: 0.8rem !important; padding: 6px 12px !important;">
                      CAT. {{ $product['catalog'] }}
                    </div>
                  @endif

                  @if(!empty($product->principal))
                    <span class="nb-badge-sm d-inline-flex align-items-center gap-1" style="font-size: 0.75rem; padding: 5px 10px;">
                      <i class="bi bi-building text-primary"></i> {{ $product->principal->name }}
                      @if(!empty($product->principal->address))
                        <span class="text-muted ms-1">({{ $product->principal->address }})</span>
                      @endif
                    </span>
                  @endif
                </div>

                <div class="card p-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
                  <h3 class="layanan-feature-title mb-3" style="font-size: 1.1rem !important; font-family: var(--font-display); font-weight: 700; color: var(--nb-ink); border-bottom: 2px solid rgba(30,30,30,0.1); padding-bottom: 8px;">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Deskripsi & Spesifikasi Produk
                  </h3>
                  <div class="profil-body-text" style="line-height: 1.8; color: var(--nb-ink);">
                    {!! \App\Services\DataService::sanitizeHtml($product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.') !!}
                  </div>
                </div>

                <div class="mt-4 pt-2 d-flex flex-wrap gap-3">
                  <a href="{{ url('/produk/beli') }}?id={{ $product['id'] }}" class="nb-btn nb-btn-primary d-inline-flex align-items-center justify-content-center text-decoration-none" style="height: 48px; padding: 0 24px; font-size: 0.88rem;">
                    <i class="bi bi-cart-check me-2" style="font-size: 1.15rem;"></i> Permintaan Penawaran & Harga
                  </a>
                  <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center justify-content-center text-decoration-none" style="height: 48px; padding: 0 20px; font-size: 0.85rem;">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog
                  </a>
                </div>
              </div>
            </div>

            {{-- Lightbox --}}
            <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0 shadow-none position-relative">
                  <button type="button" class="btn-close-lightbox" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                  </button>
                  <div class="modal-body text-center p-0">
                    <div class="lightbox-image-wrapper">
                      <img id="lightbox-product-image" src="{{ $mainImage }}" alt="{{ $product['title'] }}" class="lightbox-img" loading="lazy" decoding="async">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- JSON-LD --}}
            <script type="application/ld+json">
            {!! json_encode([
              '@context' => 'https://schema.org/',
              '@type' => 'Product',
              'name' => $product['title'],
              'image' => [
                !empty($product['image'])
                  ? (str_starts_with($product['image'], 'http') ? $product['image'] : url($product['image']))
                  : asset('images/placeholder.svg'),
              ],
              'description' => \Illuminate\Support\Str::limit(strip_tags($product['description'] ?? 'Instrumen dan reagen laboratorium analitika berkualitas tinggi dari PT. Prolabios Mitra Analitika.'), 200),
              'sku' => !empty($product['catalog']) ? $product['catalog'] : ('PLB-' . $product['id']),
              'mpn' => !empty($product['catalog']) ? $product['catalog'] : ('PLB-' . $product['id']),
              'brand' => [
                '@type' => 'Brand',
                'name' => !empty($product['sector']) ? ucwords(str_replace('-', ' ', $product['sector'])) : 'Prolabios',
              ],
              'category' => !empty($product['category']) ? ucwords(str_replace('-', ' ', $product['category'])) : 'Laboratorium',
              'offers' => [
                '@type' => 'Offer',
                'url' => product_url($product),
                'priceCurrency' => 'IDR',
                'price' => (!empty($product['price']) && $product['price'] > 0) ? (float) $product['price'] : 0,
                'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => ((!isset($product['stock']) || (int) $product['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder'),
                'seller' => [
                  '@type' => 'Organization',
                  'name' => 'PT. Prolabios Mitra Analitika',
                ],
              ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
            <script type="application/ld+json">
            {!! json_encode([
              '@context' => 'https://schema.org',
              '@type' => 'BreadcrumbList',
              'itemListElement' => [
                [
                  '@type' => 'ListItem',
                  'position' => 1,
                  'name' => 'Beranda',
                  'item' => url('/'),
                ],
                [
                  '@type' => 'ListItem',
                  'position' => 2,
                  'name' => 'Katalog Produk',
                  'item' => url('/produk'),
                ],
                [
                  '@type' => 'ListItem',
                  'position' => 3,
                  'name' => $product['title'],
                  'item' => product_url($product),
                ],
              ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
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
      background: rgba(166, 23, 28, 0.85);
      border: 1px solid rgba(166,23,28,0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
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
    .product-gallery-thumbs {
      justify-content: center;
    }
    .gallery-thumb {
      width: 64px;
      height: 64px;
      border: 1px solid var(--color-border);
      background-color: #070708;
      padding: 4px;
      cursor: pointer;
      opacity: 0.55;
      transition: all 0.2s ease;
    }
    .gallery-thumb img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .gallery-thumb:hover {
      opacity: 0.85;
    }
    .gallery-thumb.active {
      opacity: 1;
      border-color: var(--color-accent);
    }
  </style>
  <script>
    function switchProductImage(src, thumbEl) {
      const mainImg = document.getElementById('main-product-image');
      const lightboxImg = document.getElementById('lightbox-product-image');
      if (mainImg) mainImg.src = src;
      if (lightboxImg) lightboxImg.src = src;
      document.querySelectorAll('.gallery-thumb').forEach(function (el) {
        el.classList.remove('active');
      });
      if (thumbEl) thumbEl.classList.add('active');
    }
  </script>
@endsection
