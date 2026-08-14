@extends('layouts.app')

@section('title', 'Keranjang Belanja B2B - PT. Prolabios Mitra Analitika')

@push('styles')
<style>
  .cart-page-bg {
    background-color: #070708;
    min-height: 85vh;
  }

  /* Progress Stepper */
  .cart-stepper-wrap {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
  }

  .stepper-steps {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 16px;
    flex-wrap: wrap;
  }

  .step-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.4);
  }
  .step-pill.active {
    color: #ffffff;
  }
  .step-pill .step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.5);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
  }
  .step-pill.active .step-num {
    background: #ff4950;
    color: #ffffff;
  }

  /* Item Card Panel */
  .cart-item-card {
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
    transition: border-color 0.2s ease, transform 0.2s ease;
  }
  .cart-item-card:hover {
    border-color: rgba(255, 73, 80, 0.3);
  }

  /* Image Box - Strictly Enforced Dimensions */
  .cart-img-box {
    width: 72px !important;
    height: 72px !important;
    min-width: 72px !important;
    min-height: 72px !important;
    max-width: 72px !important;
    max-height: 72px !important;
    flex-shrink: 0 !important;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.12);
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 6px;
    overflow: hidden;
  }
  .cart-img-box img {
    max-width: 100% !important;
    max-height: 100% !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
    display: block !important;
    margin: 0 auto !important;
  }

  /* Qty Pill Horizontal Layout */
  .b2b-qty-pill {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #0f1015 !important;
    border: 1px solid rgba(255, 73, 80, 0.35) !important;
    border-radius: 100px !important;
    padding: 4px 8px !important;
    gap: 4px !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5) !important;
  }
  button.b2b-qty-btn,
  .b2b-qty-btn {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    font-size: 0.9rem !important;
    transition: all 0.25s ease !important;
    padding: 0 !important;
    line-height: 1 !important;
    outline: none !important;
    box-shadow: none !important;
  }
  button.b2b-qty-btn:hover,
  .b2b-qty-btn:hover {
    background: rgba(255, 73, 80, 0.25) !important;
    border-color: #ff4950 !important;
    color: #ffffff !important;
    transform: scale(1.1) !important;
  }
  input.b2b-qty-input,
  .b2b-qty-input {
    width: 44px !important;
    height: 32px !important;
    background: transparent !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 0.95rem !important;
    text-align: center !important;
    outline: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    margin: 0 !important;
    appearance: textfield !important;
    -moz-appearance: textfield !important;
    -webkit-appearance: none !important;
  }
  input.b2b-qty-input::-webkit-inner-spin-button,
  input.b2b-qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
  }

  /* Catalog Code Badge */
  .cart-cat-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    padding: 3px 8px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
  }

  /* Sidebar Card */
  .cart-sidebar-panel {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
  }

  .rfq-info-box {
    background: rgba(255, 73, 80, 0.04);
    border: 1px solid rgba(255, 73, 80, 0.15);
    border-radius: 12px;
    padding: 1rem;
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.5;
  }
</style>
@endpush

