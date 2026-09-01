{{-- resources/views/partials/catalog-sidebar.blade.php --}}
<aside id="catalog-sidebar">
  <!-- Mobile Filter Toggle Button -->
  <button class="catalog-filter-toggle-btn w-100 d-md-none mb-4 d-flex align-items-center justify-content-between py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
    <span><i class="bi bi-funnel me-2"></i>Filter & Kategori</span>
    <i class="bi bi-chevron-down"></i>
  </button>

  <!-- Collapsible Content for Mobile, always open on Medium+ screens -->
  <div class="collapse d-md-block" id="sidebarCollapse">
    <!-- Categories -->
    <div class="mb-5">
      <h3 class="profil-sidebar-title">Kategori Produk</h3>
      <nav class="layanan-sidebar-nav" id="produk-sidebar">
        <a href="{{ url('/produk') }}?category=all#catalog-section"
           class="layanan-sidebar-link {{ ($activeCategory ?? 'all') === 'all' ? 'is-active' : '' }}">
          Semua Kategori
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
              <i class="bi bi-chevron-{{ ($activeCategory ?? '') === $catKey ? 'down' : 'right' }} chevron-icon" style="font-size: 0.7rem; opacity: 0.6;"></i>
            </a>

            <!-- Subcategories container -->
            <div id="sub-group-{{ $catKey }}" class="sub-category-group ps-3 mb-3 {{ ($activeCategory ?? '') === $catKey ? '' : 'd-none' }}" style="max-height: 350px; overflow-y: auto; border-left: 1px solid var(--color-border); margin-left: 8px;">
              <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory=all#catalog-section"
                 class="sub-category-link {{ ($activeCategory ?? '') === $catKey && (!($activeSubCategory ?? null) || $activeSubCategory === 'all') ? 'is-active' : '' }}">
                Semua {{ $catData['name'] }}
              </a>
              @foreach($catData['subs'] as $subKey => $subName)
                <a href="{{ url('/produk') }}?category={{ $catKey }}&subcategory={{ $subKey }}#catalog-section"
                   class="sub-category-link {{ ($activeCategory ?? '') === $catKey && ($activeSubCategory ?? '') === $subKey ? 'is-active' : '' }}">
                  {{ $subName }}
                </a>
              @endforeach
            </div>
          @else
            <!-- Category without subcategories: direct filter link -->
            <a href="{{ url('/produk') }}?category={{ $catKey }}#catalog-section"
               class="layanan-sidebar-link {{ ($activeCategory ?? '') === $catKey ? 'is-active' : '' }}">
              {{ $catData['name'] }}
            </a>
          @endif
        @endforeach
      </nav>
    </div>

    <div class="profil-cta-box">
      <h3 class="profil-sidebar-title">Butuh Bantuan?</h3>
      <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Diskusikan kebutuhan produk Anda dengan tim teknis kami.</p>
      <a href="{{ url('/kontak') }}?subjek=inquiry" class="profil-cta-btn d-block mb-3">Tanya Produk <i class="bi bi-arrow-right"></i></a>
      <a href="{{ !empty($siteSettings['catalog_pdf_url']) ? $siteSettings['catalog_pdf_url'] : asset('catalog.pdf') }}" target="_blank" rel="noopener noreferrer" class="profil-social-link"><i class="bi bi-download"></i> Unduh Katalog PDF</a>
    </div>
  </div>
</aside>
