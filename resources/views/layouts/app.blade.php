<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')</title>
  <meta name="description" content="@yield('meta_description', $siteSettings['meta_default_description'] ?? 'PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium dan instrumen.')">
  <meta name="keywords" content="@yield('meta_keywords', $siteSettings['meta_default_keywords'] ?? 'prolabios, alat laboratorium, mikrobiologi, instrumen lab')">
  <link rel="canonical" href="@yield('canonical', request()->url())">
  @if(!empty($siteSettings['google_search_console_id']))
    <meta name="google-site-verification" content="{{ $siteSettings['google_search_console_id'] }}">
  @endif
  <link rel="shortcut icon" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('favicon.ico') }}">
  <link rel="icon" type="image/png" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('images/favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ !empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : asset('images/favicon.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Preconnect to Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Soft Neo-Brutalism Typography: Bricolage Grotesque (display) + IBM Plex Sans (body) + System Monospace -->
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

  <!-- Core App Styles via Vite (Bundled Bootstrap 5 + Icons + Soft Neo-Brutalism) -->
  @vite(['resources/css/style.css', 'resources/css/site.css'])

  @stack('styles')

  <!-- Page Preloads -->
  @yield('preload')

  <!-- Open Graph / Facebook Metadata -->
  <meta property="og:site_name" content="{{ $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika' }}">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:locale" content="id_ID">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta property="og:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta property="og:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  <!-- Twitter Card Metadata -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'PROLABIOS | Solusi Analitika & Mikrobiologi')">
  <meta name="twitter:description" content="@yield('og_description', 'Penyedia media kultur, instrumen lab, dan perlengkapan pengujian terbaik di Indonesia.')">
  <meta name="twitter:image" content="@yield('og_image', asset('images/logo-prolabios.png'))">

  @php
    $gaId = $siteSettings['google_analytics_id'] ?? null;
    $gtmId = $siteSettings['google_tag_manager_id'] ?? null;
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
<body class="@yield('body_class')">
  @if(!empty($gtmId))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  @endif

  <a class="visually-hidden-focusable position-absolute top-0 start-0 m-2 p-2 bg-dark text-white" href="#main-content">Lewati ke konten</a>

  @include('layouts.partials.navbar')

  <main id="main-content">
    @yield('content')
  </main>

  @include('layouts.partials.footer')
  @include('layouts.partials.cookie-consent')

  <!-- Core App Scripts (Bundled Bootstrap 5 + SweetAlert2 + Site Behaviors) -->
  @vite(['resources/js/app.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var mainNavbar = document.getElementById('mainNavbar');
      if (mainNavbar) {
        mainNavbar.querySelectorAll('a.nav-link, a.dropdown-item').forEach(function (link) {
          link.addEventListener('click', function () {
            if (window.innerWidth < 992 && mainNavbar.classList.contains('show')) {
              var bsCollapse = bootstrap.Collapse.getInstance(mainNavbar) || new bootstrap.Collapse(mainNavbar, { toggle: false });
              bsCollapse.hide();
            }
          });
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
