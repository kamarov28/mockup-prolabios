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
    <div class="hitech-tab-bar mb-5" role="tablist" aria-label="Pilihan Sektor Industri">
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

    @php
      $sp = $homeData['sector_panels'] ?? [];
    @endphp

    <!-- Tab Content Panels (Interactive Spec Card) -->
    <div class="hitech-tab-panels">
      <!-- Panel 1: Pharma -->
      @php $ph = $sp['pharma'] ?? []; @endphp
      <div class="hitech-tab-panel active" id="panel-pharma" role="tabpanel" aria-labelledby="tab-pharma" tabindex="0">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6 d-flex flex-column justify-content-between">
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
          <div class="col-lg-6 d-flex flex-column justify-content-between">
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
          <div class="col-lg-6 d-flex flex-column justify-content-between">
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
          <div class="col-lg-6 d-flex flex-column justify-content-between">
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
