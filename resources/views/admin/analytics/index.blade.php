@extends('admin.layout')

@section('title', 'Analitik Web Google Analytics 4')
@section('page_title', 'Analitik Web')

@section('admin_content')
<div class="dash-wrapper">

  {{-- ── 1. Header Strip ──────────────────────────────────────────────────────── --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <span class="admin-page-label">Laporan &amp; Intelijen Pengunjung</span>
      <h2 class="admin-page-title mb-0" style="font-size: 1.5rem;">Google Analytics 4 • Sebaran Wilayah &amp; Produk</h2>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
      {{-- Date Range Toggle --}}
      <div class="btn-group btn-group-sm" role="group" aria-label="Filter Rentang Waktu">
        <button type="button" class="btn btn-outline-secondary ga4-range-btn" data-days="7">7 Hari</button>
        <button type="button" class="btn btn-outline-secondary ga4-range-btn active" data-days="30">30 Hari</button>
        <button type="button" class="btn btn-outline-secondary ga4-range-btn" data-days="90">90 Hari</button>
      </div>

      <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" id="ga4-refresh-btn">
        <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
        <span>Segarkan</span>
      </button>

      <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
        <i data-lucide="sliders" style="width: 14px; height: 14px;"></i>
        <span>Pengaturan</span>
      </a>
    </div>
  </div>

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
                  <button type="submit" class="btn btn-sm btn-primary align-self-start d-inline-flex align-items-center gap-1">
                    <i data-lucide="upload" style="width: 13px; height: 13px;"></i>
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
                  <button type="submit" class="btn btn-sm btn-primary align-self-start d-inline-flex align-items-center gap-1">
                    <i data-lucide="save" style="width: 13px; height: 13px;"></i>
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
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon-wrap" style="background: #FEE2E2; color: var(--color-accent, #A6171C);">
          <i data-lucide="users"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Pengguna Aktif</span>
          <span class="dash-stat-val" id="kpi-total-users" style="color: var(--color-accent, #A6171C);">-</span>
          <span class="text-muted" style="font-size: 0.72rem;" id="kpi-users-sub">Rentang waktu terpilih</span>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon-wrap" style="background: #E0F2FE; color: #0284C7;">
          <i data-lucide="eye"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Tayangan Halaman</span>
          <span class="dash-stat-val" id="kpi-total-views" style="color: #0284C7;">-</span>
          <span class="text-muted" style="font-size: 0.72rem;" id="kpi-views-sub">Total Page Views</span>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon-wrap" style="background: #FEF3C7; color: #D97706;">
          <i data-lucide="activity"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Rasio Tayangan / User</span>
          <span class="dash-stat-val" id="kpi-ratio" style="color: #D97706;">-</span>
          <span class="text-muted" style="font-size: 0.72rem;">Kedalaman Interaksi</span>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="dash-stat-card">
        <div class="dash-stat-icon-wrap" style="background: #DCFCE7; color: #16A34A;">
          <i data-lucide="shield-check"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Status Google API</span>
          <span class="dash-stat-val" id="kpi-api-status" style="font-size: 1.1rem; color: #16A34A;">Menghubungkan</span>
          <span class="text-muted" style="font-size: 0.72rem;" id="kpi-last-sync">Sinkronisasi...</span>
        </div>
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

  {{-- ── 5. Technical Diagnostics & Credentials Info ─────────────────────────── --}}
  <div class="admin-card">
    <div class="admin-card-header py-2 px-3">
      <div class="d-flex align-items-center gap-2">
        <i data-lucide="info" style="width: 15px; height: 15px; color: var(--color-text-secondary);"></i>
        <h2 class="admin-card-header-title mb-0" style="font-size: 0.88rem;">Informasi Teknis &amp; Status Integrasi</h2>
      </div>
    </div>
    <div class="admin-card-body p-3">
      <div class="row g-3 small">
        <div class="col-md-4">
          <div class="p-2 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
            <span class="text-muted d-block" style="font-size: 0.72rem;">Google Service Account</span>
            <span class="fw-semibold text-break" style="font-size: 0.8rem;">
              {{ $status['service_email'] ?? 'Belum terpasang' }}
            </span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-2 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
            <span class="text-muted d-block" style="font-size: 0.72rem;">GA4 Property ID</span>
            <span class="fw-semibold font-monospace" style="font-size: 0.8rem;">
              {{ $status['property_id'] ?? 'Belum disetel' }}
            </span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-2 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
            <span class="text-muted d-block" style="font-size: 0.72rem;">Masa Berlaku Cache</span>
            <span class="fw-semibold" style="font-size: 0.8rem;">
              20 Menit (Gunakan tombol Segarkan untuk sinkronisasi paksa)
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let currentDays = 30;

    const kpiUsers = document.getElementById('kpi-total-users');
    const kpiViews = document.getElementById('kpi-total-views');
    const kpiRatio = document.getElementById('kpi-ratio');
    const kpiStatus = document.getElementById('kpi-api-status');
    const kpiSync = document.getElementById('kpi-last-sync');

    const regionsContainer = document.getElementById('regions-container');
    const pagesContainer = document.getElementById('pages-container');
    const refreshBtn = document.getElementById('ga4-refresh-btn');
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
      regionsContainer.innerHTML = errHtml;
      pagesContainer.innerHTML = errHtml;
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

      if (window.lucide) { lucide.createIcons(); }
    }

    function loadData(forceRefresh = false) {
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

      const params = new URLSearchParams({
        days: currentDays,
        ...(forceRefresh ? { refresh: '1' } : {})
      });

      fetch('{{ route('admin.analytics.data') }}?' + params.toString(), {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json' }
      })
      .then(function(res) { return res.json(); })
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
