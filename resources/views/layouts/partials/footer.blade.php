<!-- Corporate Footer -->
<footer class="site-footer pt-5 pb-3 mt-auto">
  <div class="container">
    <div class="row gy-4">

      <!-- Col 1: Office -->
      <div class="col-lg-3 col-md-6 col-12">
        <div class="mb-3">
          <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="38" width="auto" class="footer-logo" loading="lazy" decoding="async">
        </div>
        <p class="mb-3 mt-3 footer-text">
          <strong>{{ strtoupper($siteSettings['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA') }}</strong><br>
          {!! nl2br(e($siteSettings['contact_address'] ?? "Ruko Plaza de Lumina Blok B No. 27, Semanan, Kalideres\nJakarta Barat, DKI Jakarta 11850")) !!}
        </p>
        <div class="d-flex gap-2 mt-3">
          @if(!empty($siteSettings['social_facebook']))
            <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          @endif
          @if(!empty($siteSettings['social_instagram']))
            <a href="{{ $siteSettings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          @endif
          @if(!empty($siteSettings['social_linkedin']))
            <a href="{{ $siteSettings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          @endif
          @if(!empty($siteSettings['social_twitter']))
            <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          @endif
        </div>
      </div>

      <!-- Col 2: Company -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3 class="footer-heading">Perusahaan</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li><a href="{{ url('/profil') }}">Profil Perusahaan</a></li>
          <li><a href="{{ url('/profil') }}#visi-misi">Visi &amp; Misi</a></li>
          <li><a href="{{ url('/informasi') }}" aria-label="Berita dan informasi">Berita &amp; Informasi</a></li>
          <li><a href="{{ url('/layanan') }}">Layanan Kami</a></li>
        </ul>
      </div>

      <!-- Col 3: Contact -->
      <div class="col-lg-3 col-md-6 col-12">
        <h3 class="footer-heading">Hubungi Kami</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1 footer-icon"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="footer-contact-link">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1 footer-icon"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="footer-contact-link">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }} (Keuangan &amp; Gudang)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-envelope-fill me-2 mt-1 footer-icon"></i>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="footer-contact-link" style="word-break: break-all;">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
          </li>
        </ul>
      </div>

      <!-- Col 4: Operating Hours -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3 class="footer-heading">Jam Operasional</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-clock-fill me-2 mt-1 footer-icon"></i>
            <span class="footer-text">{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat : 09.00 – 18.00 WIB' }}</span>
          </li>
          <li class="d-flex align-items-center">
            <i class="bi bi-geo-alt-fill me-2 footer-icon"></i>
            <a href="{{ url('/kontak') }}" class="footer-contact-link">Formulir Kontak</a>
          </li>
        </ul>
      </div>

    </div>

    <hr class="footer-divider mt-4 mb-3">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-light small fw-medium gap-2">
      <p class="mb-0 footer-copy">&copy; {{ date('Y') }} PT Prolabios Mitra Analitika. Hak cipta dilindungi.</p>
      <div class="d-flex gap-3">
        <a href="{{ route('privacy') }}" class="footer-legal-link">Kebijakan Privasi</a>
        <span class="footer-sep">&bull;</span>
        <a href="{{ route('terms') }}" class="footer-legal-link">Syarat &amp; Ketentuan</a>
      </div>
    </div>
  </div>
</footer>
