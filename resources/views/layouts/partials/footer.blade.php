<!-- Corporate Footer -->
<footer class="site-footer pt-5 pb-3 mt-auto">
  <div class="container">
    <div class="row gy-4">
      
      <!-- Col 1: Office -->
      <div class="col-lg-3 col-md-6 col-12">
        <div class="mb-3">
          <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="38" width="auto" class="footer-logo" loading="lazy" decoding="async">
        </div>
        <p class="mb-3 mt-3"><strong>{{ strtoupper($siteSettings['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA') }}</strong><br>
        {!! nl2br(e($siteSettings['contact_address'] ?? "Ruko Plaza de Lumina Blok B No. 27, Semanan, Kalideres\nJakarta Barat, DKI Jakarta 11850")) !!}</p>
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
        <h3>Perusahaan</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li><a href="{{ url('/profil') }}">Profil Perusahaan</a></li>
          <li><a href="{{ url('/profil') }}#visi-misi">Visi &amp; Misi</a></li>
          <li><a href="{{ url('/informasi') }}" aria-label="Berita dan informasi">Berita &amp; Informasi</a></li>
          <li><a href="{{ url('/layanan') }}">Layanan Kami</a></li>
        </ul>
      </div>

      <!-- Col 3: Contact -->
      <div class="col-lg-3 col-md-6 col-12">
        <h3>Hubungi Kami</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }} (Keuangan &amp; Gudang)</a>
          </li>

          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-envelope-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" style="word-break: break-all;">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
          </li>
        </ul>
      </div>

      <!-- Col 4: Operating Hours -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3>Jam Operasional</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-3 text-light">
            <i class="bi bi-clock-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <span>{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat : 09.00 – 18.00 WIB' }}</span>
          </li>
          <li class="d-flex align-items-center">
            <i class="bi bi-geo-alt-fill me-2" style="color: var(--color-primary);"></i>
            <a href="{{ url('/kontak') }}">Formulir Kontak</a>
          </li>
        </ul>
      </div>

    </div>
    
    <hr class="border-secondary mt-4 mb-3" style="opacity: 0.25;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-light small fw-medium gap-2" style="color: #cbd5e1 !important; opacity: 1 !important;">
      <p class="mb-0">&copy; {{ date('Y') }} PT Prolabios Mitra Analitika. Hak cipta dilindungi.</p>
      <div class="d-flex gap-3">
        <a href="{{ route('privacy') }}" class="footer-legal-link" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: color 0.2s;">Kebijakan Privasi</a>
        <span style="color: rgba(255,255,255,0.2);">&bull;</span>
        <a href="{{ route('terms') }}" class="footer-legal-link" style="color: #94a3b8; text-decoration: none; font-size: 0.8rem; transition: color 0.2s;">Syarat &amp; Ketentuan</a>
      </div>
    </div>
  </div>
</footer>
