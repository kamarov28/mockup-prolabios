@extends('layouts.app')

@section('title', 'Kredensial Korporasi B2B | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body); min-height: 80vh; padding-top: 140px !important; padding-bottom: 80px !important;">
  <div class="container py-4">
    
    <div class="max-w-4xl mx-auto">
      <div class="mb-4 text-center">
        <span class="badge bg-danger bg-opacity-20 text-danger px-3 py-2 text-uppercase tracking-wider fw-semibold mb-2">Formulir B2B Procurement</span>
        <h1 class="h2 fw-bold text-white">Lengkapi Data Kredensial Korporasi</h1>
        <p class="text-secondary small">Data ini digunakan oleh Tim Penawaran Prolabios untuk mengevaluasi &amp; menerbitkan Surat Penawaran Harga Resmi (Official Quotation PDF).</p>
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
        <!-- Form Kredensial Perusahaan -->
        <div class="col-lg-7">
          <div class="card border-0 p-4 rounded-3 shadow-sm" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--color-border) !important;">
            <h3 class="h6 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-20">
              <i class="bi bi-building text-danger me-2"></i> 1. Profil Perusahaan &amp; PIC
            </h3>

            <form action="{{ route('rfq.store') }}" method="POST">
              @csrf

              <!-- Nama Perusahaan / Instansi -->
              <div class="mb-3">
                <label for="company_name" class="form-label text-white small fw-semibold">Nama Perusahaan / Instansi / RS / Pabrik <span class="text-danger">*</span></label>
                <input type="text" class="form-control bg-dark text-white border-secondary" id="company_name" name="company_name" value="{{ old('company_name') }}" required placeholder="Contoh: PT. Indofood Sukses Makmur Tbk">
              </div>

              <!-- NPWP / NIB Perusahaan -->
              <div class="mb-3">
                <label for="company_tax_id" class="form-label text-white small fw-semibold">NPWP / NIB Perusahaan (Opsional)</label>
                <input type="text" class="form-control bg-dark text-white border-secondary" id="company_tax_id" name="company_tax_id" value="{{ old('company_tax_id') }}" placeholder="Contoh: 01.234.567.8-901.000">
              </div>

              <div class="row g-3 mb-3">
                <!-- Nama PIC -->
                <div class="col-md-6">
                  <label for="pic_name" class="form-label text-white small fw-semibold">Nama Penanggung Jawab (PIC) <span class="text-danger">*</span></label>
                  <input type="text" class="form-control bg-dark text-white border-secondary" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" required placeholder="Nama Anda">
                </div>

                <!-- Jabatan PIC -->
                <div class="col-md-6">
                  <label for="pic_position" class="form-label text-white small fw-semibold">Jabatan PIC</label>
                  <input type="text" class="form-control bg-dark text-white border-secondary" id="pic_position" name="pic_position" value="{{ old('pic_position') }}" placeholder="Contoh: Procurement Manager / QC Lead">
                </div>
              </div>

              <div class="row g-3 mb-3">
                <!-- Email Korporasi -->
                <div class="col-md-6">
                  <label for="email" class="form-label text-white small fw-semibold">Email Korporasi <span class="text-danger">*</span></label>
                  <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" value="{{ old('email') }}" required placeholder="procurement@company.com">
                  <div class="form-text text-muted small">Feedback &amp; dokumen PDF penawaran akan dikirimkan ke email ini.</div>
                </div>

                <!-- No. WhatsApp PIC -->
                <div class="col-md-6">
                  <label for="phone_wa" class="form-label text-white small fw-semibold">Nomor WhatsApp PIC <span class="text-danger">*</span></label>
                  <input type="text" class="form-control bg-dark text-white border-secondary" id="phone_wa" name="phone_wa" value="{{ old('phone_wa') }}" required placeholder="081234567890">
                </div>
              </div>

              <!-- Alamat Pengiriman -->
              <div class="mb-3">
                <label for="address" class="form-label text-white small fw-semibold">Alamat Lengkap Pengiriman / Lokasi Pabrik <span class="text-danger">*</span></label>
                <textarea class="form-control bg-dark text-white border-secondary" id="address" name="address" rows="3" required placeholder="Jl. Raya Industri No. 12, Kawasan Industri, Bekasi, Jawa Barat">{{ old('address') }}</textarea>
              </div>

              <!-- Catatan Spesifik -->
              <div class="mb-4">
                <label for="notes" class="form-label text-white small fw-semibold">Catatan Pengadaan Spesifik (Opsional)</label>
                <textarea class="form-control bg-dark text-white border-secondary" id="notes" name="notes" rows="2" placeholder="Contoh: Butuh sertifikat COA / MSDS, atau opsi pengiriman bertahap">{{ old('notes') }}</textarea>
              </div>

              <button type="submit" class="btn btn-danger w-100 py-3 fw-bold text-uppercase tracking-wider">
                <i class="bi bi-send-fill me-2"></i> Kirim Pengajuan Penawaran B2B (RFQ)
              </button>
            </form>
          </div>
        </div>

        <!-- Ringkasan Item Pesanan -->
        <div class="col-lg-5">
          <div class="card border-0 p-4 rounded-3 shadow-sm" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--color-border) !important;">
            <h3 class="h6 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-20">
              <i class="bi bi-cart-check text-danger me-2"></i> 2. Ringkasan Item RFQ
            </h3>

            <div class="list-group list-group-flush bg-transparent mb-3">
              @foreach($cart as $item)
                <div class="list-group-item bg-transparent text-white px-0 py-2 border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-semibold small" style="max-width: 220px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                      {{ $item['title'] }}
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                      {{ $item['catalog'] ? 'Cat. ' . $item['catalog'] . ' • ' : '' }} {{ $item['quantity'] }} Unit
                    </div>
                  </div>
                  <div class="text-end fw-semibold" style="font-size: 0.88rem; color: var(--color-accent);">
                    {{ $item['price'] > 0 ? 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') : 'Est. Penawaran' }}
                  </div>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-between py-2 border-top border-secondary border-opacity-20 text-white fw-bold mb-4">
              <span>Total Estimasi Penawaran:</span>
              <span style="color: var(--color-accent);">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <div class="p-3 rounded bg-dark border border-secondary border-opacity-20 text-secondary" style="font-size: 0.8rem; line-height: 1.5;">
              <i class="bi bi-shield-check text-success me-1"></i>
              Data kredensial korporasi Anda dilindungi secara ketat. Tim Sales &amp; Procurement Prolabios akan memproses dokumen Surat Penawaran Resmi dan mengirimi Anda balasan.
            </div>

            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3 py-2 small">
              <i class="bi bi-pencil me-1"></i> Ubah Item di Keranjang
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
