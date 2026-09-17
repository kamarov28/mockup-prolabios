<!-- 3. Value Pillars Grid (Asymmetric High-Craft Bento) -->
<section class="section-spacious">
  <div class="container">
    <div class="row mb-5 typo-section-head">
      <div class="col-lg-7">
        <h2 class="typo-section-title">{{ $homeData['bento_title'] ?? 'Standar Infrastruktur & Keandalan' }}</h2>
        <p class="typo-section-sub">{{ $homeData['bento_subtitle'] ?? 'Dirancang untuk memenuhi standar regulasi dan menjaga kelancaran pengujian laboratorium.' }}</p>
      </div>
    </div>

    <!-- Asymmetric Bento Layout: 1 Lead Anchor Card + 3 Supporting Functional Cards -->
    <div class="row g-4">
      <!-- 1. Lead Anchor Card (Compliance & COA Engine) -->
      <div class="col-lg-7">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
              <span class="nb-badge-sm">
                <i data-lucide="shield-check" class="text-primary me-1"></i> ISO 17025 • BPOM • AKL
              </span>
            </div>

            <h3 class="hitech-bento-title fs-4 mb-3">{{ $homeData['bento_cards'][0]['title'] ?? 'Dokumentasi COA, MSDS & Sertifikasi Resmi Siap Audit' }}</h3>
            <p class="hitech-bento-desc mb-4">
              {{ $homeData['bento_cards'][0]['desc'] ?? 'Setiap batch reagen mikrobiologi dan instrumen analitika disertai Certificate of Analysis (COA), sertifikat kalibrasi pabrikan, serta nomor izin edar Kemenkes RI (AKL/AKD) lengkap untuk kelancaran audit regulasi Anda.' }}
            </p>
          </div>

          <div class="pt-3 nb-card-foot d-flex align-items-center justify-content-between flex-wrap gap-2 border-top">
            <span class="text-muted small"><i data-lucide="file-check" class="text-primary me-1"></i> BATCH COA &amp; Izin Resmi Siap Unduh</span>
            <a href="{{ url('/kontak?subjek=request_coa') }}" class="fw-semibold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--nb-primary) !important; font-size: 0.85rem;">
              <span>Minta Dokumen</span> <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 2. Cold Chain Infrastructure -->
      @php
        $mapIcon = function($icon, $default) {
          $icon = $icon ?: $default;
          $map = [
            'bi-patch-check' => 'badge-check',
            'bi-file-earmark-code' => 'file-check',
            'bi-snow' => 'thermometer-snowflake',
            'bi-tools' => 'wrench',
            'bi-box-seam' => 'package',
          ];
          return $map[$icon] ?? str_replace('bi-', '', $icon);
        };
      @endphp
      <div class="col-lg-5">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="hitech-bento-icon mb-3"><i data-lucide="{{ $mapIcon($homeData['bento_cards'][1]['icon'] ?? null, 'thermometer-snowflake') }}"></i></div>
            <h3 class="hitech-bento-title fs-5 mb-2">{{ $homeData['bento_cards'][1]['title'] ?? 'Logistik Cold-Chain Terkontrol (2°C – 8°C)' }}</h3>
            <p class="hitech-bento-desc">
              {{ $homeData['bento_cards'][1]['desc'] ?? 'Reagen sensitif suhu, enzim, dan media siap pakai dikemas dengan insulasi termal khusus dan pemantauan suhu berkala hingga tiba di laboratorium.' }}
            </p>
          </div>
          <div class="pt-3 nb-card-foot text-muted border-top" style="font-size: 0.8rem; font-weight: 600;">
            <i data-lucide="thermometer-snowflake" class="text-primary me-1"></i> Temperatur Terjaga • Packing Farmasi
          </div>
        </div>
      </div>

      <!-- 3. Technical After-Sales & Calibration -->
      <div class="col-lg-5">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="hitech-bento-icon mb-3"><i data-lucide="{{ $mapIcon($homeData['bento_cards'][2]['icon'] ?? null, 'wrench') }}"></i></div>
            <h3 class="hitech-bento-title fs-5 mb-2">{{ $homeData['bento_cards'][2]['title'] ?? 'Layanan Teknis IQ/OQ/PQ & Kalibrasi' }}</h3>
            <p class="hitech-bento-desc">
              {{ $homeData['bento_cards'][2]['desc'] ?? 'Didukung teknisi bersertifikasi untuk instalasi, kualifikasi operasional, pemeliharaan preventif, dan kalibrasi rutin mikropipet serta instrumen lab.' }}
            </p>
          </div>
          <div class="pt-3 nb-card-foot border-top">
            <a href="{{ url('/layanan') }}" class="fw-semibold text-decoration-none" style="color: var(--nb-primary) !important; font-size: 0.85rem;">
              Lihat Layanan Kalibrasi <i data-lucide="arrow-right" class="ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 4. Multi-Sector Catalog Density -->
      <div class="col-lg-7">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
              <div class="hitech-bento-icon m-0"><i data-lucide="{{ $mapIcon($homeData['bento_cards'][3]['icon'] ?? null, 'package') }}"></i></div>
              <span class="nb-badge-sm">1.000+ SKU Ready Stock</span>
            </div>
            <h3 class="hitech-bento-title fs-5 mb-2">{{ $homeData['bento_cards'][3]['title'] ?? 'Akses Cepat 1.000+ Produk & Reagen Multi-Brand' }}</h3>
            <p class="hitech-bento-desc mb-3">
              {{ $homeData['bento_cards'][3]['desc'] ?? 'Kemitraan resmi dengan prinsipal global (Bioendo, Terragene, Scharlau, C-Technologies, dll) untuk pasokan reagen, instrumen otomatisasi, dan konsumabel lab tanpa hambatan rantai pasok.' }}
            </p>
          </div>
          <div class="pt-3 nb-card-foot d-flex align-items-center justify-content-between flex-wrap gap-2 border-top">
            <span class="text-muted small">Reagen · Media Kultur · Indikator Biologi · Air Sampler</span>
            <a href="{{ url('/produk') }}" class="fw-semibold text-decoration-none" style="color: var(--nb-primary) !important; font-size: 0.85rem;">
              Lihat Katalog <i data-lucide="arrow-right" class="ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
