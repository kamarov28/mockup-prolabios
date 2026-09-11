<!-- 4. Temukan Sektor Industri -->
<section class="section-spacious focus-section-pin">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 mb-md-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">{{ $homeData['sector_title'] ?? 'Temukan Sektor Industri' }}</h2>
        <p class="typo-section-sub">{{ $homeData['sector_subtitle'] ?? 'Pilih sektor industri Anda untuk melihat alur pengujian dan produk yang relevan.' }}</p>
      </div>
    </div>

    @php
      $sp = $homeData['sector_panels'] ?? [];
      $sectors = [
        'pharma' => [
          'label' => 'Farmasi & Biotech',
          'icon' => 'pill',
          'tag' => $sp['pharma']['tag'] ?? 'FARMASI & KOSMETIK',
          'title' => $sp['pharma']['title'] ?? 'Pengujian Endotoksin & <span class="text-accent">Validasi Sterilisasi</span>',
          'desc' => $sp['pharma']['desc'] ?? 'Kit Uji Endotoksin LAL (Bioendo), Indikator Biologi SCBI (Terragene), serta media kultur standar farmakope untuk kepatuhan QC obat & kosmetik.',
          'link' => $sp['pharma']['link'] ?? '/sektor?s=pharmaceutical#sektor-nav',
          'linkText' => 'Jelajahi Solusi Farmasi',
          'compliance' => 'USP / EP / BP Compliant',
          'cat' => 'CAT. BIO-TAL01',
          'brand' => 'Bioendo Reagents',
          'badge' => 'Ready Stock',
          'badgeIcon' => 'check',
          'prodTitle' => 'Gel Clot Lyophilized Amebocyte Lysate (TAL/LAL)',
          'prodDesc' => 'Reagen sensitivitas tinggi (0.03 – 0.25 EU/ml) untuk deteksi cepat endotoksin bakteri pada sediaan farmasi injeksi, air WFI, dan alat kesehatan steril.',
          'stat1_label' => 'Sensitivitas',
          'stat1_val' => '0.03 EU/mL',
          'stat1_class' => 'fw-bold small nb-mono',
          'stat2_label' => 'Kemasan',
          'stat2_val' => '5.2 mL / Vial',
          'stat2_class' => 'fw-bold small nb-mono',
          'certIcon' => 'file-check',
          'cert' => 'Sertifikat COA per batch',
          'rfqLink' => url('/produk?q=endotoxin'),
          'rfqAria' => 'Ajukan RFQ produk reagen endotoksin',
        ],
        'fnb' => [
          'label' => 'Makanan & Minuman',
          'icon' => 'coffee',
          'tag' => $sp['fnb']['tag'] ?? 'INDUSTRI MAKANAN & MINUMAN',
          'title' => $sp['fnb']['title'] ?? 'Deteksi Cepat Patogen & <span class="text-accent">Monitoring Higiene</span>',
          'desc' => $sp['fnb']['desc'] ?? 'Deteksi cepat patogen pangan (Salmonella, Listeria, E. coli) dan indikator higiene ATP untuk memastikan kepatuhan standar HACCP & BPOM.',
          'link' => $sp['fnb']['link'] ?? '/sektor?s=food#sektor-nav',
          'linkText' => 'Jelajahi Solusi F&B',
          'compliance' => 'HACCP & ISO 22000',
          'cat' => 'CAT. SCH-MEDIA02',
          'brand' => 'Scharlau Microbiology',
          'badge' => 'Ready Stock',
          'badgeIcon' => 'check',
          'prodTitle' => 'Chromogenic Media for Salmonella & E. coli',
          'prodDesc' => 'Media kultur selektif diferensiasi warna spesifik untuk identifikasi koloni patogen makanan dalam 24 jam dengan akurasi isolasi tinggi.',
          'stat1_label' => 'Inkubasi',
          'stat1_val' => '24 Jam (37°C)',
          'stat1_class' => 'fw-bold small nb-mono',
          'stat2_label' => 'Bentuk',
          'stat2_val' => 'Dehydrated / Ready Plate',
          'stat2_class' => 'fw-bold small',
          'certIcon' => 'shield-check',
          'cert' => 'BPOM Food Standard',
          'rfqLink' => url('/produk?q=salmonella'),
          'rfqAria' => 'Ajukan RFQ media kromogenik salmonella',
        ],
        'healthcare' => [
          'label' => 'Kesehatan & Klinis',
          'icon' => 'hospital',
          'tag' => $sp['healthcare']['tag'] ?? 'KESEHATAN & CSSD RUMAH SAKIT',
          'title' => $sp['healthcare']['title'] ?? 'Diagnostik & <span class="text-accent">Indikator Sterilisasi</span>',
          'desc' => $sp['healthcare']['desc'] ?? 'Identifikasi mikroba, uji sensitivitas antibiotik MIC, serta indikator kimia & biologi untuk sterilisator CSSD rumah sakit.',
          'link' => $sp['healthcare']['link'] ?? '/sektor?s=hospital-clinic#sektor-nav',
          'linkText' => 'Jelajahi Solusi Kesehatan',
          'compliance' => 'AKL Kemenkes RI',
          'cat' => 'CAT. TER-BT20',
          'brand' => 'Terragene Bionova',
          'badge' => 'AKL Certified',
          'badgeIcon' => 'badge-check',
          'prodTitle' => 'Self-Contained Biological Indicator (SCBI) Steam',
          'prodDesc' => 'Indikator biologi Geobacillus stearothermophilus untuk monitoring sterilisasi uap CSSD rumah sakit dengan pembacaan cepat 24 jam.',
          'stat1_label' => 'Organisme',
          'stat1_val' => 'G. stearothermophilus',
          'stat1_class' => 'fw-bold small fst-italic',
          'stat2_label' => 'Populasi Spora',
          'stat2_val' => '> 10^6 CFU',
          'stat2_class' => 'fw-bold small nb-mono',
          'certIcon' => 'badge-check',
          'cert' => 'Kemenkes AKL Resmi',
          'rfqLink' => url('/produk?q=indicator'),
          'rfqAria' => 'Ajukan RFQ indikator biologi SCBI',
        ],
        'brewing' => [
          'label' => 'Brewing & Riset',
          'icon' => 'beaker',
          'tag' => $sp['brewing']['tag'] ?? 'INDUSTRI BREWING & RISET',
          'title' => $sp['brewing']['title'] ?? 'Kontrol Pembusukan & <span class="text-accent">Kualitas Fermentasi</span>',
          'desc' => $sp['brewing']['desc'] ?? 'Media spesifik bakteri pembusuk bir (Lactobacillus, Pediococcus) dan penanganan cairan presisi untuk riset biologi molekuler.',
          'link' => $sp['brewing']['link'] ?? '/sektor?s=brewing#sektor-nav',
          'linkText' => 'Jelajahi Solusi Brewing',
          'compliance' => 'R&D Quality Control',
          'cat' => 'CAT. DOH-NBB01',
          'brand' => 'Döhler NBB Diagnostics',
          'badge' => 'Ready Stock',
          'badgeIcon' => 'check',
          'prodTitle' => 'NBB®-A Agar for Spoilage Microorganisms',
          'prodDesc' => 'Media deteksi selektif spesifik untuk isolasi bakteri pembusuk bir dan fermentasi (Lactobacillus & Pediococcus) tanpa gangguan ragi kultur.',
          'stat1_label' => 'Deteksi Target',
          'stat1_val' => 'Lactobacillus / Pediococcus',
          'stat1_class' => 'fw-bold small',
          'stat2_label' => 'Format',
          'stat2_val' => 'Solid Ready Agar',
          'stat2_class' => 'fw-bold small',
          'certIcon' => 'file-check',
          'cert' => 'Brewing Lab Protocol',
          'rfqLink' => url('/produk?q=nbb'),
          'rfqAria' => 'Ajukan RFQ media NBB agar brewing',
        ],
      ];
    @endphp

    <!-- Sector Tabs Bar (Responsive) -->
    <div class="hitech-tab-bar mb-4 mb-md-5" role="tablist" aria-label="Pilihan Sektor Industri">
      @foreach($sectors as $id => $sec)
        <button class="hitech-tab-btn {{ $loop->first ? 'active' : '' }}" role="tab" id="tab-{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="panel-{{ $id }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-target="{{ $id }}">
          <i data-lucide="{{ $sec['icon'] }}" class="me-2" aria-hidden="true"></i> {{ $sec['label'] }}
        </button>
      @endforeach
    </div>

    <!-- Tab Content Panels (Interactive Spec Cards) -->
    <div class="hitech-tab-panels">
      @foreach($sectors as $id => $sec)
        <div class="hitech-tab-panel {{ $loop->first ? 'active' : '' }}" id="panel-{{ $id }}" role="tabpanel" aria-labelledby="tab-{{ $id }}" tabindex="0">
          <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
              <div class="hitech-info-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
                <div>
                  <span class="hitech-panel-tag">{{ $sec['tag'] }}</span>
                  <h3 class="hitech-panel-title">{!! $sec['title'] !!}</h3>
                  <p class="hitech-panel-desc">{{ $sec['desc'] }}</p>
                </div>
                <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top align-items-center">
                  <a href="{{ url($sec['link']) }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
                    {{ $sec['linkText'] }} <i data-lucide="arrow-right"></i>
                  </a>
                  <span class="nb-badge-sm"><i data-lucide="badge-check" class="text-primary me-1"></i> {{ $sec['compliance'] }}</span>
                </div>
              </div>
            </div>

            <!-- Interactive Spec Card Preview -->
            <div class="col-lg-6">
              <div class="hitech-spec-card p-4 rounded-3 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider">
                    <div class="d-flex align-items-center gap-2">
                      <span class="product-cat-code">{{ $sec['cat'] }}</span>
                      <span class="text-muted small">{{ $sec['brand'] }}</span>
                    </div>
                    <span class="nb-badge-sm" style="color: #1E1E1E;"><i data-lucide="{{ $sec['badgeIcon'] }}" class="me-1" style="color:#A6171C;"></i> {{ $sec['badge'] }}</span>
                  </div>

                  <h4 class="fs-6 fw-semibold mb-2">{{ $sec['prodTitle'] }}</h4>
                  <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                    {{ $sec['prodDesc'] }}
                  </p>

                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="hitech-spec-stat p-2 rounded">
                        <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">{{ $sec['stat1_label'] }}</div>
                        <div class="{{ $sec['stat1_class'] }}">{{ $sec['stat1_val'] }}</div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="hitech-spec-stat p-2 rounded">
                        <div class="text-muted" style="font-size: 0.72rem; font-weight: 600;">{{ $sec['stat2_label'] }}</div>
                        <div class="{{ $sec['stat2_class'] }}">{{ $sec['stat2_val'] }}</div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider">
                  <span class="text-muted" style="font-size: 0.78rem; font-weight: 500;"><i data-lucide="{{ $sec['certIcon'] }}" class="text-primary me-1"></i> {{ $sec['cert'] }}</span>
                  <a href="{{ $sec['rfqLink'] }}" class="nb-btn nb-btn-primary" style="font-size: 0.8rem; padding: 0.45rem 0.9rem;" aria-label="{{ $sec['rfqAria'] }}">
                    <i data-lucide="shopping-cart"></i> Tambah RFQ
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
