<!-- Top Bar (Search & Contact) -->
<div class="site-utility-bar py-2">
  <div class="container d-flex flex-wrap justify-content-between align-items-center small">
    <div>
      <span><i class="bi bi-telephone-fill text-primary me-1"></i> Contact Us: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="text-decoration-none fw-medium">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }} (Marketing)</a></span>
      <span class="mx-2 text-muted opacity-50">|</span>
      <a href="mailto:{{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}" class="text-decoration-none fw-medium"><i class="bi bi-envelope-fill text-primary me-1"></i> {{ $siteSettings['contact_email'] ?? 'marketing@prolabios.com' }}</a>
    </div>
    <div class="mt-2 mt-md-0">
      <form class="d-flex search-form" action="{{ url('/produk') }}" method="GET">
        <div class="input-group input-group-sm">
          <input type="text" name="q" class="form-control utility-search-input" placeholder="Search products..." aria-label="Search laboratory products and reagents" value="{{ request()->query('q') ?? request()->query('s') }}">
          <button class="btn utility-search-btn" type="submit" aria-label="Submit search query">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Header / Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
  <div class="container-fluid px-3 px-lg-4">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : asset('images/logo-prolabios.png') }}" alt="{{ $siteSettings['company_name'] ?? 'Prolabios' }}" height="40" width="auto" decoding="async" fetchpriority="high">
      </a>
    
    <!-- Mobile Actions (Cart & Search) -->
    <div class="d-flex align-items-center gap-3 ms-auto me-3 d-lg-none">
      @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
      <a href="{{ route('cart.index') }}" class="text-white position-relative d-inline-flex align-items-center p-1" title="Keranjang Belanja B2B" aria-label="Keranjang Belanja B2B">
        <i class="bi bi-cart3" style="font-size: 1.25rem;"></i>
        <span class="nav-cart-badge" style="display: {{ $cartCount > 0 ? 'inline-flex' : 'none' }};">
          {{ $cartCount }}
        </span>
      </a>

      <button type="button" id="mobile-search-open" class="btn p-1 border-0 bg-transparent text-white d-inline-flex align-items-center" title="Search Products" aria-label="Search Products" aria-haspopup="dialog" aria-controls="search-overlay">
        <i class="bi bi-search" style="font-size: 1.15rem;"></i>
      </button>
    </div>

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
            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('profil*') ? 'active' : '' }}" href="{{ url('/profil') }}">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('produk*') ? 'active' : '' }}" href="{{ url('/produk') }}">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('sektor*') ? 'active' : '' }}" href="{{ url('/sektor') }}">Sectors</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('layanan*') ? 'active' : '' }}" href="{{ url('/layanan') }}">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('informasi*') ? 'active' : '' }}" href="{{ url('/informasi') }}">News &amp; Articles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}" href="{{ url('/kontak') }}">Contacts</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-sm btn-outline-danger px-3 py-2 mt-1 mt-lg-0 d-inline-flex align-items-center gap-2" href="{{ !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf') }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.9rem;">
              <i class="bi bi-download"></i> Download the Catalog
            </a>
          </li>
          <li class="nav-item d-none d-lg-flex align-items-center gap-3 ms-lg-2 mt-3 mt-lg-0 navbar-utilities">
            <a href="{{ route('cart.index') }}" class="nav-link p-0 text-white position-relative" title="Keranjang Belanja B2B" aria-label="Keranjang Belanja B2B">
              <i class="bi bi-cart3" style="font-size: 1.25rem; vertical-align: middle;"></i>
              @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
              <span id="cart-badge-count" class="nav-cart-badge" style="display: {{ $cartCount > 0 ? 'inline-flex' : 'none' }};">
                {{ $cartCount }}
              </span>
            </a>
            <button type="button" id="nav-search-open" class="nav-link p-0 text-white border-0 bg-transparent ms-2" title="Search Products" aria-label="Search Products" aria-haspopup="dialog" aria-controls="search-overlay">
              <i class="bi bi-search" style="font-size: 1.05rem; vertical-align: middle;"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
