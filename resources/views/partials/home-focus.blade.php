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
      $masterSectors = collect(app(\App\Services\SectorService::class)->getSectors())->keyBy('id');
      $defaultSectorMap = [
        'pharma' => 'pharmaceutical',
        'fnb' => 'food',
        'healthcare' => 'hospital-clinic',
        'brewing' => 'brewing',
      ];
      $defaultIcons = [
        'pharma' => 'pill',
        'fnb' => 'coffee',
        'healthcare' => 'hospital',
        'brewing' => 'beaker',
      ];

      $sectors = [];
      foreach ($defaultSectorMap as $sKey => $defaultId) {
        $secConfig = $sp[$sKey] ?? [];
        $secId = !empty($secConfig['sector_id']) ? $secConfig['sector_id'] : $defaultId;
        $master = $masterSectors->get($secId);

        $name = $master['name'] ?? ucwords(str_replace('-', ' ', $secId));
        $descFromMaster = '';
        if ($master && !empty($master['description'])) {
            $descFromMaster = is_array($master['description']) ? ($master['description'][0] ?? '') : $master['description'];
        }

        $sectors[$sKey] = [
          'label' => $name,
          'icon' => $defaultIcons[$sKey] ?? 'folder-tree',
          'sector_id' => $secId,
          'tag' => !empty($secConfig['tag']) ? $secConfig['tag'] : 'SEKTOR ' . strtoupper($name),
          'title' => !empty($secConfig['title']) ? $secConfig['title'] : 'Solusi Pengujian & Kendali Mutu ' . $name,
          'desc' => !empty($secConfig['desc']) ? $secConfig['desc'] : ($descFromMaster ?: 'Solusi laboratorium, instrumen analitik, dan reagen pendukung pengujian industri ' . $name . '.'),
          'link' => !empty($secConfig['link']) && str_contains($secConfig['link'], '?s=') ? $secConfig['link'] : '/sektor?s=' . $secId . '#sektor-nav',
          'image' => !empty($secConfig['image']) ? $secConfig['image'] : ($master['image'] ?? '/images/sectors/' . $secId . '.jpg'),
        ];
      }
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
