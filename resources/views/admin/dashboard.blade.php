@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')

{{-- ── Stat Cards ─────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

  <div class="col-md-3">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Pengajuan RFQ</span>
      <div class="admin-stat-value" style="color: var(--color-accent, #FF4950);">{{ $rfqsCount }}</div>
      <a href="{{ route('admin.rfqs.index') }}" class="admin-stat-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

  <div class="col-md-3">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Total Produk</span>
      <div class="admin-stat-value">{{ $productsCount }}</div>
      <a href="{{ route('admin.products') }}" class="admin-stat-link">Kelola <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

  <div class="col-md-3">
    <div class="admin-stat-card">
      <span class="admin-stat-label">Total Artikel</span>
      <div class="admin-stat-value">{{ $postsCount }}</div>
      <a href="{{ route('admin.posts') }}" class="admin-stat-link">Kelola <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>

  <div class="col-md-3">
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

    {{-- Recent RFQ Inquiries --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Inquiry Masuk</span>
          <h2 class="admin-card-header-title">Pengajuan RFQ Terbaru</h2>
        </div>
        <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost">
          Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div class="admin-card-body-flush">
        @if(count($recentRfqs) > 0)
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Nomor RFQ</th>
                  <th>Status</th>
                  <th>Pemohon &amp; Instansi</th>
                  <th>Item</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentRfqs as $rfq)
                  <tr>
                    <td>
                      <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-accent, #FF4950);">
                        {{ $rfq->rfq_number }}
                      </a>
                    </td>
                    <td>
                      <span class="admin-badge {{ $rfq->status_badge_class }}">{{ $rfq->status_label }}</span>
                    </td>
                    <td>
                      <strong class="d-block text-white" style="font-size: 0.85rem;">{{ $rfq->name }}</strong>
                      <span class="text-secondary small">{{ $rfq->company_name }}</span>
                    </td>
                    <td>
                      <span class="admin-badge admin-badge-muted">{{ $rfq->items->count() }} item</span>
                    </td>
                    <td>
                      <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm">
                        <i class="bi bi-eye"></i> Detail
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <i class="bi bi-inbox" style="font-size: 2rem; color: var(--color-text-muted); opacity: 0.4;"></i>
            <p class="mt-2 mb-0" style="color: var(--color-text-muted); font-size: 0.85rem;">Belum ada pengajuan RFQ terbaru.</p>
          </div>
        @endif
      </div>
    </div>

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
                      <a href="{{ route('admin.products.edit', $p['id']) }}" class="cell-title text-decoration-none">{{ \Illuminate\Support\Str::limit($p['title'] ?? '', 40) }}</a>
                    </td>
                    <td><span class="admin-badge admin-badge-muted text-capitalize">{{ str_replace('-', ' ', $p['category'] ?? '') }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <p class="mb-0" style="color: var(--color-text-muted); font-size: 0.85rem;">Belum ada produk.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Recent Posts --}}
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Konten</span>
          <h2 class="admin-card-header-title">Artikel Terbaru</h2>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
          <i class="bi bi-plus-lg"></i> Tambah
        </a>
      </div>
      <div class="admin-card-body-flush">
        @if(count($recentPosts) > 0)
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Judul</th>
                  <th>Kategori</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentPosts as $post)
                  <tr>
                    <td>
                      <a href="{{ route('admin.posts.edit', $post['slug']) }}" class="cell-title text-decoration-none">{{ \Illuminate\Support\Str::limit($post['title'] ?? '', 50) }}</a>
                    </td>
                    <td><span class="admin-badge admin-badge-success text-capitalize">{{ $post['category'] ?? '—' }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <p class="mb-0" style="color: var(--color-text-muted); font-size: 0.85rem;">Belum ada artikel.</p>
          </div>
        @endif
      </div>
    </div>

  </div>

  {{-- Right: Chart --}}
  <div class="col-lg-4 d-flex flex-column gap-4">

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
    const el = document.getElementById('categoryChart');
    if (!el) return;
    const ctx = el.getContext('2d');
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
  });
</script>
@endsection
