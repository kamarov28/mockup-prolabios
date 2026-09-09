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
  @stack('head')

  <!-- Open Graph / Twitter -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('og_title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')">
  <meta property="og:description" content="@yield('og_description', $siteSettings['meta_default_description'] ?? 'Distributor alat laboratorium dan instrumen analitika.')">
  <meta property="og:url" content="@yield('canonical', request()->url())">
  <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
  <meta property="og:site_name" content="{{ $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika' }}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', $siteSettings['company_name'] ?? 'PT. Prolabios Mitra Analitika')">
  <meta name="twitter:description" content="@yield('og_description', $siteSettings['meta_default_description'] ?? 'Distributor alat laboratorium dan instrumen analitika.')">
  <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

  @stack('meta')
</head>
<body class="@yield('body_class')">
  @include('layouts.partials.navbar')

  <main id="main-content">
    @yield('content')
  </main>

  @include('layouts.partials.footer')
  @include('layouts.partials.search-modal')
  @include('layouts.partials.cookie-consent')

  @vite(['resources/js/app.js'])
  @stack('scripts')
</body>
</html>
