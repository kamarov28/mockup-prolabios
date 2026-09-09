@extends('layouts.app')

@section('title', 'Kontak | PROLABIOS')

@section('content')
  <!-- Hero Banner (Soft Neo-Brutalism) -->
  <section class="profil-hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9">
          <span class="nb-badge">
            <i class="bi bi-chat-square-dots me-1"></i> HUBUNGI KAMI
          </span>
          <h1 class="profil-main-title">
            Kontak &amp; Layanan Pelanggan
          </h1>
          <p class="profil-main-subtitle">
            Hubungi tim Prolabios untuk pertanyaan umum, konsultasi teknis pengujian laboratorium, atau permintaan perbaikan dan kalibrasi instrumen.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Content -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Contact Info Sidebar -->
        <div class="col-12 col-lg-4 order-2 order-lg-1">

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-geo-alt"></i></div>
            <h3 class="kontak-info-title">Alamat Kantor</h3>
            <p class="profil-body-text mb-0">{!! nl2br(e($siteSettings['contact_address'] ?? "Komplek Cibinong Griya Asri Blok: A9/10, RT 01 RW 08\nCibinong – Bogor, West Java, Indonesia 16913")) !!}</p>
          </div>

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-telephone"></i></div>
            <h3 class="kontak-info-title">Telepon Kantor</h3>
            <p class="profil-body-text mb-1"><strong style="color: var(--nb-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Marketing &amp; Sales:</strong></p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="kontak-info-link">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }}</a>
            <p class="profil-body-text mt-3 mb-1"><strong style="color: var(--nb-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Keuangan &amp; Gudang:</strong></p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="kontak-info-link">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }}</a>
          </div>

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-envelope"></i></div>
            <h3 class="kontak-info-title">Email Resmi</h3>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="kontak-info-link">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
            <a href="mailto:sandi@prolabios.com" class="kontak-info-link">sandi@prolabios.com</a>
          </div>

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-clock"></i></div>
            <h3 class="kontak-info-title">Jam Operasional</h3>
            <p class="profil-body-text mb-0">{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat: 08.00 – 17.00 WIB' }}</p>
          </div>

        </div>

        <!-- Contact Form -->
        <div class="col-12 col-lg-8 order-1 order-lg-2">
          <div class="card p-4 p-md-5">
            <span class="nb-badge mb-2"><i class="bi bi-envelope-paper me-1"></i> TINGGALKAN PESAN</span>
            <h2 class="profil-section-title mb-3">Kirim Pesan &amp; Pertanyaan</h2>

            {{-- RFQ Redirection Notice --}}
            <div class="mb-4 p-3 d-flex align-items-start gap-3" style="background: var(--nb-bg-soft); border: 1.5px solid var(--nb-ink); border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 var(--nb-ink);">
              <i class="bi bi-info-circle-fill text-primary mt-1 flex-shrink-0" style="font-size: 1.25rem;"></i>
              <div class="small" style="color: var(--nb-ink); line-height: 1.5;">
                <strong class="d-block mb-1" style="font-family: var(--font-display); font-size: 0.88rem;">Informasi Permintaan Penawaran Harga (RFQ):</strong>
                Formulir kontak ini khusus untuk pertanyaan umum, bantuan teknis, dan layanan servis. Pengajuan penawaran harga resmi (RFQ) institusi dilakukan melalui <a href="{{ url('/produk') }}" class="fw-bold text-decoration-underline" style="color: var(--nb-primary);">Katalog Produk</a> dengan menambahkan produk ke dalam Keranjang RFQ.
              </div>
            </div>

            <form id="contactForm" class="contact-form" action="{{ route('contact.submit', [], false) }}" method="POST" data-recaptcha-key="{{ config('services.recaptcha.site_key') ?? '' }}">
              @csrf
              {{-- Anti-Bot Honeypot Field --}}
              <div style="display:none !important; position:absolute; left:-9999px;" aria-hidden="true">
                <label for="_hp_website">Leave this field blank</label>
                <input type="text" name="_hp_website" id="_hp_website" tabindex="-1" autocomplete="off" value="">
              </div>

              {{-- reCAPTCHA v3 Token --}}
              <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-contact">

              <div class="row g-3 g-md-4">
                <div class="col-md-6">
                  <label for="nama" class="kontak-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control kontak-form-input" id="nama" name="nama" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="col-md-6">
                  <label for="email" class="kontak-form-label">Email Pribadi / Kontak <span class="text-danger">*</span></label>
                  <input type="email" class="form-control kontak-form-input" id="email" name="email" required placeholder="contoh: budi@gmail.com" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Masukkan format email yang valid (contoh: nama@email.com)">
                </div>
                <div class="col-md-6">
                  <label for="telepon" class="kontak-form-label">Nomor WhatsApp / Telepon Pribadi</label>
                  <input type="tel" class="form-control kontak-form-input" id="telepon" name="telepon" placeholder="Contoh: 081234567890" inputmode="numeric" pattern="^[0-9+\-\s]{8,20}$" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" title="Hanya boleh berupa angka dan karakter nomor telepon">
                </div>
                <div class="col-md-6">
                  <label for="perusahaan" class="kontak-form-label">Asal Instansi / Perusahaan <span class="text-danger">*</span></label>
                  <input type="text" class="form-control kontak-form-input" id="perusahaan" name="perusahaan" required placeholder="Nama institusi, universitas, atau perusahaan">
                </div>
                <div class="col-12">
                  <label for="subjek" class="kontak-form-label">Keperluan / Subjek <span class="text-danger">*</span></label>
                  <select class="form-select kontak-form-input" id="subjek" name="subjek" required>
                    <option value="">-- Pilih Keperluan --</option>
                    <option value="inquiry">Pertanyaan Umum</option>
                    <option value="service">Permintaan Perbaikan / Kalibrasi</option>
                    <option value="consultation">Konsultasi Metode &amp; Teknis</option>
                    <option value="labdesign">Desain &amp; Pembangunan Lab</option>
                    <option value="other">Lainnya</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="pesan" class="kontak-form-label">Detail Pesan <span class="text-danger">*</span></label>
                  <textarea class="form-control kontak-form-input" id="pesan" name="pesan" rows="5" required placeholder="Tuliskan pertanyaan, konsultasi, atau pesan yang ingin disampaikan..."></textarea>
                </div>
                <div class="col-12 mt-3">
                  <div class="p-3 d-flex align-items-center gap-2" style="background: var(--nb-bg-soft); border: 1.5px solid var(--nb-ink); border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 var(--nb-ink);">
                    <i class="bi bi-clock-history text-primary flex-shrink-0" style="font-size: 1.1rem;"></i>
                    <span class="small" style="font-size: 0.8rem; color: var(--nb-ink); line-height: 1.4;">
                      <strong>Komitmen Respon Cepat (Maksimal 1×24 Jam Kerja):</strong> Setiap pertanyaan teknis, konsultasi metode, atau permohonan kalibrasi instrumen akan direspon oleh tim teknis kami dalam 1×24 jam kerja.
                    </span>
                  </div>
                </div>
                <div class="col-12 mt-3">
                  <button type="submit" class="kontak-submit-btn">
                    <i class="bi bi-send-fill me-1"></i> Kirim Pesan Sekarang
                  </button>
                </div>
              </div>
            </form>

            <div id="formSuccess" style="display: none; text-align: center; padding: 40px 20px; background: var(--nb-bg-soft); border: 2px solid #1E1E1E; border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
              <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: var(--nb-accent); border: 2px solid #1E1E1E; border-radius: var(--nb-radius-sm); box-shadow: 2px 2px 0 #1E1E1E;">
                <i class="bi bi-check2-circle" style="font-size: 2rem; color: var(--nb-ink);"></i>
              </div>
              <h3 class="profil-section-title" style="font-size: 1.5rem !important; margin-bottom: 10px !important;">Pesan Berhasil Terkirim!</h3>
              <p class="profil-body-text mb-4" style="max-width: 500px; margin-left: auto; margin-right: auto;">Terima kasih telah menghubungi PT Prolabios Mitra Analitika. Tim sales &amp; teknis kami akan segera menindaklanjuti pesan Anda dalam 1×24 jam kerja.</p>
              <a href="{{ url('/') }}" class="nb-btn nb-btn-primary d-inline-flex mx-auto">Kembali ke Beranda <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  @if(!empty($siteSettings['google_maps_embed_url']))
  <section class="pb-5 pt-0">
    <div class="container">
      <div class="overflow-hidden" style="height: 380px; border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
        <iframe src="{{ $siteSettings['google_maps_embed_url'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>
  @endif

  @push('scripts')
  @if(config('services.recaptcha.site_key'))
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  @endif
  @endpush
@endsection
