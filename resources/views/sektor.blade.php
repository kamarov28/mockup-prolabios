@extends('layouts.app')

@section('title', 'Sektor Industri | PROLABIOS')
@section('meta_description', 'Sektor industri yang dilayani Prolabios - Farmasi, Food & Beverage, Mikrobiologi, dan berbagai industri lain dengan solusi laboratorium terpercaya.')
@section('meta_keywords', 'sektor industri, farmasi, food beverage, mikrobiologi, industri, solusi laboratorium, prolabios')
@section('canonical_url', url('/sektor'))

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Industrial Sector</span>
      <h1 class="editorial-page-title">Focus Sectors</h1>
      <p class="editorial-page-subtitle">Serving various industrial sectors with cutting-edge analytics solutions</p>
    </div>
  </div>

  <!-- Sektor Content -->
  <section style="padding: 80px 0;" id="sektor-nav">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          @php
            $firstSectorId = (isset($sectors) && count($sectors) > 0) ? $sectors[0]['id'] : 'biomolecular';
            $activeSector = request()->get('s') ?? request()->get('kategori') ?? $firstSectorId;
          @endphp

          <div class="mb-5">
            <h3 class="profil-sidebar-title">Select a Sector</h3>
            <nav class="layanan-sidebar-nav">
              @if(isset($sectors) && count($sectors) > 0)
                @foreach($sectors as $sec)
                  <a href="{{ url('/sektor') }}?s={{ $sec['id'] }}#sektor-nav"
                     class="layanan-sidebar-link {{ $activeSector == $sec['id'] ? 'is-active' : '' }}">
                    {{ $sec['name'] }}
                  </a>
                @endforeach
              @else
                <a href="#" class="layanan-sidebar-link is-active">Brewing</a>
              @endif
            </nav>
          </div>

          <div class="profil-cta-box d-none d-md-block">
            <h3 class="profil-sidebar-title">Looking for tailored solutions?</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Discuss your industry-specific needs with our technical team.</p>
            <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Free Consultation <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
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
            <!-- Sector Hero Image -->
            <div class="profil-hero-img mb-5">
              <img src="{{ $currentImage }}" alt="{{ $currentData['name'] }} Sector" class="w-100" style="object-fit: cover; height: 360px; display: block;" loading="lazy" decoding="async">
            </div>

            <!-- Sector Title & Description -->
            <h2 class="profil-section-title">{{ $currentData['name'] }}</h2>
            @foreach($descriptionParagraphs as $desc)
              <p class="profil-body-text mb-4">{!! \App\Services\DataService::sanitizeHtml($desc) !!}</p>
            @endforeach

            <hr style="border-color: var(--color-border); margin: 48px 0;">

            <!-- Product Table -->
            <h3 class="profil-section-title" style="font-size: 1.4rem !important;">Discover Our Products</h3>
            <p class="profil-body-text mb-4">We offer a wide variety of specialized products designed to support operational activities, research, and analysis in the <strong style="color: rgba(255,255,255,0.85);">{{ $currentData['name'] }}</strong> sector.</p>

            <!-- Mobile Swipe Indicator -->
            <div class="d-md-none text-end mb-2">
              <span class="badge" style="background: rgba(255, 73, 80, 0.08); color: var(--color-accent); border: 1px solid rgba(255, 73, 80, 0.2); font-size: 0.68rem; font-family: var(--font-headline); letter-spacing: 0.5px; padding: 6px 12px; border-radius: 100px;">
                <i class="bi bi-arrow-left-right me-1"></i> Geser Tabel
              </span>
            </div>

            <div class="table-responsive mt-2">
              <table class="table custom-table align-middle" style="min-width: 650px;">
                <thead>
                  <tr>
                    <th>Catalogue</th>
                    <th>Product</th>
                    <th>Application</th>
                  </tr>
                </thead>
                <tbody>
                  @php $hasProducts = false; @endphp
                  @if(isset($products) && count($products) > 0)
                    @foreach($products as $prod)
                      @if(isset($prod['sector']) && in_array($currentData['id'], explode(',', $prod['sector'])))
                        @php $hasProducts = true; @endphp
                        <tr>
                          <td style="color: var(--color-text-muted); font-size: 0.82rem;">{{ $prod['catalog'] ?? '-' }}</td>
                          <td>
                            <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none fw-semibold" style="color: var(--color-accent);">
                              {{ $prod['title'] }}
                            </a>
                          </td>
                          <td style="color: var(--color-text-muted); font-size: 0.88rem;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 150) }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>

            @if(!$hasProducts)
              <p style="color: var(--color-text-muted); font-size: 0.9rem; padding: 16px 0; border-top: 1px solid var(--color-border);">
                Belum ada produk spesifik untuk sektor ini. <a href="{{ url('/produk') }}" style="color: var(--color-accent);">Lihat semua produk kami</a>.
              </p>
            @endif

            <hr style="border-color: var(--color-border); margin: 48px 0;">

            <!-- Related Products -->
            <h3 class="profil-section-title" style="font-size: 1.4rem !important;">Related Product</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
              @if(isset($products) && count($products) > 0)
                @php
                  $related = collect($products)->filter(function($p) use ($currentData) {
                      return isset($p['sector']) && $p['sector'] != $currentData['id'];
                  })->shuffle()->take(3);
                @endphp
                @foreach($related as $prod)
                  <div class="col">
                    <div class="card h-100 product-card-premium border-0">
                      <div class="img-wrap">
                        <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                      </div>
                      <div class="card-body p-3">
                        @if(!empty($prod['catalog']))
                          <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-bottom: 6px; font-family: var(--font-headline); text-transform: uppercase; letter-spacing: 1px;">Cat. {{ $prod['catalog'] }}</div>
                        @endif
                        <h4 class="card-title fs-6 fw-bold">
                          <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none" style="color: #fff;">{{ $prod['title'] }}</a>
                        </h4>
                        <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 8px; margin-bottom: 16px;">{{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 80) }}</p>
                        <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="profil-cta-btn" style="font-size: 0.72rem;">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>

            <!-- Mobile-only CTA Box -->
            <div class="profil-cta-box d-md-none mt-5">
              <h3 class="profil-sidebar-title">Looking for tailored solutions?</h3>
              <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Discuss your industry-specific needs with our technical team.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Free Consultation <i class="bi bi-arrow-right"></i></a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  @include('partials.gsap-loader')
  <script>
    document.addEventListener('DOMContentLoaded', function() {


      function decodeHtmlEntity(str) {
        if (!str) return '';
        const txt = document.createElement("textarea");
        txt.innerHTML = str;
        return txt.value;
      }

      const sectors = @json($sectors);
      const products = @json($products);
      const detailProductUrl = "{{ url('/produk/detail') }}";
      const allProductsUrl = "{{ url('/produk') }}";

      const sidebarLinks = document.querySelectorAll('#sektor-nav .layanan-sidebar-link');
      const contentArea = document.querySelector('#sektor-nav .col-lg-9');

      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const urlObj = new URL(this.href);
          const sectorId = urlObj.searchParams.get('s');
          if (!sectorId) return;
          const sector = sectors.find(s => s.id === sectorId);
          if (!sector) return;

          sidebarLinks.forEach(l => l.classList.remove('is-active'));
          this.classList.add('is-active');

          const titleEl = contentArea.querySelector('.profil-section-title');
          if (titleEl) titleEl.textContent = sector.name;

          const imgEl = contentArea.querySelector('.profil-hero-img img');
          if (imgEl) {
            let sectorImg = sector.image || "{{ $defaultImage }}";
            if (sectorImg.startsWith('/')) sectorImg = "{{ url('/') }}" + sectorImg;
            imgEl.src = sectorImg;
            imgEl.alt = sector.name + ' Sector';
          }

          // Update description paragraphs
          const bodyTexts = contentArea.querySelectorAll('p.profil-body-text');
          const firstHr = contentArea.querySelector('hr');
          bodyTexts.forEach(p => { if (firstHr && p.compareDocumentPosition(firstHr) & Node.DOCUMENT_POSITION_FOLLOWING) p.remove(); });

          const description = sector.description && sector.description.length > 0 ? sector.description : [
            `Kami menyediakan berbagai solusi mutakhir untuk mendukung aktivitas dan pengujian di sektor <strong>${sector.name}</strong>.`,
            `Jelajahi rangkaian produk spesifik yang kami tawarkan untuk memenuhi kebutuhan pengujian harian laboratorium Anda.`
          ];

          if (firstHr && titleEl) {
            description.reverse().forEach(descText => {
              const p = document.createElement('p');
              p.className = 'profil-body-text mb-4';
              p.innerHTML = descText;
              titleEl.parentNode.insertBefore(p, firstHr);
            });
          }

          // Update product table
          const tbody = contentArea.querySelector('table tbody');
          if (tbody) {
            tbody.innerHTML = '';
            const sectorProducts = products.filter(p => p.sector && p.sector.split(',').includes(sector.id));
            sectorProducts.forEach(prod => {
              const tr = document.createElement('tr');
              tr.innerHTML = `
                <td style="color: var(--color-text-muted); font-size: 0.82rem;">${prod.catalog || '-'}</td>
                <td><a href="${detailProductUrl}?id=${encodeURIComponent(prod.title)}" class="text-decoration-none fw-semibold" style="color: var(--color-accent);">${prod.title}</a></td>
                <td style="color: var(--color-text-muted); font-size: 0.88rem;">${(decodeHtmlEntity(prod.description || '').replace(/<\/?[^>]+(>|$)/g, '').trim()).substring(0, 150) || '-'}</td>
              `;
              tbody.appendChild(tr);
            });
          }

          history.pushState(null, '', window.location.pathname + '?s=' + sector.id);

          if (typeof initGSAPAnimations === 'function') {
            initGSAPAnimations();
          }
        });
      });

      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sectorId = urlParams.get('s') || 'brewing';
        const activeLink = Array.from(sidebarLinks).find(link => {
          const urlObj = new URL(link.href);
          return urlObj.searchParams.get('s') === sectorId;
        });
        if (activeLink) activeLink.dispatchEvent(new Event('click'));
      });
    });
  </script>
  @endpush
@endsection
