{{-- resources/views/partials/catalog-sidebar.blade.php --}}
<aside id="catalog-sidebar">
  <!-- Mobile & Tablet Filter Toggle Button -->
  <button class="catalog-filter-toggle-btn w-100 d-lg-none mb-3 d-flex align-items-center justify-content-between py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
    <span><i class="bi bi-funnel me-2"></i>Filter & Kategori</span>
    <i class="bi bi-chevron-down"></i>
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
      <i class="bi bi-search" style="cursor: pointer;" onclick="document.getElementById('catalog-search-form').submit();"></i>
      <input type="text" id="local-search-input" name="s" placeholder="Cari produk, reagen, atau katalog..." aria-label="Cari produk" value="{{ request()->query('s') ?? request()->query('q') }}">
    </form>

    <!-- Categories Card -->
    <div class="card p-4 mb-4">
      <h3 class="profil-sidebar-title mb-3">
        <i class="bi bi-grid-fill me-2 text-primary"></i> Kategori Produk
      </h3>
      <nav class="layanan-sidebar-nav" id="produk-sidebar">
        <a href="{{ url('/produk') }}?category=all#catalog-section"
           class="layanan-sidebar-link d-flex align-items-center justify-content-between {{ ($activeCategory ?? 'all') === 'all' ? 'is-active' : '' }}">
          <span>Semua Kategori</span>
          <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
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
              <i class="bi bi-chevron-{{ ($activeCategory ?? '') === $catKey ? 'down' : 'right' }} chevron-icon" style="font-size: 0.75rem;"></i>
            </a>

            <!-- Subcategories container -->
            <div id="sub-group-{{ $catKey }}" class="sub-category-group {{ ($activeCategory ?? '') === $catKey ? '' : 'd-none' }}">
              <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory=all#catalog-section"
                 class="sub-category-link {{ ($activeCategory ?? '') === $catKey && (!($activeSubCategory ?? null) || $activeSubCategory === 'all') ? 'is-active' : '' }}">
                <span>Semua {{ $catData['name'] }}</span>
                <i class="bi bi-arrow-right-short sub-category-icon"></i>
              </a>
              @foreach($catData['subs'] as $subKey => $subName)
                <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory={{ $subKey }}#catalog-section"
                   class="sub-category-link {{ ($activeCategory ?? '') === $catKey && ($activeSubCategory ?? '') === $subKey ? 'is-active' : '' }}">
                  <span>{{ $subName }}</span>
                  <i class="bi bi-arrow-right-short sub-category-icon"></i>
                </a>
              @endforeach
            </div>
          @else
            <!-- Category without subcategories: direct filter link -->
            <a href="{{ url('/produk') }}?category={{ $catKey }}#catalog-section"
               class="layanan-sidebar-link d-flex align-items-center justify-content-between {{ ($activeCategory ?? '') === $catKey ? 'is-active' : '' }}">
              <span>{{ $catData['name'] }}</span>
              <i class="bi bi-arrow-right-short fs-5 ms-auto"></i>
            </a>
          @endif
        @endforeach
      </nav>
    </div>

    <!-- Sidebar Card 2: Butuh Bantuan CTA -->
    <div class="profil-cta-box p-4">
      <span class="nb-badge mb-3">KONSULTASI PRODUK</span>
      <h3 class="profil-sidebar-title">Butuh Bantuan?</h3>
      <p>Diskusikan kebutuhan spesifikasi produk atau instrumen laboratorium Anda langsung dengan tim teknis kami.</p>
      <a href="{{ url('/kontak') }}?subjek=inquiry" class="nb-btn nb-btn-primary w-100 justify-content-center mb-2">
        Tanya Tim Teknis <i class="bi bi-arrow-right ms-1"></i>
      </a>
      <a href="{{ !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf') }}" target="_blank" rel="noopener noreferrer" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.82rem;">
        <i class="bi bi-download me-1"></i> Unduh Katalog PDF
      </a>
    </div>
  </div>
</aside>
