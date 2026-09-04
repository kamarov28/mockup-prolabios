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
    <div class="d-inline-flex gap-2 align-items-center">
      <div class="admin-view-switcher">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="admin-view-switcher-btn {{ ($viewMode ?? 'table') === 'table' ? 'active' : '' }}" title="Tampilan Tabel">
          <i class="bi bi-table"></i>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'kanban']) }}" class="admin-view-switcher-btn {{ ($viewMode ?? 'table') === 'kanban' ? 'active' : '' }}" title="Tampilan Papan Kanban">
          <i class="bi bi-kanban"></i>
        </a>
      </div>
      <a href="{{ route('admin.rfqs.export', request()->query()) }}" class="admin-btn admin-btn-ghost text-success" title="Download Excel/CSV sesuai filter saat ini">
        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Ekspor Excel
      </a>
      <span class="admin-badge admin-badge-muted px-3 py-2">
        Total: {{ ($viewMode ?? 'table') === 'kanban' ? ($totalRfqs ?? 0) : ($rfqs->total() ?? 0) }} Pengajuan
      </span>
    </div>
  </div>

  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.rfqs.index') }}" method="GET">
      @if(request('view') === 'kanban')
        <input type="hidden" name="view" value="kanban">
      @endif
      <div class="row g-3 align-items-center">
        <div class="col-md-4">
          <input type="text" name="s" id="local-search-input" class="form-control"
                 placeholder="Cari nomor RFQ, pemohon, instansi, WA..."
                 value="{{ request('s') }}" aria-label="Kata kunci pencarian">
        </div>
        <div class="col-md-3">
          <input type="text" name="product_name" class="form-control" value="{{ request('product_name') }}" placeholder="Filter nama produk / SKU..." aria-label="Filter Produk">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select" aria-label="Filter Status">
            <option value="">Semua status</option>
            @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
              <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-1">
          <button type="button" class="admin-btn admin-btn-ghost w-100 justify-content-center"
                  data-bs-toggle="collapse" data-bs-target="#rfqDateFilterCollapse"
                  aria-expanded="{{ (request('start_date') || request('end_date')) ? 'true' : 'false' }}"
                  aria-controls="rfqDateFilterCollapse" title="Filter Rentang Tanggal">
            <i class="bi bi-calendar-range"></i>
          </button>
        </div>
        <div class="col-md-2 d-flex gap-1">
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center" title="Terapkan Filter">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
          @if(request('s') || request('product_name') || request('status') || request('start_date') || request('end_date'))
            <a href="{{ route('admin.rfqs.index', request('view') === 'kanban' ? ['view' => 'kanban'] : []) }}" class="admin-btn admin-btn-ghost justify-content-center" title="Reset Filter">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>
      </div>

      <div class="collapse {{ (request('start_date') || request('end_date')) ? 'show' : '' }} mt-3" id="rfqDateFilterCollapse">
        <div style="border: 2px solid #1E1E1E; border-radius: 4px; padding: 14px 16px; background-color: var(--color-surface-2);">
          <div class="row g-3 align-items-end">
            <div class="col-md-5">
              <label class="admin-form-label" for="start_date">Dari Tanggal</label>
              <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-5">
              <label class="admin-form-label" for="end_date">Sampai Tanggal</label>
              <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
              <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
                <i class="bi bi-check2"></i> Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card-body-flush">
    @if(($viewMode ?? 'table') === 'kanban')
      <div style="display: flex; gap: 18px; padding: 20px; overflow-x: auto; min-height: 520px; background-color: var(--color-bg, #D6D0C5); align-items: flex-start;" class="table-responsive">
        @foreach($kanbanColumns as $statusKey => $column)
          <div style="flex: 0 0 310px; width: 310px; background: #FFFFFF; border: 2px solid #1E1E1E; border-radius: 6px; box-shadow: 4px 4px 0 #1E1E1E; display: flex; flex-direction: column;">
            <div style="padding: 12px 16px; border-bottom: 2px solid #1E1E1E; background: var(--color-surface-2, #EDE8E0); display: flex; justify-content: space-between; align-items: center;">
              <span style="font-family: var(--font-headline); font-weight: 700; font-size: 0.92rem; color: var(--color-text-main);">
                {{ $column['label'] }}
              </span>
              <span class="admin-badge admin-badge-muted" style="font-size: 0.72rem; padding: 2px 8px;">
                {{ $column['rfqs']->count() }}
              </span>
            </div>
            <div style="padding: 14px; display: flex; flex-direction: column; gap: 12px; max-height: 70vh; overflow-y: auto;">
              @forelse($column['rfqs'] as $rfq)
                <div style="background: #FFFFFF; border: 2px solid #1E1E1E; border-radius: 4px; padding: 14px; box-shadow: 2px 2px 0 #1E1E1E; transition: transform 0.15s ease;">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-accent, #A6171C); font-size: 0.88rem;">
                      {{ $rfq->rfq_number }}
                    </a>
                    <span style="font-size: 0.7rem; color: var(--color-text-muted);">
                      {{ $rfq->created_at ? $rfq->created_at->format('d/m H:i') : '-' }}
                    </span>
                  </div>
                  <div class="mb-2">
                    <strong class="d-block" style="font-size: 0.85rem; color: var(--color-text-main);">{{ $rfq->name }}</strong>
                    <span class="small" style="color: var(--color-text-muted); font-size: 0.78rem;">{{ $rfq->company_name }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center pt-2 mt-2" style="border-top: 1px solid var(--color-border); font-size: 0.75rem;">
                    <span class="admin-badge admin-badge-muted">
                      {{ $rfq->items->count() }} item
                    </span>
                    <div class="d-flex gap-2">
                      <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-success" title="Hubungi WA">
                        <i class="bi bi-whatsapp"></i>
                      </a>
                      <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="text-secondary" title="Buka Detail">
                        <i class="bi bi-arrow-up-right-square"></i>
                      </a>
                    </div>
                  </div>
                </div>
              @empty
                <div class="text-center py-4" style="color: var(--color-text-muted); font-size: 0.8rem;">
                  Tidak ada pengajuan
                </div>
              @endforelse
            </div>
          </div>
        @endforeach
      </div>
    @else
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
                    <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-accent, #A6171C);">
                      {{ $rfq->rfq_number }}
                    </a>
                  </td>
                  <td>
                    <span class="admin-badge {{ $rfq->status_badge_class }}">
                      {{ $rfq->status_label }}
                    </span>
                  </td>
                  <td>
                    <strong class="d-block" style="color: var(--color-text-main);">{{ $rfq->name }}</strong>
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
                    <span class="admin-badge admin-badge-muted">
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
                      <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" class="m-0 form-delete">
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
          <i class="bi bi-inbox" style="font-size: 3rem; color: var(--color-text-muted); opacity: 0.6;"></i>
          <h4 class="h6 mt-3 mb-1" style="color: var(--color-text-main); font-weight: 700;">Belum Ada Pengajuan RFQ</h4>
          <p class="small mb-0" style="color: var(--color-text-muted);">Pengajuan penawaran dari pelanggan akan otomatis tampil di tabel ini.</p>
        </div>
      @endif
    @endif
  </div>

</div>

@endsection
