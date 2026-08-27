<!-- 4. Temukan Sektor Industri -->
<section class="section-spacious focus-section-pin">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">{{ $homeData['sector_title'] ?? 'Temukan Sektor Industri' }}</h2>
        <p class="typo-section-sub">{{ $homeData['sector_subtitle'] ?? 'Pilih sektor industri Anda untuk melihat alur pengujian dan produk yang relevan.' }}</p>
      </div>
    </div>

    <!-- Sector Tabs Bar -->
    <div class="hitech-tab-bar mb-5">
      <button class="hitech-tab-btn active" data-target="pharma">
        <i class="bi bi-capsule me-2"></i> Farmasi &amp; Biotech
      </button>
      <button class="hitech-tab-btn" data-target="fnb">
        <i class="bi bi-cup-hot me-2"></i> Makanan &amp; Minuman
      </button>
      <button class="hitech-tab-btn" data-target="healthcare">
        <i class="bi bi-hospital me-2"></i> Kesehatan &amp; Klinis
      </button>
      <button class="hitech-tab-btn" data-target="brewing">
        <i class="bi bi-bezier2 me-2"></i> Brewing &amp; Riset
      </button>
    </div>

    @php
      $sp = $homeData['sector_panels'] ?? [];
    @endphp

    <!-- Tab Content Panels -->
    <div class="hitech-tab-panels">
      <!-- Panel 1: Pharma -->
      @php $ph = $sp['pharma'] ?? []; @endphp
      <div class="hitech-tab-panel active" id="panel-pharma">
        <div class="row g-4 align-items-center">
          <div class="col-lg-6">
            <span class="hitech-panel-tag">{{ $ph['tag'] ?? 'PHARMACEUTICAL & COSMETICS' }}</span>
            <h3 class="hitech-panel-title">{!! $ph['title'] ?? 'Endotoxin Testing & <span class="text-accent">Sterilization Validation</span>' !!}</h3>
            <p class="hitech-panel-desc">{{ $ph['desc'] ?? 'LAL Endotoxin Test Kits (Bioendo), SCBI Biological Indicators (Terragene), and Pharmacopoeia-grade culture media for drug & cosmetic QC compliance.' }}</p>
            <div class="d-flex gap-3 mt-4">
              <a href="{{ url($ph['link'] ?? '/sektor?s=pharmaceutical#sektor-nav') }}" class="typo-btn-link">Jelajahi Solusi Farmasi <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hitech-panel-box">
              <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Produk &amp; Reagen Rekomendasi</div>
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
      @php $fn = $sp['fnb'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-fnb">
        <div class="row g-4 align-items-center">
          <div class="col-lg-6">
            <span class="hitech-panel-tag">{{ $fn['tag'] ?? 'FOOD & BEVERAGE INDUSTRY' }}</span>
            <h3 class="hitech-panel-title">{!! $fn['title'] ?? 'Rapid Pathogen Detection & <span class="text-accent">Hygiene Monitoring</span>' !!}</h3>
            <p class="hitech-panel-desc">{{ $fn['desc'] ?? 'Rapid pathogen detection (Salmonella, Listeria, E. coli) and ATP hygiene indicators ensuring food safety compliance for HACCP & BPOM.' }}</p>
            <div class="d-flex gap-3 mt-4">
              <a href="{{ url($fn['link'] ?? '/sektor?s=food#sektor-nav') }}" class="typo-btn-link">Jelajahi Solusi F&amp;B <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hitech-panel-box">
              <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Produk &amp; Reagen Rekomendasi</div>
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
      @php $hc = $sp['healthcare'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-healthcare">
        <div class="row g-4 align-items-center">
          <div class="col-lg-6">
            <span class="hitech-panel-tag">{{ $hc['tag'] ?? 'HEALTHCARE & HOSPITAL CSSD' }}</span>
            <h3 class="hitech-panel-title">{!! $hc['title'] ?? 'Diagnostics & <span class="text-accent">Sterilization Indicators</span>' !!}</h3>
            <p class="hitech-panel-desc">{{ $hc['desc'] ?? 'Microbial identification, MIC antibiotic susceptibility testing, and chemical/biological indicators for hospital CSSD sterilizers.' }}</p>
            <div class="d-flex gap-3 mt-4">
              <a href="{{ url($hc['link'] ?? '/sektor?s=hospital-clinic#sektor-nav') }}" class="typo-btn-link">Jelajahi Solusi Kesehatan <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hitech-panel-box">
              <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Produk &amp; Reagen Rekomendasi</div>
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
      @php $br = $sp['brewing'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-brewing">
        <div class="row g-4 align-items-center">
          <div class="col-lg-6">
            <span class="hitech-panel-tag">{{ $br['tag'] ?? 'BREWING & RESEARCH LABS' }}</span>
            <h3 class="hitech-panel-title">{!! $br['title'] ?? 'Spoilage Control & <span class="text-accent">Fermentation Quality</span>' !!}</h3>
            <p class="hitech-panel-desc">{{ $br['desc'] ?? 'Specific media for beer spoilage bacteria (Lactobacillus, Pediococcus) and precision liquid handling for R&D molecular biology.' }}</p>
            <div class="d-flex gap-3 mt-4">
              <a href="{{ url($br['link'] ?? '/sektor?s=brewing#sektor-nav') }}" class="typo-btn-link">Jelajahi Solusi Brewing <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hitech-panel-box">
              <div class="hitech-box-header"><i class="bi bi-box-seam me-2"></i> Produk &amp; Reagen Rekomendasi</div>
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
