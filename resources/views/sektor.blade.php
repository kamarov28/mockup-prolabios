@extends('layouts.app')

@section('title', 'Sektor Industri | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['sectors_banner_image'] ?? 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['sectors_banner_image'] ?? 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['sectors_title'] ?? 'Sektor Industri' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['sectors_subtitle'] ?? 'Solusi spesifik untuk berbagai sektor industri dan laboratorium' }}</p>
    </div>
  </div>

  <!-- Sektor Content -->
  <section class="py-5 bg-light" id="sektor-nav">
    <div class="container">
      <div class="row">
        <!-- Sidebar (Left) -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Pilih Sektor</h2>
            <div class="list-group list-group-flush">
              @php $activeSector = request()->get('s') ?? request()->get('kategori') ?? 'brewing'; @endphp
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeSector == $sec['id'] ? 'active' : '' }}">
                  {{ $sec['name'] }}
                </a>
                @endforeach
              @else
                <a href="#" class="list-group-item list-group-item-action sector-sidebar-link active">Brewing</a>
              @endif
            </div>
          </div>
          
          <div class="p-4 rounded shadow-sm text-center animate-on-scroll animate-slide-right delay-100 sidebar-cta-box">
            <h2 class="h5 fw-bold mb-3">Butuh Solusi Khusus?</h2>
            <p class="small mb-3">Konsultasikan kebutuhan sektor industri Anda dengan tim teknis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="btn w-100 fw-bold shadow-sm">Konsultasi Gratis</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm animate-on-scroll animate-slide-up">
            @php
              $currentData = null;
              if (isset($sectors)) {
                  foreach ($sectors as $sec) {
                      if ($sec['id'] == $activeSector) {
                          $currentData = $sec;
                          break;
                      }
                  }
              }

            @php
              $descriptionParagraphs = $currentData['description'] ?? [];
              if (empty($descriptionParagraphs)) {
                  $descriptionParagraphs = [
                      "Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>" . ($currentData['name'] ?? '') . "</strong>. Seluruh produk kami dikembangkan dengan standar kualitas tertinggi guna menjamin keandalan, akurasi, dan kepatuhan terhadap standar industri terkini.",
                      "Jelajahi rangkaian produk spesifik yang kami tawarkan, mulai dari reagen, instrumen analitik, hingga media kultur yang dirancang khusus untuk memenuhi kebutuhan pengujian harian laboratorium maupun lini produksi Anda."
                  ];
              }

              $defaultImage = 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
              $currentImage = $currentData['image'] ?? $defaultImage;
              if ($currentImage && strpos($currentImage, '/') === 0) {
                  $currentImage = asset($currentImage);
              }
            @endphp

            @if($currentData)
            <img src="{{ $currentImage }}" alt="{{ $currentData['name'] }} Sector" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height: 400px; object-fit: cover;">
            
            <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">{{ $currentData['name'] }}</h2>
            @foreach($descriptionParagraphs as $desc)
              <p class="text-justify text-muted mb-3" style="text-align: justify; line-height: 1.8;">{!! $desc !!}</p>
            @endforeach

            <hr class="my-5">

            <h3 class="h4 mb-4 fw-bold">Temukan Produk Kami:</h3>
            <p class="text-muted">Kami memiliki berbagai macam produk khusus yang dirancang untuk mendukung kegiatan operasional, riset, dan analisis di sektor <strong>{{ $currentData['name'] }}</strong>.</p>
            
            <div class="table-responsive mt-4">
              <table class="table custom-table table-hover align-middle">
                <thead>
                  <tr>
                    <th class="py-3 px-4">Catalogue</th>
                    <th class="py-3 px-4">Product</th>
                    <th class="py-3 px-4">Application</th>
                  </tr>
                </thead>
                <tbody>
                  @php $hasProducts = false; @endphp
                  @if(isset($products) && count($products) > 0)
                    @foreach($products as $prod)
                      @if(isset($prod['sector']) && in_array($currentData['id'], explode(',', $prod['sector'])))
                        @php $hasProducts = true; @endphp
                        <tr>
                          <td class="py-3 px-4 text-secondary">{{ $prod['catalog'] ?? '-' }}</td>
                          <td class="py-3 px-4">
                            <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none fw-semibold" style="color: var(--color-primary, #e63946);">
                              {{ $prod['title'] }}
                            </a>
                          </td>
                          <td class="py-3 px-4 text-secondary">{{ $prod['description'] ?? '-' }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            @if(!$hasProducts)
              <div class="alert alert-light mt-3 border">
                Belum ada produk spesifik yang ditambahkan untuk sektor ini. <a href="{{ url('/produk') }}" class="alert-link text-decoration-none">Lihat semua produk kami</a>.
              </div>
            @endif

            <!-- Related Product Section -->
            <h3 class="h4 mt-5 mb-4 fw-bold border-bottom border-primary border-2 d-inline-block pb-2">Related Product</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
              @if(isset($products) && count($products) > 0)
                @php 
                  $related = collect($products)->filter(function($p) use ($currentData) {
                      return isset($p['sector']) && $p['sector'] != $currentData['id'];
                  })->shuffle()->take(3); 
                @endphp
                @foreach($related as $prod)
                  <div class="col animate-on-scroll animate-slide-up delay-{{ ($loop->index + 1) * 100 }}">
                    <div class="card h-100 product-card-premium border-0">
                      <div class="img-wrap">
                        <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                      </div>
                      <div class="card-body p-3">
                        @if(!empty($prod['catalog']))
                        <div class="text-muted small mb-2 fw-semibold" style="font-size: 0.75rem;">Cat. {{ $prod['catalog'] }}</div>
                        @endif
                        <h4 class="card-title fs-6 fw-bold">
                          <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none text-dark hover-primary">{{ $prod['title'] }}</a>
                        </h4>
                        <p class="card-text text-muted small mt-2 mb-3" style="font-size: 0.78rem;">{{ Str::limit($prod['description'] ?? '', 80) }}</p>
                        <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">Lihat Detail</a>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
            <!-- End Related Product Section -->
            
            @else
            <div class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="text-muted mt-3">Sedang memuat data sektor...</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sectors = @json($sectors);
      const products = @json($products);
      const detailProductUrl = "{{ url('/produk/detail') }}";
      const allProductsUrl = "{{ url('/produk') }}";
      
      const sidebarLinks = document.querySelectorAll('#sektor-nav .list-group-item');
      const contentCard = document.querySelector('#sektor-nav .col-lg-9 > div');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          const urlObj = new URL(this.href);
          const sectorId = urlObj.searchParams.get('s');
          
          if (!sectorId) return;
          
          // Find sector data
          const sector = sectors.find(s => s.id === sectorId);
          if (!sector) return;
          
          // Update active class in sidebar
          sidebarLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          
          // Update Sector Title
          const titleEl = contentCard.querySelector('h2');
          if (titleEl) {
            titleEl.textContent = sector.name;
          }
          
          // Update Sector Image src & alt
          const imgEl = contentCard.querySelector('img');
          if (imgEl) {
            let sectorImg = sector.image || "{{ $defaultImage }}";
            if (sectorImg.startsWith('/')) {
              sectorImg = "{{ url('/') }}" + sectorImg;
            }
            imgEl.src = sectorImg;
            imgEl.alt = sector.name + ' Sector';
          }
          
          // Update Sector Description
          let currentEl = contentCard.querySelector('h2').nextElementSibling;
          while (currentEl && currentEl.tagName !== 'HR') {
            const nextEl = currentEl.nextElementSibling;
            currentEl.remove();
            currentEl = nextEl;
          }
          
          // Insert new description paragraphs before <hr>
          const hrEl = contentCard.querySelector('hr');
          const description = sector.description && sector.description.length > 0 ? sector.description : [
              `Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>${sector.name}</strong>. Seluruh produk kami dikembangkan dengan standar kualitas tertinggi guna menjamin keandalan, akurasi, dan kepatuhan terhadap standar industri terkini.`,
              `Jelajahi rangkaian produk spesifik yang kami tawarkan, mulai dari reagen, instrumen analitik, hingga media kultur yang dirancang khusus untuk memenuhi kebutuhan pengujian harian laboratorium maupun lini produksi Anda.`
          ];
          
          description.forEach(descText => {
            const p = document.createElement('p');
            p.className = 'text-justify text-muted mb-3';
            p.style.textAlign = 'justify';
            p.style.lineHeight = '1.8';
            p.innerHTML = descText;
            hrEl.parentNode.insertBefore(p, hrEl);
          });
          
          // Update Sektor name in the "Temukan Produk Kami" section text
          const productSecText = contentCard.querySelector('p.text-muted strong');
          if (productSecText) {
            productSecText.textContent = sector.name;
          }
          
          // Update Sektor Products Table
          const tbody = contentCard.querySelector('table tbody');
          if (tbody) {
            tbody.innerHTML = '';
            
            const sectorProducts = products.filter(p => p.sector && p.sector.split(',').includes(sector.id));
            
            if (sectorProducts.length > 0) {
              sectorProducts.forEach(prod => {
                const tr = document.createElement('tr');
                
                const tdCatalog = document.createElement('td');
                tdCatalog.className = 'py-3 px-4 text-secondary';
                tdCatalog.textContent = prod.catalog || '-';
                
                const tdProduct = document.createElement('td');
                tdProduct.className = 'py-3 px-4';
                
                const aLink = document.createElement('a');
                aLink.href = detailProductUrl + '?id=' + encodeURIComponent(prod.title);
                aLink.className = 'text-decoration-none fw-semibold';
                aLink.style.color = 'var(--color-primary, #e63946)';
                aLink.textContent = prod.title;
                
                tdProduct.appendChild(aLink);
                
                const tdApp = document.createElement('td');
                tdApp.className = 'py-3 px-4 text-secondary';
                tdApp.textContent = prod.description || '-';
                
                tr.appendChild(tdCatalog);
                tr.appendChild(tdProduct);
                tr.appendChild(tdApp);
                tbody.appendChild(tr);
              });
              
              // Hide alert
              const alertEl = contentCard.querySelector('.alert');
              if (alertEl) alertEl.remove();
            } else {
              // Show alert
              let alertEl = contentCard.querySelector('.alert');
              if (!alertEl) {
                alertEl = document.createElement('div');
                alertEl.className = 'alert alert-light mt-3 border';
                alertEl.innerHTML = `Belum ada produk spesifik yang ditambahkan untuk sektor ini. <a href="${allProductsUrl}" class="alert-link text-decoration-none">Lihat semua produk kami</a>.`;
                tbody.parentNode.parentNode.appendChild(alertEl);
              }
            }
          }
          
          // Re-trigger animations in content card
          contentCard.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.classList.add('is-visible');
          });
          
          // Update URL
          history.pushState(null, '', window.location.pathname + '?s=' + sector.id);
        });
      });
      
      // Handle browser back/forward buttons
      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sectorId = urlParams.get('s') || 'brewing';
        
        const activeLink = Array.from(sidebarLinks).find(link => {
          const urlObj = new URL(link.href);
          return urlObj.searchParams.get('s') === sectorId;
        });
        
        if (activeLink) {
          activeLink.dispatchEvent(new Event('click'));
        }
      });
    });
  </script>
  @endpush
