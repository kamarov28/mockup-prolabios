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
            Konsultasikan kebutuhan pengadaan peralatan laboratorium, permintaan penawaran harga (RFQ institusi), atau jadwal servis instrumen bersama tim kami.
          </p>
        </div>
      </div>

      <!-- Quick Fast Stats Strip -->
      <div class="profil-stats-strip">
        <div class="profil-stat-box">
          <div class="profil-stat-num">Respon Cepat</div>
          <div class="profil-stat-label">Balasan RFQ Dalam 1×24 Jam Kerja</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Kantor Pusat</div>
          <div class="profil-stat-label">Cibinong, Bogor, Jawa Barat</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Kanal Resmi</div>
          <div class="profil-stat-label">Telepon Kantor, Email &amp; WhatsApp</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">B2B Support</div>
          <div class="profil-stat-label">Faktur Pajak &amp; Legalitas Lengkap</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Content -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Contact Info Sidebar -->
        <div class="col-lg-4 col-md-5 order-2 order-md-1">

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
        <div class="col-lg-8 col-md-7 order-1 order-md-2">
          <div class="card p-4 p-md-5" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
            <span class="nb-badge mb-2"><i class="bi bi-envelope-paper me-1"></i> TINGGALKAN PESAN</span>
            <h2 class="profil-section-title mb-4">Kirim Pesan atau Permintaan RFQ</h2>

            <form id="contactForm" class="contact-form" onsubmit="return handleContactForm(event)">
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
                  <label for="email" class="kontak-form-label">Email Perusahaan / Institusi <span class="text-danger">*</span></label>
                  <input type="email" class="form-control kontak-form-input" id="email" name="email" required placeholder="contoh@instansi.com" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Masukkan format email yang valid (contoh: nama@email.com)">
                </div>
                <div class="col-md-6">
                  <label for="telepon" class="kontak-form-label">Nomor WhatsApp / Telepon</label>
                  <input type="tel" class="form-control kontak-form-input" id="telepon" name="telepon" placeholder="+62 xxx xxxx xxxx" inputmode="numeric" pattern="^[0-9+\-\s]{8,20}$" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" title="Hanya boleh berupa angka dan karakter nomor telepon">
                </div>
                <div class="col-md-6">
                  <label for="perusahaan" class="kontak-form-label">Perusahaan / Universitas / Instansi</label>
                  <input type="text" class="form-control kontak-form-input" id="perusahaan" name="perusahaan" placeholder="Nama instansi pengadaan Anda">
                </div>
                <div class="col-12">
                  <label for="subjek" class="kontak-form-label">Keperluan / Subjek <span class="text-danger">*</span></label>
                  <select class="form-select kontak-form-input" id="subjek" name="subjek" required>
                    <option value="">-- Pilih Keperluan --</option>
                    <option value="inquiry">Pertanyaan Spesifikasi Produk</option>
                    <option value="quotation">Permintaan Penawaran Harga (RFQ)</option>
                    <option value="service">Permintaan Perbaikan / Kalibrasi</option>
                    <option value="consultation">Konsultasi Metode &amp; Teknis</option>
                    <option value="labdesign">Desain &amp; Pembangunan Lab</option>
                    <option value="other">Lainnya</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="pesan" class="kontak-form-label">Detail Pesan / Spesifikasi Kebutuhan <span class="text-danger">*</span></label>
                  <textarea class="form-control kontak-form-input" id="pesan" name="pesan" rows="5" required placeholder="Tuliskan detail pertanyaan atau daftar produk yang ingin diajukan penawarannya..."></textarea>
                </div>
                <div class="col-12 mt-4">
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
  @include('partials.gsap-loader')

  @if(config('services.recaptcha.site_key'))
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      window.handleContactForm = async function(e) {
        e.preventDefault();
        const form = document.getElementById('contactForm');
        const success = document.getElementById('formSuccess');
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!form || !success || !submitBtn) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let valid = true;
        requiredFields.forEach(function(field) {
          if (!field.value.trim()) {
            field.style.borderColor = 'var(--color-accent)';
            valid = false;
          } else {
            field.style.borderColor = 'var(--color-border)';
          }
        });
        if (!valid) return false;

        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

        const csrfToken = '{{ csrf_token() }}';

        @if(config('services.recaptcha.site_key'))
        // Generate reCAPTCHA v3 token before submitting
        let recaptchaToken = '';
        try {
          recaptchaToken = await grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact_submit'});
          document.getElementById('g-recaptcha-response-contact').value = recaptchaToken;
        } catch (err) {
          console.error('reCAPTCHA error:', err);
          alert('Verifikasi keamanan gagal. Silakan muat ulang halaman dan coba lagi.');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
          return false;
        }
        @endif

        const formData = new FormData(form);
        fetch('{{ route("contact.submit", [], false) }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            form.style.display = 'none';
            success.style.display = 'block';
            const msgEl = success.querySelector('p.profil-body-text');
            if (msgEl) msgEl.textContent = data.message;
            success.scrollIntoView({ behavior: 'smooth', block: 'center' });
          } else {
            alert(data.message || 'Gagal mengirim pesan.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }
        })
        .catch(error => {
          console.error('Error submitting form:', error);
          alert('Terjadi gangguan koneksi atau server. Silakan hubungi admin.');
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        });
        return false;
      };

      const urlParams = new URLSearchParams(window.location.search);
      const subjekParam = urlParams.get('subjek');
      const produkParam = urlParams.get('produk');
      if (subjekParam) {
        const subjekSelect = document.getElementById('subjek');
        if (subjekSelect) subjekSelect.value = subjekParam;
      }
      if (produkParam) {
        const pesanTextarea = document.getElementById('pesan');
        if (pesanTextarea) {
          pesanTextarea.value = `Halo Prolabios,\n\nSaya tertarik dan ingin meminta informasi lebih lanjut / penawaran harga untuk produk: "${decodeURIComponent(produkParam)}".\n\nTerima kasih.`;
        }
      }
    });
  </script>
  @endpush
@endsection
