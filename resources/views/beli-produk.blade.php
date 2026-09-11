@extends('layouts.app')

@php
  $seoTitle = $product
    ? ('Minta Penawaran: ' . $product['title'] . (!empty($product['catalog']) ? ' (' . $product['catalog'] . ')' : '') . ' | PROLABIOS')
    : 'Produk Tidak Ditemukan | PROLABIOS';
  $seoDesc = $product
    ? 'Ajukan penawaran resmi untuk ' . $product['title'] . ' dari PT. Prolabios Mitra Analitika.'
    : 'Produk laboratorium Prolabios tidak ditemukan.';
  $seoImage = '';
  if ($product && !empty($product['image'])) {
    $seoImage = str_starts_with($product['image'], 'http')
      ? $product['image']
      : url($product['image']);
  }
  $productSlug = is_array($product) ? ($product['slug'] ?? null) : ($product->slug ?? null);
  $seoCanonical = ($product && $productSlug)
    ? route('produk.beli', ['slug' => $productSlug])
    : url('/produk');
  $detailUrl = $product ? product_url($product) : url('/produk');
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('canonical', $seoCanonical)
@section('og_type', 'product')
@section('og_title', $seoTitle)
@section('og_description', $seoDesc)
@section('og_image', $seoImage)

@section('content')
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Pengadaan & Penawaran</span>
      <p class="editorial-page-title">Minta Penawaran Produk</p>
      <p class="editorial-page-subtitle">Tentukan jumlah unit, lalu tambahkan ke keranjang penawaran institusi</p>
    </div>
  </div>

  <section class="section-main">
    <div class="container">
      @if($product)
        @php
          $mainImage = $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
          $stock = (int) ($product['stock'] ?? 0);
          $price = (float) ($product['price'] ?? 0);
        @endphp

        <div class="row g-5">
          <div class="col-md-5">
            <div class="detail-product-img-wrap" style="cursor: default;">
              <img src="{{ $mainImage }}" alt="{{ $product['title'] }}" class="w-100 detail-product-img" loading="lazy" decoding="async">
            </div>
            <div class="mt-3">
              <a href="{{ $detailUrl }}" class="nb-btn nb-btn-ghost detail-nav-btn text-decoration-none">
                <i data-lucide="file-text" class="me-2"></i> Lihat Spesifikasi Lengkap
              </a>
            </div>
          </div>

          <div class="col-md-7">
            <h1 class="profil-section-title detail-product-title mb-2">{{ $product['title'] }}</h1>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
              @if(!empty($product['catalog']))
                <span class="product-cat-code">CAT. {{ $product['catalog'] }}</span>
              @endif
              @if(!empty($product['category']))
                <span class="nb-badge-sm text-capitalize">{{ str_replace('-', ' ', $product['category']) }}</span>
              @endif
              @if(!empty($product->principal))
                <span class="nb-badge-sm d-inline-flex align-items-center gap-1">
                  <i data-lucide="building" class="text-primary"></i> {{ $product->principal->name }}
                </span>
              @endif
            </div>

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
                      <i data-lucide="package" class="me-1"></i> Stok Siap: {{ $stock }} unit
                    </span>
                  @else
                    <span class="nb-badge-stock nb-badge-stock--empty">
                      <i data-lucide="history" class="me-1"></i> Pesanan Khusus (Indent)
                    </span>
                  @endif
                </div>
              </div>

              <form action="{{ route('cart.add') }}" method="POST" id="beli-produk-form" class="mb-1">
                @csrf
                <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
                <input type="hidden" name="title" value="{{ $product['title'] }}">

                <div class="d-flex flex-wrap align-items-end gap-3">
                  <div>
                    <label class="d-block text-uppercase fw-bold mb-2 detail-qty-label">Jumlah Unit</label>
                    <div class="nb-stepper-wrap">
                      <button type="button" class="nb-stepper-btn" aria-label="Kurangi jumlah unit" onclick="stepQty(-1)">
                        <i data-lucide="minus"></i>
                      </button>
                      <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="nb-stepper-input hide-spinner" data-stock="{{ $stock }}">
                      <button type="button" class="nb-stepper-btn" aria-label="Tambah jumlah unit" onclick="stepQty(1)">
                        <i data-lucide="plus"></i>
                      </button>
                    </div>
                  </div>

                  <button type="submit" class="nb-btn nb-btn-primary flex-grow-1 detail-add-btn" aria-label="Tambah {{ $product['title'] }} ke keranjang penawaran">
                    <i data-lucide="shopping-cart" class="me-2"></i> Tambah ke Keranjang Penawaran
                  </button>
                </div>

                <div id="indent-notice" class="p-3 mt-3 detail-indent-notice is-hidden">
                  <i data-lucide="info" class="me-1"></i>
                  Jumlah yang Anda pesan melebihi stok siap ({{ $stock }} unit). Kelebihannya akan diproses sebagai <strong>pesanan khusus</strong> — estimasi waktu pengadaan akan diinformasikan Tim Sales pada Surat Penawaran.
                </div>
              </form>
            </div>

            <div class="mb-4 p-3 d-flex align-items-center gap-3 rfq-trust-box">
              <div class="nb-status-icon-box detail-response-icon flex-shrink-0">
                <i data-lucide="history" class="text-dark"></i>
              </div>
              <div class="detail-trust-copy">
                <strong class="d-block detail-datasheet-title">Komitmen Respon Cepat (Maksimal 1×24 Jam Kerja)</strong>
                Permintaan Surat Penawaran Harga (SPH) institusi diproses maksimal dalam 1×24 jam kerja dengan garansi keaslian instrumen/reagen dari prinsipal.
              </div>
            </div>

            <div class="d-flex flex-wrap gap-3">
              <a href="{{ route('cart.index') }}" class="nb-btn detail-nav-btn detail-nav-btn--cart text-decoration-none">
                <i data-lucide="shopping-cart" class="me-2"></i> Lihat Keranjang Penawaran
              </a>
              <a href="{{ $detailUrl }}" class="nb-btn nb-btn-ghost detail-nav-btn text-decoration-none">
                <i data-lucide="arrow-left" class="me-2"></i> Kembali ke Detail Produk
              </a>
            </div>
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
  </section>
@endsection
