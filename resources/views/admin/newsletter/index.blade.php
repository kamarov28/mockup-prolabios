@extends('admin.layout')

@section('title', 'Manajemen Buletin & Siaran Sektor')
@section('page_title', 'Buletin & Siaran Sektor')

@section('admin_content')
<div class="admin-content-wrap">

  <x-admin.page-header
    title="Manajemen Buletin & Siaran Sektor"
    subtitle="Kelola basis data pelanggan institusi laboratorium dan kirimkan pembaruan produk / regulasi tersegmentasi per sektor industri."
    badge="Communication Hub">
    <x-slot:actions>
      <button type="button" class="admin-btn admin-btn-outline" data-bs-toggle="modal" data-bs-target="#importNewsletterModal">
        <i data-lucide="file-spreadsheet"></i> Impor Excel
      </button>
      <a href="{{ route('admin.newsletter.create') }}" class="admin-btn admin-btn-primary">
        <i data-lucide="send"></i> Buat Siaran Pesan Baru
      </a>
    </x-slot:actions>
  </x-admin.page-header>

  <!-- Metric Badges -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="admin-stat-card">
        <span class="admin-stat-label">Pelanggan Aktif</span>
        <div class="admin-stat-value" style="color: var(--color-accent, #A6171C);">{{ $totalSubscribers }}</div>
        <span class="text-muted" style="font-size: 0.78rem;">Institusi &amp; analis lab</span>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="admin-stat-card">
        <span class="admin-stat-label">Total Siaran Terkirim</span>
        <div class="admin-stat-value" style="color: #0F172A;">{{ $totalBroadcasts }}</div>
        <span class="text-muted" style="font-size: 0.78rem;">Kampanye email resmi</span>
      </div>
    </div>
  </div>

  <!-- Subscribers Table -->
  <div class="admin-card mb-4">
    <div class="admin-card-header flex-wrap">
      <div>
        <span class="admin-card-header-label">DATABASE SUBSCRIBER</span>
        <h2 class="admin-card-header-title">Daftar Pelanggan Terdaftar</h2>
      </div>
      
      <!-- Sector Filter Form -->
      <form action="{{ route('admin.newsletter.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
        <select name="sector" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 0.82rem; width: 190px;">
          <option value="">-- Semua Sektor --</option>
          @foreach($sectors as $sec)
            <option value="{{ $sec->id }}" {{ $sectorFilter === $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
          @endforeach
        </select>
        @if($sectorFilter)
          <a href="{{ route('admin.newsletter.index') }}" class="admin-btn admin-btn-outline admin-btn-sm" title="Reset filter">Reset</a>
        @endif
      </form>
    </div>

    <div class="table-responsive">
      <table class="admin-table align-middle mb-0">
        <thead>
          <tr>
            <th>NAMA &amp; EMAIL</th>
            <th>PERUSAHAAN / INSTANSI</th>
            <th>KOTA</th>
            <th>SEKTOR MINAT</th>
            <th>STATUS</th>
            <th>TERDAFTAR</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscribers as $sub)
            <tr>
              <td>
                <strong class="d-block text-dark">{{ $sub->full_name }}</strong>
                <span class="text-muted small">{{ $sub->email }}</span>
              </td>
              <td>{{ $sub->company }}</td>
              <td>{{ $sub->city ?: '—' }}</td>
              <td>
                <div class="d-flex flex-wrap gap-1">
                  @foreach($sub->sectors as $s)
                    <span class="admin-badge admin-badge-neutral" style="font-size: 0.72rem;">
                      {{ $s->name }}
                    </span>
                  @endforeach
                </div>
              </td>
              <td>
                @if($sub->is_active)
                  <span class="admin-badge admin-badge-success" style="font-size: 0.72rem;">Aktif</span>
                @else
                  <span class="admin-badge admin-badge-neutral" style="font-size: 0.72rem;">Unsubscribed</span>
                @endif
              </td>
              <td class="text-muted small">{{ $sub->created_at->format('d/m/Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada pelanggan terdaftar di sektor ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($subscribers->hasPages())
      <div class="p-3 border-top">
        {{ $subscribers->links() }}
      </div>
    @endif
  </div>

  <!-- Recent Broadcasts History -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <span class="admin-card-header-label">LOG BROADCAST</span>
        <h2 class="admin-card-header-title">Riwayat Siaran Email (Broadcasts)</h2>
      </div>
    </div>

    <div class="table-responsive">
      <table class="admin-table align-middle mb-0">
        <thead>
          <tr>
            <th>SUBJEK PESAN</th>
            <th>TARGET SEKTOR</th>
            <th>TIPE</th>
            <th>PENERIMA</th>
            <th>PENGIRIM</th>
            <th>WAKTU KIRIM</th>
          </tr>
        </thead>
        <tbody>
          @forelse($broadcasts as $bc)
            <tr>
              <td>
                <strong class="text-dark">{{ $bc->subject }}</strong>
              </td>
              <td>
                @if($bc->targetSector)
                  <span class="admin-badge admin-badge-accent" style="font-size: 0.72rem;">{{ $bc->targetSector->name }}</span>
                @else
                  <span class="admin-badge admin-badge-neutral" style="font-size: 0.72rem;">Semua Sektor</span>
                @endif
              </td>
              <td>
                <span class="admin-badge admin-badge-neutral" style="font-size: 0.72rem;">{{ strtoupper(str_replace('_', ' ', $bc->type)) }}</span>
              </td>
              <td><span class="fw-semibold">{{ $bc->recipients_count }}</span> kontak</td>
              <td>{{ $bc->sender?->name ?? 'Sistem' }}</td>
              <td class="text-muted small">{{ $bc->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada siaran email yang dikirim.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($broadcasts->hasPages())
      <div class="p-3 border-top">
        {{ $broadcasts->links() }}
      </div>
    @endif
  </div>

</div>

{{-- Modal Import Excel / Spreadsheet Pelanggan Buletin --}}
<div class="modal fade" id="importNewsletterModal" tabindex="-1" aria-labelledby="importNewsletterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border: 2px solid var(--color-border); border-radius: 12px; box-shadow: var(--shadow-md);">
      <div class="modal-header" style="border-bottom: 1px solid var(--color-border); background: var(--color-surface-2);">
        <div>
          <span class="admin-badge admin-badge-accent mb-1">Subscriber Ingestion</span>
          <h5 class="modal-title fw-bold" id="importNewsletterModalLabel" style="color: var(--color-text-main);">Impor Pelanggan via Excel / CSV</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.newsletter.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3 p-3" style="background: #FFF5F5; border: 1px solid #FED7D7; border-radius: 8px;">
            <div class="d-flex align-items-start gap-2">
              <i data-lucide="info" style="color: var(--color-accent); flex-shrink: 0; width: 18px; height: 18px; margin-top: 2px;"></i>
              <div class="small" style="color: #742A2A;">
                <strong>Panduan Pengisian:</strong>
                <ul class="mb-0 ps-3 mt-1" style="font-size: 0.82rem;">
                  <li>Unduh template resmi berformat multi-sheet di bawah.</li>
                  <li><strong>Sheet 1 (Data Pelanggan)</strong>: Email & Nama Depan wajib diisi. Sektor minat dapat diisi lebih dari satu (pisahkan koma).</li>
                  <li><strong>Sheet 2 (Panduan & Referensi)</strong>: Berisi daftar resmi ID & nama Sektor Industri laboratorium Prolabios.</li>
                  <li>Email yang sudah terdaftar akan otomatis dilewati (anti-duplikasi).</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="mb-3 text-center">
            <a href="{{ route('admin.newsletter.import.template') }}" class="admin-btn admin-btn-outline w-100 justify-content-center">
              <i data-lucide="download"></i> Unduh Template Excel (.xlsx)
            </a>
          </div>

          <div class="mb-2">
            <label for="excel_file" class="admin-form-label">Pilih File Spreadsheet <span style="color: var(--color-accent);">*</span></label>
            <input class="form-control" type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required>
            <div class="form-text small" style="color: var(--color-text-muted);">Mendukung format .xlsx, .xls, atau .csv (Maks. 10MB)</div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--color-border); background: var(--color-surface-2);">
          <button type="button" class="admin-btn admin-btn-outline" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="admin-btn admin-btn-primary">
            <i data-lucide="cloud-upload"></i> Upload & Proses Impor
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
