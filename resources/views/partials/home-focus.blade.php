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

    <!-- Tab Content Panels (Interactive Spec Card) -->
    <div class="hitech-tab-panels">
      <!-- Panel 1: Pharma -->
      @php $ph = $sp['pharma'] ?? []; @endphp
      <div class="hitech-tab-panel active" id="panel-pharma">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6 d-flex flex-column justify-content-between">
            <div>
              <span class="hitech-panel-tag">{{ $ph['tag'] ?? 'PHARMACEUTICAL & COSMETICS' }}</span>
              <h3 class="hitech-panel-title">{!! $ph['title'] ?? 'Endotoxin Testing & <span class="text-accent">Sterilization Validation</span>' !!}</h3>
              <p class="hitech-panel-desc">{{ $ph['desc'] ?? 'LAL Endotoxin Test Kits (Bioendo), SCBI Biological Indicators (Terragene), and Pharmacopoeia-grade culture media for drug & cosmetic QC compliance.' }}</p>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
              <a href="{{ url($ph['link'] ?? '/sektor?s=pharmaceutical#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                Jelajahi Solusi Farmasi <i class="bi bi-arrow-right"></i>
              </a>
              <span class="text-muted small font-monospace"><i class="bi bi-patch-check text-accent me-1"></i> USP / EP / BP Compliant</span>
            </div>
          </div>

          <!-- Interactive Spec Card Preview -->
          <div class="col-lg-6">
            <div class="hitech-spec-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider">
                  <div class="d-flex align-items-center gap-2">
                    <span class="product-cat-code">CAT. BIO-TAL01</span>
                    <span class="text-muted small">Bioendo Reagents</span>
                  </div>
                  <span class="nb-badge-sm" style="color: #1E1E1E;"><i class="bi bi-check2 me-1" style="color:#A6171C;"></i> Ready Stock</span>
                </div>

                <h4 class="fs-6 fw-semibold mb-2">Gel Clot Lyophilized Amebocyte Lysate (TAL/LAL)</h4>
                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                  Reagen sensitivitas tinggi (0.03 – 0.25 EU/ml) untuk deteksi cepat endotoksin bakteri pada sediaan farmasi injeksi, air WFI, dan alat kesehatan steril.
                </p>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Sensitivitas</div>
                      <div class="fw-medium small">0.03 EU/mL</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Kemasan</div>
                      <div class="fw-medium small">5.2 mL / Vial</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted font-monospace" style="font-size: 0.75rem;">Sertifikat COA per batch</span>
                <a href="{{ url('/produk?q=endotoxin') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 2: FNB -->
      @php $fn = $sp['fnb'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-fnb">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6 d-flex flex-column justify-content-between">
            <div>
              <span class="hitech-panel-tag">{{ $fn['tag'] ?? 'FOOD & BEVERAGE INDUSTRY' }}</span>
              <h3 class="hitech-panel-title">{!! $fn['title'] ?? 'Rapid Pathogen Detection & <span class="text-accent">Hygiene Monitoring</span>' !!}</h3>
              <p class="hitech-panel-desc">{{ $fn['desc'] ?? 'Rapid pathogen detection (Salmonella, Listeria, E. coli) and ATP hygiene indicators ensuring food safety compliance for HACCP & BPOM.' }}</p>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
              <a href="{{ url($fn['link'] ?? '/sektor?s=food#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                Jelajahi Solusi F&amp;B <i class="bi bi-arrow-right"></i>
              </a>
              <span class="text-muted small font-monospace"><i class="bi bi-patch-check text-accent me-1"></i> HACCP &amp; ISO 22000</span>
            </div>
          </div>

          <!-- Interactive Spec Card Preview -->
          <div class="col-lg-6">
            <div class="hitech-spec-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider">
                  <div class="d-flex align-items-center gap-2">
                    <span class="product-cat-code">CAT. SCH-MEDIA02</span>
                    <span class="text-muted small">Scharlau Microbiology</span>
                  </div>
                  <span class="nb-badge-sm" style="color: #1E1E1E;"><i class="bi bi-check2 me-1" style="color:#A6171C;"></i> Ready Stock</span>
                </div>

                <h4 class="fs-6 fw-semibold mb-2">Chromogenic Media for Salmonella &amp; E. coli</h4>
                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                  Media kultur selektif diferensiasi warna spesifik untuk identifikasi koloni patogen makanan dalam 24 jam dengan akurasi isolasi tinggi.
                </p>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Inkubasi</div>
                      <div class="fw-medium small">24 Jam (37°C)</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Bentuk</div>
                      <div class="fw-medium small">Dehydrated / Ready Plate</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted font-monospace" style="font-size: 0.75rem;">BPOM Food Standard</span>
                <a href="{{ url('/produk?q=salmonella') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 3: Healthcare -->
      @php $hc = $sp['healthcare'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-healthcare">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6 d-flex flex-column justify-content-between">
            <div>
              <span class="hitech-panel-tag">{{ $hc['tag'] ?? 'HEALTHCARE & HOSPITAL CSSD' }}</span>
              <h3 class="hitech-panel-title">{!! $hc['title'] ?? 'Diagnostics & <span class="text-accent">Sterilization Indicators</span>' !!}</h3>
              <p class="hitech-panel-desc">{{ $hc['desc'] ?? 'Microbial identification, MIC antibiotic susceptibility testing, and chemical/biological indicators for hospital CSSD sterilizers.' }}</p>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
              <a href="{{ url($hc['link'] ?? '/sektor?s=hospital-clinic#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                Jelajahi Solusi Kesehatan <i class="bi bi-arrow-right"></i>
              </a>
              <span class="text-muted small font-monospace"><i class="bi bi-patch-check text-accent me-1"></i> AKL Kemenkes RI</span>
            </div>
          </div>

          <!-- Interactive Spec Card Preview -->
          <div class="col-lg-6">
            <div class="hitech-spec-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider">
                  <div class="d-flex align-items-center gap-2">
                    <span class="product-cat-code">CAT. TER-BT20</span>
                    <span class="text-muted small">Terragene Bionova</span>
                  </div>
                  <span class="nb-badge-sm" style="color: #1E1E1E;"><i class="bi bi-patch-check me-1" style="color:#A6171C;"></i> AKL Certified</span>
                </div>

                <h4 class="fs-6 fw-semibold mb-2">Self-Contained Biological Indicator (SCBI) Steam</h4>
                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                  Indikator biologi Geobacillus stearothermophilus untuk monitoring sterilisasi uap CSSD rumah sakit dengan pembacaan cepat 24 jam.
                </p>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Organisme</div>
                      <div class="fw-medium small">G. stearothermophilus</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Populasi Spora</div>
                      <div class="fw-medium small">&gt; 10^6 CFU</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted font-monospace" style="font-size: 0.75rem;">Kemenkes AKL Resmi</span>
                <a href="{{ url('/produk?q=indicator') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 4: Brewing -->
      @php $br = $sp['brewing'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-brewing">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6 d-flex flex-column justify-content-between">
            <div>
              <span class="hitech-panel-tag">{{ $br['tag'] ?? 'BREWING & RESEARCH LABS' }}</span>
              <h3 class="hitech-panel-title">{!! $br['title'] ?? 'Spoilage Control & <span class="text-accent">Fermentation Quality</span>' !!}</h3>
              <p class="hitech-panel-desc">{{ $br['desc'] ?? 'Specific media for beer spoilage bacteria (Lactobacillus, Pediococcus) and precision liquid handling for R&D molecular biology.' }}</p>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
              <a href="{{ url($br['link'] ?? '/sektor?s=brewing#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                Jelajahi Solusi Brewing <i class="bi bi-arrow-right"></i>
              </a>
              <span class="text-muted small font-monospace"><i class="bi bi-patch-check text-accent me-1"></i> R&amp;D Quality Control</span>
            </div>
          </div>

          <!-- Interactive Spec Card Preview -->
          <div class="col-lg-6">
            <div class="hitech-spec-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider">
                  <div class="d-flex align-items-center gap-2">
                    <span class="product-cat-code">CAT. DOH-NBB01</span>
                    <span class="text-muted small">Döhler NBB Diagnostics</span>
                  </div>
                  <span class="nb-badge-sm" style="color: #1E1E1E;"><i class="bi bi-check2 me-1" style="color:#A6171C;"></i> Ready Stock</span>
                </div>

                <h4 class="fs-6 fw-semibold mb-2">NBB®-A Agar for Spoilage Microorganisms</h4>
                <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                  Media deteksi selektif spesifik untuk isolasi bakteri pembusuk bir dan fermentasi (Lactobacillus &amp; Pediococcus) tanpa gangguan ragi kultur.
                </p>

                <div class="row g-2 mb-3">
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Deteksi Target</div>
                      <div class="fw-medium small">Lactobacillus / Pediococcus</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted font-monospace" style="font-size: 0.7rem;">Format</div>
                      <div class="fw-medium small">Solid Ready Agar</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted font-monospace" style="font-size: 0.75rem;">Brewing Lab Protocol</span>
                <a href="{{ url('/produk?q=nbb') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
