<!-- 4. Temukan Sektor Industri -->
<section class="section-spacious focus-section-pin">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 mb-md-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">{{ $homeData['sector_title'] ?? 'Temukan Sektor Industri' }}</h2>
        <p class="typo-section-sub">{{ $homeData['sector_subtitle'] ?? 'Pilih sektor industri Anda untuk melihat alur pengujian dan produk yang relevan.' }}</p>
      </div>
    </div>

    <!-- Sector Tabs Bar (Responsive: Full-width stacked on mobile, 2x2 on tablet, inline on desktop) -->
    <div class="hitech-tab-bar mb-4 mb-md-5" role="tablist" aria-label="Pilihan Sektor Industri">
      <button class="hitech-tab-btn active" role="tab" id="tab-pharma" aria-selected="true" aria-controls="panel-pharma" tabindex="0" data-target="pharma">
        <i class="bi bi-capsule me-2" aria-hidden="true"></i> Farmasi &amp; Biotech
      </button>
      <button class="hitech-tab-btn" role="tab" id="tab-fnb" aria-selected="false" aria-controls="panel-fnb" tabindex="-1" data-target="fnb">
        <i class="bi bi-cup-hot me-2" aria-hidden="true"></i> Makanan &amp; Minuman
      </button>
      <button class="hitech-tab-btn" role="tab" id="tab-healthcare" aria-selected="false" aria-controls="panel-healthcare" tabindex="-1" data-target="healthcare">
        <i class="bi bi-hospital me-2" aria-hidden="true"></i> Kesehatan &amp; Klinis
      </button>
      <button class="hitech-tab-btn" role="tab" id="tab-brewing" aria-selected="false" aria-controls="panel-brewing" tabindex="-1" data-target="brewing">
        <i class="bi bi-bezier2 me-2" aria-hidden="true"></i> Brewing &amp; Riset
      </button>
    </div>

    @push('styles')
    <style>
      .focus-section-pin .hitech-tab-bar {
        border: 2px solid #1E1E1E !important;
        border-radius: 8px !important;
        background: #FFFFFF !important;
        padding: 6px !important;
        box-shadow: 4px 4px 0 #1E1E1E !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
        width: 100% !important;
      }
      .focus-section-pin .hitech-tab-btn {
        border: 2px solid #1E1E1E !important;
        border-radius: 6px !important;
        background: #FFFFFF !important;
        color: #1E1E1E !important;
        font-family: var(--font-headline, 'Bricolage Grotesque', sans-serif) !important;
        font-weight: 700 !important;
        padding: 9px 18px !important;
        box-shadow: 2px 2px 0 #1E1E1E !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        transition: transform 0.12s ease, box-shadow 0.12s ease, background 0.12s ease !important;
      }
      .focus-section-pin .hitech-tab-btn:hover {
        background: #FEFEFE !important;
        transform: translate(1px, 1px) !important;
        box-shadow: 1px 1px 0 #1E1E1E !important;
      }
      .focus-section-pin .hitech-tab-btn:active {
        transform: translate(2px, 2px) !important;
        box-shadow: 0 0 0 #1E1E1E !important;
      }
      .focus-section-pin .hitech-tab-btn.active {
        background: #F1C045 !important;
        border-color: #1E1E1E !important;
        color: #1E1E1E !important;
        transform: translate(2px, 2px) !important;
        box-shadow: 0 0 0 #1E1E1E !important;
      }
      .focus-section-pin .hitech-tab-btn.active:hover {
        background: #e6b233 !important;
        transform: translate(2px, 2px) !important;
        box-shadow: 0 0 0 #1E1E1E !important;
      }

      /* Tablet view (576px - 991.98px): Equal 2x2 grid */
      @media (min-width: 576px) and (max-width: 991.98px) {
        .focus-section-pin .hitech-tab-bar {
          display: grid !important;
          grid-template-columns: repeat(2, 1fr) !important;
          gap: 8px !important;
          padding: 8px !important;
        }
        .focus-section-pin .hitech-tab-btn {
          width: 100% !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          text-align: center !important;
          padding: 10px 8px !important;
          font-size: 0.85rem !important;
        }
      }

      /* Mobile Phone (< 576px): Full-width orderly vertical stack */
      @media (max-width: 575.98px) {
        .focus-section-pin .hitech-tab-bar {
          display: flex !important;
          flex-direction: column !important;
          gap: 7px !important;
          padding: 8px !important;
          box-shadow: 3px 3px 0 #1E1E1E !important;
        }
        .focus-section-pin .hitech-tab-btn {
          width: 100% !important;
          display: flex !important;
          align-items: center !important;
          justify-content: flex-start !important;
          text-align: left !important;
          padding: 11px 14px !important;
          font-size: 0.88rem !important;
          min-height: 46px !important;
        }
      }

      /* Consistent card styling for both Left (Info) and Right (Spec) cards */
      .focus-section-pin .hitech-info-card,
      .focus-section-pin .hitech-spec-card {
        background: #FFFFFF !important;
        border: 2px solid #1E1E1E !important;
        border-radius: 8px !important;
        box-shadow: 4px 4px 0 #1E1E1E !important;
        width: 100% !important;
        box-sizing: border-box !important;
      }
      .focus-section-pin .hitech-info-card {
        background: #FEFEFE !important;
      }
      .focus-section-pin .hitech-tab-panel.active > .row > .col-lg-6:first-child {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding-left: calc(var(--bs-gutter-x) * 0.5) !important;
        padding-right: calc(var(--bs-gutter-x) * 0.5) !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
      }
      @media (max-width: 575.98px) {
        .focus-section-pin .hitech-info-card,
        .focus-section-pin .hitech-spec-card {
          padding: 1.25rem !important;
          box-shadow: 3px 3px 0 #1E1E1E !important;
        }
      }
    </style>
    @endpush

    @php
      $sp = $homeData['sector_panels'] ?? [];
    @endphp

    <!-- Tab Content Panels (Interactive Spec Card) -->
    <div class="hitech-tab-panels">
      <!-- Panel 1: Pharma -->
      @php $ph = $sp['pharma'] ?? []; @endphp
      <div class="hitech-tab-panel active" id="panel-pharma" role="tabpanel" aria-labelledby="tab-pharma" tabindex="0">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="hitech-info-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="hitech-panel-tag">{{ $ph['tag'] ?? 'FARMASI & KOSMETIK' }}</span>
                <h3 class="hitech-panel-title">{!! $ph['title'] ?? 'Pengujian Endotoksin & <span class="text-accent">Validasi Sterilisasi</span>' !!}</h3>
                <p class="hitech-panel-desc">{{ $ph['desc'] ?? 'Kit Uji Endotoksin LAL (Bioendo), Indikator Biologi SCBI (Terragene), serta media kultur standar farmakope untuk kepatuhan QC obat & kosmetik.' }}</p>
              </div>
              <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
                <a href="{{ url($ph['link'] ?? '/sektor?s=pharmaceutical#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                  Jelajahi Solusi Farmasi <i class="bi bi-arrow-right"></i>
                </a>
                <span class="nb-badge-sm"><i class="bi bi-patch-check-fill text-primary me-1"></i> USP / EP / BP Compliant</span>
              </div>
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
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Sensitivitas</div>
                      <div class="fw-bold small nb-mono">0.03 EU/mL</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Kemasan</div>
                      <div class="fw-bold small nb-mono">5.2 mL / Vial</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted" style="font-size: 0.78rem; font-weight: 500;"><i class="bi bi-file-earmark-check text-primary me-1"></i> Sertifikat COA per batch</span>
                <a href="{{ url('/produk?q=endotoxin') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;" aria-label="Ajukan RFQ produk reagen endotoksin">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 2: FNB -->
      @php $fn = $sp['fnb'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-fnb" role="tabpanel" aria-labelledby="tab-fnb" tabindex="0">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="hitech-info-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="hitech-panel-tag">{{ $fn['tag'] ?? 'INDUSTRI MAKANAN & MINUMAN' }}</span>
                <h3 class="hitech-panel-title">{!! $fn['title'] ?? 'Deteksi Cepat Patogen & <span class="text-accent">Monitoring Higiene</span>' !!}</h3>
                <p class="hitech-panel-desc">{{ $fn['desc'] ?? 'Deteksi cepat patogen pangan (Salmonella, Listeria, E. coli) dan indikator higiene ATP untuk memastikan kepatuhan standar HACCP & BPOM.' }}</p>
              </div>
              <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
                <a href="{{ url($fn['link'] ?? '/sektor?s=food#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                  Jelajahi Solusi F&amp;B <i class="bi bi-arrow-right"></i>
                </a>
                <span class="nb-badge-sm"><i class="bi bi-patch-check-fill text-primary me-1"></i> HACCP &amp; ISO 22000</span>
              </div>
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
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Inkubasi</div>
                      <div class="fw-bold small nb-mono">24 Jam (37°C)</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Bentuk</div>
                      <div class="fw-bold small">Dehydrated / Ready Plate</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted" style="font-size: 0.78rem; font-weight: 500;"><i class="bi bi-shield-check text-primary me-1"></i> BPOM Food Standard</span>
                <a href="{{ url('/produk?q=salmonella') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;" aria-label="Ajukan RFQ media kromogenik salmonella">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 3: Healthcare -->
      @php $hc = $sp['healthcare'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-healthcare" role="tabpanel" aria-labelledby="tab-healthcare" tabindex="0">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="hitech-info-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="hitech-panel-tag">{{ $hc['tag'] ?? 'KESEHATAN & CSSD RUMAH SAKIT' }}</span>
                <h3 class="hitech-panel-title">{!! $hc['title'] ?? 'Diagnostik & <span class="text-accent">Indikator Sterilisasi</span>' !!}</h3>
                <p class="hitech-panel-desc">{{ $hc['desc'] ?? 'Identifikasi mikroba, uji sensitivitas antibiotik MIC, serta indikator kimia & biologi untuk sterilisator CSSD rumah sakit.' }}</p>
              </div>
              <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
                <a href="{{ url($hc['link'] ?? '/sektor?s=hospital-clinic#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                  Jelajahi Solusi Kesehatan <i class="bi bi-arrow-right"></i>
                </a>
                <span class="nb-badge-sm"><i class="bi bi-patch-check-fill text-primary me-1"></i> AKL Kemenkes RI</span>
              </div>
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
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Organisme</div>
                      <div class="fw-bold small fst-italic">G. stearothermophilus</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Populasi Spora</div>
                      <div class="fw-bold small nb-mono">&gt; 10^6 CFU</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted" style="font-size: 0.78rem; font-weight: 500;"><i class="bi bi-patch-check text-primary me-1"></i> Kemenkes AKL Resmi</span>
                <a href="{{ url('/produk?q=indicator') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;" aria-label="Ajukan RFQ indikator biologi SCBI">
                  <i class="bi bi-cart-plus"></i> Tambah RFQ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel 4: Brewing -->
      @php $br = $sp['brewing'] ?? []; @endphp
      <div class="hitech-tab-panel" id="panel-brewing" role="tabpanel" aria-labelledby="tab-brewing" tabindex="0">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="hitech-info-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="hitech-panel-tag">{{ $br['tag'] ?? 'INDUSTRI BREWING & RISET' }}</span>
                <h3 class="hitech-panel-title">{!! $br['title'] ?? 'Kontrol Pembusukan & <span class="text-accent">Kualitas Fermentasi</span>' !!}</h3>
                <p class="hitech-panel-desc">{{ $br['desc'] ?? 'Media spesifik bakteri pembusuk bir (Lactobacillus, Pediococcus) dan penanganan cairan presisi untuk riset biologi molekuler.' }}</p>
              </div>
              <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
                <a href="{{ url($br['link'] ?? '/sektor?s=brewing#sektor-nav') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                  Jelajahi Solusi Brewing <i class="bi bi-arrow-right"></i>
                </a>
                <span class="nb-badge-sm"><i class="bi bi-patch-check-fill text-primary me-1"></i> R&amp;D Quality Control</span>
              </div>
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
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Deteksi Target</div>
                      <div class="fw-bold small">Lactobacillus / Pediococcus</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="hitech-spec-stat p-2 rounded">
                      <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">Format</div>
                      <div class="fw-bold small">Solid Ready Agar</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                <span class="text-muted" style="font-size: 0.78rem; font-weight: 500;"><i class="bi bi-journal-check text-primary me-1"></i> Brewing Lab Protocol</span>
                <a href="{{ url('/produk?q=nbb') }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;" aria-label="Ajukan RFQ media NBB agar brewing">
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
