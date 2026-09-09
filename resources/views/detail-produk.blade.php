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
                           alt="Logo {{ $product->principal->name }}"
                           style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                  @endif
                  <div>
                    <span class="d-block text-muted text-uppercase fw-bold detail-principal-label">Prinsipal Resmi</span>
                    <strong class="d-block text-dark detail-principal-name">{{ $product->principal->name }}</strong>
                    @if(!empty($product->principal->address))
                      <span class="text-muted small detail-principal-address"><i class="bi bi-geo-alt me-1"></i>{{ $product->principal->address }}</span>
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
                      <i class="bi bi-building text-primary"></i> {{ $product->principal->name }}
                      @if(!empty($product->principal->address))
                        <span class="text-muted ms-1">({{ $product->principal->address }})</span>
                      @endif
                    </span>
                  @endif
                </div>

                <div class="card p-4 mb-4">
                  <h3 class="layanan-feature-title detail-section-heading mb-3">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>Deskripsi & Spesifikasi Produk
                  </h3>
                  <div class="profil-body-text mb-4">
                    {!! \App\Services\DataService::sanitizeHtml($product['description'] ?? 'Tidak ada deskripsi spesifik yang tersedia untuk produk ini.') !!}
                  </div>

                  {{-- B2B Technical Datasheet & Specification Link --}}
                  <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-3 rfq-details-box">
                    <div class="d-flex align-items-center gap-3">
                      <div class="rfq-trust-box">
                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 1.4rem;"></i>
                      </div>
                      <div>
                        <strong class="d-block detail-datasheet-title">Dokumen Lembar Data &amp; Spesifikasi Teknis (PDF)</strong>
                        <span class="text-muted small detail-datasheet-sub">
                          @if(!empty($product->principal))
                            Brosur teknis &amp; lembar data spesifikasi resmi dari {{ $product->principal->name }}.
                          @else
                            Lembar spesifikasi dan petunjuk teknis analitika dari prinsipal resmi.
                          @endif
                        </span>
                      </div>
                    </div>
                    @if(!empty($product['datasheet_url']))
                      <a href="{{ $product['datasheet_url'] }}" target="_blank" rel="noopener noreferrer" class="nb-btn nb-btn-primary d-inline-flex align-items-center gap-2 detail-btn-sm">
                        <i class="bi bi-download"></i> Unduh Spesifikasi (PDF) <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.75rem;"></i>
                      </a>
                    @else
                      <a href="{{ url('/kontak') }}?subjek=consultation&pesan={{ urlencode('Permintaan lembar data teknis / MSDS / CoA resmi untuk produk: ' . $product['title'] . (!empty($product['catalog']) ? ' (CAT. ' . $product['catalog'] . ')' : '')) }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2 detail-btn-ghost-sm">
                        <i class="bi bi-envelope-paper"></i> Request Lembar Data Resmi <i class="bi bi-arrow-right ms-1"></i>
                      </a>
                    @endif
                  </div>
                </div>

                @php
                  $stock = (int) ($product['stock'] ?? 0);
                  $price = (float) ($product['price'] ?? 0);
                @endphp

                <!-- RFQ Procurement & Direct Add-to-Cart Card -->
                <div class="card p-4 mb-4">
                  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom detail-spec-divider">
                    <div>
                      <span class="text-muted small d-block mb-1 fw-medium">Estimasi Harga Unit / Penawaran Resmi:</span>
                      <strong class="fs-4 d-block detail-price">
                        {{ $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : 'Hubungi Tim Penawaran' }}
                      </strong>
                    </div>
                    <div>
                      @if($stock > 0)
                        <span class="nb-badge-stock">
                          <i class="bi bi-box-seam me-1"></i> Stok Siap: {{ $stock }} unit
                        </span>
                      @else
                        <span class="nb-badge-stock nb-badge-stock--empty">
                          <i class="bi bi-clock-history me-1"></i> Pesanan Khusus (Indent)
                        </span>
                      @endif
                    </div>
                  </div>

                  <!-- Direct Add to Cart Form -->
                  <form action="{{ route('cart.add') }}" method="POST" id="beli-produk-form" class="mb-1">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
                    <input type="hidden" name="title" value="{{ $product['title'] }}">

                    <div class="d-flex flex-wrap align-items-end gap-3">
                      <div>
                        <label class="d-block text-uppercase fw-bold mb-2 detail-qty-label">Jumlah Unit</label>
                        <div class="nb-stepper-wrap">
                          <button type="button" class="nb-stepper-btn" aria-label="Kurangi jumlah unit" onclick="stepQty(-1)">
                            <i class="bi bi-dash-lg"></i>
                          </button>
                          <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="nb-stepper-input hide-spinner" data-stock="{{ $stock }}">
                          <button type="button" class="nb-stepper-btn" aria-label="Tambah jumlah unit" onclick="stepQty(1)">
                            <i class="bi bi-plus-lg"></i>
                          </button>
                        </div>
                      </div>

                      <button type="submit" class="nb-btn nb-btn-primary flex-grow-1 detail-add-btn" aria-label="Tambah {{ $product['title'] }} ke keranjang penawaran">
                        <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang Penawaran
                      </button>
                    </div>

                    <!-- Live Indent Notice -->
                    <div id="indent-notice" class="p-3 mt-3 detail-indent-notice" style="display: none;">
                      <i class="bi bi-info-circle-fill me-1"></i>
                      Jumlah yang Anda pesan melebihi stok siap ({{ $stock }} unit). Kelebihannya akan diproses sebagai <strong>pesanan khusus</strong> — estimasi waktu pengadaan akan diinformasikan Tim Sales pada Surat Penawaran.
                    </div>
                  </form>
                </div>

                {{-- B2B Trust Badge & SLA Response Commitment --}}
                <div class="mb-4 p-3 d-flex align-items-center gap-3 rfq-trust-box">
                  <div class="nb-status-icon-box detail-response-icon flex-shrink-0">
                    <i class="bi bi-clock-history text-dark"></i>
                  </div>
                  <div style="font-size: 0.82rem; line-height: 1.4; color: var(--nb-ink);">
                    <strong class="d-block detail-datasheet-title">Komitmen Respon Cepat (Maksimal 1×24 Jam Kerja)</strong>
                    Permintaan Surat Penawaran Harga (SPH) institusi diproses maksimal dalam 1×24 jam kerja dengan garansi keaslian instrumen/reagen dari prinsipal.
                  </div>
                </div>

                <div class="mt-4 pt-2 d-flex flex-wrap gap-3">
                  <a href="{{ route('cart.index') }}" class="nb-btn d-inline-flex align-items-center justify-content-center text-decoration-none detail-add-btn" style="padding: 0 20px; font-size: 0.85rem; background: var(--nb-accent); color: var(--nb-ink); border: var(--nb-border); box-shadow: var(--nb-shadow);">
                    <i class="bi bi-cart me-2"></i> Lihat Keranjang Penawaran
                  </a>
                  <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center justify-content-center text-decoration-none detail-add-btn" style="padding: 0 20px; font-size: 0.85rem;">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog
                  </a>
                </div>
              </div>
            </div>

            {{-- Lightbox Modal --}}
            <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxModalLabel" aria-hidden="true" data-bs-backdrop="true">
              <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95vw; margin: 1.5rem auto;">
                <div class="modal-content bg-transparent border-0 shadow-none position-relative">
                  <button type="button" class="btn-close-lightbox" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
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
@endsection
