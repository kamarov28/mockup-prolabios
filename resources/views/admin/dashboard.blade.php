@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')
<div class="row g-4 mb-4">
  <!-- Card: Products -->
  <div class="col-md-4">
    <div class="card bg-white border-start border-primary border-4 h-100 shadow-sm">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-bold small">Total Produk</span>
          <h2 class="display-6 fw-bold text-dark mt-1 mb-0">{{ $productsCount }}</h2>
        </div>
        <div class="bg-light-danger text-danger p-3 rounded-circle" style="background-color: rgba(211, 47, 47, 0.08);">
          <i class="bi bi-box-seam fs-1"></i>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 px-4">
        <a href="{{ route('admin.products') }}" class="text-decoration-none text-danger fw-semibold small">Kelola Produk &raquo;</a>
      </div>
    </div>
  </div>

  <!-- Card: Posts -->
  <div class="col-md-4">
    <div class="card bg-white border-start border-success border-4 h-100 shadow-sm">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-bold small">Total Artikel (Info)</span>
          <h2 class="display-6 fw-bold text-dark mt-1 mb-0">{{ $postsCount }}</h2>
        </div>
        <div class="bg-light-success text-success p-3 rounded-circle" style="background-color: rgba(25, 135, 84, 0.08);">
          <i class="bi bi-newspaper fs-1"></i>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 px-4">
        <a href="{{ route('admin.posts') }}" class="text-decoration-none text-success fw-semibold small">Kelola Artikel &raquo;</a>
      </div>
    </div>
  </div>

  <!-- Card: Sectors -->
  <div class="col-md-4">
    <div class="card bg-white border-start border-info border-4 h-100 shadow-sm">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-bold small">Sektor Industri</span>
          <h2 class="display-6 fw-bold text-dark mt-1 mb-0">{{ $sectorsCount }}</h2>
        </div>
        <div class="bg-light-info text-info p-3 rounded-circle" style="background-color: rgba(13, 202, 240, 0.08);">
          <i class="bi bi-collection fs-1"></i>
        </div>
      </div>
      <div class="card-footer bg-white border-0 py-3 px-4">
        <a href="{{ route('admin.sectors') }}" class="text-decoration-none text-info fw-semibold small">Kelola Sektor &raquo;</a>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Left: Recent Lists -->
  <div class="col-lg-8 d-flex flex-column gap-4">
    <!-- Recent Products -->
    <div class="card bg-white shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-box-seam text-danger me-2"></i>Produk Terbaru</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
      </div>
      <div class="card-body p-0">
        @if(count($recentProducts) > 0)
          <div class="table-responsive">
            <table class="table mb-0 align-middle">
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
                    <td class="text-muted small fw-semibold">{{ $p['catalog'] ?: '-' }}</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="rounded me-2" style="width: 35px; height: 35px; object-fit: contain; background: #f8f9fa;">
                        <span class="fw-semibold text-dark">{{ $p['title'] }}</span>
                      </div>
                    </td>
                    <td><span class="badge bg-light text-dark border text-capitalize">{{ str_replace('-', ' ', $p['category']) }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <p class="text-muted mb-0">Belum ada data produk.</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Recent Articles -->
    <div class="card bg-white shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-newspaper text-success me-2"></i>Artikel Terbaru</h2>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg me-1"></i>Tulis Artikel</a>
      </div>
      <div class="card-body p-0">
        @if(count($recentPosts) > 0)
          <div class="table-responsive">
            <table class="table mb-0 align-middle">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Judul Artikel</th>
                  <th>Kategori</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentPosts as $post)
                  <tr>
                    <td class="text-muted small">{{ $post['date'] }}</td>
                    <td><span class="fw-semibold text-dark">{{ Str::limit($post['title'], 45) }}</span></td>
                    <td><span class="badge bg-light text-success border text-capitalize">{{ $post['category'] }}</span></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-5">
            <p class="text-muted mb-0">Belum ada artikel diterbitkan.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Right: Sync & Chart -->
  <div class="col-lg-4 d-flex flex-column gap-4">
    <!-- Card: Google Sheets Sync -->
    <div class="card bg-white shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-google text-primary me-2"></i>Sinkronisasi Katalog</h2>
        <span class="badge bg-{{ ($homeData['last_sync_status'] ?? '') === 'success' ? 'success' : (($homeData['last_sync_status'] ?? '') === 'failed' ? 'danger' : 'secondary') }}">
          {{ ($homeData['last_sync_status'] ?? '') === 'success' ? 'Aktif / Sukses' : (($homeData['last_sync_status'] ?? '') === 'failed' ? 'Gagal' : 'Belum Pernah') }}
        </span>
      </div>
      <div class="card-body p-4">
        <p class="text-muted small">Hubungkan dan sinkronisasikan katalog produk di website ini dengan lembar kerja Google Sheets Anda secara langsung.</p>
        
        <div class="mb-3 p-3 bg-light rounded border text-muted small">
          <div class="d-flex justify-content-between mb-1">
            <span>Waktu Terakhir:</span>
            <span class="fw-bold text-dark" id="sync-time">{{ $homeData['last_sync_time'] ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Metode:</span>
            <span class="fw-bold text-dark">Google Sheets API</span>
          </div>
        </div>

        <button class="btn btn-primary w-100 fw-bold py-2" id="btn-trigger-sync">
          <span class="spinner-border spinner-border-sm d-none me-2" id="sync-spinner" role="status" aria-hidden="true"></span>
          <i class="bi bi-arrow-repeat me-1" id="sync-icon"></i> <span id="sync-text">Sinkronkan Sekarang</span>
        </button>

        <div class="mt-3 d-none" id="sync-log-container">
          <label class="form-label small fw-bold text-dark">Log Output Terakhir:</label>
          <pre class="bg-dark text-light p-2 rounded small" id="sync-log-text" style="max-height: 120px; overflow-y: auto; font-size: 0.75rem; white-space: pre-wrap;"></pre>
        </div>
        
        @if(!empty($homeData['last_sync_log']))
        <div class="mt-3" id="sync-log-container-saved">
          <label class="form-label small fw-bold text-dark">Log Output Terakhir:</label>
          <pre class="bg-dark text-light p-2 rounded small" style="max-height: 120px; overflow-y: auto; font-size: 0.75rem; white-space: pre-wrap;">{{ $homeData['last_sync_log'] }}</pre>
        </div>
        @endif
      </div>
    </div>

    <!-- Card: Product Distribution -->
    <div class="card bg-white shadow-sm">
      <div class="card-header">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-pie-chart text-warning me-2"></i>Penyebaran Produk</h2>
      </div>
      <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
        <div style="width: 100%; max-width: 200px; height: 200px; position: relative;">
          <canvas id="categoryChart"></canvas>
        </div>
        <div class="mt-4 w-100">
          <ul class="list-group list-group-flush small">
              @php 
                $colors = ['#D32F2F', '#0DCAF0', '#198754', '#6C757D', '#F59E0B', '#8B5CF6', '#EC4899', '#10B981'];
                $i = 0;
                $colorCount = count($colors);
              @endphp
              @foreach($categoryDist as $catName => $count)
                @php 
                  $currentColor = $colors[$i % $colorCount];
                  $i++;
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  <span><i class="bi bi-circle-fill me-2" style="color: {{ $currentColor }};"></i>{{ $catName }}</span>
                  <span class="fw-bold">{{ $count }}</span>
                </li>
              @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@section('admin_scripts')
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // 1. Chart.js Doughnut Init
      const ctx = document.getElementById('categoryChart').getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: {!! json_encode(array_keys($categoryDist)) !!},
          datasets: [{
            data: {!! json_encode(array_values($categoryDist)) !!},
            backgroundColor: ['#D32F2F', '#0DCAF0', '#198754', '#6C757D', '#F59E0B', '#8B5CF6', '#EC4899'],
            hoverOffset: 4,
            borderWidth: 2,
            borderColor: '#ffffff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          cutout: '70%'
        }
      });

      // 2. AJAX Trigger for Google Sheets Sync
      const btnSync = document.getElementById('btn-trigger-sync');
      if (btnSync) {
        btnSync.addEventListener('click', function(e) {
          e.preventDefault();
          
          const spinner = document.getElementById('sync-spinner');
          const icon = document.getElementById('sync-icon');
          const text = document.getElementById('sync-text');
          const logContainer = document.getElementById('sync-log-container');
          const logText = document.getElementById('sync-log-text');
          const savedLog = document.getElementById('sync-log-container-saved');
          
          // Disable button, show loading
          btnSync.disabled = true;
          spinner.classList.remove('d-none');
          icon.classList.add('d-none');
          text.textContent = 'Menyinkronkan...';
          
          fetch('{{ route("admin.sync-sheets") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(errData => { throw errData; });
            }
            return response.json();
          })
          .then(data => {
            btnSync.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
            text.textContent = 'Sinkronkan Sekarang';
            
            alert(data.message);
            location.reload();
          })
          .catch(err => {
            btnSync.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
            text.textContent = 'Sinkronkan Sekarang';
            
            alert(err.message || 'Terjadi kesalahan teknis saat sinkronisasi.');
            if (savedLog) savedLog.classList.add('d-none');
            logContainer.classList.remove('d-none');
            logText.textContent = err.log || err.message || JSON.stringify(err);
          });
        });
      }
    });
  </script>
@endsection
@endsection
