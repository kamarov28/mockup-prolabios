<!-- 4. Temukan Sektor Industri -->
<section class="section-spacious focus-section-pin">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 mb-md-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">{{ $homeData['sector_title'] ?? 'Temukan Sektor Industri' }}</h2>
        <p class="typo-section-sub">{{ $homeData['sector_subtitle'] ?? 'Solusi laboratorium, instrumen analitik, dan reagen pendukung pengujian untuk berbagai sektor industri.' }}</p>
      </div>
      <div>
        <a href="{{ url('/sektor') }}" class="nb-btn nb-btn-ghost d-none d-md-inline-flex align-items-center gap-1.5" style="font-size: 0.88rem;">
          Lihat Semua Sektor <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
        </a>
      </div>
    </div>

    @php
      $sp = $homeData['sector_panels'] ?? [];
      $sectors = [
        'pharma' => [
          'label' => 'Farmasi & Biotech',
          'icon' => 'pill',
          'tag' => !empty($sp['pharma']['tag']) ? $sp['pharma']['tag'] : 'SEKTOR FARMASI & KOSMETIK',
          'title' => !empty($sp['pharma']['title']) ? $sp['pharma']['title'] : 'Solusi Pengujian & Kendali Mutu Farmasi',
          'desc' => !empty($sp['pharma']['desc']) ? $sp['pharma']['desc'] : 'Mendukung pengujian mikrobiologi dan kendali mutu industri farmasi serta kosmetik. Kami menyediakan media kultur standar farmakope, reagen pengujian sterilitas, dan instrumen analitik untuk memastikan kepatuhan regulasi CPOB dan farmakope internasional.',
          'link' => !empty($sp['pharma']['link']) ? $sp['pharma']['link'] : '/sektor?s=pharmaceutical#sektor-nav',
          'image' => '/images/sectors/pharmaceutical.jpg',
        ],
        'fnb' => [
          'label' => 'Makanan & Minuman',
          'icon' => 'coffee',
          'tag' => !empty($sp['fnb']['tag']) ? $sp['fnb']['tag'] : 'SEKTOR FOOD & BEVERAGE',
          'title' => !empty($sp['fnb']['title']) ? $sp['fnb']['title'] : 'Keamanan Pangan & Pengujian Mikrobiologi',
          'desc' => !empty($sp['fnb']['desc']) ? $sp['fnb']['desc'] : 'Mendukung pengawasan mutu seluruh rantai produksi industri makanan dan minuman. Kami menyediakan media kultur deteksi patogen, sistem monitoring higiene sanitasi, serta reagen uji analitik untuk kepatuhan standar BPOM dan HACCP.',
          'link' => !empty($sp['fnb']['link']) ? $sp['fnb']['link'] : '/sektor?s=food#sektor-nav',
          'image' => '/images/sectors/food.jpg',
        ],
        'healthcare' => [
          'label' => 'Kesehatan & Klinis',
          'icon' => 'hospital',
          'tag' => !empty($sp['healthcare']['tag']) ? $sp['healthcare']['tag'] : 'SEKTOR KESEHATAN & KLINIS',
          'title' => !empty($sp['healthcare']['title']) ? $sp['healthcare']['title'] : 'Dukungan Diagnostik & Pemantauan Sterilisasi',
          'desc' => !empty($sp['healthcare']['desc']) ? $sp['healthcare']['desc'] : 'Menyediakan media kultur mikrobiologi klinis, reagen identifikasi patogen, serta indikator sterilisasi instalasi CSSD rumah sakit dengan dukungan sertifikasi dan izin edar resmi Kemenkes RI.',
          'link' => !empty($sp['healthcare']['link']) ? $sp['healthcare']['link'] : '/sektor?s=hospital-clinic#sektor-nav',
          'image' => '/images/sectors/hospital-clinic.jpg',
        ],
        'brewing' => [
          'label' => 'Brewing & Riset',
          'icon' => 'beaker',
          'tag' => !empty($sp['brewing']['tag']) ? $sp['brewing']['tag'] : 'SEKTOR BREWING & FERMENTASI',
          'title' => !empty($sp['brewing']['title']) ? $sp['brewing']['title'] : 'Kendali Mutu Fermentasi & Riset Biologi',
          'desc' => !empty($sp['brewing']['desc']) ? $sp['brewing']['desc'] : 'Solusi deteksi mikroorganisme pembusuk dan pemantauan aktivitas fermentasi untuk industri minuman dan riset mikrobiologi guna menjaga kemurnian kultur dan stabilitas kualitas produk.',
          'link' => !empty($sp['brewing']['link']) ? $sp['brewing']['link'] : '/sektor?s=brewing#sektor-nav',
          'image' => '/images/sectors/brewing.jpg',
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

    <!-- Tab Content Panels (Single Unified Card per Sector) -->
    <div class="hitech-tab-panels">
      @foreach($sectors as $id => $sec)
        <div class="hitech-tab-panel {{ $loop->first ? 'active' : '' }}" id="panel-{{ $id }}" role="tabpanel" aria-labelledby="tab-{{ $id }}" tabindex="0">
          <div class="card p-4 p-md-5 single-sector-card" style="border: 1px solid #E5E7EB; border-radius: 12px; background: #FFFFFF; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <div class="row g-4 g-lg-5 align-items-center">
              <div class="col-lg-7">
                <span class="nb-badge-sm mb-3 d-inline-flex">
                  <i data-lucide="folder-tree" class="text-primary me-1"></i> {{ $sec['tag'] }}
                </span>
                @php
                  $renderedTitle = $sec['title'];
                  while (str_contains($renderedTitle, '&amp;amp;')) {
                    $renderedTitle = str_replace('&amp;amp;', '&amp;', $renderedTitle);
                  }
                @endphp
                <h3 class="hitech-panel-title mb-3">{!! $renderedTitle !!}</h3>
                <p class="text-muted mb-4" style="line-height: 1.75; font-size: 0.95rem;">
                  {{ $sec['desc'] }}
                </p>
                <div class="d-flex align-items-center gap-3 flex-wrap pt-2">
                  <a href="{{ url($sec['link']) }}" class="nb-btn nb-btn-primary d-inline-flex align-items-center gap-2">
                    Jelajahi Sektor Ini <i data-lucide="arrow-right"></i>
                  </a>
                  <a href="{{ url('/sektor') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-1.5" style="font-size: 0.88rem;">
                    Semua Sektor Industri <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
                  </a>
                </div>
              </div>

              <div class="col-lg-5">
                <div style="border-radius: 10px; overflow: hidden; border: 1px solid #E5E7EB; aspect-ratio: 16/10; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                  <img src="{{ asset($sec['image']) }}" alt="{{ $sec['label'] }}" class="w-100 h-100" style="object-fit: cover; display: block;" loading="lazy">
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
