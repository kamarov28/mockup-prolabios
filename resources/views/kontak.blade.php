@extends('layouts.app')

@section('title', 'Kontak | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Hubungi Kami</span>
      <h1 class="editorial-page-title">Kontak</h1>
      <p class="editorial-page-subtitle">Kami siap membantu kebutuhan laboratorium dan instrumen Anda</p>
    </div>
  </div>

  <!-- Contact Content -->
  <section class="section-main">
    <div class="container">
      <div class="row g-5">

        <!-- Contact Info -->
        <div class="col-lg-4 col-md-5 order-last order-md-first">

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-geo-alt"></i></div>
            <h3 class="kontak-info-title">Alamat Kantor</h3>
            <p class="profil-body-text">{!! nl2br(e($siteSettings['contact_address'] ?? "Komplek Cibinong Griya Asri Blok: A9/10, RT 01 RW 08\nCibinong – Bogor, West Java, Indonesia 16913")) !!}</p>
          </div>

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-telephone"></i></div>
            <h3 class="kontak-info-title">Telepon</h3>
            <p class="profil-body-text" style="margin-bottom: 4px;"><strong style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Marketing:</strong></p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="kontak-info-link">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }}</a>
            <p class="profil-body-text" style="margin-top: 12px; margin-bottom: 4px;"><strong style="color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Keuangan &amp; Gudang:</strong></p>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="kontak-info-link">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }}</a>
          </div>

          <div class="kontak-info-block">
            <div class="kontak-info-icon"><i class="bi bi-envelope"></i></div>
            <h3 class="kontak-info-title">Email</h3>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="kontak-info-link">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
            <a href="mailto:sandi@prolabios.com" class="kontak-info-link">sandi@prolabios.com</a>
          </div>

          <div class="kontak-info-block" style="border-bottom: none;">
            <div class="kontak-info-icon"><i class="bi bi-clock"></i></div>
            <h3 class="kontak-info-title">Jam Operasional</h3>
            <p class="profil-body-text">{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat: 08.00 – 17.00 WIB' }}</p>
          </div>

        </div>

        <!-- Contact Form -->
        <div class="col-lg-8 col-md-7">
          <h3 class="profil-section-title mb-5">Kirim Pesan</h3>

          <form id="contactForm" class="contact-form" onsubmit="return handleContactForm(event)">
            @csrf
            {{-- Anti-Bot Honeypot Field --}}
            <div style="display:none !important; position:absolute; left:-9999px;" aria-hidden="true">
              <label for="_hp_website">Leave this field blank</label>
              <input type="text" name="_hp_website" id="_hp_website" tabindex="-1" autocomplete="off" value="">
            </div>

            {{-- reCAPTCHA v3 Token --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-contact">

            <div class="row g-4">
              <div class="col-md-6">
                <label for="nama" class="kontak-form-label">Nama Lengkap <span style="color: var(--color-accent);">*</span></label>
                <input type="text" class="form-control kontak-form-input" id="nama" name="nama" required placeholder="Contoh: Budi Santoso">
              </div>
              <div class="col-md-6">
                <label for="email" class="kontak-form-label">Email <span style="color: var(--color-accent);">*</span></label>
                <input type="email" class="form-control kontak-form-input" id="email" name="email" required placeholder="contoh@email.com" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Masukkan format email yang valid (contoh: nama@email.com)">
              </div>
              <div class="col-md-6">
                <label for="telepon" class="kontak-form-label">Nomor Telepon</label>
                <input type="tel" class="form-control kontak-form-input" id="telepon" name="telepon" placeholder="+62 xxx xxxx xxxx" inputmode="numeric" pattern="^[0-9+\-\s]{8,20}$" oninput="this.value = this.value.replace(/[^0-9+\-\s]/g, '')" title="Hanya boleh berupa angka dan karakter nomor telepon">
              </div>
              <div class="col-md-6">
                <label for="perusahaan" class="kontak-form-label">Perusahaan / Instansi</label>
                <input type="text" class="form-control kontak-form-input" id="perusahaan" name="perusahaan" placeholder="Nama perusahaan atau instansi Anda">
              </div>
              <div class="col-12">
                <label for="subjek" class="kontak-form-label">Subjek <span style="color: var(--color-accent);">*</span></label>
                <select class="form-select kontak-form-input" id="subjek" name="subjek" required>
                  <option value="">-- Pilih Subjek --</option>
                  <option value="inquiry">Pertanyaan Produk</option>
                  <option value="quotation">Permintaan Penawaran Harga</option>
                  <option value="service">Permintaan Servis / Perbaikan</option>
                  <option value="consultation">Konsultasi Teknis</option>
                  <option value="labdesign">Desain Laboratorium</option>
                  <option value="other">Lainnya</option>
                </select>
              </div>
              <div class="col-12">
                <label for="pesan" class="kontak-form-label">Pesan <span style="color: var(--color-accent);">*</span></label>
                <textarea class="form-control kontak-form-input" id="pesan" name="pesan" rows="5" required placeholder="Tulis pesan Anda di sini..."></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="kontak-submit-btn">Kirim Pesan <i class="bi bi-send"></i></button>
              </div>
            </div>
          </form>

          <div id="formSuccess" style="display: none; text-align: center; padding: 60px 0; border: 1px solid var(--color-border);">
            <i class="bi bi-check-circle" style="font-size: 3rem; color: #4ade80; display: block; margin-bottom: 20px;"></i>
            <h3 class="profil-section-title" style="font-size: 1.4rem !important;">Pesan Terkirim!</h3>
            <p class="profil-body-text mb-4">Terima kasih telah menghubungi kami. Tim kami akan merespons dalam 1×24 jam kerja.</p>
            <a href="{{ url('/') }}" class="profil-cta-btn">Kembali ke Beranda <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>

      </div>
    </div>
  </section>

  @if(!empty($siteSettings['google_maps_embed_url']))
  <section class="pb-5 pt-0">
    <div class="container">
      <div class="rounded-3 overflow-hidden shadow-sm" style="height: 380px; border: 1px solid var(--color-border);">
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
