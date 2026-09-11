<!-- Header / Navigation -->
<nav class="navbar navbar-expand-lg navbar-light py-3">
  <div class="container-fluid px-3 px-lg-4">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="54" width="auto" decoding="async" fetchpriority="high">
      </a>
    
    <!-- Mobile Actions (Cart, Search, and Hamburger Navigation) -->
    <div class="d-flex align-items-center gap-2 ms-auto d-lg-none">
      @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
      <a href="{{ route('cart.index') }}" class="nb-icon-btn position-relative text-decoration-none" title="Keranjang pengajuan penawaran" aria-label="Keranjang pengajuan penawaran">
        <i class="bi bi-cart3"></i>
        <span class="nav-cart-badge" style="display: {{ $cartCount > 0 ? 'inline-flex' : 'none' }};">
          {{ $cartCount }}
        </span>
      </a>

      <button type="button" id="mobile-search-open" class="nb-icon-btn" title="Cari produk" aria-label="Cari produk" aria-haspopup="dialog" aria-controls="search-overlay">
        <i class="bi bi-search"></i>
      </button>

      <label class="hamburger" for="hamburger-checkbox" aria-label="Buka menu navigasi" role="button">
        <input type="checkbox" id="hamburger-checkbox" autocomplete="off" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mainNavbar">
        <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false">
          <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
          <path class="line" d="M7 16 27 16"></path>
        </svg>
      </label>
    </div>
    
    <div class="collapse navbar-collapse" id="mainNavbar">
      <div class="navbar-collapse-inner">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('profil*') ? 'active' : '' }}" href="{{ url('/profil') }}">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('produk*') ? 'active' : '' }}" href="{{ url('/produk') }}">Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('sektor*') ? 'active' : '' }}" href="{{ url('/sektor') }}">Sektor</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('layanan*') ? 'active' : '' }}" href="{{ url('/layanan') }}">Layanan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('informasi*') ? 'active' : '' }}" href="{{ url('/informasi') }}">Informasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}" href="{{ url('/kontak') }}">Kontak</a>
          </li>
          <li class="nav-item ms-lg-2 my-2 my-lg-0">
            <a class="navbar-cta-btn d-inline-flex align-items-center justify-content-center gap-2 text-decoration-none" href="{{ !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf') }}" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-file-earmark-arrow-down"></i> Unduh Katalog
            </a>
          </li>
          <li class="nav-item d-none d-lg-flex align-items-center gap-2 ms-lg-2 navbar-utilities">
            <a href="{{ route('cart.index') }}" class="nb-icon-btn position-relative text-decoration-none" title="Keranjang pengajuan penawaran" aria-label="Keranjang pengajuan penawaran">
              <i class="bi bi-cart3"></i>
              @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
              <span id="cart-badge-count" class="nav-cart-badge" style="display: {{ $cartCount > 0 ? 'inline-flex' : 'none' }};">
                {{ $cartCount }}
              </span>
            </a>
            <button type="button" id="nav-search-open" class="nb-icon-btn" title="Cari produk" aria-label="Cari produk" aria-haspopup="dialog" aria-controls="search-overlay">
              <i class="bi bi-search"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
