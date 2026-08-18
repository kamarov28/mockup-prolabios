<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')</title>
  <meta name="description" content="@yield('meta_description', $siteSettings['meta_default_description'] ?? 'PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium dan instrumen.')">
  <meta name="keywords" content="@yield('meta_keywords', $siteSettings['meta_default_keywords'] ?? 'prolabios, alat laboratorium, mikrobiologi, instrumen lab')">
  @if(!empty($siteSettings['google_search_console_id']))
    <meta name="google-site-verification" content="{{ $siteSettings['google_search_console_id'] }}">
  @endif
  <link rel="shortcut icon" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('favicon.ico') }}">
  <link rel="icon" type="image/png" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('images/favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('images/favicon.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Preconnect to CDN & fonts (critical for LCP) -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Scientific Editorial Typography: Space Grotesk (Headlines) & Instrument Sans (Body) -->
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons (non-blocking) -->
  <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"></noscript>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Custom CSS via Vite -->
  @vite(['resources/css/style.css', 'resources/css/experimental-typo.css'])

  <!-- SweetAlert2 (defer non-blocking) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

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
</head>
<body>

  <!-- Header / Navigation -->
  @include('layouts.partials.navbar')

  <main>
    @yield('content')
  </main>

  <!-- Corporate Footer -->
  @include('layouts.partials.footer')

  <!-- Bootstrap first (components), then app (site behavior). Defer keeps HTML parse unblocked. -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  @vite(['resources/js/app.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var mainNavbar = document.getElementById('mainNavbar');
      var hamburgerInput = document.getElementById('hamburger-checkbox');
      
      if (mainNavbar && hamburgerInput) {
        var bsCollapse = new bootstrap.Collapse(mainNavbar, { toggle: false });
        hamburgerInput.checked = false;

        hamburgerInput.addEventListener('change', function () {
          if (hamburgerInput.checked) {
            bsCollapse.show();
          } else {
            bsCollapse.hide();
          }
        });

        mainNavbar.addEventListener('show.bs.collapse', function () {
          hamburgerInput.checked = true;
        });
        mainNavbar.addEventListener('hide.bs.collapse', function () {
          hamburgerInput.checked = false;
        });
      }

      var searchOverlay = document.getElementById('search-overlay');
      var searchOverlayInput = document.getElementById('search-overlay-input');
      var mobileSearchOpen = document.getElementById('mobile-search-open');
      var navSearchOpen = document.getElementById('nav-search-open');
      var searchCloseBtn = document.getElementById('search-close');
      var searchCloseBackdrop = document.getElementById('search-close-backdrop');

      function openSearchOverlay() {
        if (searchOverlay) {
          searchOverlay.classList.add('active');
          searchOverlay.setAttribute('aria-hidden', 'false');
          if (searchOverlayInput) {
            setTimeout(function() { searchOverlayInput.focus(); }, 100);
          }
        }
      }

      function closeSearchOverlay() {
        if (searchOverlay) {
          searchOverlay.classList.remove('active');
          searchOverlay.setAttribute('aria-hidden', 'true');
        }
      }

      if (mobileSearchOpen) mobileSearchOpen.addEventListener('click', openSearchOverlay);
      if (navSearchOpen) navSearchOpen.addEventListener('click', openSearchOverlay);
      if (searchCloseBtn) searchCloseBtn.addEventListener('click', closeSearchOverlay);
      if (searchCloseBackdrop) searchCloseBackdrop.addEventListener('click', closeSearchOverlay);

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && searchOverlay && searchOverlay.classList.contains('active')) {
          closeSearchOverlay();
        }
      });
    });
  </script>

  @if(session('success') || session('error') || session('info'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') return;

        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
          }
        });

        @if(session('success'))
          Toast.fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
        @endif
        @if(session('error'))
          Toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
        @endif
        @if(session('info'))
          Toast.fire({ icon: 'info', title: {!! json_encode(session('info')) !!} });
        @endif
      });
    </script>
  @endif

  @stack('scripts')

  <!-- Global Search Overlay & Scroll-to-top Button -->
  @include('layouts.partials.search-modal')
</body>
</html>
