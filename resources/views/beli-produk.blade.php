@extends('layouts.app')

@section('title', $product ? 'Beli ' . $product['title'] . ' - Prolabios' : 'Produk Tidak Ditemukan - Prolabios')

@if(isset($product) && $product)
  @section('og_title', $product['title'] . ' | PROLABIOS')
  @section('og_description', 'Harga, stok, dan pemesanan ' . $product['title'] . ' di PT. Prolabios Mitra Analitika.')
  @section('og_image', $product['image'])
@endif

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Belanja Produk</span>
      <p class="editorial-page-title">Harga &amp; Ketersediaan Stok</p>
      <p class="editorial-page-subtitle">Ajukan permintaan penawaran resmi untuk produk ini</p>
    </div>
  </div>

  <section class="section-main">
    <div class="container">
      <div class="row g-5">
        <div class="col-12" style="max-width: 760px; margin: 0 auto;">
          @if($product)
            @php
              $stock = (int) ($product['stock'] ?? 0);
              $price = (float) ($product['price'] ?? 0);
            @endphp

            <!-- Back to description link -->
            <div class="mb-4">
              <a href="{{ product_url($product) }}" class="nb-btn nb-btn-ghost text-decoration-none" style="font-size: 0.82rem; padding: 6px 14px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Deskripsi &amp; Spesifikasi Produk
              </a>
            </div>

            <!-- Compact Product Header -->
            <div class="card p-4 d-flex flex-row align-items-center gap-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
              <div style="width: 100px; height: 100px; flex-shrink: 0; border: 2px solid var(--nb-ink); border-radius: var(--nb-radius-sm); background-color: var(--nb-bg-soft); padding: 8px;">
                <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="w-100 h-100" style="object-fit: contain;" loading="lazy" decoding="async">
              </div>
              <div>
                @if(!empty($product['catalog']))
                  <div class="product-cat-code mb-1">Cat. No: {{ $product['catalog'] }}</div>
                @endif
                <h1 class="profil-section-title" style="font-size: 1.5rem !important; margin: 4px 0 0 !important; color: var(--nb-ink);">{{ $product['title'] }}</h1>
              </div>
            </div>

            <!-- Price Box -->
            <div class="card p-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
              <span class="text-muted small d-block mb-1 fw-medium">Harga Estimasi / Penawaran per Unit:</span>
              <strong class="fs-2 d-block mb-3" style="color: var(--nb-primary); font-family: var(--font-display); font-weight: 700;">
                {{ $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : 'Hubungi Tim Penawaran' }}
              </strong>

              @if($stock > 0)
                <span class="nb-badge-sm d-inline-flex align-items-center gap-1" style="background: #e6f4ea; color: #137333; border-color: #137333;">
                  <i class="bi bi-box-seam me-1"></i> Stok Siap: {{ $stock }} unit
                </span>
              @else
                <span class="nb-badge-sm d-inline-flex align-items-center gap-1" style="background: var(--nb-accent); color: var(--nb-ink);">
                  <i class="bi bi-clock-history me-1"></i> Stok kosong — tersedia sebagai pesanan khusus
                </span>
              @endif

              <p class="small mt-3 mb-0" style="color: var(--nb-muted); line-height: 1.6;">
                Harga di atas bersifat estimasi awal. Harga &amp; diskon final akan dikonfirmasi oleh Tim Sales kami melalui Surat Penawaran Resmi (PDF) setelah pengajuan Anda ditinjau.
              </p>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="beli-produk-form">
              @csrf
              <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
              <input type="hidden" name="title" value="{{ $product['title'] }}">

              <div class="d-flex flex-wrap align-items-end gap-3 mb-4">
                <div>
                  <label class="d-block text-uppercase fw-bold mb-2" style="font-size: 0.72rem; letter-spacing: 1px; color: var(--nb-ink); font-family: var(--font-mono);">Jumlah Unit</label>
                  <div class="d-inline-flex align-items-center" style="border: 2px solid var(--nb-ink); background: #FFFFFF; height: 48px; border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 var(--nb-ink);">
                    <button type="button" class="btn border-0 px-3 h-100 text-dark d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(-1)">
                      <i class="bi bi-dash-lg fw-bold"></i>
                    </button>
                    <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="form-control text-center bg-transparent border-0 fw-bold h-100 hide-spinner" style="width: 60px; font-size: 1.05rem; font-family: var(--font-mono); outline: none; box-shadow: none; color: var(--nb-ink);" data-stock="{{ $stock }}">
                    <button type="button" class="btn border-0 px-3 h-100 text-dark d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(1)">
                      <i class="bi bi-plus-lg fw-bold"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="nb-btn nb-btn-primary flex-grow-1" style="height: 48px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                  <i class="bi bi-cart-plus me-2" style="font-size: 1.15rem;"></i> Tambah ke Keranjang Penawaran
                </button>
              </div>

              <!-- Live Indent Notice -->
              <div id="indent-notice" class="p-3 mb-3" style="display: none; background: var(--nb-accent); border: 2px solid var(--nb-ink); border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 var(--nb-ink); color: var(--nb-ink); font-size: 0.85rem;">
                <i class="bi bi-info-circle-fill me-1"></i>
                Jumlah yang Anda pesan melebihi stok siap ({{ $stock }} unit). Kelebihannya akan diproses sebagai <strong>pesanan khusus</strong> — estimasi waktu pengadaan akan diinformasikan Tim Sales pada Surat Penawaran.
              </div>
            </form>

            <div class="mt-4 pt-2 d-flex flex-wrap gap-2">
              <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost text-decoration-none" style="font-size: 0.82rem; padding: 8px 16px;">
                <i class="bi bi-arrow-left me-1"></i> Katalog Produk
              </a>
              <a href="{{ route('cart.index') }}" class="nb-btn nb-btn-ghost text-decoration-none" style="font-size: 0.82rem; padding: 8px 16px; background: var(--nb-accent); color: var(--nb-ink) !important;">
                <i class="bi bi-cart me-1"></i> Lihat Keranjang Penawaran
              </a>
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

  <script>
    function stepQty(amount) {
      const input = document.getElementById('qty-input');
      if (input) {
        let val = parseInt(input.value) || 1;
        val = Math.max(1, val + amount);
        input.value = val;
        toggleIndentNotice();
      }
    }

    function toggleIndentNotice() {
      const input = document.getElementById('qty-input');
      const notice = document.getElementById('indent-notice');
      if (!input || !notice) return;

      const stock = parseInt(input.dataset.stock || '0', 10);
      const qty = parseInt(input.value || '1', 10);

      notice.style.display = (qty > stock) ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
      const input = document.getElementById('qty-input');
      if (input) {
        input.addEventListener('input', toggleIndentNotice);
        toggleIndentNotice();
      }
    });
  </script>
@endsection
