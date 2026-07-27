<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')</title>
  <meta name="description" content="@yield('meta_description', 'PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium dan instrumen.')">
  <meta name="keywords" content="@yield('meta_keywords', 'prolabios, alat laboratorium, mikrobiologi, instrumen lab')">
  <link rel="canonical" href="@yield('canonical_url', request()->url())">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Preconnect to CDN & fonts (critical for LCP) -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Single editorial face, essential weights only (was 3 families × many weights) -->
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons (non-blocking) -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"></noscript>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Custom CSS via Vite -->
  @vite(['resources/css/style.css', 'resources/css/experimental-typo.css'])

  <!-- Page Preloads -->
  @yield('preload')

  <!-- Open Graph / Facebook / Twitter Metadata -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika ahay & Mikrobiologi')">
  <meta property="og:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta property="og:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta name="twitter:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
</head>
<body>

  <!-- Top Bar (Search & Contact) -->
  <div class="premium-top-bar py-2">
    <div class="container d-flex flex-wrap justify-content-between align-items-center small">
      <div>
        <span><i class="bi bi-telephone-fill text-primary me-1"></i> Contact Us: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="text-decoration-none fw-medium">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a></span>
        <span class="mx-2 text-muted opacity-50">|</span>
        <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="text-decoration-none fw-medium"><i class="bi bi-envelope-fill text-primary me-1"></i> {{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
      </div>
      <div class="mt-2 mt-md-0">
        <form class="d-flex search-form" action="{{ url('/produk') }}" method="GET">
          <div class="input-group input-group-sm">
            <input type="text" name="q" class="form-control search-input-pill" placeholder="Search products..." aria-label="Search" value="{{ request()->query('q') ?? request()->query('s') }}">
            <button class="btn search-btn-pill" type="submit" aria-label="Search products">
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
          <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="40" width="auto" decoding="async" fetchpriority="high">
        </a>
      
      <!-- Mobile Search Trigger -->
      <button type="button" id="mobile-search-open" class="btn p-0 border-0 bg-transparent text-white ms-auto me-3 d-lg-none" title="Search Products" aria-label="Search Products" aria-haspopup="dialog" aria-controls="search-overlay">
        <i class="bi bi-search" style="font-size: 1.25rem;"></i>
      </button>

      <label class="hamburger">
        <input type="checkbox" id="hamburger-checkbox" autocomplete="off">
        <svg viewBox="0 0 32 32">
          <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
          <path class="line" d="M7 16 27 16"></path>
        </svg>
      </label>
      
      <div class="collapse navbar-collapse" id="mainNavbar">
        <div class="navbar-collapse-inner">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold">
            <li class="nav-item">
              <a class="nav-link {{ request()->is('/') ? 'text-primary active' : '' }}" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('profil') ? 'text-primary active' : '' }}" href="{{ url('/profil') }}">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('produk') ? 'text-primary active' : '' }}" href="{{ url('/produk') }}">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('sektor') ? 'text-primary active' : '' }}" href="{{ url('/sektor') }}">Sectors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('layanan') ? 'text-primary active' : '' }}" href="{{ url('/layanan') }}">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('informasi') ? 'text-primary active' : '' }}" href="{{ url('/informasi') }}">Information</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('kontak') ? 'text-primary active' : '' }}" href="{{ url('/kontak') }}">Contacts</a>
            </li>
            <li class="nav-item ms-lg-2">
              <a class="btn btn-sm btn-outline-danger px-3 py-2 mt-1 mt-lg-0 rounded-pill d-inline-flex align-items-center gap-2" href="{{ $siteSettings['catalog_pdf_url'] ?? 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs' }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.9rem;">
                <i class="bi bi-download"></i> Download the Catalog
              </a>
            </li>
            <li class="nav-item d-none d-lg-flex align-items-center gap-3 ms-lg-4 mt-3 mt-lg-0 navbar-utilities">
              <button type="button" id="nav-search-open" class="nav-link p-0 text-white border-0 bg-transparent" title="Search Products" aria-label="Search Products" aria-haspopup="dialog" aria-controls="search-overlay">
                <i class="bi bi-search" style="font-size: 1.05rem; vertical-align: middle;"></i>
              </button>
            </li>
          </ul>
        </div>
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
      
      <hr class="border-secondary mt-4 mb-3" style="opacity: 0.15;">
      
      <div class="text-center text-light opacity-50 small">
        <p class="mb-0">&copy; 2026 PT Prolabios Mitra Analitika. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap first (components), then app (site behavior). Defer keeps HTML parse unblocked. -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  @vite(['resources/js/app.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var mainNavbar = document.getElementById('mainNavbar');
      var hamburgerInput = document.getElementById('hamburger-checkbox');
      
      if (mainNavbar && hamburgerInput) {
        // Initialize bootstrap collapse instance programmatically
        var bsCollapse = new bootstrap.Collapse(mainNavbar, { toggle: false });
        
        // Reset checkbox to unchecked initially (since collapse is closed by default)
        hamburgerInput.checked = false;

        // Toggle collapse programmatically when checkbox changes
        hamburgerInput.addEventListener('change', function () {
          if (hamburgerInput.checked) {
            bsCollapse.show();
          } else {
            bsCollapse.hide();
          }
        });

        // Also sync state if Bootstrap collapse events are triggered by other triggers
        mainNavbar.addEventListener('show.bs.collapse', function () {
          hamburgerInput.checked = true;
        });
        mainNavbar.addEventListener('hide.bs.collapse', function () {
          hamburgerInput.checked = false;
        });
      }
    });
  </script>

  @stack('scripts')



  <button type="button" id="scroll-to-top" class="btn-scroll-to-top" style="opacity: 0; visibility: hidden;" aria-label="Back to top">
    <i class="bi bi-arrow-up-short"></i>
  </button>

  <div id="search-overlay" class="search-overlay" role="dialog" aria-modal="true" aria-label="Product search" aria-hidden="true">
    <button type="button" class="search-close-btn" id="search-close" aria-label="Close search">
      <i class="bi bi-x-lg"></i>
    </button>
    <div class="search-overlay-content">
      <form action="{{ url('/produk') }}" method="GET" class="search-overlay-form" role="search">
        <input type="search" name="q" id="search-overlay-input" placeholder="Type product keywords..." autocomplete="off" enterkeyhint="search">
        <div class="search-hint">Press enter to search or ESC to cancel</div>
        
        <div class="search-suggestions mt-4">
          <span class="d-block mb-3 text-muted" style="font-size: 0.72rem; font-family: var(--font-headline); letter-spacing: 1.5px; text-transform: uppercase;">Search Suggestions</span>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            @php
              $suggestions = ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'];
              try {
                  $productTitles = \Illuminate\Support\Facades\DB::table('products')->pluck('title')->toArray();
                  if (!empty($productTitles)) {
                      $wordsList = [];
                      foreach ($productTitles as $title) {
                          // Bersihkan karakter spesial
                          $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
                          $words = explode(' ', $clean);
                          foreach ($words as $word) {
                              $word = trim($word);
                              // Filter kata-kata umum yang kurang bernilai pencarian
                              if (strlen($word) > 3 && !in_array(strtolower($word), ['smart', 'digital', 'microbial', 'system', 'recombinant', 'based', 'automatic', 'with', 'without', 'medium', 'base'])) {
                                  $wordsList[] = $word;
                              }
                          }
                      }
                      if (!empty($wordsList)) {
                          $suggestions = array_slice(array_unique($wordsList), 0, 7);
                      }
                  }
              } catch (\Exception $e) {
                  // Fallback ke default jika DB bermasalah
              }
            @endphp
            
            @foreach($suggestions as $tag)
              <a href="{{ url('/produk?q=' . urlencode($tag)) }}" class="suggestion-tag">{{ $tag }}</a>
            @endforeach
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
