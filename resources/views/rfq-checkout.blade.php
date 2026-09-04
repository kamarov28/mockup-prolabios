@extends('layouts.app')

@section('title', 'Pengajuan Penawaran | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--nb-bg); min-height: 85vh; padding-top: 140px !important; padding-bottom: 80px !important;">
  <div class="container py-4">

    <div class="max-w-4xl mx-auto" style="max-width: 960px;">
      <div class="mb-5 text-center">
        <span class="nb-badge mb-3">FORMULIR PENAWARAN</span>
        <h1 class="profil-section-title mb-2">Lengkapi Data Pengajuan Penawaran</h1>
        <p class="profil-body-text" style="max-width: 600px; margin: auto; color: var(--nb-muted);">Data ini digunakan oleh Tim Sales Prolabios untuk menghubungi Anda &amp; memberikan penawaran resmi.</p>
      </div>

      @if($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-20 alert-dismissible fade show rounded-0 mb-4" role="alert">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
      @endif

      <form action="{{ route('rfq.store') }}" method="POST" id="rfqForm">
        @csrf

        {{-- Anti-Bot Honeypot Field (Hidden from authentic users) --}}
        <div style="display:none !important; position:absolute; left:-9999px;" aria-hidden="true">
          <label for="_hp_website">Leave this field blank</label>
          <input type="text" name="_hp_website" id="_hp_website" tabindex="-1" autocomplete="off" value="">
        </div>

        <div class="row g-4 rfq-checkout-row">
          <!-- 1. Form Informasi Pemohon -->
          <div class="col-lg-7">
            <div class="cart-sidebar-panel rfq-card-form h-100">
              <h3 class="cart-sidebar-title d-flex align-items-center gap-2">
                <i class="bi bi-person-lines-fill" style="color: var(--color-accent);"></i> 1. Informasi Pemohon
              </h3>

              <!-- Nama Lengkap -->
              <div class="mb-3">
                <label for="name" class="kontak-form-label">Nama Lengkap <span style="color: var(--color-accent);">*</span></label>
                <input type="text" class="form-control rfq-input" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
              </div>

              <!-- Email Pribadi -->
              <div class="mb-3">
                <label for="email" class="kontak-form-label">Email Pribadi <span style="color: var(--color-accent);">*</span></label>
                <input type="email" class="form-control rfq-input" id="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com" autocomplete="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Masukkan format email yang valid (contoh: nama@email.com)">
                <div class="form-text small" style="color: var(--color-text-muted); font-size: 0.75rem;">Konfirmasi pengajuan penawaran akan dikirimkan ke email ini.</div>
              </div>

              <!-- Nama Perusahaan / Instansi -->
              <div class="mb-3">
                <label for="company_name" class="kontak-form-label">Nama Instansi / Perusahaan <span style="color: var(--color-accent);">*</span></label>
                <input type="text" class="form-control rfq-input" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="Contoh: PT. Indofood Sukses Makmur Tbk / Lab Farmasi Univ. X">
              </div>

              <!-- Nomor WhatsApp -->
              <div class="mb-3">
                <label for="phone_wa" class="kontak-form-label">Nomor WhatsApp <span style="color: var(--color-accent);">*</span></label>
                <input type="tel" class="form-control rfq-input" id="phone_wa" name="phone_wa" value="{{ old('phone_wa') }}" required placeholder="Contoh: 081234567890" inputmode="numeric" pattern="^[0-9+\-\s]{8,20}$" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" title="Nomor WhatsApp hanya boleh berupa angka (minimal 8 digit)">
                <div class="form-text small" style="color: var(--color-text-muted); font-size: 0.75rem;">Hanya menerima angka / nomor telepon aktif WhatsApp.</div>
              </div>

              <!-- Catatan Tambahan -->
              <div class="mb-0 mb-lg-3">
                <label for="notes" class="kontak-form-label">Catatan Tambahan <span style="color: var(--color-text-muted); font-weight: normal;">(Opsional)</span></label>
                <textarea class="form-control rfq-input" id="notes" name="notes" rows="3" placeholder="Contoh: Butuh sertifikat COA / MSDS, pengiriman urgent, dll.">{{ old('notes') }}</textarea>
              </div>
            </div>
          </div>

          <!-- 2. Ringkasan Produk & Submit CTA -->
          <div class="col-lg-5">
            <div class="cart-sidebar-panel rfq-card-summary h-100 d-flex flex-column justify-content-between">
              <div>
                <h3 class="cart-sidebar-title d-flex align-items-center gap-2">
                  <i class="bi bi-cart-check" style="color: var(--color-accent);"></i> 2. Ringkasan Produk
                </h3>

                <div class="list-group list-group-flush bg-transparent mb-3">
                  @foreach($cart as $item)
                    <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1.5px solid rgba(30,30,30,0.12) !important;">
                      <div>
                        <div class="fw-semibold small" style="font-family: var(--font-display); font-size: 0.95rem; max-width: 220px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; color: var(--nb-ink);">
                          {{ $item['title'] }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--nb-muted); margin-top: 4px;">
                          {{ !empty($item['catalog']) ? 'Cat. ' . $item['catalog'] . ' • ' : '' }} {{ $item['quantity'] }} Unit
                        </div>
                      </div>
                      <div class="text-end fw-bold" style="font-family: var(--font-display); font-size: 0.95rem; color: var(--nb-primary);">
                        {{ $item['price'] > 0 ? 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') : 'Est. Penawaran' }}
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="d-flex justify-content-between py-3 fw-bold mb-3" style="border-top: 2px solid var(--nb-ink); border-bottom: 2px solid var(--nb-ink); color: var(--nb-ink);">
                  <span style="font-family: var(--font-mono); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Estimasi Total:</span>
                  <span style="font-family: var(--font-display); font-size: 1.2rem; color: var(--nb-primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <div class="rfq-info-box mb-4" style="background: var(--nb-bg-soft); border: 1.5px solid var(--nb-ink); border-radius: var(--nb-radius-sm); padding: 12px; box-shadow: 2px 2px 0 var(--nb-ink); color: var(--nb-ink);">
                  <i class="bi bi-info-circle-fill me-1" style="color: var(--nb-primary);"></i>
                  Harga di atas adalah estimasi katalog. Tim sales kami akan memberikan diskon khusus &amp; harga final via WhatsApp/Email.
                </div>
              </div>

              <div>
                <button type="submit" id="rfqSubmitBtn" class="rfq-primary-btn w-100 mb-2" style="border: none;">
                  <i class="bi bi-send-fill me-2"></i> Kirim Pengajuan Penawaran
                </button>

                <a href="{{ route('cart.index') }}" class="rfq-secondary-btn">
                  <i class="bi bi-pencil me-1"></i> Ubah Keranjang Belanja
                </a>
              </div>
            </div>
          </div>
        </div>

        {{-- reCAPTCHA v3 Token --}}
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-rfq">
      </form>

    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rfqForm');
    const btn = document.getElementById('rfqSubmitBtn');
    if (form && btn) {
      form.addEventListener('submit', function() {
        if (form.checkValidity()) {
          btn.disabled = true;
          btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim Pengajuan...';
        }
      });
    }
  });
</script>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'rfq_submit'})
        .then(function(token) {
            document.getElementById('g-recaptcha-response-rfq').value = token;
        });
    });
</script>
@endif

<style>
  .rfq-input {
    background-color: #FFFFFF !important;
    border: var(--nb-border) !important;
    color: var(--nb-ink) !important;
    font-size: 0.9rem !important;
    padding: 10px 14px !important;
    border-radius: var(--nb-radius-sm) !important;
    box-shadow: 2px 2px 0 rgba(30,30,30,0.12) !important;
    transition: box-shadow 0.12s ease !important;
  }
  .rfq-input::placeholder {
    color: var(--nb-muted) !important;
    opacity: 0.6;
  }
  .rfq-input:focus {
    border-color: var(--nb-primary) !important;
    box-shadow: 3px 3px 0 var(--nb-ink) !important;
    outline: none !important;
    color: var(--nb-ink) !important;
  }
  .kontak-form-label {
    color: var(--nb-ink) !important;
    font-weight: 700 !important;
    font-family: var(--font-display) !important;
    font-size: 0.85rem !important;
    margin-bottom: 6px !important;
  }
  .cart-sidebar-title {
    color: var(--nb-ink) !important;
    font-family: var(--font-display) !important;
    font-weight: 700 !important;
  }
</style>
@endsection
