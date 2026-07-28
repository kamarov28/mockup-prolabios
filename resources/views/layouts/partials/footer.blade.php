<!-- Corporate Footer -->
<footer class="premium-footer pt-5 pb-3 mt-auto">
  <div class="container">
    <div class="row gy-4">
      
      <!-- Col 1: Office -->
      <div class="col-lg-3 col-md-6 col-12">
        <div class="mb-3">
          <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="38" width="auto" class="footer-logo" loading="lazy" decoding="async">
        </div>
        <p class="mb-3 mt-3"><strong>{{ strtoupper($siteSettings['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA') }}</strong><br>
        Komplek Cibinong Griya Asri Blok: A9/10, RT 01 RW 08<br>
        Cibinong – Bogor, West Java, Indonesia 16913</p>
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
        <h3>Company</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li><a href="{{ url('/profil') }}">Company Profile</a></li>
          <li><a href="{{ url('/profil') }}#visi-misi">Vision & Mission</a></li>
          <li><a href="{{ url('/informasi') }}">News & Events</a></li>
          <li><a href="{{ url('/layanan') }}">Our Services</a></li>
        </ul>
      </div>

      <!-- Col 3: Contact -->
      <div class="col-lg-3 col-md-6 col-12">
        <h3>Contact Us</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a>
          </li>
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-telephone-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }} (Finance &amp; Wh)</a>
          </li>

          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-envelope-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" style="word-break: break-all;">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
          </li>
        </ul>
      </div>

      <!-- Col 4: Operating Hours -->
      <div class="col-lg-3 col-md-6 col-6">
        <h3>Operating Hours</h3>
        <ul class="list-unstyled footer-links lh-lg">
          <li class="d-flex align-items-start mb-3 text-light">
            <i class="bi bi-clock-fill me-2 mt-1" style="color: var(--color-primary);"></i>
            <span>{{ $siteSettings['operational_hours'] ?? 'Monday – Friday : 09.00 – 18.00 WIB' }}</span>
          </li>
          <li class="d-flex align-items-center">
            <i class="bi bi-geo-alt-fill me-2" style="color: var(--color-primary);"></i>
            <a href="{{ url('/kontak') }}">Contact Form</a>
          </li>
        </ul>
      </div>

    </div>
    
    <hr class="border-secondary mt-4 mb-3" style="opacity: 0.25;">
    
    <div class="text-center text-light opacity-75 small">
      <p class="mb-0">&copy; 2026 PT Prolabios Mitra Analitika. All Rights Reserved.</p>
    </div>
  </div>
</footer>
