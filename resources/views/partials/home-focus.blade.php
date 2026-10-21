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
      $defaultScopes = [
        'pharma' => "Pengujian Endotoksin Bakteri (Metode Gel Clot, Turbidimetri & Kromogenik)\nValidasi Siklus Sterilisasi Uap, EtO & Indikator Biologi SCBI\nMedia Uji Batas Mikroba & Monitoring Ruang Bersih (Cleanroom)",
        'fnb' => "Deteksi Cepat Patogen Kritis (Salmonella, Listeria, E. coli, Coliform)\nMonitoring Higiene Permukaan & Uji Cepat Sanitasi ATP Swab\nMedia Kultur Kromogenik & Analisis Kapang Khamir Pangan",
        'healthcare' => "Monitoring Efektivitas Sterilisasi CSSD (Steam, VH2O2, EO Plasma)\nMedia Kultur Diagnostik & Uji Kepekaan Antibiotik (MIC)\nReagen Pewarnaan & Bahan Habis Pakai Laboratorium Klinik",
        'brewing' => "Deteksi Selektif Bakteri Pembusuk Bir (Lactobacillus & Pediococcus)\nMonitoring Kemurnian Ragi & Deteksi Kontaminasi Mikroba Liar\nPengujian Mikrobiologi Air Proses & Sanitasi Jalur Produksi",
      ];
      $defaultCompliance = [
        'pharma' => 'USP / EP / BP / CPOB Compliant',
        'fnb' => 'HACCP & Standar BPOM',
        'healthcare' => 'Izin Edar AKL Kemenkes RI',
        'brewing' => 'Standar QC Brewing & ISO',
      ];

      $sectors = [
        'pharma' => [
          'label' => 'Farmasi & Biotech',
          'icon' => 'pill',
          'tag' => !empty($sp['pharma']['tag']) ? $sp['pharma']['tag'] : 'FARMASI & KOSMETIK',
          'title' => !empty($sp['pharma']['title']) ? $sp['pharma']['title'] : 'Uji Endotoksin & <span class="text-accent">Validasi Sterilisasi</span>',
          'desc' => !empty($sp['pharma']['desc']) ? $sp['pharma']['desc'] : 'Solusi pengujian pirogenitas, kit uji endotoksin bakteri LAL/TAL, indikator biologi SCBI, dan media kultur terstandarisasi untuk kendali mutu CPOB/GMP.',
          'link' => !empty($sp['pharma']['link']) ? $sp['pharma']['link'] : '/sektor?s=pharmaceutical#sektor-nav',
          'scope' => !empty($sp['pharma']['scope']) ? $sp['pharma']['scope'] : $defaultScopes['pharma'],
          'compliance' => !empty($sp['pharma']['compliance']) ? $sp['pharma']['compliance'] : $defaultCompliance['pharma'],
        ],
        'fnb' => [
          'label' => 'Makanan & Minuman',
          'icon' => 'coffee',
          'tag' => !empty($sp['fnb']['tag']) ? $sp['fnb']['tag'] : 'INDUSTRI MAKANAN & MINUMAN',
          'title' => !empty($sp['fnb']['title']) ? $sp['fnb']['title'] : 'Deteksi Cepat Patogen & <span class="text-accent">Monitoring Higiene</span>',
          'desc' => !empty($sp['fnb']['desc']) ? $sp['fnb']['desc'] : 'Deteksi cepat patogen pangan dan sistem pemantauan sanitasi permukaan untuk memastikan keamanan rantai produksi pangan sesuai standar HACCP & BPOM.',
          'link' => !empty($sp['fnb']['link']) ? $sp['fnb']['link'] : '/sektor?s=food#sektor-nav',
          'scope' => !empty($sp['fnb']['scope']) ? $sp['fnb']['scope'] : $defaultScopes['fnb'],
          'compliance' => !empty($sp['fnb']['compliance']) ? $sp['fnb']['compliance'] : $defaultCompliance['fnb'],
        ],
        'healthcare' => [
          'label' => 'Kesehatan & Klinis',
          'icon' => 'hospital',
          'tag' => !empty($sp['healthcare']['tag']) ? $sp['healthcare']['tag'] : 'KESEHATAN & CSSD RUMAH SAKIT',
          'title' => !empty($sp['healthcare']['title']) ? $sp['healthcare']['title'] : 'Diagnostik Mikrobiologi & <span class="text-accent">Indikator Sterilisasi</span>',
          'desc' => !empty($sp['healthcare']['desc']) ? $sp['healthcare']['desc'] : 'Reagen identifikasi mikroba, uji sensitivitas antibiotik, serta indikator sterilisasi instalasi CSSD rumah sakit berizin edar resmi Kemenkes RI.',
          'link' => !empty($sp['healthcare']['link']) ? $sp['healthcare']['link'] : '/sektor?s=hospital-clinic#sektor-nav',
          'scope' => !empty($sp['healthcare']['scope']) ? $sp['healthcare']['scope'] : $defaultScopes['healthcare'],
          'compliance' => !empty($sp['healthcare']['compliance']) ? $sp['healthcare']['compliance'] : $defaultCompliance['healthcare'],
        ],
        'brewing' => [
          'label' => 'Brewing & Riset',
          'icon' => 'beaker',
          'tag' => !empty($sp['brewing']['tag']) ? $sp['brewing']['tag'] : 'INDUSTRI BREWING & RISET',
          'title' => !empty($sp['brewing']['title']) ? $sp['brewing']['title'] : 'Kontrol Pembusukan & <span class="text-accent">Kualitas Fermentasi</span>',
          'desc' => !empty($sp['brewing']['desc']) ? $sp['brewing']['desc'] : 'Media selektif bakteri pembusuk bir dan analisis mikrobiologi presisi untuk menjaga stabilitas organoleptik dan kualitas fermentasi industri.',
          'link' => !empty($sp['brewing']['link']) ? $sp['brewing']['link'] : '/sektor?s=brewing#sektor-nav',
          'scope' => !empty($sp['brewing']['scope']) ? $sp['brewing']['scope'] : $defaultScopes['brewing'],
          'compliance' => !empty($sp['brewing']['compliance']) ? $sp['brewing']['compliance'] : $defaultCompliance['brewing'],
        ],
      ];

      foreach ($sectors as $id => &$sec) {
        $rawItems = array_filter(array_map('trim', explode("\n", (string) $sec['scope'])));
        $sec['scope_items'] = !empty($rawItems) ? $rawItems : [
          'Prosedur kendali mutu terstandarisasi',
          'Pengujian akurasi analitik & kepatuhan regulasi',
          'Dukungan dokumentasi teknis & sertifikat'
        ];
      }
      unset($sec);
    @endphp

    <!-- Sector Tabs Bar (Responsive) -->
    <div class="hitech-tab-bar mb-4 mb-md-5" role="tablist" aria-label="Pilihan Sektor Industri">
      @foreach($sectors as $id => $sec)
        <button class="hitech-tab-btn {{ $loop->first ? 'active' : '' }}" role="tab" id="tab-{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="panel-{{ $id }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-target="{{ $id }}">
          <i data-lucide="{{ $sec['icon'] }}" class="me-2" aria-hidden="true"></i> {{ $sec['label'] }}
        </button>
      @endforeach
    </div>

    <!-- Tab Content Panels (Sector Overview & Capabilities) -->
    <div class="hitech-tab-panels">
      @foreach($sectors as $id => $sec)
        <div class="hitech-tab-panel {{ $loop->first ? 'active' : '' }}" id="panel-{{ $id }}" role="tabpanel" aria-labelledby="tab-{{ $id }}" tabindex="0">
          <div class="row g-4 align-items-stretch">
            {{-- Left Column: Sector Overview --}}
            <div class="col-lg-6">
              <div class="hitech-info-card p-4 p-md-5 rounded-3 h-100 d-flex flex-column justify-content-between">
                <div>
                  <span class="hitech-panel-tag mb-3">{{ $sec['tag'] }}</span>
                  @php
                    $renderedTitle = $sec['title'];
                    while (str_contains($renderedTitle, '&amp;amp;')) {
                      $renderedTitle = str_replace('&amp;amp;', '&amp;', $renderedTitle);
                    }
                  @endphp
                  <h3 class="hitech-panel-title mb-3">{!! $renderedTitle !!}</h3>
                  <p class="hitech-panel-desc mb-4">{{ $sec['desc'] }}</p>
                </div>
                <div class="d-flex flex-wrap gap-3 pt-3 border-top align-items-center justify-content-between">
                  <a href="{{ url($sec['link']) }}" class="nb-btn nb-btn-primary d-inline-flex align-items-center gap-2">
                    Jelajahi Solusi Sektor <i data-lucide="arrow-right"></i>
                  </a>
                  <span class="nb-badge-sm"><i data-lucide="shield-check" class="text-primary me-1"></i> Jaminan Mutu Industri</span>
                </div>
              </div>
            </div>

            {{-- Right Column: Testing Scope & Compliance --}}
            <div class="col-lg-6">
              <div class="hitech-spec-card p-4 p-md-5 rounded-3 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom hitech-spec-divider flex-wrap gap-2">
                    <h4 class="fs-6 fw-bold text-uppercase tracking-wider text-dark mb-0 d-flex align-items-center gap-2" style="font-family: var(--font-display); letter-spacing: 0.5px;">
                      <i data-lucide="check-circle-2" style="width: 16px; height: 16px; color: var(--nb-primary);"></i> Cakupan Pengujian &amp; Alur Kerja
                    </h4>
                    <span class="nb-badge-sm"><i data-lucide="badge-check" class="me-1 text-primary"></i> {{ $sec['compliance'] }}</span>
                  </div>

                  <p class="text-muted mb-3" style="font-size: 0.88rem; line-height: 1.6;">
                    Prosedur kendali mutu dan pengujian analitik terstandarisasi yang didukung Prolabios untuk sektor ini:
                  </p>

                  <ul class="list-unstyled my-3 d-flex flex-column gap-2.5">
                    @foreach($sec['scope_items'] as $item)
                      <li class="d-flex align-items-start gap-2">
                        <i data-lucide="chevron-right" style="width: 16px; height: 16px; color: var(--nb-primary); margin-top: 3px;" class="flex-shrink-0"></i>
                        <span style="font-size: 0.88rem; color: var(--nb-ink); line-height: 1.5;">{{ $item }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top hitech-spec-divider flex-wrap gap-2">
                  <span class="text-muted small"><i data-lucide="file-check" class="text-primary me-1"></i> Dokumentasi &amp; COA Lengkap</span>
                  <a href="{{ url('/kontak?subjek=consultation') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-1.5" style="font-size: 0.84rem;">
                    Konsultasi Tim Teknis <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
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
