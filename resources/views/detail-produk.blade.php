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
  $productSlug = $product
    ? (is_array($product) ? ($product['slug'] ?? null) : ($product->slug ?? null))
    : null;
  $beliUrl = $productSlug
    ? route('produk.beli', ['slug' => $productSlug])
    : url('/produk');
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

            <div class="d-flex align-items-start justify-content-between flex-wrap gap-4 detail-header-divider">
              <div class="flex-grow-1 detail-header-copy">
                <h1 class="profil-section-title detail-product-title">{{ $product['title'] }}</h1>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                  @if(!empty($product['category']))
                    <span class="nb-badge-sm text-capitalize">{{ str_replace('-', ' ', $product['category']) }}</span>
                  @endif
                  @if(!empty($product['catalog']))
                    <span class="product-cat-code">
                      CAT. {{ $product['catalog'] }}
                    </span>
                  @endif
                </div>
              </div>

              @if(!empty($product->principal))
                <div class="detail-principal-card">
                  @if(!empty($product->principal->logo))
                    <div class="detail-principal-logo-box">
                      <img src="{{ str_starts_with($product->principal->logo, 'http') || str_starts_with($product->principal->logo, '/') ? $product->principal->logo : asset('storage/' . $product->principal->logo) }}"
                           alt="Logo {{ $product->principal->name }}">
                    </div>
                  @endif
                  <div>
                    <span class="d-block text-muted text-uppercase fw-bold detail-principal-label">Prinsipal Resmi</span>
                    <strong class="d-block text-dark detail-principal-name">{{ $product->principal->name }}</strong>
                    @if(!empty($product->principal->address))
                      <span class="text-muted small detail-principal-address"><i data-lucide="map-pin" class="me-1"></i>{{ $product->principal->address }}</span>
                    @endif
                  </div>
                </div>
              @endif
            </div>

            <div class="row g-5">
              <div class="col-md-5">
                <div class="detail-product-img-wrap" data-bs-toggle="modal" data-bs-target="#imageLightboxModal" title="Klik untuk memperbesar gambar">
                  <img id="main-product-image" src="{{ $mainImage }}" alt="{{ $product['title'] }} — Instrumen & Reagen Laboratorium" class="w-100 detail-product-img" loading="lazy" decoding="async">
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
                    <div class="product-cat-code">
                      CAT. {{ $product['catalog'] }}
                    </div>
                  @endif

                  @if(!empty($product->principal))
                    <span class="nb-badge-sm d-inline-flex align-items-center gap-1">
                      <i data-lucide="building" class="text-primary"></i> {{ $product->principal->name }}
                      @if(!empty($product->principal->address))
                        <span class="text-muted ms-1">({{ $product->principal->address }})</span>
                      @endif
                    </span>
                  @endif
                </div>

                <div class="card p-4 mb-4">
                  <h3 class="layanan-feature-title detail-section-heading mb-3">
                    <i data-lucide="file-text" class="text-primary me-2"></i>Deskripsi & Spesifikasi Produk
                  </h3>
                  <div class="profil-body-text mb-4">
                    {!! \App\Services\DataService::sanitizeHtml($product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.') !!}
                  </div>

                  {{-- B2B Technical Datasheet & Specification Link --}}
                  <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-3 rfq-details-box">
                    <div class="d-flex align-items-center gap-3">
                      <div class="rfq-trust-icon">
                        <i data-lucide="file-text" class="text-danger"></i>
                      </div>
                      <div>
                        <strong class="d-block detail-datasheet-title">Dokumen Lembar Data & Spesifikasi Teknis (PDF)</strong>
                        <span class="text-muted small detail-datasheet-sub">
                          @if(!empty($product->principal))
                            Brosur teknis & lembar data spesifikasi resmi dari {{ $product->principal->name }}.
                          @else
                            Lembar spesifikasi dan petunjuk teknis analitika dari prinsipal resmi.
                          @endif
                        </span>
                      </div>
                    </div>
                    @if(!empty($product['datasheet_url']))
                      <a href="{{ $product['datasheet_url'] }}" target="_blank" rel="noopener noreferrer" class="nb-btn nb-btn-primary d-inline-flex align-items-center gap-2 detail-btn-sm">
                        <i data-lucide="download"></i> Unduh Spesifikasi (PDF) <i data-lucide="external-link" class="ms-1"></i>
                      </a>
                    @else
                      <a href="{{ url('/kontak') }}?subjek=consultation&pesan={{ urlencode('Permintaan lembar data teknis / MSDS / CoA resmi untuk produk: ' . $product['title'] . (!empty($product['catalog']) ? ' (CAT. ' . $product['catalog'] . ')' : '')) }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2 detail-btn-ghost-sm">
                        <i data-lucide="mail"></i> Request Lembar Data Resmi <i data-lucide="arrow-right" class="ms-1"></i>
                      </a>
                    @endif
                  </div>
                </div>

                <div class="mt-2 d-flex flex-wrap gap-3">
                  <a href="{{ $beliUrl }}" class="nb-btn nb-btn-primary detail-nav-btn text-decoration-none">
                    <i data-lucide="shopping-cart" class="me-2"></i> Minta Penawaran
                  </a>
                  <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost detail-nav-btn text-decoration-none">
                    <i data-lucide="arrow-left" class="me-2"></i> Kembali ke Katalog
                  </a>
                </div>
              </div>
            </div>

            {{-- Lightbox Modal --}}
            <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true" data-bs-backdrop="true">
              <div class="modal-dialog modal-dialog-centered modal-xl detail-lightbox-dialog">
                <div class="modal-content bg-transparent border-0 shadow-none position-relative">
                  <button type="button" class="btn-close-lightbox" data-bs-dismiss="modal" aria-label="Tutup">
                    <i data-lucide="x"></i>
                  </button>
                  <div class="modal-body text-center p-0" data-bs-dismiss="modal">
                    <div class="lightbox-image-wrapper" onclick="event.stopPropagation();">
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
              <i data-lucide="package" class="detail-empty-icon"></i>
              <h2 class="profil-section-title detail-empty-title">Produk Tidak Ditemukan</h2>
              <p class="profil-body-text mb-4">Maaf, produk yang Anda cari tidak tersedia.</p>
              <a href="{{ url('/produk') }}" class="profil-cta-btn">Kembali ke Daftar Produk <i data-lucide="arrow-right"></i></a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
