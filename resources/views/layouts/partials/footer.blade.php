<!-- Corporate Footer -->
<footer class="site-footer pt-5 pb-3 mt-auto">
  <div class="container">
    <div class="row gy-4">

      <!-- Col 1: Brand, Office & Trust -->
      <div class="col-lg-3 col-md-6 col-12">
        <div class="mb-3">
          <a href="{{ url('/') }}" class="footer-logo-box" aria-label="{{ $siteSettings['company_name'] ?? 'Prolabios' }} Beranda">
            <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="36" class="footer-logo" loading="lazy" decoding="async">
          </a>
        </div>
        <p class="mb-2 footer-text">
          <strong class="text-white">{{ strtoupper($siteSettings['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA') }}</strong><br>
          {!! nl2br(e($siteSettings['contact_address'] ?? 'GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914')) !!}
        </p>
        <div class="footer-trust-pill mb-3">
          <i class="bi bi-shield-check me-1 footer-trust-icon" aria-hidden="true"></i>
          <span>Mendukung Faktur Pajak (PKP)</span>
        </div>
        <div class="d-flex gap-2">
          @if(!empty($siteSettings['social_facebook']))
            <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_instagram']))
            <a href="{{ $siteSettings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_linkedin']))
            <a href="{{ $siteSettings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_twitter']))
            <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon" aria-label="Twitter"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
          @endif
        </div>
      </div>

      <!-- Col 2: Katalog & RFQ -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3 class="footer-heading">Katalog &amp; RFQ</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li><a href="{{ url('/produk') }}"><i class="bi bi-grid me-1 footer-subicon" aria-hidden="true"></i>Katalog Produk</a></li>
          <li><a href="{{ url('/sektor') }}"><i class="bi bi-building me-1 footer-subicon" aria-hidden="true"></i>Sektor Industri</a></li>
          <li><a href="{{ url('/cart') }}"><i class="bi bi-cart3 me-1 footer-subicon" aria-hidden="true"></i>Keranjang RFQ</a></li>
          <li><a href="{{ url('/layanan') }}"><i class="bi bi-tools me-1 footer-subicon" aria-hidden="true"></i>Layanan Servis</a></li>
        </ul>
      </div>

      <!-- Col 3: Perusahaan -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3 class="footer-heading">Perusahaan</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li><a href="{{ url('/profil') }}">Profil Perusahaan</a></li>
          <li><a href="{{ url('/profil') }}#visi-misi">Visi &amp; Misi</a></li>
          <li><a href="{{ url('/informasi') }}" aria-label="Berita dan informasi">Berita &amp; Wawasan</a></li>
          <li><a href="{{ url('/kontak') }}">Hubungi Kami</a></li>
        </ul>
      </div>

      <!-- Col 4: Kontak & Respon Cepat -->
      <div class="col-lg-3 col-md-6 col-12">
        <h3 class="footer-heading">Kontak &amp; Penawaran</h3>

        @if(!empty($waNumber))
          <a href="https://wa.me/{{ $waNumber }}?text={{ $waDefaultMsg }}" target="_blank" rel="noopener noreferrer" class="footer-wa-btn mb-3" aria-label="Konsultasi WhatsApp Tim Penawaran">
            <i class="bi bi-whatsapp" aria-hidden="true"></i>
            <span>Konsultasi WhatsApp</span>
          </a>
        @endif

        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1 footer-icon" aria-hidden="true"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="footer-contact-link">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1 footer-icon" aria-hidden="true"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="footer-contact-link">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }} (Keuangan)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-envelope-fill me-2 mt-1 footer-icon" aria-hidden="true"></i>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="footer-contact-link text-break">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-clock-fill me-2 mt-1 footer-icon" aria-hidden="true"></i>
            <span class="footer-text">{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat : 09.00 – 18.00 WIB' }}</span>
          </li>
        </ul>
      </div>

    </div>

    <hr class="footer-divider mt-4 mb-3">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-light small fw-medium gap-2">
      <p class="mb-0 footer-copy">&copy; {{ date('Y') }} PT Prolabios Mitra Analitika. Hak cipta dilindungi.</p>
      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('privacy') }}" class="footer-legal-link">Kebijakan Privasi</a>
        <span class="footer-sep" aria-hidden="true">&bull;</span>
        <a href="{{ route('terms') }}" class="footer-legal-link">Syarat &amp; Ketentuan</a>
        <span class="footer-sep d-none d-sm-inline" aria-hidden="true">&bull;</span>
        <a href="#top" class="footer-legal-link d-none d-sm-inline-flex align-items-center" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
          <i class="bi bi-arrow-up-short me-1" aria-hidden="true"></i> Ke Atas
        </a>
      </div>
    </div>
  </div>
</footer>
