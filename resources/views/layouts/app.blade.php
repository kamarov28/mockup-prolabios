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
  <link rel="stylesheet" href="{{ asset('css/sticky-sidebar.css') }}?v=2">
  {{-- Critical sticky fallback (in case public CSS is cached/missing) --}}
  <style>
    html, body { overflow-x: clip; }
    @media (min-width: 768px) {
      #catalog-section > .container > .row,
      #sektor-nav > .container > .row { align-items: flex-start !important; }
      #catalog-section > .container > .row > .col-lg-3,
      #catalog-section > .container > .row > .col-md-4,
      #sektor-nav > .container > .row > .col-lg-3,
      #sektor-nav > .container > .row > .col-md-4,
      #sektor-sidebar,
      .page-sidebar-sticky {
        position: sticky !important;
        top: 96px !important;
        align-self: flex-start !important;
        max-height: calc(100vh - 112px);
        overflow-y: auto;
        z-index: 20;
      }
    }
  </style>

  @stack('styles')

  <!-- SweetAlert2 (defer non-blocking) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  <!-- Page Preloads -->
  @yield('preload')

  <!-- Open Graph / Facebook Metadata -->
  <meta property="og:site_name" content="{{ $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika' }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta property="og:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta property="og:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  <!-- Twitter Card Metadata -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ request()->url() }}">
  <meta name="twitter:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta name="twitter:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta name="twitter:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  <!-- Google Analytics 4 / GTM (If configured via .env or SiteSettings) -->
  @php
    $gaId = config('services.google_analytics_id', env('GOOGLE_ANALYTICS_ID', $siteSettings['google_analytics_id'] ?? null));
    $gtmId = config('services.google_tag_manager_id', env('GOOGLE_TAG_MANAGER_ID', $siteSettings['google_tag_manager_id'] ?? null));
  @endphp
  @if(!empty($gaId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $gaId }}');
    </script>
  @endif
  @if(!empty($gtmId))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
  @endif
</head>
<body>
  @if(!empty($gtmId))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  @endif

  <!-- Header / Navigation -->
  @include('layouts.partials.navbar')

  <main>
    @yield('content')
  </main>

  <!-- Corporate Footer -->
  @include('layouts.partials.footer')

  <!-- Cookie Consent Notice -->
  @include('layouts.partials.cookie-consent')

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
