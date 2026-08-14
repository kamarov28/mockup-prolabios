@extends('layouts.app')

@section('title', 'Pengajuan Penawaran | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body); min-height: 80vh; padding-top: 140px !important; padding-bottom: 80px !important;">
  <div class="container py-4">
    
    <div class="max-w-4xl mx-auto">
      <div class="mb-4 text-center">
        <span class="badge bg-danger bg-opacity-20 text-danger px-3 py-2 text-uppercase tracking-wider fw-semibold mb-2">Formulir Pengajuan Penawaran</span>
        <h1 class="h2 fw-bold text-white">Lengkapi Data Pengajuan Penawaran</h1>
        <p class="text-secondary small">Data ini digunakan oleh Tim Sales Prolabios untuk menghubungi Anda &amp; memberikan penawaran resmi.</p>
      </div>

      @if($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-20 alert-dismissible fade show mb-4" role="alert">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-4">
        <!-- Form Informasi Pemohon -->
        <div class="col-lg-7">
          <div class="card border-0 p-4 rounded-3 shadow-sm" style="background: var(--color-surface, #0e0e10); border: 1px solid var(--color-border) !important;">
            <h3 class="h6 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-20">
              <i class="bi bi-person-lines-fill text-danger me-2"></i> 1. Informasi Pemohon
            </h3>

            <form action="{{ route('rfq.store') }}" method="POST">
              @csrf

              {{-- Anti-Bot Honeypot Field (Hidden from authentic users) --}}
              <div style="display:none !important; position:absolute; left:-9999px;" aria-hidden="true">
                <label for="_hp_website">Leave this field blank</label>
                <input type="text" name="_hp_website" id="_hp_website" tabindex="-1" autocomplete="off" value="">
              </div>

              <!-- Nama Lengkap -->
              <div class="mb-3">
                <label for="name" class="form-label text-white small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control rfq-input" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
              </div>

              <!-- Email Pribadi -->
              <div class="mb-3">
                <label for="email" class="form-label text-white small fw-semibold">Email Pribadi <span class="text-danger">*</span></label>
                <input type="email" class="form-control rfq-input" id="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com" autocomplete="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Masukkan format email yang valid (contoh: nama@email.com)">
                <div class="form-text text-muted small">Konfirmasi pengajuan penawaran akan dikirimkan ke email ini.</div>
              </div>

              <!-- Nama Perusahaan / Instansi -->
              <div class="mb-3">
                <label for="company_name" class="form-label text-white small fw-semibold">Nama Instansi / Perusahaan <span class="text-danger">*</span></label>
                <input type="text" class="form-control rfq-input" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="Contoh: PT. Indofood Sukses Makmur Tbk / Lab Farmasi Univ. X">
              </div>

              <!-- Nomor WhatsApp -->
              <div class="mb-3">
                <label for="phone_wa" class="form-label text-white small fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                <input type="tel" class="form-control rfq-input" id="phone_wa" name="phone_wa" value="{{ old('phone_wa') }}" required placeholder="Contoh: 081234567890" inputmode="numeric" pattern="^[0-9+\-\s]{8,20}$" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" title="Nomor WhatsApp hanya boleh berupa angka (minimal 8 digit)">
                <div class="form-text text-muted small">Hanya menerima angka / nomor telepon aktif WhatsApp.</div>
              </div>

              <!-- Catatan Tambahan -->
              <div class="mb-4">
                <label for="notes" class="form-label text-white small fw-semibold">Catatan Tambahan <span class="text-muted">(Opsional)</span></label>
                <textarea class="form-control rfq-input" id="notes" name="notes" rows="3" placeholder="Contoh: Butuh sertifikat COA / MSDS, pengiriman urgent, dll.">{{ old('notes') }}</textarea>
              </div>

              <button type="submit" class="btn btn-danger w-100 py-3 fw-bold text-uppercase tracking-wider">
                <i class="bi bi-send-fill me-2"></i> Kirim Pengajuan Penawaran
              </button>
            </form>
          </div>
        </div>

        <!-- Ringkasan Produk -->
        <div class="col-lg-5">
          <div class="card border-0 p-4 rounded-3 shadow-sm" style="background: var(--color-surface, #0e0e10); border: 1px solid var(--color-border) !important;">
            <h3 class="h6 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-20">
              <i class="bi bi-cart-check text-danger me-2"></i> 2. Ringkasan Produk
            </h3>

            <div class="list-group list-group-flush bg-transparent mb-3">
              @foreach($cart as $item)
                <div class="list-group-item bg-transparent text-white px-0 py-2 border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-semibold small" style="max-width: 220px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                      {{ $item['title'] }}
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      {{ !empty($item['catalog']) ? 'Cat. ' . $item['catalog'] . ' • ' : '' }} {{ $item['quantity'] }} Unit
                    </div>
                  </div>
                  <div class="text-end fw-semibold" style="font-size: 0.88rem; color: var(--color-accent);">
                    {{ $item['price'] > 0 ? 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') : 'Harga Katalog' }}
                  </div>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-between py-2 border-top border-secondary border-opacity-20 text-white fw-bold mb-3">
              <span>Estimasi Total Katalog:</span>
              <span style="color: var(--color-accent);">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="p-3 rounded border border-secondary border-opacity-20 text-secondary" style="background: rgba(0, 0, 0, 0.4); font-size: 0.8rem; line-height: 1.5;">
              <i class="bi bi-info-circle text-info me-1"></i>
              *Harga di atas adalah estimasi harga katalog. Harga final dan diskon khusus akan diberikan langsung oleh tim sales kami via Email/WhatsApp.
            </div>

            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3 py-2 small">
              <i class="bi bi-pencil me-1"></i> Ubah Keranjang Belanja
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
  .rfq-input {
    background-color: #070708 !important;
    border: 1px solid var(--color-border) !important;
    color: #ffffff !important;
    font-size: 0.9rem !important;
    padding: 10px 14px !important;
    border-radius: 6px !important;
    transition: border-color 0.25s ease, box-shadow 0.25s ease !important;
  }
  .rfq-input::placeholder {
    color: rgba(255, 255, 255, 0.25) !important;
  }
  .rfq-input:focus {
    background-color: #0e0e10 !important;
    border-color: var(--color-accent) !important;
    box-shadow: 0 0 0 3px rgba(255, 73, 80, 0.12) !important;
    color: #ffffff !important;
  }
</style>
@endsection
