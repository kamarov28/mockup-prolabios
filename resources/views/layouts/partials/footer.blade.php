<!-- Corporate Footer (Apple-style Minimal & Clean) -->
<footer class="site-footer mt-auto">
  <div class="container py-5">
    <div class="row g-4 mb-4">

      <!-- Col 1: Brand & Identity -->
      <div class="col-lg-4 col-md-6 col-12 pe-lg-4">
        <div class="mb-3">
          <a href="{{ url('/') }}" class="footer-logo-link" aria-label="{{ $siteSettings['company_name'] ?? 'Prolabios' }} Beranda">
            <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="40" class="footer-logo" loading="lazy" decoding="async">
          </a>
        </div>
        <p class="footer-company-name mb-1">
          {{ strtoupper($siteSettings['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA') }}
        </p>
        <p class="footer-address mb-3">
          {!! nl2br(e($siteSettings['contact_address'] ?? 'GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914')) !!}
        </p>
        <div class="footer-status-pill mb-3">
          <span class="status-dot"></span>
          <span>Pengusaha Kena Pajak (PKP) Terdaftar</span>
        </div>
        <div class="footer-social-strip d-flex gap-3">
          @if(!empty($siteSettings['social_facebook']))
            <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_instagram']))
            <a href="{{ $siteSettings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_linkedin']))
            <a href="{{ $siteSettings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
          @endif
          @if(!empty($siteSettings['social_twitter']))
            <a href="{{ $siteSettings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Twitter"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
          @endif
        </div>
      </div>

      <!-- Col 2: Katalog & Solusi -->
      <div class="col-lg-2 col-md-3 col-6">
        <h4 class="footer-section-title">Katalog &amp; Solusi</h4>
        <ul class="list-unstyled footer-nav-list">
          <li><a href="{{ url('/produk') }}">Katalog Produk</a></li>
          <li><a href="{{ url('/sektor') }}">Sektor Industri</a></li>
          <li><a href="{{ url('/layanan') }}">Layanan Teknis</a></li>
          <li><a href="{{ url('/cart') }}">Pengajuan RFQ</a></li>
        </ul>
      </div>

      <!-- Col 3: Perusahaan -->
      <div class="col-lg-2 col-md-3 col-6">
        <h4 class="footer-section-title">Perusahaan</h4>
        <ul class="list-unstyled footer-nav-list">
          <li><a href="{{ url('/profil') }}">Tentang Kami</a></li>
          <li><a href="{{ url('/profil') }}#visi-misi">Visi &amp; Misi</a></li>
          <li><a href="{{ url('/informasi') }}">Berita &amp; Riset</a></li>
          <li><a href="{{ url('/kontak') }}">Hubungi Kami</a></li>
        </ul>
      </div>

      <!-- Col 4: Hubungi Kami & Penawaran -->
      <div class="col-lg-4 col-md-6 col-12 ps-lg-4">
        <h4 class="footer-section-title">Layanan Pelanggan</h4>
        <ul class="list-unstyled footer-nav-list footer-contact-info">
          <li>
            <span class="info-label">Marketing</span>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }}</a>
          </li>
          <li>
            <span class="info-label">Keuangan</span>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }}</a>
          </li>
          <li>
            <span class="info-label">Email Resmi</span>
            <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}">{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
          </li>
          <li>
            <span class="info-label">Jam Kerja</span>
            <span>{{ $siteSettings['operational_hours'] ?? 'Senin – Jumat : 08.00 – 17.00 WIB' }}</span>
          </li>
        </ul>
        @if(!empty($waNumber))
          <div class="mt-3">
            <a href="https://wa.me/{{ $waNumber }}?text={{ $waDefaultMsg }}" target="_blank" rel="noopener noreferrer" class="footer-wa-pill" aria-label="Konsultasi WhatsApp">
              <i class="bi bi-whatsapp"></i> Chat WhatsApp
            </a>
          </div>
        @endif
      </div>

    </div>

    <!-- Legal & Copyright Bar -->
    <div class="footer-bottom-bar pt-4 mt-2">
      <div class="row align-items-center gy-2">
        <div class="col-md-6 col-12">
          <p class="mb-0 footer-copy-text">&copy; {{ date('Y') }} PT Prolabios Mitra Analitika. Hak cipta dilindungi.</p>
        </div>
        <div class="col-md-6 col-12 text-md-end">
          <div class="footer-legal-links d-inline-flex flex-wrap align-items-center gap-3">
            <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
            <span class="sep" aria-hidden="true">&bull;</span>
            <a href="{{ route('terms') }}">Syarat &amp; Ketentuan</a>
            <span class="sep" aria-hidden="true">&bull;</span>
            <a href="#top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" class="d-inline-flex align-items-center gap-1">
              Ke Atas <i class="bi bi-chevron-up"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
