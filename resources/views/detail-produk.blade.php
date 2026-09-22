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
      <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ url('/produk') }}" class="nb-mono small text-muted text-decoration-none">
          <i data-lucide="arrow-left" style="width: 14px; height: 14px; vertical-align: -2px;"></i> Katalog Produk
        </a>
        @if(!empty($product['category']))
          <span class="text-muted small">/</span>
          <span class="nb-mono small text-muted text-capitalize">{{ str_replace('-', ' ', $product['category']) }}</span>
        @endif
      </div>
      <p class="editorial-page-title">{{ $product['title'] ?? 'Detail Produk' }}</p>
      <p class="editorial-page-subtitle">Spesifikasi teknis, ketersediaan lot stok, dan pengajuan penawaran resmi</p>
    </div>
  </div>

  <section class="section-main detail-product-page">
    <div class="container">
      @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-20 alert-dismissible fade show rounded-0 mb-4" role="alert">
          <i data-lucide="check-circle-2" class="me-2"></i> {{ session('success') }}
          <a href="{{ route('cart.index') }}" class="fw-bold text-success text-decoration-underline ms-2">Buka Keranjang Pengajuan &rarr;</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
      @endif

      <div class="row g-5">
        <div class="col-12">
          @if($product)
            @php
              $galleryImages = !empty($product['gallery_images']) ? $product['gallery_images'] : [];
              $mainImage = !empty($product['image']) ? $product['image'] : asset('images/placeholder.svg');
              $allImages = array_values(array_unique(array_merge([$mainImage], $galleryImages)));
              $stock = (int) ($product['stock'] ?? 0);
              $price = (float) ($product['price'] ?? 0);
              $productWaMsg = 'Halo Tim Sales Prolabios, saya ingin konsultasi ketersediaan dan penawaran resmi untuk produk ' . $product['title'] . (!empty($product['catalog']) ? ' (Cat: ' . $product['catalog'] . ')' : '') . '. Terima kasih.';
              $targetWa = !empty($siteSettings['whatsapp_number']) ? preg_replace('/[^0-9]/', '', $siteSettings['whatsapp_number']) : '6282187929433';
              $productWaUrl = 'https://wa.me/' . $targetWa . '?text=' . rawurlencode($productWaMsg);
            @endphp

            <div class="d-flex align-items-start justify-content-between flex-wrap gap-4 detail-header-divider">
              <div class="flex-grow-1 detail-header-copy">
                <h1 class="profil-section-title detail-product-title">{{ $product['title'] }}</h1>
                <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                  @if(!empty($product['catalog']))
                    <span class="product-cat-code">CAT. {{ $product['catalog'] }}</span>
                  @endif
                  @if(!empty($product['category']))
                    <span class="text-muted small">·</span>
                    <span class="text-muted small text-capitalize">{{ str_replace('-', ' ', $product['category']) }}</span>
                  @endif
                  @if(!empty($product->principal))
                    <span class="text-muted small">·</span>
                    <span class="text-dark small fw-semibold">{{ $product->principal->name }}</span>
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
                      <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}" data-img="{{ $imgPath }}" role="button" tabindex="0" aria-label="Foto produk {{ $loop->iteration }}">
                        <img src="{{ $imgPath }}" alt="Foto produk {{ $loop->iteration }}" loading="lazy" decoding="async">
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="col-md-7">
                <div class="card p-4 p-lg-5" style="border: none !important; border-radius: 12px; background: #FFFFFF; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">

                  {{-- 1. Deskripsi & Spesifikasi Produk (At the top per user requirement) --}}
                  <div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                      <h2 class="fs-6 fw-bold text-uppercase tracking-wider text-dark mb-0 d-flex align-items-center gap-2" style="font-family: var(--font-display); letter-spacing: 0.5px;">
                        <i data-lucide="file-text" style="width: 16px; height: 16px; color: var(--nb-primary);"></i> Deskripsi &amp; Spesifikasi Produk
                      </h2>
                      @if(!empty($product['datasheet_url']))
                        <a href="{{ $product['datasheet_url'] }}" target="_blank" rel="noopener noreferrer" class="d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold small" style="color: var(--nb-primary); font-size: 0.82rem;">
                          <i data-lucide="download" style="width: 14px; height: 14px;"></i> Unduh Spesifikasi (PDF) <i data-lucide="external-link" style="width: 11px; height: 11px;" class="ms-1"></i>
                        </a>
                      @endif
                    </div>

                    <div class="profil-body-text mb-3 text-muted" style="line-height: 1.7; font-size: 0.92rem;">
                      {!! \App\Helpers\HtmlSanitizer::clean($product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.') !!}
                    </div>

                    @if(empty($product['datasheet_url']))
                      <div class="mb-1">
                        <a href="{{ url('/kontak') }}?subjek=consultation&pesan={{ urlencode('Permintaan lembar data teknis / MSDS / CoA resmi untuk produk: ' . $product['title'] . (!empty($product['catalog']) ? ' (CAT. ' . $product['catalog'] . ')' : '')) }}" class="d-inline-flex align-items-center gap-2 text-decoration-none text-muted small" style="font-size: 0.8rem;">
                          <i data-lucide="mail" style="width: 13px; height: 13px;"></i> Request Lembar Data Resmi / COA ke Sales &rarr;
                        </a>
                      </div>
                    @endif
                  </div>

                  {{-- Subtle Divider --}}
                  <div class="my-4 border-top" style="border-color: rgba(0,0,0,0.08) !important;"></div>

                  {{-- 2. Estimasi Harga Unit & Status Stok --}}
                  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div>
                      <span class="text-muted small d-block mb-1" style="font-size: 0.8rem;">Estimasi Harga Unit / Penawaran Resmi:</span>
                      <strong class="fs-4 d-block detail-price" style="font-family: var(--font-display); color: var(--nb-primary); font-weight: 700;">
                        {{ $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : 'Hubungi Tim Penawaran' }}
                      </strong>
                    </div>
                    <div>
                      @if($stock > 0)
                        <span class="nb-badge-stock" style="background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;">
                          <i data-lucide="package-check" class="me-1"></i> Stok Siap: {{ $stock }} unit
                        </span>
                      @else
                        <span class="nb-badge-stock nb-badge-stock--empty">
                          <i data-lucide="clock" class="me-1"></i> Pesanan Khusus (Indent)
                        </span>
                      @endif
                    </div>
                  </div>

                  {{-- 3. Form Stepper & Tambah ke Keranjang RFQ --}}
                  <form action="{{ route('cart.add') }}" method="POST" id="beli-produk-form" class="mb-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product['id'] ?? $product->id ?? '' }}">
                    <input type="hidden" name="title" value="{{ $product['title'] ?? $product->title ?? '' }}">

                    <div class="d-flex flex-wrap align-items-end gap-2 gap-sm-3">
                      <div>
                        <label class="d-block text-uppercase fw-bold mb-2 detail-qty-label" style="font-size: 0.72rem; color: var(--nb-muted);">Jumlah Unit</label>
                        <div class="nb-stepper-wrap">
                          <button type="button" class="nb-stepper-btn" data-step="-1" aria-label="Kurangi jumlah unit" onclick="stepQty(-1)">
                            <i data-lucide="minus"></i>
                          </button>
                          <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="nb-stepper-input hide-spinner" data-stock="{{ $stock }}">
                          <button type="button" class="nb-stepper-btn" data-step="1" aria-label="Tambah jumlah unit" onclick="stepQty(1)">
                            <i data-lucide="plus"></i>
                          </button>
                        </div>
                      </div>

                      <button type="submit" class="nb-btn nb-btn-primary flex-grow-1 detail-add-btn" style="height: 48px; border-radius: 8px; font-weight: 600;" aria-label="Tambah {{ $product['title'] }} ke keranjang penawaran">
                        <i data-lucide="shopping-cart" class="me-2"></i> Tambah ke Keranjang Penawaran
                      </button>

                      <a href="{{ $productWaUrl }}" target="_blank" rel="noopener noreferrer" class="nb-btn nb-btn-ghost detail-wa-btn" style="height: 48px; border-radius: 8px; font-weight: 600;" title="Konsultasi cepat via WhatsApp">
                        <i data-lucide="message-circle" class="text-success me-1"></i> Tanya Sales
                      </a>
                    </div>

                    <div id="indent-notice" class="p-3 mt-3 detail-indent-notice is-hidden">
                      <i data-lucide="info" class="me-1"></i>
                      Jumlah yang Anda pesan melebihi stok siap ({{ $stock }} unit). Sisa unit diproses sebagai <strong>pesanan khusus</strong> (lead time tercantum pada SPH resmi).
                    </div>
                  </form>

                  {{-- 4. Subtle SLA Trust Micro-copy --}}
                  <div class="d-flex align-items-center gap-2 text-muted small pt-1" style="font-size: 0.8rem;">
                    <i data-lucide="shield-check" class="text-success flex-shrink-0" style="width: 15px; height: 15px;"></i>
                    <span>Surat Penawaran Harga (SPH) resmi &amp; konfirmasi lot diterbitkan sales dalam 1–2 jam kerja.</span>
                  </div>

                  {{-- 5. Navigation Links --}}
                  <div class="pt-3 mt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: rgba(0,0,0,0.06) !important;">
                    <a href="{{ url('/produk') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-1">
                      <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Kembali ke Katalog Produk
                    </a>
                    <a href="{{ route('cart.index') }}" class="text-decoration-none fw-medium small d-inline-flex align-items-center gap-1" style="color: var(--nb-primary);">
                      <i data-lucide="receipt" style="width: 14px; height: 14px;"></i> Buka Keranjang Penawaran &rarr;
                    </a>
                  </div>

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
                    <div class="lightbox-image-wrapper">
                      <img id="lightbox-product-image" src="{{ $mainImage }}" alt="{{ $product['title'] }}" class="lightbox-img" loading="lazy" decoding="async">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- JSON-LD --}}
            <script type="application/ld+json" @nonce>
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
            <script type="application/ld+json" @nonce>
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

            {{-- Mobile Sticky Action Bar --}}
            <div class="detail-mobile-sticky-bar d-md-none">
              <div class="container d-flex align-items-center justify-content-between gap-3">
                <div class="text-truncate">
                  <div class="fw-bold small text-truncate" style="color: var(--nb-ink); font-size: 0.85rem;">{{ $product['title'] }}</div>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    @if($stock > 0)
                      <span class="text-success fw-medium">Ready Stock ({{ $stock }})</span>
                    @else
                      <span class="text-warning fw-medium">Pesanan Khusus (Indent)</span>
                    @endif
                  </div>
                </div>
                <button type="button" onclick="document.getElementById('beli-produk-form').scrollIntoView({behavior: 'smooth', block: 'center'})" class="nb-btn nb-btn-primary flex-shrink-0" style="height: 38px; padding: 0.35rem 0.85rem; font-size: 0.82rem; border-radius: 6px;">
                  <i data-lucide="shopping-cart"></i> Tambah
                </button>
              </div>
            </div>
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
