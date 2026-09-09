@extends('layouts.app')

@section('title', 'Keranjang Pengajuan Penawaran - PT. Prolabios Mitra Analitika')

@section('content')
<section class="cart-page-bg">
  <div class="container py-2">

    <!-- Stepper Navigation -->
    <div class="cart-stepper-wrap">
      <div class="d-flex flex-row align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex flex-row align-items-center gap-3 flex-wrap">
          <div class="d-inline-flex align-items-center gap-2 fw-bold text-ink">
            <span class="step-num-badge step-num-active">1</span>
            <span class="step-label">Keranjang Pengajuan</span>
          </div>
          <span class="fw-bold text-ink" style="font-size: 0.85rem;">&gt;</span>
          <div class="d-inline-flex align-items-center gap-2 text-muted">
            <span class="step-num-badge step-num-inactive">2</span>
            <span class="step-label">Data Kontak &amp; Instansi</span>
          </div>
          <span class="fw-bold text-ink" style="font-size: 0.85rem;">&gt;</span>
          <div class="d-inline-flex align-items-center gap-2 text-muted">
            <span class="step-num-badge step-num-inactive">3</span>
            <span class="step-label">Konfirmasi Selesai</span>
          </div>
        </div>

        @if(!empty($cart) && count($cart) > 0)
          <div>
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="confirmClearCart(event, this);" class="m-0">
              @csrf
              <button type="submit" class="cart-clear-btn">
                <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <!-- Header Title -->
    <div class="mb-4">
      <h1 class="profil-section-title" style="font-size: 2.2rem !important; margin-bottom: 8px !important;">Daftar Item Pengajuan Penawaran</h1>
      <p class="profil-body-text mb-0">Periksa daftar item dan kuantitas produk sebelum melanjutkan ke form pengajuan.</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
      <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-20 alert-dismissible fade show rounded-0 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-20 alert-dismissible fade show rounded-0 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
      </div>
    @endif

    @if(!empty($cart) && count($cart) > 0)
      <div class="row g-4">

        <!-- Left Column: Item Cards List -->
        <div class="col-lg-8">

          @foreach($cart as $title => $item)
            <div class="cart-item-card">
              <div class="row align-items-center g-3">
                
                <!-- 1. Thumbnail Image -->
                <div class="col-auto">
                  <div class="cart-img-box">
                    <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item['title'] }} — Item keranjang" loading="lazy" decoding="async">
                  </div>
                </div>

                <!-- 2. Product Name & Catalog & Stock Status -->
                <div class="col">
                  <a href="{{ product_url($item) }}" class="text-decoration-none fw-semibold d-block mb-1" style="color: var(--nb-ink) !important; font-family: var(--font-headline); font-size: 1.05rem; line-height: 1.35;">
                    {{ $item['title'] }}
                  </a>
                  <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    @if(!empty($item['catalog']))
                      <span class="cart-cat-badge">
                        CAT. {{ $item['catalog'] }}
                      </span>
                    @endif

                    @php
                      $stockVal = (int)($item['stock'] ?? 0);
                      $isIndent = $item['quantity'] > $stockVal;
                    @endphp

                    @if(!$isIndent)
                      <span class="nb-badge-stock">
                        <i class="bi bi-box-seam me-1"></i> Stok Siap
                      </span>
                    @else
                      <span class="nb-badge-stock nb-badge-stock--empty" title="Stok siap {{ $stockVal }} unit. Sisa {{ $item['quantity'] - $stockVal }} unit akan diproses sebagai pesanan khusus.">
                        <i class="bi bi-clock-history me-1"></i> Pesanan khusus (siap: {{ $stockVal }})
                      </span>
                    @endif
                  </div>
                </div>

                <!-- 3. Quantity Stepper -->
                <div class="col-12 col-sm-auto">
                  <form action="{{ route('cart.update') }}" method="POST" class="m-0 cart-update-form" onsubmit="event.preventDefault(); updateCartItemAjax(this);">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                    <input type="hidden" name="title" value="{{ $item['title'] }}">
                    <div class="nb-stepper-wrap" style="height: 38px;">
                      <button type="button" class="nb-stepper-btn" style="width: 34px; font-size: 0.95rem;" aria-label="Kurangi Jumlah" onclick="stepCartQty(this, -1)">
                        <i class="bi bi-dash-lg"></i>
                      </button>
                      <input type="text" inputmode="numeric" pattern="[0-9]*" name="quantity" value="{{ $item['quantity'] }}" aria-label="Jumlah Qty" class="nb-stepper-input cart-qty-input hide-spinner" style="width: 46px; font-size: 0.95rem;" onchange="updateCartItemAjax(this.form)">
                      <button type="button" class="nb-stepper-btn" style="width: 34px; font-size: 0.95rem;" aria-label="Tambah Jumlah" onclick="stepCartQty(this, 1)">
                        <i class="bi bi-plus-lg"></i>
                      </button>
                    </div>
                  </form>
                </div>

                <!-- 4. Subtotal & Remove Button -->
                <div class="col-auto text-end">
                  <div class="d-flex align-items-center gap-3">
                    <div>
                      <span class="item-subtotal-val" style="font-family: var(--font-headline); font-weight: 700; color: var(--nb-primary); font-size: 1.1rem;">
                        {{ $item['price'] > 0 ? 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') : 'Est. Penawaran' }}
                      </span>
                    </div>

                    <form action="{{ route('cart.remove') }}" method="POST" class="m-0" onsubmit="event.preventDefault(); removeCartItemAjax(this);">
                      @csrf
                      <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                      <input type="hidden" name="title" value="{{ $item['title'] }}">
                      <button type="submit" class="cart-remove-btn" title="Hapus Item" aria-label="Hapus item">
                        <i class="bi bi-trash3" style="font-size: 0.88rem;"></i>
                      </button>
                    </form>
                  </div>
                </div>

              </div>
            </div>
          @endforeach

        </div>

        <!-- Right Column: Summary & Checkout CTA -->
        <div class="col-lg-4">
          <div class="cart-sidebar-panel sticky-top" style="top: 130px; z-index: 100;">
            <h3 class="cart-sidebar-title d-flex align-items-center gap-2">
              <i class="bi bi-receipt" style="color: var(--nb-primary);"></i> Ringkasan Pengajuan
            </h3>

            <div class="d-flex justify-content-between mb-2 small">
              <span style="color: var(--nb-muted);">Total Volume Barang:</span>
              <strong style="font-family: var(--font-mono); color: var(--nb-ink);" id="sidebar-total-units">
                {{ array_sum(array_column($cart, 'quantity')) }} Unit
              </strong>
            </div>

            @php
              $totalEstimate = 0;
              foreach($cart as $i) {
                $totalEstimate += ($i['price'] * $i['quantity']);
              }
            @endphp

            <div class="d-flex justify-content-between mb-3 small">
              <span style="color: var(--nb-muted);">Estimasi Subtotal Katalog:</span>
              <strong style="font-family: var(--font-display); font-size: 1.25rem; color: var(--nb-primary);" id="sidebar-total-estimate">
                {{ $totalEstimate > 0 ? 'Rp ' . number_format($totalEstimate, 0, ',', '.') : 'Rp 0' }}
              </strong>
            </div>

            <hr style="border-color: rgba(30,30,30,0.15); margin: 1.25rem 0;">

            <div class="rfq-info-box mb-4">
              <div class="d-flex gap-2">
                <i class="bi bi-shield-check fs-5 flex-shrink-0 text-primary"></i>
                <div style="font-size: 0.82rem; color: var(--nb-ink);">
                  <strong class="d-block mb-1" style="font-family: var(--font-display); font-weight: 700;">Informasi Penawaran</strong>
                  Harga final, diskon khusus kuantitas, dan estimasi waktu pengadaan akan diinformasikan langsung oleh Tim Sales via Email/WhatsApp.
                </div>
              </div>
            </div>

            <a href="{{ route('rfq.checkout') }}" class="rfq-primary-btn">
              Lanjut ke Form Pengajuan <i class="bi bi-arrow-right ms-2"></i>
            </a>

            <a href="{{ url('/produk') }}" class="rfq-secondary-btn">
              <i class="bi bi-plus-lg me-1"></i> Tambah Produk Lain
            </a>
          </div>
        </div>

      </div>
    @else
      <div class="card cart-empty-card text-center p-5 mx-auto">
        <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--nb-muted); display: block; margin-bottom: 20px;"></i>
        <h2 class="profil-section-title" style="font-size: 1.6rem !important; color: var(--nb-ink);">Keranjang Belanja Masih Kosong</h2>
        <p class="profil-body-text mb-4" style="color: var(--nb-muted);">Pilih produk laboratorium atau reagen di katalog untuk mulai membuat pengajuan penawaran harga.</p>
        <div>
          <a href="{{ url('/produk') }}" class="nb-btn nb-btn-primary">Jelajahi Katalog Produk <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
      </div>
    @endif

  </div>
</section>
@endsection
