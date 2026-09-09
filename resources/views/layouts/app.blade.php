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
  <link rel="preload" as="image" href="{{ asset('images/hero-placeholder.webp') }}" type="image/webp">

  <!-- Open Graph Defaults -->
  <meta property="og:locale" content="id_ID">
  <meta property="og:site_name" content="{{ $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika' }}">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')) ?: ($siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika'))">
  <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: ($siteSettings['meta_default_description'] ?? 'Distributor alat laboratorium dan instrumen analitika.'))">
  <meta property="og:url" content="@yield('canonical', request()->url())">
  <meta property="og:image" content="@yield('og_image', !empty($siteSettings['og_image']) ? $siteSettings['og_image'] : asset('images/og-default.jpg'))">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title')) ?: ($siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika'))">
  <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: ($siteSettings['meta_default_description'] ?? 'Distributor alat laboratorium dan instrumen analitika.'))">
  <meta name="twitter:image" content="@yield('og_image', !empty($siteSettings['og_image']) ? $siteSettings['og_image'] : asset('images/og-default.jpg'))">

  @stack('meta')

  @php
    $gaId = $siteSettings['google_analytics_id'] ?? null;
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
</head>
<body class="@yield('body_class')">
  <a class="visually-hidden-focusable" href="#main-content">Lewati ke konten utama</a>

  @include('layouts.partials.navbar')

  <main id="main-content">
    @yield('content')
  </main>

  @include('layouts.partials.footer')
  @include('layouts.partials.search-modal')
  @include('layouts.partials.cookie-consent')

  @vite(['resources/js/app.js'])

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Close mobile navbar on link click
      var mainNavbar = document.getElementById('mainNavbar');
      if (mainNavbar) {
        mainNavbar.querySelectorAll('.nav-link, .dropdown-item').forEach(function (link) {
          link.addEventListener('click', function () {
            if (window.innerWidth < 992 && mainNavbar.classList.contains('show')) {
              var bsCollapse = new bootstrap.Collapse(mainNavbar, { toggle: false });
              bsCollapse.hide();
            }
          });
        });
      }
    });

    // Flash toasts via SweetAlert2 if available
    @if(session('success') || session('error') || session('warning') || session('info'))
      document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swal === 'undefined') return;
        @if(session('success'))
          Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: @json(session('success')), showConfirmButton: false, timer: 3500, timerProgressBar: true });
        @endif
        @if(session('error'))
          Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: @json(session('error')), showConfirmButton: false, timer: 4500, timerProgressBar: true });
        @endif
        @if(session('warning'))
          Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: @json(session('warning')), showConfirmButton: false, timer: 4000, timerProgressBar: true });
        @endif
        @if(session('info'))
          Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: @json(session('info')), showConfirmButton: false, timer: 3500, timerProgressBar: true });
        @endif
      });
    @endif
  </script>

  @stack('scripts')
</body>
</html>
