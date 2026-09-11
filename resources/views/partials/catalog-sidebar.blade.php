{{-- resources/views/partials/catalog-sidebar.blade.php --}}
<aside id="catalog-sidebar">
  <!-- Mobile & Tablet Filter Toggle Button -->
  <button class="catalog-filter-toggle-btn w-100 d-lg-none mb-3 d-flex align-items-center justify-content-between py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
    <span><i data-lucide="filter" class="me-2"></i>Filter & Kategori</span>
    <i data-lucide="chevron-down"></i>
  </button>

  <!-- Collapsible Content for Mobile & Tablet, always open on Desktop (lg+) screens -->
  <div class="collapse d-lg-block" id="sidebarCollapse">
    <!-- Search Input Card (Full width of sidebar) -->
    <form action="{{ url('/produk') }}" method="GET" id="catalog-search-form" class="produk-search-wrap w-100 mb-4">
      @if(request()->query('category'))
        <input type="hidden" name="category" value="{{ request()->query('category') }}">
      @endif
      @if(request()->query('subcategory'))
        <input type="hidden" name="subcategory" value="{{ request()->query('subcategory') }}">
      @endif
      <i data-lucide="search" style="cursor: pointer;" onclick="document.getElementById('catalog-search-form').submit();"></i>
      <input type="text" id="local-search-input" name="s" placeholder="Cari produk, reagen, atau katalog..." aria-label="Cari produk" value="{{ request()->query('s') ?? request()->query('q') }}">
    </form>

    <!-- Categories Card -->
    <div class="card p-4 mb-4">
      <h3 class="profil-sidebar-title mb-3">
        <i data-lucide="grid" class="me-2 text-primary"></i> Kategori Produk
      </h3>
      <nav class="layanan-sidebar-nav" id="produk-sidebar">
        <a href="{{ url('/produk') }}?category=all#catalog-section"
           class="layanan-sidebar-link d-flex align-items-center justify-content-between {{ ($activeCategory ?? 'all') === 'all' ? 'is-active' : '' }}">
          <span>Semua Kategori</span>
          <i data-lucide="chevron-right" class="fs-5 ms-auto"></i>
        </a>
        @foreach($categoriesStructure as $catKey => $catData)
          @if(!empty($catData['subs']))
            <!-- Category with subcategories: acts as accordion toggle -->
            <a href="#"
               class="layanan-sidebar-link d-flex justify-content-between align-items-center category-accordion-btn {{ ($activeCategory ?? '') === $catKey ? 'is-active' : '' }}"
               role="button"
               aria-expanded="{{ ($activeCategory ?? '') === $catKey ? 'true' : 'false' }}"
               aria-controls="sub-group-{{ $catKey }}"
               data-target="sub-group-{{ $catKey }}">
              <span>{{ $catData['name'] }}</span>
              <i data-lucide="{{ ($activeCategory ?? '') === $catKey ? 'chevron-down' : 'chevron-right' }}" class="chevron-icon" style="font-size: 0.75rem;"></i>
            </a>

            <!-- Subcategories container -->
            <div id="sub-group-{{ $catKey }}" class="sub-category-group {{ ($activeCategory ?? '') === $catKey ? '' : 'd-none' }}">
              <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory=all#catalog-section"
                 class="sub-category-link {{ ($activeCategory ?? '') === $catKey && (!($activeSubCategory ?? null) || $activeSubCategory === 'all') ? 'is-active' : '' }}">
                <span>Semua {{ $catData['name'] }}</span>
                <i data-lucide="chevron-right" class="sub-category-icon"></i>
              </a>
              @foreach($catData['subs'] as $subKey => $subName)
                <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory={{ $subKey }}#catalog-section"
                   class="sub-category-link {{ ($activeCategory ?? '') === $catKey && ($activeSubCategory ?? '') === $subKey ? 'is-active' : '' }}">
                  <span>{{ $subName }}</span>
                  <i data-lucide="chevron-right" class="sub-category-icon"></i>
                </a>
              @endforeach
            </div>
          @else
            <!-- Category without subcategories: direct filter link -->
            <a href="{{ url('/produk') }}?category={{ $catKey }}#catalog-section"
               class="layanan-sidebar-link d-flex align-items-center justify-content-between {{ ($activeCategory ?? '') === $catKey ? 'is-active' : '' }}">
              <span>{{ $catData['name'] }}</span>
              <i data-lucide="chevron-right" class="fs-5 ms-auto"></i>
            </a>
          @endif
        @endforeach
      </nav>
    </div>

    <!-- Sidebar Card 2: Butuh Bantuan CTA -->
    @include('partials.sidebar-cta', [
      'badge' => 'KONSULTASI PRODUK',
      'title' => 'Butuh Bantuan?',
      'text' => 'Diskusikan kebutuhan spesifikasi produk atau instrumen laboratorium Anda langsung dengan tim teknis kami.',
      'primaryUrl' => url('/kontak') . '?subjek=inquiry',
      'primaryText' => 'Tanya Tim Teknis',
      'secondaryUrl' => !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf'),
      'secondaryText' => 'Unduh Katalog PDF',
      'secondaryLucide' => 'download',
      'secondaryBlank' => true
    ])
  </div>
</aside>
