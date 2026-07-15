<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')</title>
  <meta name="description" content="PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium dan instrumen.">
  
  <!-- Preconnect to CDN -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  
  <!-- Font (Plus Jakarta Sans for Premium Aesthetic) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Bootstrap Icons (Non-blocking loading) -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"></noscript>
  
  <!-- Bootstrap 5 CSS -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  
  <!-- Page Preloads -->
  @yield('preload')

  <!-- Open Graph / Facebook / Twitter Metadata -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta property="og:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta property="og:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta name="twitter:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <!-- Fast Theme & Motion Check (Avoids Flash of Light Background/Animations) -->
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
    if (localStorage.getItem('motion') === 'disabled') {
      document.documentElement.classList.add('no-motion');
    }
  </script>
</head>
<body>

  <!-- Top Bar (Search & Contact) -->
  <div class="premium-top-bar py-2">
    <div class="container d-flex flex-wrap justify-content-between align-items-center small">
      <div>
        <span><i class="bi bi-telephone-fill text-primary me-1"></i> Hubungi Kami: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="text-decoration-none fw-medium">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a></span>
        <span class="mx-2 text-muted opacity-50">|</span>
        <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="text-decoration-none fw-medium"><i class="bi bi-envelope-fill text-primary me-1"></i> {{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
      </div>
      <div class="mt-2 mt-md-0">
        <form class="d-flex search-form" action="{{ url('/produk') }}" method="GET">
          <div class="input-group input-group-sm">
            <input type="text" name="q" class="form-control search-input-pill" placeholder="Cari produk..." aria-label="Search" value="{{ request()->query('q') ?? request()->query('s') }}">
            <button class="btn search-btn-pill" type="submit" aria-label="Cari produk">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Header / Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
          <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="40">
        </a>
      
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold">
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('/') ? 'text-primary active' : '' }}" href="{{ url('/') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('profil') ? 'text-primary active' : '' }}" href="{{ url('/profil') }}">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('produk') ? 'text-primary active' : '' }}" href="{{ url('/produk') }}">Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('sektor') ? 'text-primary active' : '' }}" href="{{ url('/sektor') }}">Sektor</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('layanan') ? 'text-primary active' : '' }}" href="{{ url('/layanan') }}">Layanan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('informasi') ? 'text-primary active' : '' }}" href="{{ url('/informasi') }}">Informasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3 {{ request()->is('kontak') ? 'text-primary active' : '' }}" href="{{ url('/kontak') }}">Kontak</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-sm btn-outline-danger px-3 py-2 mt-1 mt-lg-0 rounded-pill d-inline-flex align-items-center gap-2" href="{{ $siteSettings['catalog_pdf_url'] ?? 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs' }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.9rem;">
              <i class="bi bi-download"></i> Unduh Katalog
            </a>
          </li>
          <li class="nav-item d-flex align-items-center gap-3 ms-lg-3 mt-3 mt-lg-0">
            <button type="button" id="motion-toggle" class="btn btn-link nav-link p-0 border-0 bg-transparent" style="text-decoration: none;" aria-label="Toggle Animations" title="Aktif/Nonaktifkan Animasi">
              <i id="motion-toggle-icon" class="bi bi-play-circle-fill" style="font-size: 1.2rem;"></i>
            </button>
            <button type="button" id="theme-toggle" class="btn btn-link nav-link p-0 text-dark border-0 bg-transparent" style="text-decoration: none;" aria-label="Toggle Theme">
              <i id="theme-toggle-icon" class="bi bi-moon-fill" style="font-size: 1.2rem;"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  <!-- Corporate Footer -->
  <footer class="premium-footer pt-5 pb-3 mt-auto">
    <div class="container">
      <div class="row gy-4">
        
        <!-- Col 1: Kantor -->
        <div class="col-lg-3 col-md-6 col-12">
          <div class="mb-3">
            <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="38" class="footer-logo">
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

        <!-- Col 2: Perusahaan -->
        <div class="col-lg-3 col-md-6 col-6">
          <h5>Perusahaan</h5>
          <ul class="list-unstyled footer-links lh-lg">
            <li><a href="{{ url('/profil') }}">Profil Perusahaan</a></li>
            <li><a href="{{ url('/profil') }}#visi-misi">Visi & Misi</a></li>
            <li><a href="{{ url('/informasi') }}">Berita & Kegiatan</a></li>
            <li><a href="{{ url('/layanan') }}">Layanan Kami</a></li>
          </ul>
        </div>

        <!-- Col 3: Kontak -->
        <div class="col-lg-3 col-md-6 col-12">
          <h5>Hubungi Kami</h5>
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

        <!-- Col 4: Jam Operasional -->
        <div class="col-lg-3 col-md-6 col-6">
          <h5>Jam Operasional</h5>
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
      
      <hr class="border-secondary mt-4 mb-3" style="opacity: 0.15;">
      
      <div class="text-center text-light opacity-50 small">
        <p class="mb-0">&copy; 2026 PT Prolabios Mitra Analitika. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Custom Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  
  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')

  <!-- Theme Toggle Handler -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const themeToggle = document.getElementById('theme-toggle');
      const themeToggleIcon = document.getElementById('theme-toggle-icon');
      
      // Determine initial state and align icon class
      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      if (isDark && themeToggleIcon) {
        themeToggleIcon.className = 'bi bi-sun-fill text-warning';
      }

      if (themeToggle) {
        themeToggle.addEventListener('click', function() {
          if (themeToggleIcon) {
            themeToggleIcon.classList.add('rotated');
            setTimeout(() => {
              themeToggleIcon.classList.remove('rotated');
            }, 600);
          }

          const currentTheme = document.documentElement.getAttribute('data-theme');
          if (currentTheme !== 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            if (themeToggleIcon) {
              themeToggleIcon.className = 'bi bi-sun-fill text-warning rotated';
            }
          } else {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
            if (themeToggleIcon) {
              themeToggleIcon.className = 'bi bi-moon-fill text-dark rotated';
            }
          }
        });
      }

      // Motion Toggle logic
      const motionToggle = document.getElementById('motion-toggle');
      const motionToggleIcon = document.getElementById('motion-toggle-icon');
      
      // Determine initial state and align icon class
      const isMotionDisabled = document.documentElement.classList.contains('no-motion');
      if (isMotionDisabled && motionToggleIcon) {
        motionToggleIcon.className = 'bi bi-pause-circle-fill';
      }

      if (motionToggle) {
        motionToggle.addEventListener('click', function() {
          const isDisabled = document.documentElement.classList.contains('no-motion');
          if (!isDisabled) {
            document.documentElement.classList.add('no-motion');
            localStorage.setItem('motion', 'disabled');
            if (motionToggleIcon) {
              motionToggleIcon.className = 'bi bi-pause-circle-fill';
            }
          } else {
            document.documentElement.classList.remove('no-motion');
            localStorage.setItem('motion', 'enabled');
            if (motionToggleIcon) {
              motionToggleIcon.className = 'bi bi-play-circle-fill';
            }
          }
        });
      }

      // Scroll to Top logic
      const scrollToTopBtn = document.getElementById('scroll-to-top');
      if (scrollToTopBtn) {
        window.addEventListener('scroll', function() {
          if (window.scrollY > 300) {
            scrollToTopBtn.style.opacity = '1';
            scrollToTopBtn.style.visibility = 'visible';
          } else {
            scrollToTopBtn.style.opacity = '0';
            scrollToTopBtn.style.visibility = 'hidden';
          }
        });

        scrollToTopBtn.addEventListener('click', function() {
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      }
    });
  </script>

  <!-- Scroll to Top Float Button -->
  <button type="button" id="scroll-to-top" class="btn btn-primary btn-scroll-to-top shadow-lg rounded-circle" style="position: fixed; bottom: 30px; right: 30px; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; z-index: 1050; opacity: 0; visibility: hidden; transition: all 0.3s ease; border: none; background-color: var(--color-primary, #D32F2F); color: #ffffff;">
    <i class="bi bi-arrow-up-short" style="font-size: 1.75rem; line-height: 1;"></i>
  </button>
</body>
</html>
