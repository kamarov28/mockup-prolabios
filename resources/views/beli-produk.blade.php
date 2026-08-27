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
              <a href="{{ product_url($product) }}" class="text-decoration-none" style="color: var(--color-text-muted); font-size: 0.82rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Deskripsi &amp; Spesifikasi Produk
              </a>
            </div>

            <!-- Compact Product Header -->
            <div class="d-flex align-items-center gap-4 mb-4 pb-4" style="border-bottom: 1px solid var(--color-border);">
              <div style="width: 90px; height: 90px; flex-shrink: 0; border: 1px solid var(--color-border); background-color: #070708; padding: 8px;">
                <img src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $product['title'] }}" class="w-100 h-100" style="object-fit: contain;" loading="lazy" decoding="async">
              </div>
              <div>
                @if(!empty($product['catalog']))
                  <span style="font-family: var(--font-headline); font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: var(--color-accent);">Cat. No: {{ $product['catalog'] }}</span>
                @endif
                <h1 class="profil-section-title" style="font-size: 1.4rem !important; margin: 4px 0 0 !important;">{{ $product['title'] }}</h1>
              </div>
            </div>

            <!-- Price Box -->
            <div class="p-4 mb-4 rounded border border-secondary border-opacity-20" style="background: rgba(255,255,255,0.02);">
              <span class="text-muted small d-block mb-1">Harga Estimasi / Penawaran per Unit:</span>
              <strong class="fs-2 d-block mb-3" style="color: var(--color-accent);">
                {{ $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : 'Hubungi Tim Penawaran' }}
              </strong>

              @if($stock > 0)
                <span class="badge bg-success bg-opacity-20 text-success px-3 py-2">
                  <i class="bi bi-box-seam me-1"></i> Stok Siap: {{ $stock }} unit
                </span>
              @else
                <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-2">
                  <i class="bi bi-clock-history me-1"></i> Stok kosong — tersedia sebagai pesanan khusus
                </span>
              @endif

              <p class="profil-body-text small mt-3 mb-0" style="opacity: 0.75;">
                Harga di atas bersifat estimasi awal. Harga &amp; diskon final akan dikonfirmasi oleh Tim Sales kami melalui Surat Penawaran Resmi (PDF) setelah pengajuan Anda ditinjau.
              </p>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" id="beli-produk-form">
              @csrf
              <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
              <input type="hidden" name="title" value="{{ $product['title'] }}">

              <div class="d-flex flex-wrap align-items-end gap-4 mb-4">
                <div>
                  <label class="d-block text-uppercase fw-bold mb-2" style="font-size: 0.68rem; letter-spacing: 1.5px; color: var(--color-text-muted); font-family: var(--font-headline);">Jumlah Unit</label>
                  <div class="d-inline-flex align-items-center" style="border: 1px solid var(--color-border); background: var(--color-surface); height: 46px; border-radius: 4px;">
                    <button type="button" class="btn border-0 px-3 h-100 text-white-50 hover-white d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(-1)">
                      <i class="bi bi-dash-lg" style="font-size: 0.85rem;"></i>
                    </button>
                    <input type="number" id="qty-input" name="quantity" min="1" max="9999" value="1" class="form-control text-center text-white bg-transparent border-0 fw-bold h-100 hide-spinner" style="width: 60px; font-size: 0.95rem; font-family: var(--font-headline); outline: none; box-shadow: none;" data-stock="{{ $stock }}">
                    <button type="button" class="btn border-0 px-3 h-100 text-white-50 hover-white d-flex align-items-center justify-content-center" style="background: transparent;" onclick="stepQty(1)">
                      <i class="bi bi-plus-lg" style="font-size: 0.85rem;"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="kontak-submit-btn border-0 cursor-pointer flex-grow-1" style="height: 46px; margin: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem; letter-spacing: 1px;">
                  <i class="bi bi-cart-plus me-2" style="font-size: 1.1rem;"></i> Tambah ke Keranjang Penawaran
                </button>
              </div>

              <!-- Live Indent Notice -->
              <div id="indent-notice" class="p-3 rounded border border-warning border-opacity-30 bg-warning bg-opacity-10 text-warning small" style="display: none;">
                <i class="bi bi-info-circle me-1"></i>
                Jumlah yang Anda pesan melebihi stok siap ({{ $stock }} unit). Kelebihannya akan diproses sebagai <strong>pesanan khusus</strong> — estimasi waktu pengadaan akan diinformasikan Tim Sales pada Surat Penawaran.
              </div>
            </form>

            <div class="mt-4">
              <a href="{{ url('/produk') }}" class="profil-cta-btn border-0 d-inline-flex align-items-center justify-content-center text-decoration-none" style="height: 46px; padding: 0 24px; font-size: 0.78rem;">
                Kembali ke Katalog <i class="bi bi-arrow-right ms-2"></i>
              </a>
              <a href="{{ route('cart.index') }}" class="profil-cta-btn border-0 d-inline-flex align-items-center justify-content-center text-decoration-none ms-2" style="height: 46px; padding: 0 24px; font-size: 0.78rem;">
                <i class="bi bi-cart me-2"></i> Lihat Keranjang Penawaran
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
