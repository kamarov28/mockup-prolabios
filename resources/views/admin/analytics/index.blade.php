@extends('admin.layout')

@section('title', 'Analitik Web Google Analytics 4')
@section('page_title', 'Analitik Web')

@section('admin_content')
<div class="dash-wrapper">

  {{-- ── 1. Page Header Standard ─────────────────────────────────────────────── --}}
  <x-admin.page-header
    label="Laporan &amp; Intelijen Pengunjung"
    title="Google Analytics 4 • Sebaran Wilayah & Produk"
    description="Statistik kunjungan pengguna live, sebaran provinsi di Indonesia, dan katalog produk terpopuler yang disinkronisasi melalui Google Analytics Data API.">
    <x-slot:actions>
      {{-- Range Toggle Switcher --}}
      <div class="dash-segmented-control" role="tablist">
        <button type="button" class="dash-segment-btn ga4-range-btn" data-days="7">7 Hari</button>
        <button type="button" class="dash-segment-btn ga4-range-btn active" data-days="30">30 Hari</button>
        <button type="button" class="dash-segment-btn ga4-range-btn" data-days="90">90 Hari</button>
      </div>

      <button type="button" class="admin-btn admin-btn-outline admin-btn-sm" id="ga4-export-csv-btn">
        <i data-lucide="download"></i>
        <span>Ekspor CSV</span>
      </button>

      <button type="button" class="admin-btn admin-btn-outline admin-btn-sm" onclick="window.print()">
        <i data-lucide="printer"></i>
        <span>Cetak</span>
      </button>

      <button type="button" class="admin-btn admin-btn-outline admin-btn-sm" id="ga4-refresh-btn">
        <i data-lucide="refresh-cw"></i>
        <span>Segarkan</span>
      </button>

      <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="admin-btn admin-btn-outline admin-btn-sm">
        <i data-lucide="sliders"></i>
        <span>Pengaturan</span>
      </a>
    </x-slot:actions>
  </x-admin.page-header>

  {{-- ── 2. Status / In-Page Setup Notice ─────────────────────────────────────── --}}
  @if(!$status['service_ready'] || empty($status['property_id']))
  <div class="admin-card mb-4" style="border-left: 4px solid var(--color-accent, #A6171C);">
    <div class="admin-card-body p-4">
      <div class="d-flex align-items-start gap-3">
        <div class="rounded-circle p-2 d-inline-flex" style="background: #FEE2E2; color: var(--color-accent, #A6171C); flex-shrink: 0;">
          <i data-lucide="alert-circle" style="width: 24px; height: 24px;"></i>
        </div>
        <div class="w-100">
          <h3 class="h6 fw-bold mb-1" style="color: var(--color-text-main);">Konfigurasi Google Analytics 4 Belum Lengkap</h3>
          <p class="small text-muted mb-3" style="max-width: 760px;">
            Agar live data sebaran wilayah pengunjung dan produk terpopuler bisa ditarik langsung dari Google Analytics Data API ke server ini, pastikan kunci JSON Service Account dan Property ID sudah terpasang.
          </p>

          <div class="row g-3">
            {{-- Upload Credentials Form --}}
            <div class="col-md-6">
              <div class="p-3 rounded h-100" style="background: var(--color-surface-2, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0);">
                <span class="d-block fw-semibold small mb-2 text-dark">
                  <i data-lucide="key" style="width: 14px; height: 14px; color: var(--color-accent);" class="d-inline"></i> 1. Kunci Robot Service Account (.json)
                </span>
                @if($status['service_ready'])
                  <div class="small text-success mb-2 d-flex align-items-center gap-1">
                    <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                    <span>Terpasang: <code>{{ $status['service_email'] ?? 'service-account.json' }}</code></span>
                  </div>
                @else
                  <div class="small text-danger mb-2 d-flex align-items-center gap-1">
                    <i data-lucide="x-circle" style="width: 14px; height: 14px;"></i>
                    <span>Belum ada file kunci di server ini</span>
                  </div>
                @endif

                <form action="{{ route('admin.analytics.upload-credentials') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-2">
                  @csrf
                  <input type="file" name="credentials_file" class="form-control form-control-sm" accept=".json,application/json" required>
                  <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm align-self-start">
                    <i data-lucide="upload"></i>
                    <span>Unggah File Kunci JSON</span>
                  </button>
                </form>
              </div>
            </div>

            {{-- Set Property ID Form --}}
            <div class="col-md-6">
              <div class="p-3 rounded h-100" style="background: var(--color-surface-2, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0);">
                <span class="d-block fw-semibold small mb-2 text-dark">
                  <i data-lucide="hash" style="width: 14px; height: 14px; color: #0284C7;" class="d-inline"></i> 2. GA4 Property ID (Numerik 9 Digit)
                </span>
                @if(!empty($status['property_id']))
                  <div class="small text-success mb-2 d-flex align-items-center gap-1">
                    <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                    <span>Tersimpan: <code>{{ $status['property_id'] }}</code></span>
                  </div>
                @else
                  <div class="small text-danger mb-2 d-flex align-items-center gap-1">
                    <i data-lucide="x-circle" style="width: 14px; height: 14px;"></i>
                    <span>Property ID belum diisi</span>
                  </div>
                @endif

                <form action="{{ route('admin.analytics.update-property') }}" method="POST" class="d-flex flex-column gap-2">
                  @csrf
                  <input type="text" name="ga4_property_id" class="form-control form-control-sm" placeholder="Contoh: 557886119" value="{{ $status['property_id'] ?? '' }}" required>
                  <button type="submit" class="admin-btn admin-btn-primary admin-btn-sm align-self-start">
                    <i data-lucide="save"></i>
                    <span>Simpan Property ID</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- ── 3. KPI Micro-Stat Cards ──────────────────────────────────────────────── --}}
  <div class="row g-3 mb-4 align-items-stretch">
    {{-- Card 1: Active Users --}}
    <div class="col-sm-6 col-xl-3 d-flex">
      <div class="dash-stat-card w-100">
        <div class="dash-stat-icon-wrap" style="background: #FEE2E2; color: var(--color-accent, #A6171C);">
          <i data-lucide="users"></i>
        </div>
        <div class="dash-stat-content d-flex flex-column justify-content-center">
          <span class="dash-stat-label text-truncate">Pengguna Aktif</span>
          <div class="dash-stat-val text-truncate" id="kpi-total-users">-</div>
          <span class="text-muted text-truncate" style="font-size: 0.72rem;" id="kpi-users-sub">Rentang waktu terpilih</span>
        </div>
      </div>
    </div>

    {{-- Card 2: Page Views --}}
    <div class="col-sm-6 col-xl-3 d-flex">
      <div class="dash-stat-card w-100">
        <div class="dash-stat-icon-wrap" style="background: #E0F2FE; color: #0284C7;">
          <i data-lucide="eye"></i>
        </div>
        <div class="dash-stat-content d-flex flex-column justify-content-center">
          <span class="dash-stat-label text-truncate">Tayangan Halaman</span>
          <div class="dash-stat-val text-truncate" id="kpi-total-views" style="color: #0284C7;">-</div>
          <span class="text-muted text-truncate" style="font-size: 0.72rem;" id="kpi-views-sub">Total Page Views</span>
        </div>
      </div>
    </div>

    {{-- Card 3: Ratio --}}
    <div class="col-sm-6 col-xl-3 d-flex">
      <div class="dash-stat-card w-100">
        <div class="dash-stat-icon-wrap" style="background: #FEF3C7; color: #D97706;">
          <i data-lucide="activity"></i>
        </div>
        <div class="dash-stat-content d-flex flex-column justify-content-center">
          <span class="dash-stat-label text-truncate">Rasio Tayangan / User</span>
          <div class="dash-stat-val text-truncate" id="kpi-ratio" style="color: #D97706;">-</div>
          <span class="text-muted text-truncate" style="font-size: 0.72rem;">Kedalaman Interaksi</span>
        </div>
      </div>
    </div>

    {{-- Card 4: Status API --}}
    <div class="col-sm-6 col-xl-3 d-flex">
      <div class="dash-stat-card w-100">
        <div class="dash-stat-icon-wrap" style="background: #DCFCE7; color: #16A34A;">
          <i data-lucide="shield-check"></i>
        </div>
        <div class="dash-stat-content d-flex flex-column justify-content-center">
          <span class="dash-stat-label text-truncate">Status Google API</span>
          <div class="dash-stat-val text-truncate" id="kpi-api-status" style="font-size: 1.25rem; color: #16A34A; line-height: 1.2;">Menghubungkan</div>
          <span class="text-muted text-truncate" style="font-size: 0.72rem;" id="kpi-last-sync">Sinkronisasi...</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── 3.5. Daily Visit Trends Chart ────────────────────────────────────── --}}
  <div class="admin-card mb-4 position-relative">
    <div class="admin-card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div class="d-flex align-items-center gap-2">
        <i data-lucide="trending-up" style="width: 16px; height: 16px; color: var(--color-accent, #A6171C);"></i>
        <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Tren Kunjungan Harian (Pengguna &amp; Tayangan Halaman)</h2>
      </div>
      <div class="d-flex align-items-center gap-3 ms-auto" style="font-size: 0.74rem;">
        <span class="d-flex align-items-center gap-1">
          <span style="display: inline-block; width: 10px; height: 10px; border-radius: 2px; background: var(--color-accent, #A6171C);"></span>
          <span class="text-muted">Pengguna Aktif</span>
        </span>
        <span class="d-flex align-items-center gap-1">
          <span style="display: inline-block; width: 10px; height: 10px; border-radius: 2px; background: #0284C7;"></span>
          <span class="text-muted">Tayangan Halaman</span>
        </span>
        <span id="trend-hover-indicator" class="badge bg-light text-dark border px-2 py-1 ms-1" style="font-size: 0.74rem; display: none;"></span>
      </div>
    </div>
    <div class="admin-card-body p-3 position-relative" id="trend-container">
      <div class="text-center py-5 text-muted">
        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
        <p class="small mb-0">Memuat grafik tren kunjungan harian...</p>
      </div>
    </div>
  </div>

  {{-- ── 4. Main Two-Column Analytics Details ──────────────────────────────────── --}}
  <div class="row g-3 mb-4">

    {{-- Left Column: Sebaran Pengunjung per Wilayah (Provinsi) --}}
    <div class="col-lg-6">
      <div class="admin-card h-100">
        <div class="admin-card-header py-2 px-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="map-pin" style="width: 16px; height: 16px; color: var(--color-accent, #A6171C);"></i>
            <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Sebaran Pengunjung per Wilayah (Provinsi)</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;" id="regions-count-badge">Wilayah Terdeteksi</span>
        </div>
        <div class="admin-card-body p-3" id="regions-container">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data demografi wilayah...</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Right Column: Top Halaman & Detail Produk Terpopuler --}}
    <div class="col-lg-6">
      <div class="admin-card h-100">
        <div class="admin-card-header py-2 px-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="compass" style="width: 16px; height: 16px; color: #0284C7;"></i>
            <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Halaman &amp; Produk Paling Populer</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;" id="pages-count-badge">URL Teratas</span>
        </div>
        <div class="admin-card-body p-3" id="pages-container">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data halaman terpopuler...</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── 5. Secondary Details: Sumber Trafik & Kategori Perangkat ────────────── --}}
  <div class="row g-3 mb-4">

    {{-- Left Column: Kanal Sumber Trafik --}}
    <div class="col-lg-6">
      <div class="admin-card h-100">
        <div class="admin-card-header py-2 px-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="share-2" style="width: 16px; height: 16px; color: #D97706;"></i>
            <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Kanal Sumber Trafik (Acquisition)</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;" id="sources-count-badge">Sumber Terdeteksi</span>
        </div>
        <div class="admin-card-body p-3" id="sources-container">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data kanal sumber trafik...</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Right Column: Kategori Perangkat --}}
    <div class="col-lg-6">
      <div class="admin-card h-100">
        <div class="admin-card-header py-2 px-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="monitor" style="width: 16px; height: 16px; color: #16A34A;"></i>
            <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Kategori Perangkat Pengunjung</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;" id="devices-count-badge">Platform Pembeli</span>
        </div>
        <div class="admin-card-body p-3" id="devices-container">
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data kategori perangkat...</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── 6. Technical Diagnostics & Credentials Info ─────────────────────────── --}}
  <div class="admin-card">
    <div class="admin-card-header py-2 px-3">
      <div class="d-flex align-items-center gap-2">
        <i data-lucide="info" style="width: 15px; height: 15px; color: var(--color-text-secondary);"></i>
        <h2 class="admin-card-header-title mb-0" style="font-size: 0.88rem;">Informasi Teknis &amp; Status Integrasi</h2>
      </div>
    </div>
    <div class="admin-card-body p-3">
      <div class="row g-3 align-items-stretch">
        <div class="col-md-4 d-flex">
          <div class="p-3 rounded h-100 w-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 1px solid var(--color-border); min-height: 82px;">
            <span class="text-muted d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.4px;">Google Service Account</span>
            <span class="fw-semibold text-break" style="font-size: 0.82rem; color: var(--color-text-main);">
              {{ $status['service_email'] ?? 'Belum terpasang' }}
            </span>
          </div>
        </div>
        <div class="col-md-4 d-flex">
          <div class="p-3 rounded h-100 w-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 1px solid var(--color-border); min-height: 82px;">
            <span class="text-muted d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.4px;">GA4 Property ID</span>
            <span class="fw-semibold font-monospace" style="font-size: 0.88rem; color: var(--color-text-main);">
              {{ $status['property_id'] ?? 'Belum disetel' }}
            </span>
          </div>
        </div>
        <div class="col-md-4 d-flex">
          <div class="p-3 rounded h-100 w-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 1px solid var(--color-border); min-height: 82px;">
            <span class="text-muted d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600; letter-spacing: 0.4px;">Masa Berlaku Cache</span>
            <span class="fw-semibold" style="font-size: 0.82rem; color: var(--color-text-main);">
              20 Menit (Gunakan tombol Segarkan untuk pembaruan instan)
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script @nonce>
  document.addEventListener('DOMContentLoaded', function() {
    let currentDays = 30;
    let lastLoadedData = null;

    const kpiUsers = document.getElementById('kpi-total-users');
    const kpiViews = document.getElementById('kpi-total-views');
    const kpiRatio = document.getElementById('kpi-ratio');
    const kpiStatus = document.getElementById('kpi-api-status');
    const kpiSync = document.getElementById('kpi-last-sync');

    const trendContainer = document.getElementById('trend-container');
    const regionsContainer = document.getElementById('regions-container');
    const pagesContainer = document.getElementById('pages-container');
    const sourcesContainer = document.getElementById('sources-container');
    const devicesContainer = document.getElementById('devices-container');
    const refreshBtn = document.getElementById('ga4-refresh-btn');
    const exportCsvBtn = document.getElementById('ga4-export-csv-btn');
    const rangeBtns = document.querySelectorAll('.ga4-range-btn');

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function renderError(msg) {
      const errHtml = `
        <div class="p-4 text-center">
          <div class="d-inline-flex p-2 rounded-circle mb-2" style="background: #FEE2E2; color: #DC2626;">
            <i data-lucide="alert-triangle" style="width: 22px; height: 22px;"></i>
          </div>
          <h4 class="h6 fw-bold mb-1">Gagal Menarik Data</h4>
          <p class="small text-muted mb-0">${escapeHtml(msg)}</p>
        </div>
      `;
      if (trendContainer) trendContainer.innerHTML = errHtml;
      regionsContainer.innerHTML = errHtml;
      pagesContainer.innerHTML = errHtml;
      if (sourcesContainer) sourcesContainer.innerHTML = errHtml;
      if (devicesContainer) devicesContainer.innerHTML = errHtml;
      if (kpiStatus) {
        kpiStatus.textContent = 'Terhambat';
        kpiStatus.style.color = '#DC2626';
      }
      if (window.lucide) { lucide.createIcons(); }
    }

    function renderAnalytics(data) {
      if (data.status !== 'success') {
        renderError(data.message || 'Respons tidak valid dari server.');
        return;
      }

      lastLoadedData = data;

      // 1. Update KPIs
      const totalUsers = data.total_users || 0;
      const totalViews = data.total_views || 0;
      const ratio = totalUsers > 0 ? (totalViews / totalUsers).toFixed(1) : '0';

      if (kpiUsers) kpiUsers.textContent = totalUsers.toLocaleString('id-ID');
      if (kpiViews) kpiViews.textContent = totalViews.toLocaleString('id-ID');
      if (kpiRatio) kpiRatio.textContent = ratio + 'x';
      if (kpiStatus) {
        kpiStatus.textContent = 'Terhubung';
        kpiStatus.style.color = '#16A34A';
      }
      if (kpiSync) {
        kpiSync.textContent = 'Update: ' + (data.updated_at || 'Baru saja');
      }

      // 1.5. Render Daily Trends Chart
      if (trendContainer) {
        const trends = data.trends || [];
        if (trends.length === 0) {
          trendContainer.innerHTML = `
            <div class="text-center py-5 text-muted">
              <i data-lucide="inbox" style="width: 28px; height: 28px;" class="mb-2 text-secondary"></i>
              <p class="small mb-0">Belum ada tren kunjungan harian tercatat dalam rentang waktu ini.</p>
            </div>
          `;
        } else {
          const maxVal = Math.max(1, ...trends.map(function(t) { return Math.max(t.users, t.views); }));
          const width = 940;
          const height = 180;
          const padL = 40;
          const padR = 20;
          const padT = 20;
          const padB = 30;
          const plotW = width - padL - padR;
          const plotH = height - padT - padB;
          const count = trends.length;

          function getX(i) {
            return count <= 1 ? (padL + plotW / 2) : (padL + (i / (count - 1)) * plotW);
          }
          function getY(val) {
            return padT + plotH - (val / maxVal) * plotH;
          }

          // Points
          const viewsPoints = trends.map(function(t, i) { return getX(i).toFixed(1) + ',' + getY(t.views).toFixed(1); }).join(' ');
          const usersPoints = trends.map(function(t, i) { return getX(i).toFixed(1) + ',' + getY(t.users).toFixed(1); }).join(' ');

          // Area path for views
          const firstX = getX(0).toFixed(1);
          const lastX = getX(count - 1).toFixed(1);
          const baseY = (padT + plotH).toFixed(1);
          const viewsArea = 'M ' + firstX + ',' + baseY + ' L ' + viewsPoints.replace(/ /g, ' L ') + ' L ' + lastX + ',' + baseY + ' Z';

          // Gridlines
          let gridSvg = '';
          const gridSteps = 3;
          for (let g = 0; g <= gridSteps; g++) {
            const gVal = Math.round((maxVal / gridSteps) * g);
            const gY = (padT + plotH - (g / gridSteps) * plotH).toFixed(1);
            gridSvg += `
              <line x1="${padL}" y1="${gY}" x2="${width - padR}" y2="${gY}" stroke="var(--color-border, #E2E8F0)" stroke-dasharray="3 3" stroke-width="1" />
              <text x="${padL - 8}" y="${parseFloat(gY) + 3}" text-anchor="end" font-size="10" fill="var(--color-text-secondary, #64748B)">${gVal}</text>
            `;
          }

          // X-Axis Date Labels
          let datesSvg = '';
          const step = Math.max(1, Math.ceil(count / 8));
          trends.forEach(function(t, i) {
            if (i % step === 0 || i === count - 1) {
              const dx = getX(i).toFixed(1);
              datesSvg += `
                <text x="${dx}" y="${height - 6}" text-anchor="middle" font-size="10" fill="var(--color-text-secondary, #64748B)">${escapeHtml(t.label)}</text>
              `;
            }
          });

          // Circles / Dots with Tooltips
          let dotsSvg = '';
          trends.forEach(function(t, i) {
            const x = getX(i).toFixed(1);
            const yUsers = getY(t.users).toFixed(1);
            const yViews = getY(t.views).toFixed(1);
            dotsSvg += `
              <circle cx="${x}" cy="${yViews}" r="3" fill="#0284C7" stroke="#FFFFFF" stroke-width="1.5"></circle>
              <circle cx="${x}" cy="${yUsers}" r="3" fill="var(--color-accent, #A6171C)" stroke="#FFFFFF" stroke-width="1.5"></circle>
            `;
          });

          // Hitboxes for hover & touch
          let hitboxesSvg = '';
          const slotW = count > 1 ? (plotW / (count - 1)) : plotW;
          trends.forEach(function(t, i) {
            const x = getX(i);
            const xLeft = count === 1 ? padL : (i === 0 ? padL : x - slotW / 2);
            const w = count === 1 ? plotW : (i === 0 || i === count - 1 ? slotW / 2 : slotW);
            hitboxesSvg += `
              <rect x="${xLeft.toFixed(1)}" y="0" width="${w.toFixed(1)}" height="${height}" fill="transparent" class="trend-hitbox" data-idx="${i}" style="cursor: pointer;"></rect>
            `;
          });

          trendContainer.innerHTML = `
            <div class="w-100 overflow-x-auto position-relative" style="min-height: ${height}px;">
              <div id="trend-tooltip" style="position: absolute; display: none; pointer-events: none; z-index: 25; background: #0F172A; color: #FFFFFF; padding: 6px 10px; border-radius: 6px; font-size: 0.72rem; box-shadow: 0 4px 14px rgba(0,0,0,0.25); transform: translate(-50%, -125%); white-space: nowrap;">
                <div id="trend-tooltip-date" class="fw-bold mb-1" style="font-size: 0.74rem; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 2px;"></div>
                <div class="d-flex align-items-center gap-2">
                  <span class="d-flex align-items-center gap-1"><span style="display:inline-block; width: 7px; height: 7px; border-radius: 2px; background: #F87171;"></span><span id="trend-tooltip-users"></span> user</span>
                  <span class="d-flex align-items-center gap-1"><span style="display:inline-block; width: 7px; height: 7px; border-radius: 2px; background: #38BDF8;"></span><span id="trend-tooltip-views"></span> views</span>
                </div>
              </div>
              <svg id="trend-svg" viewBox="0 0 ${width} ${height}" class="w-100" style="min-width: 600px; height: auto; display: block;" preserveAspectRatio="xMidYMid meet">
                <defs>
                  <linearGradient id="viewsGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#0284C7" stop-opacity="0.25" />
                    <stop offset="100%" stop-color="#0284C7" stop-opacity="0.0" />
                  </linearGradient>
                </defs>
                ${gridSvg}
                <path d="${viewsArea}" fill="url(#viewsGradient)" />
                <polyline fill="none" stroke="#0284C7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="${viewsPoints}" />
                <polyline fill="none" stroke="var(--color-accent, #A6171C)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="${usersPoints}" />
                ${dotsSvg}
                ${datesSvg}
                <line id="trend-hover-line" y1="${padT}" y2="${height - padB}" stroke="#64748B" stroke-width="1.5" stroke-dasharray="3 3" style="display: none; pointer-events: none;"></line>
                <circle id="trend-hover-views-dot" r="5.5" fill="#0284C7" stroke="#FFFFFF" stroke-width="2" style="display: none; pointer-events: none;"></circle>
                <circle id="trend-hover-users-dot" r="5.5" fill="var(--color-accent, #A6171C)" stroke="#FFFFFF" stroke-width="2" style="display: none; pointer-events: none;"></circle>
                ${hitboxesSvg}
              </svg>
            </div>
          `;

          // Attach Hover & Touch Handlers
          const trendSvg = document.getElementById('trend-svg');
          const hoverLine = document.getElementById('trend-hover-line');
          const hoverViewsDot = document.getElementById('trend-hover-views-dot');
          const hoverUsersDot = document.getElementById('trend-hover-users-dot');
          const tooltip = document.getElementById('trend-tooltip');
          const tooltipDate = document.getElementById('trend-tooltip-date');
          const tooltipUsers = document.getElementById('trend-tooltip-users');
          const tooltipViews = document.getElementById('trend-tooltip-views');
          const hoverIndicator = document.getElementById('trend-hover-indicator');

          function showPoint(idx) {
            const t = trends[idx];
            if (!t) return;
            const x = getX(idx);
            const yUsers = getY(t.users);
            const yViews = getY(t.views);

            if (hoverLine) {
              hoverLine.setAttribute('x1', x.toFixed(1));
              hoverLine.setAttribute('x2', x.toFixed(1));
              hoverLine.style.display = 'block';
            }

            if (hoverViewsDot) {
              hoverViewsDot.setAttribute('cx', x.toFixed(1));
              hoverViewsDot.setAttribute('cy', yViews.toFixed(1));
              hoverViewsDot.style.display = 'block';
            }

            if (hoverUsersDot) {
              hoverUsersDot.setAttribute('cx', x.toFixed(1));
              hoverUsersDot.setAttribute('cy', yUsers.toFixed(1));
              hoverUsersDot.style.display = 'block';
            }

            if (tooltip && trendSvg) {
              const svgRect = trendSvg.getBoundingClientRect();
              const scaleX = svgRect.width / width;
              const scaleY = svgRect.height / height;
              const posX = x * scaleX;
              const posY = Math.min(yUsers, yViews) * scaleY;

              tooltip.style.left = posX + 'px';
              tooltip.style.top = posY + 'px';
              if (tooltipDate) tooltipDate.textContent = t.label + ' (' + t.date + ')';
              if (tooltipUsers) tooltipUsers.textContent = t.users;
              if (tooltipViews) tooltipViews.textContent = t.views;
              tooltip.style.display = 'block';
            }

            if (hoverIndicator) {
              hoverIndicator.textContent = t.label + ': ' + t.users + ' User • ' + t.views + ' Views';
              hoverIndicator.style.display = 'inline-block';
            }
          }

          function hidePoint() {
            if (hoverLine) hoverLine.style.display = 'none';
            if (hoverViewsDot) hoverViewsDot.style.display = 'none';
            if (hoverUsersDot) hoverUsersDot.style.display = 'none';
            if (tooltip) tooltip.style.display = 'none';
            if (hoverIndicator) hoverIndicator.style.display = 'none';
          }

          if (trendSvg) {
            trendSvg.addEventListener('mousemove', function(e) {
              const rect = e.target.closest('.trend-hitbox');
              if (rect && rect.dataset.idx !== undefined) {
                showPoint(parseInt(rect.dataset.idx, 10));
              }
            });

            trendSvg.addEventListener('mouseleave', function() {
              hidePoint();
            });

            trendSvg.addEventListener('touchstart', function(e) {
              const touch = e.touches[0];
              const target = document.elementFromPoint(touch.clientX, touch.clientY);
              const rect = target ? target.closest('.trend-hitbox') : null;
              if (rect && rect.dataset.idx !== undefined) {
                showPoint(parseInt(rect.dataset.idx, 10));
              }
            }, { passive: true });

            trendSvg.addEventListener('touchmove', function(e) {
              const touch = e.touches[0];
              const target = document.elementFromPoint(touch.clientX, touch.clientY);
              const rect = target ? target.closest('.trend-hitbox') : null;
              if (rect && rect.dataset.idx !== undefined) {
                showPoint(parseInt(rect.dataset.idx, 10));
              }
            }, { passive: true });
          }
        }
      }

      // 2. Render Regions
      const regions = data.regions || [];
      const maxUsers = Math.max(1, data.max_users || 1);

      if (regions.length === 0) {
        regionsContainer.innerHTML = `
          <div class="text-center py-5 text-muted">
            <i data-lucide="inbox" style="width: 28px; height: 28px;" class="mb-2 text-secondary"></i>
            <p class="small mb-0">Belum ada kunjungan wilayah tercatat dalam rentang waktu ini.</p>
          </div>
        `;
      } else {
        let rHtml = '<div class="d-flex flex-column gap-3">';
        regions.forEach(function(r) {
          const pct = Math.min(100, Math.round((r.users / maxUsers) * 100));
          const share = totalUsers > 0 ? Math.round((r.users / totalUsers) * 100) : 0;
          rHtml += `
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1 small">
                <span class="fw-semibold text-truncate" style="max-width: 220px; color: var(--color-text-main);">
                  ${escapeHtml(r.name)}
                </span>
                <span class="text-muted" style="font-size: 0.76rem;">
                  <strong>${r.users}</strong> pengguna (${share}%) • ${r.views} views
                </span>
              </div>
              <div class="progress" style="height: 7px; background-color: var(--color-surface-2, #E2E8F0); border-radius: 4px;">
                <div class="progress-bar" style="width: ${pct}%; background-color: var(--color-accent, #A6171C); border-radius: 4px;"></div>
              </div>
            </div>
          `;
        });
        rHtml += '</div>';
        regionsContainer.innerHTML = rHtml;
      }

      // 3. Render Top Pages
      const topPages = data.top_pages || [];
      if (topPages.length === 0) {
        pagesContainer.innerHTML = `
          <div class="text-center py-5 text-muted">
            <i data-lucide="inbox" style="width: 28px; height: 28px;" class="mb-2 text-secondary"></i>
            <p class="small mb-0">Belum ada kunjungan halaman tercatat dalam rentang waktu ini.</p>
          </div>
        `;
      } else {
        let pHtml = '<div class="list-group list-group-flush">';
        topPages.forEach(function(p, idx) {
          pHtml += `
            <div class="list-group-item px-0 py-2 border-bottom d-flex align-items-center justify-content-between gap-3" style="background: transparent;">
              <div class="d-flex align-items-center gap-2 overflow-hidden">
                <span class="badge rounded-circle bg-light text-secondary border d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.72rem; flex-shrink: 0;">
                  ${idx + 1}
                </span>
                <div class="overflow-hidden">
                  <div class="fw-medium text-truncate small" style="color: var(--color-text-main);">
                    ${escapeHtml(p.label)}
                  </div>
                  <div class="text-muted text-truncate font-monospace" style="font-size: 0.72rem;">
                    ${escapeHtml(p.path)}
                  </div>
                </div>
              </div>
              <div class="text-end flex-shrink-0">
                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.76rem;">
                  <strong>${p.views}</strong> views
                </span>
                <span class="text-muted d-block mt-1" style="font-size: 0.68rem;">
                  ${p.users} users
                </span>
              </div>
            </div>
          `;
        });
        pHtml += '</div>';
        pagesContainer.innerHTML = pHtml;
      }

      // 4. Render Traffic Sources
      if (sourcesContainer) {
        const sources = data.sources || [];
        const sourcesBadge = document.getElementById('sources-count-badge');
        if (sourcesBadge) {
          sourcesBadge.textContent = sources.length + ' Sumber Terdeteksi';
        }

        if (sources.length === 0) {
          sourcesContainer.innerHTML = `
            <div class="text-center py-5 text-muted">
              <i data-lucide="inbox" style="width: 28px; height: 28px;" class="mb-2 text-secondary"></i>
              <p class="small mb-0">Belum ada kanal sumber trafik tercatat dalam rentang waktu ini.</p>
            </div>
          `;
        } else {
          let sHtml = '<div class="d-flex flex-column gap-3">';
          const maxSourceUsers = Math.max(1, ...sources.map(function(s) { return s.users; }));
          sources.forEach(function(s) {
            const pct = Math.min(100, Math.round((s.users / maxSourceUsers) * 100));
            const share = totalUsers > 0 ? Math.round((s.users / totalUsers) * 100) : 0;
            sHtml += `
              <div>
                <div class="d-flex justify-content-between align-items-center mb-1 small">
                  <span class="fw-semibold text-truncate" style="max-width: 240px; color: var(--color-text-main);">
                    ${escapeHtml(s.label)}
                  </span>
                  <span class="text-muted" style="font-size: 0.76rem;">
                    <strong>${s.users}</strong> pengunjung (${share}%) • ${s.sessions} sesi
                  </span>
                </div>
                <div class="progress" style="height: 7px; background-color: var(--color-surface-2, #E2E8F0); border-radius: 4px;">
                  <div class="progress-bar" style="width: ${pct}%; background-color: #D97706; border-radius: 4px;"></div>
                </div>
              </div>
            `;
          });
          sHtml += '</div>';
          sourcesContainer.innerHTML = sHtml;
        }
      }

      // 5. Render Device Categories
      if (devicesContainer) {
        const devices = data.devices || [];
        if (devices.length === 0) {
          devicesContainer.innerHTML = `
            <div class="text-center py-5 text-muted">
              <i data-lucide="inbox" style="width: 28px; height: 28px;" class="mb-2 text-secondary"></i>
              <p class="small mb-0">Belum ada data perangkat tercatat dalam rentang waktu ini.</p>
            </div>
          `;
        } else {
          let dHtml = '<div class="row g-3">';
          devices.forEach(function(d) {
            const share = totalUsers > 0 ? Math.round((d.users / totalUsers) * 100) : 0;
            let icon = 'monitor';
            let iconBg = '#E0F2FE';
            let iconColor = '#0284C7';
            if (d.category === 'mobile') {
              icon = 'smartphone';
              iconBg = '#DCFCE7';
              iconColor = '#16A34A';
            } else if (d.category === 'tablet') {
              icon = 'tablet';
              iconBg = '#FEF3C7';
              iconColor = '#D97706';
            }

            dHtml += `
              <div class="col-sm-6 col-md-4">
                <div class="p-3 rounded border text-center h-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2, #F8FAFC);">
                  <div>
                    <div class="rounded-circle mx-auto d-inline-flex p-2 mb-2" style="background: ${iconBg}; color: ${iconColor};">
                      <i data-lucide="${icon}" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div class="fw-semibold small" style="color: var(--color-text-main);">${escapeHtml(d.label)}</div>
                  </div>
                  <div class="mt-3">
                    <div class="h5 fw-bold mb-0" style="color: var(--color-text-main);">${d.users.toLocaleString('id-ID')}</div>
                    <span class="text-muted" style="font-size: 0.72rem;">${share}% dari total (${d.views} tayangan)</span>
                  </div>
                </div>
              </div>
            `;
          });
          dHtml += '</div>';
          devicesContainer.innerHTML = dHtml;
        }
      }

      if (window.lucide) { lucide.createIcons(); }
    }

    function loadData(forceRefresh = false) {
      if (trendContainer) {
        trendContainer.innerHTML = `
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat grafik tren kunjungan harian (${currentDays} hari)...</p>
          </div>
        `;
      }
      regionsContainer.innerHTML = `
        <div class="text-center py-5 text-muted">
          <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
          <p class="small mb-0">Memuat data wilayah (${currentDays} hari)...</p>
        </div>
      `;
      pagesContainer.innerHTML = `
        <div class="text-center py-5 text-muted">
          <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
          <p class="small mb-0">Memuat data halaman terpopuler (${currentDays} hari)...</p>
        </div>
      `;
      if (sourcesContainer) {
        sourcesContainer.innerHTML = `
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data kanal sumber trafik (${currentDays} hari)...</p>
          </div>
        `;
      }
      if (devicesContainer) {
        devicesContainer.innerHTML = `
          <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
            <p class="small mb-0">Memuat data kategori perangkat (${currentDays} hari)...</p>
          </div>
        `;
      }

      const params = new URLSearchParams({
        days: currentDays,
        ...(forceRefresh ? { refresh: '1' } : {})
      });

      fetch('{{ route('admin.analytics.data') }}?' + params.toString(), {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
      })
      .then(function(res) {
        if (!res.ok) {
          throw new Error('Server mengembalikan kode status ' + res.status);
        }
        return res.json();
      })
      .then(function(data) { renderAnalytics(data); })
      .catch(function(err) { renderError('Koneksi terputus: ' + err.message); });
    }

    // Attach Event Listeners
    if (refreshBtn) {
      refreshBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loadData(true);
      });
    }

    if (exportCsvBtn) {
      exportCsvBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (!lastLoadedData || lastLoadedData.status !== 'success') {
          alert('Data analitik belum selesai dimuat. Tunggu hingga data tampil di layar.');
          return;
        }

        let csv = '\uFEFF'; // BOM for Excel UTF-8 recognition
        csv += 'LAPORAN ANALITIK GOOGLE ANALYTICS 4 - PT PROLABIOS MITRA ANALITIKA\r\n';
        csv += 'Rentang Waktu: ' + currentDays + ' Hari Terakhir\r\n';
        csv += 'Diekspor Pada: ' + new Date().toLocaleString('id-ID') + '\r\n\r\n';

        // 1. KPI
        csv += '=== RINGKASAN METRIK ===\r\n';
        csv += 'Metrik,Nilai\r\n';
        csv += 'Pengguna Aktif,' + (lastLoadedData.total_users || 0) + '\r\n';
        csv += 'Total Tayangan Halaman,' + (lastLoadedData.total_views || 0) + '\r\n';
        csv += 'Rasio Tayangan per User,' + ((lastLoadedData.total_users || 0) > 0 ? (lastLoadedData.total_views / lastLoadedData.total_users).toFixed(2) : 0) + '\r\n\r\n';

        // 1.5. Tren Kunjungan Harian
        csv += '=== TREN KUNJUNGAN HARIAN ===\r\n';
        csv += 'Tanggal,Label,Pengguna Aktif,Tayangan Halaman\r\n';
        (lastLoadedData.trends || []).forEach(function(t) {
          csv += '"' + t.date + '","' + t.label + '",' + t.users + ',' + t.views + '\r\n';
        });
        csv += '\r\n';

        // 2. Wilayah
        csv += '=== SEBARAN WILAYAH (PROVINSI) ===\r\n';
        csv += 'No,Nama Wilayah,Pengguna Aktif,Tayangan Halaman,Persentase Share (%)\r\n';
        (lastLoadedData.regions || []).forEach(function(r, idx) {
          const share = lastLoadedData.total_users > 0 ? ((r.users / lastLoadedData.total_users) * 100).toFixed(1) : '0';
          csv += (idx + 1) + ',"' + r.name.replace(/"/g, '""') + '",' + r.users + ',' + r.views + ',' + share + '%\r\n';
        });
        csv += '\r\n';

        // 3. Produk & Halaman Terpopuler
        csv += '=== HALAMAN & PRODUK PALING POPULER ===\r\n';
        csv += 'No,Judul Halaman / Produk,URL Path,Tayangan Halaman,Pengguna Aktif\r\n';
        (lastLoadedData.top_pages || []).forEach(function(p, idx) {
          csv += (idx + 1) + ',"' + p.label.replace(/"/g, '""') + '","' + p.path.replace(/"/g, '""') + '",' + p.views + ',' + p.users + '\r\n';
        });
        csv += '\r\n';

        // 4. Kanal Sumber Trafik
        csv += '=== KANAL SUMBER TRAFIK ===\r\n';
        csv += 'No,Kanal / Sumber,Pengguna Aktif,Total Sesi\r\n';
        (lastLoadedData.sources || []).forEach(function(s, idx) {
          csv += (idx + 1) + ',"' + s.label.replace(/"/g, '""') + '",' + s.users + ',' + s.sessions + '\r\n';
        });
        csv += '\r\n';

        // 5. Perangkat
        csv += '=== KATEGORI PERANGKAT ===\r\n';
        csv += 'No,Kategori Perangkat,Pengguna Aktif,Tayangan Halaman\r\n';
        (lastLoadedData.devices || []).forEach(function(d, idx) {
          csv += (idx + 1) + ',"' + d.label.replace(/"/g, '""') + '",' + d.users + ',' + d.views + '\r\n';
        });

        // Trigger Download
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        const dateStr = new Date().toISOString().slice(0, 10);
        a.href = url;
        a.download = 'prolabios-analitik-' + currentDays + 'hari-' + dateStr + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
      });
    }

    rangeBtns.forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        rangeBtns.forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        currentDays = parseInt(this.getAttribute('data-days'), 10) || 30;
        loadData(false);
      });
    });

    // Initial Load
    loadData(false);
  });
</script>
@endsection
