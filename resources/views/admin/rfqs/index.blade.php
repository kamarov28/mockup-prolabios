@extends('admin.layout')

@section('title', 'Daftar Pengajuan RFQ')
@section('page_title', 'Pengajuan RFQ')

@section('admin_content')

<div class="admin-card">

  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Inquiry & Penawaran</span>
      <h2 class="admin-card-header-title">Daftar Pengajuan Masuk</h2>
    </div>
    <div class="d-inline-flex gap-2">
      <a href="{{ route('admin.rfqs.export', request()->query()) }}" class="admin-btn admin-btn-ghost text-success" title="Download Excel/CSV sesuai filter saat ini">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Excel
      </a>
      <span class="badge bg-secondary bg-opacity-20 text-white px-3 py-2 border border-secondary border-opacity-30">
        Total: {{ $rfqs->total() }} Pengajuan
      </span>
    </div>
  </div>

  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.rfqs.index') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-3">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); border-right: 1px solid var(--color-border);">
              <i class="bi bi-search" style="font-size: 0.8rem;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main); font-size: 0.88rem;"
                   placeholder="Cari RFQ, pemohon, instansi, WA..." value="{{ request('s') }}" aria-label="Kata kunci pencarian">
          </div>
        </div>
        <div class="col-md-3">
          <input type="text" name="product_name" class="form-control" value="{{ request('product_name') }}" placeholder="Filter nama produk / SKU..." aria-label="Filter Produk" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
            <option value="">Semua status</option>
            @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
              <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <div class="input-group">
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" title="Dari Tanggal" aria-label="Dari Tanggal" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
            <span class="input-group-text" style="background: transparent; color: var(--color-text-muted); border-color: var(--color-border);">-</span>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" title="Sampai Tanggal" aria-label="Sampai Tanggal" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
          </div>
        </div>
        <div class="col-md-1 d-flex gap-1">
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center" title="Terapkan Filter">
            <i class="bi bi-funnel"></i>
          </button>
          @if(request('s') || request('product_name') || request('status') || request('start_date') || request('end_date'))
            <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost justify-content-center" title="Reset Filter">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card-body-flush">
    @if(count($rfqs) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Nomor RFQ</th>
              <th>Status</th>
              <th>Pemohon & Instansi</th>
              <th>Kontak</th>
              <th>Total Item</th>
              <th>Tanggal Masuk</th>
              <th style="text-align: right;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rfqs as $rfq)
              <tr>
                <td>
                  <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-accent, #FF4950);">
                    {{ $rfq->rfq_number }}
                  </a>
                </td>
                <td>
                  <span class="admin-badge {{ $rfq->status_badge_class }}">
                    {{ $rfq->status_label }}
                  </span>
                </td>
                <td>
                  <strong class="d-block text-white">{{ $rfq->name }}</strong>
                  <span class="text-secondary small">{{ $rfq->company_name }}</span>
                </td>
                <td>
                  <div>
                    <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-decoration-none text-success small d-inline-flex align-items-center gap-1">
                      <i class="bi bi-whatsapp"></i> {{ $rfq->phone_wa }}
                    </a>
                  </div>
                  <div class="text-secondary small">
                    <i class="bi bi-envelope me-1"></i>{{ $rfq->email }}
                  </div>
                </td>
                <td>
                  <span class="badge bg-secondary bg-opacity-20 text-light border border-secondary border-opacity-30">
                    {{ $rfq->items->count() }} Produk ({{ $rfq->items->sum('quantity') }} unit)
                  </span>
                </td>
                <td>
                  <span class="text-secondary small">
                    {{ $rfq->created_at ? $rfq->created_at->format('d M Y, H:i') : '-' }}
                  </span>
                </td>
                <td style="text-align: right;">
                  <div class="d-inline-flex gap-2">
                    <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="admin-btn admin-btn-ghost admin-btn-sm" title="Lihat Detail">
                      <i class="bi bi-eye"></i> Detail
                    </a>
                    <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" onsubmit="return confirm('Hapus data pengajuan ini?');" class="m-0">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="admin-btn admin-btn-danger admin-btn-sm" title="Hapus Pengajuan">
                        <i class="bi bi-trash3"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination — sama style dengan tabel produk --}}
      @if($rfqs->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid var(--color-border);">
          <span style="font-size: 0.72rem; color: var(--color-text-muted); letter-spacing: 0.5px;">
            Halaman <strong style="color: var(--color-text-main);">{{ $rfqs->currentPage() }}</strong> dari <strong style="color: var(--color-text-main);">{{ $rfqs->lastPage() }}</strong>
          </span>
          <nav aria-label="Navigasi halaman">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item {{ $rfqs->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $rfqs->previousPageUrl() ?? '#' }}" aria-label="Sebelumnya">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              @for($i = 1; $i <= $rfqs->lastPage(); $i++)
                <li class="page-item {{ $rfqs->currentPage() == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ $rfqs->url($i) }}">{{ $i }}</a>
                </li>
              @endfor
              <li class="page-item {{ !$rfqs->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $rfqs->nextPageUrl() ?? '#' }}" aria-label="Berikutnya">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      @endif

    @else
      <div class="text-center py-5">
        <i class="bi bi-inbox text-secondary" style="font-size: 3rem; opacity: 0.5;"></i>
        <h4 class="h6 text-white mt-3 mb-1">Belum Ada Pengajuan RFQ</h4>
        <p class="text-secondary small mb-0">Pengajuan penawaran dari pelanggan akan otomatis tampil di tabel ini.</p>
      </div>
    @endif
  </div>

</div>

@endsection