@section('content')
<section class="cart-page-bg" style="padding-top: 155px !important; padding-bottom: 80px !important;">
  <div class="container py-2">

    <!-- Stepper Navigation -->
    <div class="cart-stepper-wrap" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem;">
      <div style="display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: space-between !important; flex-wrap: wrap !important; gap: 16px;">
        <div style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 16px !important; flex-wrap: wrap !important;">
          <div style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; font-size: 0.82rem; font-weight: 600; color: #ffffff;">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: #ff4950; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">1</span>
            <span>Keranjang RFQ</span>
          </div>
          <span style="color: rgba(255, 255, 255, 0.3); font-size: 0.85rem; font-weight: bold;">&gt;</span>
          <div style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; font-size: 0.82rem; font-weight: 600; color: rgba(255, 255, 255, 0.4);">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.5); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">2</span>
            <span>Kredensial Korporasi</span>
          </div>
          <span style="color: rgba(255, 255, 255, 0.3); font-size: 0.85rem; font-weight: bold;">&gt;</span>
          <div style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 8px !important; font-size: 0.82rem; font-weight: 600; color: rgba(255, 255, 255, 0.4);">
            <span style="width: 24px; height: 24px; border-radius: 50%; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.5); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">3</span>
            <span>Surat Penawaran Resmi</span>
          </div>
        </div>

        @if(!empty($cart) && count($cart) > 0)
          <div>
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="confirmClearCart(event, this);" class="m-0">
              @csrf
              <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <!-- Header Title -->
    <div class="mb-4">
      <h1 class="h3 fw-bold text-white mb-1">Daftar Item Pengajuan RFQ</h1>
      <p class="text-secondary small mb-0">Kelola item produk &amp; kuantitas pesanan sebelum menerbitkan Surat Penawaran Harga Resmi B2B.</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
      <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-20 alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-20 alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $item['title'] }} — RFQ Basket Item" loading="lazy" decoding="async">
                  </div>
                </div>

                <!-- 2. Product Name & Catalog & Stock Status -->
                <div class="col">
                  <a href="{{ url('/produk/detail') }}?id={{ urlencode($item['title']) }}" class="text-white text-decoration-none fw-semibold d-block mb-1 text-hover-danger" style="font-size: 0.98rem; line-height: 1.35;">
                    {{ $item['title'] }}
                  </a>
                  <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                    @if(!empty($item['catalog']))
                      <span class="cart-cat-badge">
                        <i class="bi bi-hash"></i>Cat: {{ $item['catalog'] }}
                      </span>
                    @endif

                    @php
                      $stockVal = (int)($item['stock'] ?? 0);
                      $isIndent = $item['quantity'] > $stockVal;
                    @endphp

                    @if(!$isIndent)
                      <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1" style="font-size: 0.72rem;">
                        <i class="bi bi-box-seam me-1"></i> Ready Stock
                      </span>
                    @else
                      <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-2 py-1" style="font-size: 0.72rem;" title="Stok ready {{ $stockVal }} unit. Sisa {{ $item['quantity'] - $stockVal }} unit akan diproses secara Indent / Pre-Order.">
                        <i class="bi bi-clock-history me-1"></i> Status: Indent / Pre-Order (Ready: {{ $stockVal }}, Indent: {{ $item['quantity'] - $stockVal }})
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
                    <div class="b2b-qty-pill">
                      <button type="button" class="b2b-qty-btn" aria-label="Kurangi Jumlah" onclick="stepCartQty(this, -1)">
                        <i class="bi bi-dash-lg"></i>
                      </button>
                      <input type="text" inputmode="numeric" pattern="[0-9]*" name="quantity" value="{{ $item['quantity'] }}" aria-label="Jumlah Qty" class="b2b-qty-input cart-qty-input" onchange="updateCartItemAjax(this.form)">
                      <button type="button" class="b2b-qty-btn" aria-label="Tambah Jumlah" onclick="stepCartQty(this, 1)">
                        <i class="bi bi-plus-lg"></i>
                      </button>
                    </div>
                  </form>
                </div>

                <!-- 4. Subtotal & Remove Button -->
                <div class="col-auto text-end">
                  <div class="d-flex align-items-center gap-3">
                    <div>
                      <span class="fw-bold item-subtotal-val" style="color: var(--color-accent); font-size: 1rem;">
                        {{ $item['price'] > 0 ? 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') : 'Est. Penawaran' }}
                      </span>
                    </div>

                    <form action="{{ route('cart.remove') }}" method="POST" class="m-0" onsubmit="event.preventDefault(); removeCartItemAjax(this);">
                      @csrf
                      <input type="hidden" name="id" value="{{ $item['id'] ?? '' }}">
                      <input type="hidden" name="title" value="{{ $item['title'] }}">
                      <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-color: rgba(255,255,255,0.15);" title="Hapus Item" aria-label="Hapus item">
                        <i class="bi bi-x-lg" style="font-size: 0.85rem;"></i>
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
            <h3 class="h5 fw-bold text-white mb-3 d-flex align-items-center gap-2">
              <i class="bi bi-receipt text-danger"></i> Ringkasan RFQ
            </h3>

            <div class="d-flex justify-content-between mb-2 text-secondary small">
              <span>Total Volume Barang:</span>
              <strong class="text-white fs-6" id="sidebar-total-units">
                {{ array_sum(array_column($cart, 'quantity')) }} Unit
              </strong>
            </div>

            @php
              $totalEstimate = 0;
              foreach($cart as $i) {
                $totalEstimate += ($i['price'] * $i['quantity']);
              }
            @endphp

            <div class="d-flex justify-content-between mb-3 text-secondary small">
              <span>Estimasi Subtotal Katalog:</span>
              <strong style="color: var(--color-accent);" class="fs-6" id="sidebar-total-estimate">
                {{ $totalEstimate > 0 ? 'Rp ' . number_format($totalEstimate, 0, ',', '.') : 'Rp 0' }}
              </strong>
            </div>

            <hr class="border-secondary border-opacity-20 my-3">

            <div class="rfq-info-box mb-4">
              <div class="d-flex gap-2">
                <i class="bi bi-shield-check text-success fs-5 flex-shrink-0"></i>
                <div>
                  <strong class="text-white d-block mb-1">Ketentuan RFQ Resmi B2B</strong>
                  Diskon volume kuantitas korporasi &amp; estimasi ongkir akan divalidasi resmi oleh Sales Engineer Prolabios via Surat Penawaran Resmi.
                </div>
              </div>
            </div>

            <a href="{{ route('rfq.checkout') }}" class="btn btn-danger w-100 py-3 fw-semibold text-uppercase tracking-wider rounded-3 shadow">
              Lanjut Isi Kredensial Korporasi <i class="bi bi-arrow-right ms-2"></i>
            </a>

            <a href="{{ url('/produk') }}" class="btn btn-outline-light w-100 mt-2 py-2 small border-opacity-20">
              <i class="bi bi-plus-lg me-1"></i> Tambah Produk Lain
            </a>
          </div>
        </div>

      </div>
    @else
      <div class="text-center py-5 rounded-3" style="border: 1px dashed var(--color-border); background: rgba(255,255,255,0.01);">
        <i class="bi bi-cart-x text-secondary" style="font-size: 3.5rem;"></i>
        <h3 class="h5 text-white mt-3 mb-2">Keranjang Belanja Anda Masih Kosong</h3>
        <p class="text-secondary small mb-4">Pilih produk laboratorium atau reagen di katalog untuk mulai membuat Pengajuan Penawaran (RFQ).</p>
        <a href="{{ url('/produk') }}" class="btn btn-danger px-4 py-2 fw-semibold">
          <i class="bi bi-grid-3x3-gap me-2"></i> Jelajahi Katalog Produk
        </a>
      </div>
    @endif

  </div>
</section>

@push('scripts')
<script>
  function stepCartQty(btn, amount) {
    const form = btn.closest('form');
    if (!form) return;
    const input = form.querySelector('.cart-qty-input');
    if (!input) return;

    let val = parseInt(input.value) || 1;
    val = Math.max(1, val + amount);
    input.value = val;

    updateCartItemAjax(form);
  }

  function updateCartItemAjax(form) {
    const formData = new FormData(form);
    const itemCard = form.closest('.cart-item-card');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (itemCard) {
          const subtotalEl = itemCard.querySelector('.item-subtotal-val');
          if (subtotalEl) {
            subtotalEl.textContent = data.itemSubtotal;
          }
        }

        const totalUnitsEl = document.getElementById('sidebar-total-units');
        if (totalUnitsEl) {
          totalUnitsEl.textContent = data.cartCount + ' Unit';
        }

        const totalEstEl = document.getElementById('sidebar-total-estimate');
        if (totalEstEl) {
          totalEstEl.textContent = data.totalFormatted;
        }

        const navBadge = document.getElementById('cart-badge-count');
        if (navBadge) {
          navBadge.textContent = data.cartCount;
          navBadge.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
        }
      }
    })
    .catch(err => console.error('Ajax Cart Error:', err));
  }

  function confirmClearCart(e, form) {
    e.preventDefault();
    if (typeof Swal === 'undefined') {
      if (confirm('Kosongkan semua item di keranjang?')) form.submit();
      return;
    }
    Swal.fire({
      title: 'Kosongkan Keranjang RFQ?',
      text: 'Seluruh item produk di dalam keranjang akan dihapus.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ff4950',
      cancelButtonColor: 'rgba(255, 255, 255, 0.15)',
      confirmButtonText: 'Ya, Kosongkan!',
      cancelButtonText: 'Batal',
      background: '#0f172a',
      color: '#ffffff'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  }

  function removeCartItemAjax(form) {
    if (typeof Swal === 'undefined') {
      if (!confirm('Hapus item ini dari keranjang?')) return;
      executeRemoveAjax(form);
      return;
    }

    Swal.fire({
      title: 'Hapus Item Produk?',
      text: 'Item produk ini akan dihapus dari pengajuan RFQ.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ff4950',
      cancelButtonColor: 'rgba(255, 255, 255, 0.15)',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      background: '#0f172a',
      color: '#ffffff'
    }).then((result) => {
      if (result.isConfirmed) {
        executeRemoveAjax(form);
      }
    });
  }

  function executeRemoveAjax(form) {
    const formData = new FormData(form);
    const itemCard = form.closest('.cart-item-card');

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (itemCard) {
          itemCard.style.transition = 'all 0.3s ease';
          itemCard.style.opacity = '0';
          itemCard.style.transform = 'scale(0.95)';
          setTimeout(() => {
            itemCard.remove();
            if (data.cartCount === 0) {
              window.location.reload();
            }
          }, 300);
        }

        const totalUnitsEl = document.getElementById('sidebar-total-units');
        if (totalUnitsEl) {
          totalUnitsEl.textContent = data.cartCount + ' Unit';
        }

        const totalEstEl = document.getElementById('sidebar-total-estimate');
        if (totalEstEl) {
          totalEstEl.textContent = data.totalFormatted;
        }

        const navBadge = document.getElementById('cart-badge-count');
        if (navBadge) {
          navBadge.textContent = data.cartCount;
          navBadge.style.display = data.cartCount > 0 ? 'inline-flex' : 'none';
        }

        if (typeof Swal !== 'undefined') {
          Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: 'Item berhasil dihapus',
            showConfirmButton: false,
            timer: 2000,
            background: '#0f172a',
            color: '#ffffff'
          });
        }
      }
    })
    .catch(err => console.error('Ajax Remove Error:', err));
  }
</script>
@endpush
@endsection