@endsection

@extends('layouts.app')

@section('title', 'Sektor Industri | PROLABIOS')
@section('meta_description', 'Sektor industri yang dilayani Prolabios - Farmasi, Food & Beverage, Mikrobiologi, dan berbagai industri lain dengan solusi laboratorium terpercaya.')
@section('meta_keywords', 'sektor industri, farmasi, food beverage, mikrobiologi, industri, solusi laboratorium, prolabios')
@section('canonical_url', url('/sektor'))

@section('preload')
  <link rel="preload" href="{{ $siteSettings['sectors_banner_image'] ?? 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['sectors_banner_image'] ?? 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['sectors_title'] ?? 'Sektor Industri' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['sectors_subtitle'] ?? 'Solusi spesifik untuk berbagai sektor industri dan laboratorium' }}</p>
    </div>
  </div>

  <!-- Sektor Content -->
  <section class="py-5 bg-light" id="sektor-nav">
    <div class="container">
      <div class="row">
        <!-- Sidebar (Left) -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Pilih Sektor</h2>
            <div class="list-group list-group-flush">
              @php $activeSector = request()->get('s') ?? request()->get('kategori') ?? 'brewing'; @endphp
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeSector == $sec['id'] ? 'active' : '' }}">
                  {{ $sec['name'] }}
                </a>
                @endforeach
              @else
                <a href="#" class="list-group-item list-group-item-action sector-sidebar-link active">Brewing</a>
              @endif
            </div>
          </div>
          
          <div class="p-4 rounded shadow-sm text-center animate-on-scroll animate-slide-right delay-100 sidebar-cta-box">
            <h2 class="h5 fw-bold mb-3">Butuh Solusi Khusus?</h2>
            <p class="small mb-3">Konsultasikan kebutuhan sektor industri Anda dengan tim teknis kami.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="btn w-100 fw-bold shadow-sm">Konsultasi Gratis</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm animate-on-scroll animate-slide-up">
            @php
              $currentData = null;
              if (isset($sectors)) {
                  foreach ($sectors as $sec) {
                      if ($sec['id'] == $activeSector) {
                          $currentData = $sec;
                          break;
                      }
                  }
              }
            @endphp

            @php
              $descriptionParagraphs = $currentData['description'] ?? [];
              if (empty($descriptionParagraphs)) {
                  $descriptionParagraphs = [
                      "Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>" . ($currentData['name'] ?? '') . "</strong>. Seluruh produk kami dikembangkan dengan standar kualitas tertinggi guna menjamin keandalan, akurasi, dan kepatuhan terhadap standar industri terkini.",
                      "Jelajahi rangkaian produk spesifik yang kami tawarkan, mulai dari reagen, instrumen analitik, hingga media kultur yang dirancang khusus untuk memenuhi kebutuhan pengujian harian laboratorium maupun lini produksi Anda."
                  ];
              }

              $defaultImage = 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
              $currentImage = $currentData['image'] ?? $defaultImage;
              if ($currentImage && strpos($currentImage, '/') === 0) {
                  $currentImage = asset($currentImage);
              }
            @endphp

            @if($currentData)
            <img src="{{ $currentImage }}" alt="{{ $currentData['name'] }} Sector" class="img-fluid rounded shadow-sm mb-4 w-100" style="max-height: 400px; object-fit: cover;">
            
            <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">{{ $currentData['name'] }}</h2>
            @foreach($descriptionParagraphs as $desc)
              <p class="text-justify text-muted mb-3" style="text-align: justify; line-height: 1.8;">{!! $desc !!}</p>
            @endforeach

            <hr class="my-5">

            <h3 class="h4 mb-4 fw-bold">Temukan Produk Kami:</h3>
            <p class="text-muted">Kami memiliki berbagai macam produk khusus yang dirancang untuk mendukung kegiatan operasional, riset, dan analisis di sektor <strong>{{ $currentData['name'] }}</strong>.</p>
            
            <div class="table-responsive mt-4">
              <table class="table custom-table table-hover align-middle">
                <thead>
                  <tr>
                    <th class="py-3 px-4">Catalogue</th>
                    <th class="py-3 px-4">Product</th>
                    <th class="py-3 px-4">Application</th>
                  </tr>
                </thead>
                <tbody>
                  @php $hasProducts = false; @endphp
                  @if(isset($products) && count($products) > 0)
                    @foreach($products as $prod)
                      @if(isset($prod['sector']) && in_array($currentData['id'], explode(',', $prod['sector'])))
                        @php $hasProducts = true; @endphp
                        <tr>
                          <td class="py-3 px-4 text-secondary">{{ $prod['catalog'] ?? '-' }}</td>
                          <td class="py-3 px-4">
                            <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none fw-semibold" style="color: var(--color-primary, #e63946);">
                              {{ $prod['title'] }}
                            </a>
                          </td>
                          <td class="py-3 px-4 text-secondary">{{ $prod['description'] ?? '-' }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            @if(!$hasProducts)
              <div class="alert alert-light mt-3 border">
                Belum ada produk spesifik yang ditambahkan untuk sektor ini. <a href="{{ url('/produk') }}" class="alert-link text-decoration-none">Lihat semua produk kami</a>.
              </div>
            @endif

            <!-- Related Product Section -->
            <h3 class="h4 mt-5 mb-4 fw-bold border-bottom border-primary border-2 d-inline-block pb-2">Related Product</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
              @if(isset($products) && count($products) > 0)
                @php 
                  $related = collect($products)->filter(function($p) use ($currentData) {
                      return isset($p['sector']) && $p['sector'] != $currentData['id'];
                  })->shuffle()->take(3); 
                @endphp
                @foreach($related as $prod)
                  <div class="col animate-on-scroll animate-slide-up delay-{{ ($loop->index + 1) * 100 }}">
                    <div class="card h-100 product-card-premium border-0">
                      <div class="img-wrap">
                        <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                      </div>
                      <div class="card-body p-3">
                        @if(!empty($prod['catalog']))
                        <div class="text-muted small mb-2 fw-semibold" style="font-size: 0.75rem;">Cat. {{ $prod['catalog'] }}</div>
                        @endif
                        <h4 class="card-title fs-6 fw-bold">
                          <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none text-dark hover-primary">{{ $prod['title'] }}</a>
                        </h4>
                        <p class="card-text text-muted small mt-2 mb-3" style="font-size: 0.78rem;">{{ Str::limit($prod['description'] ?? '', 80) }}</p>
                        <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="btn btn-sm btn-outline-primary w-100 fw-semibold">Lihat Detail</a>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
            <!-- End Related Product Section -->
            
            @else
            <div class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="text-muted mt-3">Sedang memuat data sektor...</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sectors = @json($sectors);
      const products = @json($products);
      const detailProductUrl = "{{ url('/produk/detail') }}";
      const allProductsUrl = "{{ url('/produk') }}";
      
      const sidebarLinks = document.querySelectorAll('#sektor-nav .list-group-item');
      const contentCard = document.querySelector('#sektor-nav .col-lg-9 > div');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          const urlObj = new URL(this.href);
          const sectorId = urlObj.searchParams.get('s');
          
          if (!sectorId) return;
          
          // Find sector data
          const sector = sectors.find(s => s.id === sectorId);
          if (!sector) return;
          
          // Update active class in sidebar
          sidebarLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          
          // Update Sector Title
          const titleEl = contentCard.querySelector('h2');
          if (titleEl) {
            titleEl.textContent = sector.name;
          }
          
          // Update Sector Image src & alt
          const imgEl = contentCard.querySelector('img');
          if (imgEl) {
            let sectorImg = sector.image || "{{ $defaultImage }}";
            if (sectorImg.startsWith('/')) {
              sectorImg = "{{ url('/') }}" + sectorImg;
            }
            imgEl.src = sectorImg;
            imgEl.alt = sector.name + ' Sector';
          }
          
          // Update Sector Description
          let currentEl = contentCard.querySelector('h2').nextElementSibling;
          while (currentEl && currentEl.tagName !== 'HR') {
            const nextEl = currentEl.nextElementSibling;
            currentEl.remove();
            currentEl = nextEl;
          }
          
          // Insert new description paragraphs before <hr>
          const hrEl = contentCard.querySelector('hr');
          const description = sector.description && sector.description.length > 0 ? sector.description : [
              `Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>${sector.name}</strong>. Seluruh produk kami dikembangkan dengan standar kualitas tertinggi guna menjamin keandalan, akurasi, dan kepatuhan terhadap standar industri terkini.`,
              `Jelajahi rangkaian produk spesifik yang kami tawarkan, mulai dari reagen, instrumen analitik, hingga media kultur yang dirancang khusus untuk memenuhi kebutuhan pengujian harian laboratorium maupun lini produksi Anda.`
          ];
          
          description.forEach(descText => {
            const p = document.createElement('p');
            p.className = 'text-justify text-muted mb-3';
            p.style.textAlign = 'justify';
            p.style.lineHeight = '1.8';
            p.innerHTML = descText;
            hrEl.parentNode.insertBefore(p, hrEl);
          });
          
          // Update Sektor name in the "Temukan Produk Kami" section text
          const productSecText = contentCard.querySelector('p.text-muted strong');
          if (productSecText) {
            productSecText.textContent = sector.name;
          }
          
          // Update Sektor Products Table
          const tbody = contentCard.querySelector('table tbody');
          if (tbody) {
            tbody.innerHTML = '';
            
            const sectorProducts = products.filter(p => p.sector && p.sector.split(',').includes(sector.id));
            
            if (sectorProducts.length > 0) {
              sectorProducts.forEach(prod => {
                const tr = document.createElement('tr');
                
                const tdCatalog = document.createElement('td');
                tdCatalog.className = 'py-3 px-4 text-secondary';
                tdCatalog.textContent = prod.catalog || '-';
                
                const tdProduct = document.createElement('td');
                tdProduct.className = 'py-3 px-4';
                
                const aLink = document.createElement('a');
                aLink.href = detailProductUrl + '?id=' + encodeURIComponent(prod.title);
                aLink.className = 'text-decoration-none fw-semibold';
                aLink.style.color = 'var(--color-primary, #e63946)';
                aLink.textContent = prod.title;
                
                tdProduct.appendChild(aLink);
                
                const tdApp = document.createElement('td');
                tdApp.className = 'py-3 px-4 text-secondary';
                tdApp.textContent = prod.description || '-';
                
                tr.appendChild(tdCatalog);
                tr.appendChild(tdProduct);
                tr.appendChild(tdApp);
                tbody.appendChild(tr);
              });
              
              // Hide alert
              const alertEl = contentCard.querySelector('.alert');
              if (alertEl) alertEl.remove();
            } else {
              // Show alert
              let alertEl = contentCard.querySelector('.alert');
              if (!alertEl) {
                alertEl = document.createElement('div');
                alertEl.className = 'alert alert-light mt-3 border';
                alertEl.innerHTML = `Belum ada produk spesifik yang ditambahkan untuk sektor ini. <a href="${allProductsUrl}" class="alert-link text-decoration-none">Lihat semua produk kami</a>.`;
                tbody.parentNode.parentNode.appendChild(alertEl);
              }
            }
          }
          
          // Re-trigger animations in content card
          contentCard.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.classList.add('is-visible');
          });
          
          // Update URL
          history.pushState(null, '', window.location.pathname + '?s=' + sector.id);
        });
      });
      
      // Handle browser back/forward buttons
      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sectorId = urlParams.get('s') || 'brewing';
        
        const activeLink = Array.from(sidebarLinks).find(link => {
          const urlObj = new URL(link.href);
          return urlObj.searchParams.get('s') === sectorId;
        });
        
        if (activeLink) {
          activeLink.dispatchEvent(new Event('click'));
        }
      });
    });
  </script>
  @endpush
@endsection
