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
        <div class="hitech-bento-card hitech-bento-card--lead h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
              <span class="nb-badge nb-badge-sm mb-0">
                <i class="bi bi-shield-check me-1"></i> VALIDASI &amp; COMPLIANCE
              </span>
              <span class="nb-mono text-muted small">ISO 17025 • BPOM • AKL</span>
            </div>

            <h3 class="hitech-bento-title fs-4 mb-3">Dokumentasi COA, MSDS &amp; Sertifikasi Resmi Siap Audit</h3>
            <p class="hitech-bento-desc mb-4">
              Setiap batch reagen mikrobiologi dan instrumen analitika disertai Certificate of Analysis (COA), sertifikat kalibrasi pabrikan, serta nomor izin edar Kemenkes RI (AKL/AKD) lengkap untuk kelancaran audit regulasi Anda.
            </p>
          </div>

          <!-- Micro Visual Widget: Live Batch Certificate Mock -->
          <div class="hitech-bento-widget p-3 mt-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 hitech-bento-widget-row text-muted" style="font-size: 0.75rem;">
              <span class="nb-mono"><i class="bi bi-file-earmark-text text-accent me-1"></i> BATCH-2026-TERRA09</span>
              <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Verified &amp; Passed</span>
            </div>
            <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.8rem;">
              <span>Terragene SCBI Biological Indicator</span>
              <a href="{{ url('/kontak?subjek=request_coa') }}" class="text-accent text-decoration-none fw-medium">
                Minta Dokumen <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Cold Chain Infrastructure -->
      <div class="col-lg-5">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="hitech-bento-icon mb-3"><i class="bi bi-snow text-accent"></i></div>
            <h3 class="hitech-bento-title fs-5 mb-2">Logistik Cold-Chain Terkontrol (2°C – 8°C)</h3>
            <p class="hitech-bento-desc">
              Reagen sensitif suhu, enzim, dan media siap pakai dikemas dengan insulasi termal khusus dan pemantauan suhu berkala hingga tiba di laboratorium.
            </p>
          </div>
          <div class="pt-3 nb-card-foot text-muted nb-mono" style="font-size: 0.75rem;">
            <i class="bi bi-thermometer-snow me-1"></i> Temperatur Terjaga • Packing Farmasi
          </div>
        </div>
      </div>

      <!-- 3. Technical After-Sales & Calibration -->
      <div class="col-lg-5">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="hitech-bento-icon mb-3"><i class="bi bi-tools text-accent"></i></div>
            <h3 class="hitech-bento-title fs-5 mb-2">Layanan Teknis IQ/OQ/PQ &amp; Kalibrasi</h3>
            <p class="hitech-bento-desc">
              Didukung teknisi bersertifikasi untuk instalasi, kualifikasi operasional, pemeliharaan preventif, dan kalibrasi rutin mikropipet serta instrumen lab.
            </p>
          </div>
          <div class="pt-3 nb-card-foot">
            <a href="{{ url('/layanan') }}" class="text-accent text-decoration-none fw-medium" style="font-size: 0.85rem;">
              Lihat Layanan Kalibrasi <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- 4. Multi-Sector Catalog Density -->
      <div class="col-lg-7">
        <div class="hitech-bento-card h-100 d-flex flex-column justify-content-between p-4 p-md-5">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
              <div class="hitech-bento-icon m-0"><i class="bi bi-box-seam text-accent"></i></div>
              <span class="nb-mono text-muted small">1,000+ SKU READY STOCK</span>
            </div>
            <h3 class="hitech-bento-title fs-5 mb-2">Akses Cepat 1.000+ Produk &amp; Reagen Multi-Brand</h3>
            <p class="hitech-bento-desc mb-3">
              Kemitraan resmi dengan prinsipal global (Bioendo, Terragene, Scharlau, C-Technologies, dll) untuk pasokan reagen, instrumen otomatisasi, dan konsumabel lab tanpa hambatan rantai pasok.
            </p>
          </div>
          <div class="d-flex flex-wrap gap-2 pt-2">
            <span class="nb-badge-sm">Endotoxin LAL</span>
            <span class="nb-badge-sm">Culture Media</span>
            <span class="nb-badge-sm">Biological Indicator</span>
            <span class="nb-badge-sm">Air Sampler</span>
            <span class="nb-badge-sm">Liquid Handling</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
