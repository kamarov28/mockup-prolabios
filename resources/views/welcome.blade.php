@extends('layouts.app')

@section('title', 'PT Prolabios Mitra Analitika | Precision Laboratory Solutions')

@section('preload')
  @php
    $firstHero = null;
    if (!empty($homeData['hero_images']) && is_array($homeData['hero_images'])) {
      $firstHero = $homeData['hero_images'][0] ?? null;
    }
  @endphp
  @if($firstHero)
    <link rel="preload" as="image" href="{{ $firstHero }}" fetchpriority="high">
  @endif
@endsection

@section('content')
  <!-- 1. Hero Section (Linear & Anduril Aesthetic — Ultra Spacious) -->
  <section class="section-spacious typo-hero">
    <div class="typo-hero-bg">
      @if(isset($homeData['hero_images']) && is_array($homeData['hero_images']))
        @foreach($homeData['hero_images'] as $index => $imgUrl)
          <img
            class="hero-bg-slide @if($index === 0) active @endif"
            src="{{ $imgUrl }}"
            alt="Prolabios Laboratory Equipment"
            decoding="async"
            @if($index === 0)
              fetchpriority="high"
            @else
              loading="lazy"
            @endif
          >
        @endforeach
      @else
        <img
          class="hero-bg-slide active"
          src="https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
          alt="Prolabios Lab Solutions"
          decoding="async"
          fetchpriority="high"
        >
      @endif
      <div class="typo-hero-overlay"></div>
    </div>
    <div class="container" style="position: relative; z-index: 2;">
      <div class="typo-hero-entrance" style="opacity: 0;">
        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="typo-pill-accent">PROFESSIONAL</span>
          <span class="typo-pill-outline">ROBUST</span>
          <span class="typo-pill-accent">OFFERING THE BEST</span>
        </div>
        <h1 class="typo-hero-title">
          Uncompromised <span class="text-accent">Testing Accuracy</span> for Industrial &amp; Research Laboratories.
        </h1>
        <p class="typo-lead">
          Official provider of analytical instruments, international standard reagents, and ready-to-use culture media. Ensuring regulatory compliance and operational efficiency for your business.
        </p>
        
        <!-- High-Tech CTAs -->
        <div class="d-flex flex-wrap gap-3 typo-hero-ctas align-items-center mt-4">
          <a href="{{ url('/produk') }}" class="typo-btn-link">
            Explore Product Catalog <i class="bi bi-arrow-right ms-1"></i>
          </a>
          <a href="{{ url('/kontak') }}" class="typo-btn-link typo-btn-link--ghost">
            Consult Lab Specialist <i class="bi bi-chat-dots ms-1"></i>
          </a>
        </div>
      </div>
      
      <!-- Manual Slider Controls -->
      @if(isset($homeData['hero_images']) && count($homeData['hero_images']) > 1)
        <div class="typo-hero-controls">
          <button id="hero-prev" class="typo-hero-ctrl-btn" aria-label="Previous Slide">
            <i class="bi bi-arrow-left"></i>
          </button>
          <button id="hero-next" class="typo-hero-ctrl-btn" aria-label="Next Slide">
            <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      @endif
    </div>
  </section>

  <!-- 2. Trusted Principals Marquee (Palantir/Anduril Monochrome Partner Strip) -->
  <section class="hitech-marquee-section">
    <div class="container mb-3 text-center">
      <span class="hitech-label-muted">AUTHORIZED GLOBAL PRINCIPALS &amp; PARTNERS</span>
    </div>
    <div class="marquee-container" style="position: relative; display: flex; overflow: hidden; user-select: none; padding: 15px 0;">
      <div class="marquee-content-single">
        <!-- Loop 1 -->
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/liofilchem.png') }}" alt="Liofilchem"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bioendo.png') }}" alt="Bioendo"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/terragene.png') }}" alt="Terragene"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/biotool.png') }}" alt="Biotool"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ifm.png') }}" alt="IFM"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bnf_korea.png') }}" alt="BNF Korea"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/leadfluid.png') }}" alt="Leadfluid"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/meizheng.png') }}" alt="Meizheng"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ksl_pulse.png') }}" alt="KSL Pulse Scientific"></div>
        
        <!-- Loop 2 -->
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/liofilchem.png') }}" alt="Liofilchem"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bioendo.png') }}" alt="Bioendo"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/terragene.png') }}" alt="Terragene"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/biotool.png') }}" alt="Biotool"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ifm.png') }}" alt="IFM"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/bnf_korea.png') }}" alt="BNF Korea"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/leadfluid.png') }}" alt="Leadfluid"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/meizheng.png') }}" alt="Meizheng"></div>
        <div class="marquee-logo-box"><img src="{{ asset('images/vendor/ksl_pulse.png') }}" alt="KSL Pulse Scientific"></div>
      </div>
    </div>
  </section>

  <!-- 3. Value Pillars Bento Grid (Scale AI & Vercel Aesthetic) -->
  <section class="section-spacious">
    <div class="container">
      <div class="row mb-5 typo-section-head">
        <div class="col-lg-7">
          <span class="typo-section-label">Capabilities</span>
          <h2 class="typo-section-title">Infrastructure &amp; Reliability Standards</h2>
          <p class="typo-section-sub text-muted">Engineered to fulfill strict regulatory compliance and ensure seamless laboratory testing continuity.</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Bento 1 -->
        <div class="col-lg-6">
          <div class="hitech-bento-card">
            <div class="hitech-bento-number">01</div>
            <div class="hitech-bento-icon"><i class="bi bi-patch-check"></i></div>
            <h3 class="hitech-bento-title">ISO &amp; AKL Certified Products</h3>
            <p class="hitech-bento-desc">Over 1,000+ officially accredited reagents and instruments, guaranteeing distribution legality for BPOM and ISO 17025 audit compliance.</p>
          </div>
        </div>

        <!-- Bento 2 -->
        <div class="col-lg-6">
          <div class="hitech-bento-card">
            <div class="hitech-bento-number">02</div>
            <div class="hitech-bento-icon"><i class="bi bi-file-earmark-code"></i></div>
            <h3 class="hitech-bento-title">Instant COA &amp; MSDS Access</h3>
            <p class="hitech-bento-desc">Every batch of reagents and culture media comes with official Certificate of Analysis (COA) and MSDS ready for lab validation download.</p>
          </div>
        </div>

        <!-- Bento 3 -->
        <div class="col-lg-6">
          <div class="hitech-bento-card">
            <div class="hitech-bento-number">03</div>
            <div class="hitech-bento-icon"><i class="bi bi-snow"></i></div>
            <h3 class="hitech-bento-title">Safe Cold-Chain Logistics</h3>
            <p class="hitech-bento-desc">Tested cold-chain infrastructure ensuring temperature-sensitive reagents remain stable and active upon arrival at your laboratory.</p>
          </div>
        </div>

        <!-- Bento 4 -->
        <div class="col-lg-6">
          <div class="hitech-bento-card">
            <div class="hitech-bento-number">04</div>
            <div class="hitech-bento-icon"><i class="bi bi-tools"></i></div>
            <h3 class="hitech-bento-title">Integrated After-Sales &amp; Calibration</h3>
            <p class="hitech-bento-desc">Comprehensive equipment qualification (IQ/OQ/PQ), routine calibration services, and technical training by application specialists.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 4. Interactive Sector Finder (Linear Tab Style — High-Tech Interactive) -->
  <section class="section-spacious focus-section-pin">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Sector Solutions</span>
          <h2 class="typo-section-title">Interactive Sector Finder</h2>
          <p class="typo-section-sub text-muted mb-0">Select your industry sector to explore tailored testing workflows and relevant products.</p>
        </div>
      </div>

      <!-- Sector Tabs Bar -->
      <div class="hitech-tab-bar mb-5">
        <button class="hitech-tab-btn active" data-target="pharma">
          <i class="bi bi-capsule me-2"></i> Pharma &amp; Biotech
        </button>
        <button class="hitech-tab-btn" data-target="fnb">
          <i class="bi bi-cup-hot me-2"></i> Food &amp; Beverage
        </button>
        <button class="hitech-tab-btn" data-target="healthcare">
          <i class="bi bi-hospital me-2"></i> Healthcare &amp; Clinical
        </button>
        <button class="hitech-tab-btn" data-target="brewing">
          <i class="bi bi-bezier2 me-2"></i> Brewing &amp; Research
        </button>
      </div>

      <!-- Tab Content Panels -->
      <div class="hitech-tab-panels">
        <!-- Panel 1: Pharma -->
        <div class="hitech-tab-panel active" id="panel-pharma">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6">
              <span class="hitech-panel-tag">PHARMACEUTICAL &amp; COSMETICS</span>
              <h3 class="hitech-panel-title">Endotoxin Testing &amp; <span class="text-accent">Sterilization Validation</span></h3>
              <p class="hitech-panel-desc">LAL Endotoxin Test Kits (Bioendo), SCBI Biological Indicators (Terragene), and Pharmacopoeia-grade culture media for drug &amp; cosmetic QC compliance.</p>
              <div class="d-flex gap-3 mt-4">
                <a href="{{ url('/sektor') }}?s=pharmaceutical#sektor-nav" class="typo-btn-link">Explore Pharma Solutions <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="hitech-panel-box">
                <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Recommended Products &amp; Reagents</div>
                <ul class="hitech-box-list">
                  <li><i class="bi bi-check2 text-accent me-2"></i> LAL Endotoxin Test Reagents</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Self-Contained Biological Indicators</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Ready-to-Use Culture Media Plates</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Cleanroom Environmental Air Samplers</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel 2: FNB -->
        <div class="hitech-tab-panel" id="panel-fnb">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6">
              <span class="hitech-panel-tag">FOOD &amp; BEVERAGE INDUSTRY</span>
              <h3 class="hitech-panel-title">Rapid Pathogen Detection &amp; <span class="text-accent">Hygiene Monitoring</span></h3>
              <p class="hitech-panel-desc">Rapid pathogen detection (Salmonella, Listeria, E. coli) and ATP hygiene indicators ensuring food safety compliance for HACCP &amp; BPOM.</p>
              <div class="d-flex gap-3 mt-4">
                <a href="{{ url('/sektor') }}?s=food#sektor-nav" class="typo-btn-link">Explore F&amp;B Solutions <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="hitech-panel-box">
                <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Recommended Products &amp; Reagents</div>
                <ul class="hitech-box-list">
                  <li><i class="bi bi-check2 text-accent me-2"></i> Rapid Pathogen Test Kits</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> ATP Hygiene Monitoring Systems</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Dip-Slide Microbial Testers</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Automated Media Preparator</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel 3: Healthcare -->
        <div class="hitech-tab-panel" id="panel-healthcare">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6">
              <span class="hitech-panel-tag">HEALTHCARE &amp; HOSPITAL CSSD</span>
              <h3 class="hitech-panel-title">Diagnostics &amp; <span class="text-accent">Sterilization Indicators</span></h3>
              <p class="hitech-panel-desc">Microbial identification, MIC antibiotic susceptibility testing, and chemical/biological indicators for hospital CSSD sterilizers.</p>
              <div class="d-flex gap-3 mt-4">
                <a href="{{ url('/sektor') }}?s=hospital-clinic#sektor-nav" class="typo-btn-link">Explore Healthcare Solutions <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="hitech-panel-box">
                <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Recommended Products &amp; Reagents</div>
                <ul class="hitech-box-list">
                  <li><i class="bi bi-check2 text-accent me-2"></i> MIC Test Strips for AST</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Bowie-Dick Test Packs</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Chromogenic Media for MRSA/VRE</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Microbial Identification Latex Kits</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel 4: Brewing -->
        <div class="hitech-tab-panel" id="panel-brewing">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6">
              <span class="hitech-panel-tag">BREWING &amp; RESEARCH LABS</span>
              <h3 class="hitech-panel-title">Spoilage Control &amp; <span class="text-accent">Fermentation Quality</span></h3>
              <p class="hitech-panel-desc">Specific media for beer spoilage bacteria (Lactobacillus, Pediococcus) and precision liquid handling for R&amp;D molecular biology.</p>
              <div class="d-flex gap-3 mt-4">
                <a href="{{ url('/sektor') }}?s=brewing#sektor-nav" class="typo-btn-link">Explore Brewing Solutions <i class="bi bi-arrow-right ms-1"></i></a>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="hitech-panel-box">
                <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Recommended Products &amp; Reagents</div>
                <ul class="hitech-box-list">
                  <li><i class="bi bi-check2 text-accent me-2"></i> NBB Spoilage Culture Media</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Automated Liquid Handling</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> Reference Standards for QC</li>
                  <li><i class="bi bi-check2 text-accent me-2"></i> BactoBank Preservation System</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. Bestseller Showcase (Clean High-Tech Cards) -->
  <section class="section-spacious typo-products-section" style="border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Featured Products</span>
          <h2 class="typo-section-title">Featured Instruments &amp; Reagents</h2>
          <p class="typo-section-sub text-muted mb-0">High-reliability analytical devices and reagents designed to streamline your laboratory workflow.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/produk') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            View Full Product Catalog <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
          @foreach($featuredProducts as $prod)
            <div class="col">
              <div class="card h-100 product-card-premium border-0">
                <div class="img-wrap">
                  <img src="{{ $prod['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $prod['title'] }}" loading="lazy" decoding="async">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                  @if(!empty($prod['catalog']))
                    <div style="font-size: 0.72rem; color: var(--color-accent); margin-bottom: 6px; font-family: var(--font-headline); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                      <i class="bi bi-upc-scan me-1"></i> Cat. {{ $prod['catalog'] }}
                    </div>
                  @endif
                  <h3 class="card-title fs-6 fw-bold">
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="text-decoration-none" style="color: #fff;">{{ $prod['title'] }}</a>
                  </h3>
                  <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 6px; margin-bottom: 14px; flex-grow: 1;">
                    {{ Str::limit(strip_tags(html_entity_decode($prod['description'] ?? '')), 85) }}
                  </p>

                  <!-- B2B Value Add Badge -->
                  <div class="b2b-product-roi-badge mb-3">
                    <i class="bi bi-check-circle-fill text-accent me-1"></i> CoA Included &bull; High Reliability
                  </div>

                  <a href="{{ url('/produk/detail') }}?id={{ urlencode($prod['title']) }}" class="profil-cta-btn w-100 text-center" style="font-size: 0.75rem;">
                    Specifications &amp; Details <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">Featured products are currently being updated.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- 6. Technical Insights & Articles (Vercel/Linear Minimalist Cards) -->
  <section class="section-spacious typo-news-section">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <span class="typo-section-label">Technical Resources</span>
          <h2 class="typo-section-title">Insights &amp; Laboratory Education</h2>
          <p class="typo-section-sub text-muted mb-0">Testing application guides, ISO/BPOM regulatory updates, and Prolabios activity news.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/informasi') }}" class="typo-btn-link" style="font-size: 0.85rem;">
            View All Articles <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row g-4">
        @if(count($recentPosts) > 0)
          @foreach($recentPosts as $post)
            @php
              $dateParts = explode(' ', $post['date']);
              $day = isset($dateParts[0]) ? $dateParts[0] : '';
              $month = isset($dateParts[1]) ? $dateParts[1] : '';
            @endphp
            <div class="col-lg-4 col-md-12">
              <div class="card typo-blog-card h-100 position-relative">
                <div class="card-body p-0">
                  <span class="typo-blog-card-meta">
                    {{ $post['category'] }} &bull; {{ $day }} {{ $month }}
                  </span>
                  <h3 class="typo-blog-card-title">
                    <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}" class="stretched-link">{{ $post['title'] }}</a>
                  </h3>
                  <p class="typo-blog-card-desc">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 140) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">No recent articles available.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  <!-- 7. Bottom Conversion Banner (Anduril Minimalist Banner) -->
  <section class="hitech-final-banner">
    <div class="container text-center py-4">
      <span class="typo-pill-accent mb-3 d-inline-block">TECHNICAL PROCUREMENT SUPPORT</span>
      <h2 class="hitech-final-title">Require Custom Procurement or Project Quote?</h2>
      <p class="hitech-final-sub">Our application specialists and technical sales team are ready to assist with instrument specifications and reagent availability.</p>
      
      <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mt-4">
        <a href="{{ url('/kontak') }}" class="typo-btn-link px-4 py-3">
          <i class="bi bi-chat-left-text-fill me-2"></i> Contact Sales / Request Quote
        </a>
        <a href="{{ $siteSettings['catalog_pdf_url'] ?? asset('catalog.pdf') }}" target="_blank" class="typo-btn-link typo-btn-link--ghost px-4 py-3">
          <i class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF Catalog
        </a>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Interactive Sector Finder Tab Logic
      const tabBtns = document.querySelectorAll('.hitech-tab-btn');
      const tabPanels = document.querySelectorAll('.hitech-tab-panel');

      tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const target = this.getAttribute('data-target');

          tabBtns.forEach(b => b.classList.remove('active'));
          tabPanels.forEach(p => p.classList.remove('active'));

          this.classList.add('active');
          const activePanel = document.getElementById('panel-' + target);
          if (activePanel) {
            activePanel.classList.add('active');
          }
        });
      });
    });
  </script>
@endpush
