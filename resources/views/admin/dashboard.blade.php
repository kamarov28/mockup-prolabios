@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')

{{-- ── Stat Cards ─────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

  <div class="col-md-4">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Total Produk</span>
      <div class="admin-stat-value">{{ $productsCount }}</div>
      <a href="{{ route('admin.products') }}" class="admin-stat-link">Kelola <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Total Artikel</span>
      <div class="admin-stat-value">{{ $postsCount }}</div>
      <a href="{{ route('admin.posts') }}" class="admin-stat-link">Kelola <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Sektor Industri</span>
      <div class="admin-stat-value">{{ $sectorsCount }}</div>
      <a href="{{ route('admin.sectors') }}" class="admin-stat-link">Kelola <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

</div>

{{-- ── Content Grid ───────────────────────────────────────────────────────── --}}
<div class="row g-4">

  {{-- Left: Recent Lists --}}
  <div class="col-lg-8 d-flex flex-column gap-4">

    {{-- Recent Products --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Konten</span>
          <h2 class="admin-card-header-title">Produk Terbaru</h2>
        </div>
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
          <i class="bi bi-plus-lg"></i> Tambah
        </a>
      </div>
      <div class="admin-card-body-flush">
        @if(count($recentProducts) > 0)
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Katalog</th>
                  <th>Produk</th>
                  <th>Kategori</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentProducts as $p)
                  <tr>
                    <td class="cell-muted">{{ $p['catalog'] ?: '—' }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}"
                             style="width: 32px; height: 32px; object-fit: contain; background: rgba(255,255,255,0.03); border-radius: 4px; border: 1px solid var(--color-border);">
                        <span class="cell-title">{{ $p['title'] }}</span>
                      </div>
                    </td>
                    <td><span class="admin-badge admin-badge-muted text-capitalize">{{ str_replace('-', ' ', $p['category']) }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.4;"></i>
            <p class="mt-3" style="color: var(--color-text-muted); font-size: 0.85rem;">Belum ada data produk.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Recent Articles --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Konten</span>
          <h2 class="admin-card-header-title">Artikel Terbaru</h2>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
          <i class="bi bi-plus-lg"></i> Tulis
        </a>
      </div>
      <div class="admin-card-body-flush">
        @if(count($recentPosts) > 0)
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Judul</th>
                  <th>Kategori</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentPosts as $post)
                  <tr>
                    <td class="cell-muted" style="white-space: nowrap;">{{ $post['date'] }}</td>
                    <td class="cell-title">{{ Str::limit($post['title'], 48) }}</td>
                    <td><span class="admin-badge admin-badge-success text-capitalize">{{ $post['category'] }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <i class="bi bi-file-text" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.4;"></i>
            <p class="mt-3" style="color: var(--color-text-muted); font-size: 0.85rem;">Belum ada artikel diterbitkan.</p>
          </div>
        @endif
      </div>
    </div>

  </div>

  {{-- Right: Sync & Chart --}}
  <div class="col-lg-4 d-flex flex-column gap-4">

    {{-- Google Sheets Sync --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Integrasi</span>
          <h2 class="admin-card-header-title">Sinkronisasi</h2>
        </div>
        @php $syncStatus = $homeData['last_sync_status'] ?? '' @endphp
        <span class="admin-badge {{ $syncStatus === 'success' ? 'admin-badge-success' : ($syncStatus === 'failed' ? 'admin-badge-accent' : 'admin-badge-muted') }}">
          {{ $syncStatus === 'success' ? 'Sukses' : ($syncStatus === 'failed' ? 'Gagal' : 'Belum') }}
        </span>
      </div>
      <div class="admin-card-body">
        <p style="color: var(--color-text-muted); font-size: 0.82rem; line-height: 1.6; margin-bottom: 16px;">
          Hubungkan dan sinkronisasikan katalog produk dengan Google Sheets Anda.
        </p>

        {{-- Meta block --}}
        <div style="border: 1px solid var(--color-border); border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; display: flex; flex-direction: column; gap: 8px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted);">Terakhir</span>
            <span id="sync-time" style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-main);">{{ $homeData['last_sync_time'] ?? '—' }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted);">Metode</span>
            <span style="font-size: 0.8rem; font-weight: 600; color: var(--color-text-main);">Google Sheets API</span>
          </div>
        </div>

        <button class="admin-btn admin-btn-primary w-100 justify-content-center" id="btn-trigger-sync">
          <span class="spinner-border spinner-border-sm d-none me-1" id="sync-spinner" role="status" aria-hidden="true"></span>
          <i class="bi bi-arrow-repeat" id="sync-icon"></i>
          <span id="sync-text">Sinkronkan Sekarang</span>
        </button>

        <div class="mt-3 d-none" id="sync-log-container">
          <label style="font-size: 0.6rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">Log Output</label>
          <pre id="sync-log-text" style="max-height: 120px; overflow-y: auto; font-size: 0.73rem;"></pre>
        </div>

        @if(!empty($homeData['last_sync_log']))
        <div class="mt-3" id="sync-log-container-saved">
          <label style="font-size: 0.6rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--color-text-muted); display: block; margin-bottom: 6px;">Log Terakhir</label>
          <pre style="max-height: 120px; overflow-y: auto; font-size: 0.73rem;">{{ $homeData['last_sync_log'] }}</pre>
        </div>
        @endif
      </div>
    </div>

    {{-- Product Distribution Chart --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Statistik</span>
          <h2 class="admin-card-header-title">Sebaran Produk</h2>
        </div>
      </div>
      <div class="admin-card-body d-flex flex-column align-items-center">
        <div style="width: 100%; max-width: 180px; height: 180px; position: relative; margin-bottom: 24px;">
          <canvas id="categoryChart"></canvas>
        </div>
        <div class="w-100">
          @php
            $colors = ['#FF4950', '#60a5fa', '#34d399', '#f59e0b', '#a78bfa', '#f472b6', '#38bdf8', '#4ade80'];
            $ci = 0; $cc = count($colors);
          @endphp
          @foreach($categoryDist as $catName => $count)
            @php $col = $colors[$ci % $cc]; $ci++; @endphp
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--color-border);">
              <span style="font-size: 0.8rem; display: flex; align-items: center; gap: 8px; color: var(--color-text-muted);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $col }}; flex-shrink: 0;"></span>
                {{ $catName }}
              </span>
              <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-text-main);">{{ $count }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>
</div>

@endsection

@section('admin_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    // ── Doughnut Chart ────────────────────────────────────────────────────
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: {!! json_encode(array_keys($categoryDist)) !!},
        datasets: [{
          data: {!! json_encode(array_values($categoryDist)) !!},
          backgroundColor: ['#FF4950', '#60a5fa', '#34d399', '#f59e0b', '#a78bfa', '#f472b6', '#38bdf8'],
          hoverOffset: 6,
          borderWidth: 2,
          borderColor: '#0e0e10'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '72%'
      }
    });

    // ── Google Sheets Sync ────────────────────────────────────────────────
    const btnSync = document.getElementById('btn-trigger-sync');
    if (btnSync) {
      btnSync.addEventListener('click', function(e) {
        e.preventDefault();
        const spinner   = document.getElementById('sync-spinner');
        const icon      = document.getElementById('sync-icon');
        const text      = document.getElementById('sync-text');
        const logCont   = document.getElementById('sync-log-container');
        const logText   = document.getElementById('sync-log-text');
        const savedLog  = document.getElementById('sync-log-container-saved');

        btnSync.disabled = true;
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');
        text.textContent = 'Menyinkronkan...';

        fetch('{{ route("admin.sync-sheets") }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))
        .then(data => {
          btnSync.disabled = false;
          spinner.classList.add('d-none');
          icon.classList.remove('d-none');
          text.textContent = 'Sinkronkan Sekarang';
          // Use global Toast from layout
          Toast.fire({ icon: 'success', title: data.message });
          setTimeout(() => location.reload(), 1500);
        })
        .catch(err => {
          btnSync.disabled = false;
          spinner.classList.add('d-none');
          icon.classList.remove('d-none');
          text.textContent = 'Sinkronkan Sekarang';
          Toast.fire({ icon: 'error', title: err.message || 'Terjadi kesalahan sinkronisasi.' });
          if (savedLog) savedLog.classList.add('d-none');
          logCont.classList.remove('d-none');
          logText.textContent = err.log || err.message || JSON.stringify(err);
        });
      });
    }

  });
</script>
@endsection
