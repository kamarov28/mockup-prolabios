@extends('admin.layout')

@section('title', 'Daftar Pengajuan RFQ')
@section('page_title', 'Pengajuan RFQ')

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Inquiry & Pengadaan</span>
    <h2 class="admin-page-title mb-1">Daftar Pengajuan RFQ</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Kelola permintaan penawaran harga resmi (Request for Quotation) dari pelanggan dan instansi.
    </p>
  </div>
  <div class="d-inline-flex align-items-center gap-2 flex-wrap">
    <div class="admin-view-switcher">
      <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}" class="admin-view-switcher-btn {{ ($viewMode ?? 'table') === 'table' ? 'active' : '' }}" title="Tampilan Tabel">
        <i data-lucide="table"></i>
      </a>
      <a href="{{ request()->fullUrlWithQuery(['view' => 'kanban']) }}" class="admin-view-switcher-btn {{ ($viewMode ?? 'table') === 'kanban' ? 'active' : '' }}" title="Tampilan Papan Kanban">
        <i data-lucide="kanban"></i>
      </a>
    </div>
    <a href="{{ route('admin.rfqs.export', request()->query()) }}" class="admin-btn admin-btn-outline" title="Download Excel/CSV sesuai filter saat ini">
      <i data-lucide="file-spreadsheet"></i> Ekspor Excel
    </a>
  </div>
</div>

<div class="admin-card">

  {{-- Filter Toolbar --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border); background: #FFFFFF;">
    <form action="{{ route('admin.rfqs.index') }}" method="GET" id="filter-rfqs-form">
      @if(request('view') === 'kanban')
        <input type="hidden" name="view" value="kanban">
      @endif
      <div class="row g-2 align-items-center">

        {{-- Search Input --}}
        <div class="col-lg-4 col-md-12">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 8px; overflow: hidden; background: #FFFFFF; transition: border-color 0.2s ease;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); background: #F8FAFC; border-right: 1px solid var(--color-border);">
              <i data-lucide="search" style="width: 15px; height: 15px;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 0 12px; color: var(--color-text-main); font-family: var(--font-body); font-size: 0.88rem; height: 38px;"
                   placeholder="Cari nomor RFQ, pemohon, instansi, WA..." value="{{ request('s') }}" aria-label="Kata kunci pencarian">
            @if(request('s'))
              <a href="{{ route('admin.rfqs.index', array_merge(request()->except('s'), request('view') === 'kanban' ? ['view' => 'kanban'] : [])) }}" style="display: flex; align-items: center; padding: 0 10px; color: var(--color-text-muted); text-decoration: none;" title="Hapus pencarian">
                <i data-lucide="x" style="width: 14px; height: 14px;"></i>
              </a>
            @endif
          </div>
        </div>

        {{-- Filter Product Name / Catalog --}}
        <div class="col-lg-3 col-md-4 col-sm-6">
          <input type="text" name="product_name" class="form-control" style="height: 38px; font-size: 0.85rem;" value="{{ request('product_name') }}" placeholder="Filter nama produk / katalog..." aria-label="Filter Produk">
        </div>

        {{-- Filter Status --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
          <select name="status" class="form-select" style="height: 38px; font-size: 0.85rem;" aria-label="Filter Status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
              <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        {{-- Toggle Date Filter --}}
        <div class="col-lg-1 col-md-2 col-sm-4">
          <button type="button" class="admin-btn admin-btn-outline w-100 justify-content-center {{ (request('start_date') || request('end_date')) ? 'active' : '' }}"
                  data-bs-toggle="collapse" data-bs-target="#rfqDateFilterCollapse"
                  aria-expanded="{{ (request('start_date') || request('end_date')) ? 'true' : 'false' }}"
                  aria-controls="rfqDateFilterCollapse" title="Filter Rentang Tanggal" style="height: 38px; font-size: 0.82rem;">
            <i data-lucide="calendar-range"></i>
            @if(request('start_date') || request('end_date'))
              <span class="badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 2px 5px;">•</span>
            @endif
          </button>
        </div>

        {{-- Filter Action Buttons --}}
        <div class="col-lg-2 col-md-2 col-sm-8 d-flex gap-1">
          <button type="submit" class="admin-btn admin-btn-primary flex-grow-1 justify-content-center" title="Terapkan Filter" style="height: 38px; font-size: 0.85rem;">
            <i data-lucide="filter"></i> Filter
          </button>
          @if(request('s') || request('product_name') || request('status') || request('start_date') || request('end_date'))
            <a href="{{ route('admin.rfqs.index', request('view') === 'kanban' ? ['view' => 'kanban'] : []) }}" class="admin-btn admin-btn-ghost justify-content-center" title="Reset Filter" style="height: 38px;">
              <i data-lucide="x"></i>
            </a>
          @endif
        </div>
      </div>

      {{-- Collapsible Date Filter --}}
      <div class="collapse {{ (request('start_date') || request('end_date')) ? 'show' : '' }} mt-3" id="rfqDateFilterCollapse">
        <div style="border: 1px solid var(--color-border); border-radius: 6px; padding: 16px; background: #F8FAFC;">
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
                <i data-lucide="check"></i> Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card-body-flush">
    @if(($viewMode ?? 'table') === 'kanban')
      <div style="display: flex; gap: 16px; padding: 20px; overflow-x: auto; min-height: 520px; background-color: var(--color-bg); align-items: flex-start;" class="table-responsive">
        @foreach($kanbanColumns as $statusKey => $column)
          <div style="flex: 0 0 300px; width: 300px; background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 6px; display: flex; flex-direction: column; overflow: hidden;">
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--color-border); background: var(--color-surface-2); display: flex; justify-content: space-between; align-items: center;">
              <span style="font-family: var(--font-headline); font-weight: 700; font-size: 0.9rem; color: var(--color-text-main);">
                {{ $column['label'] }}
              </span>
              <span class="admin-badge admin-badge-muted" style="font-size: 0.72rem; padding: 2px 8px;">
                {{ $column['rfqs']->count() }}
              </span>
            </div>
            <div style="padding: 12px; display: flex; flex-direction: column; gap: 10px; max-height: 70vh; overflow-y: auto;">
              @forelse($column['rfqs'] as $rfq)
                <div style="background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 6px; padding: 12px; transition: border-color 0.15s ease;">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-accent, #A6171C); font-family: var(--font-mono); font-size: 0.88rem;">
                      {{ $rfq->rfq_number }}
                    </a>
                    <span style="font-size: 0.72rem; color: var(--color-text-muted);">
                      {{ $rfq->created_at ? $rfq->created_at->format('d/m H:i') : '-' }}
                    </span>
                  </div>
                  <div class="mb-2">
                    <strong class="d-block" style="font-size: 0.85rem; color: var(--color-text-main);">{{ $rfq->name }}</strong>
                    <span class="small" style="color: var(--color-text-muted); font-size: 0.78rem;">{{ $rfq->company_name ?: '—' }}</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center pt-2 mt-2" style="border-top: 1px solid var(--color-border); font-size: 0.75rem;">
                    <span class="admin-badge admin-badge-muted">
                      {{ $rfq->items->count() }} item
                    </span>
                    <div class="d-inline-flex align-items-center gap-1">
                      <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="admin-action-link" style="color: #16A34A; width: 28px; height: 28px;" title="Hubungi WA">
                        <x-brand-icon name="whatsapp" size="13" />
                      </a>
                      <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="admin-action-link view" style="width: 28px; height: 28px;" title="Buka Detail">
                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
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
                <th style="width: 40px; text-align: center;">
                  <input type="checkbox" class="form-check-input select-all-checkbox" style="cursor: pointer;" title="Pilih Semua">
                </th>
                <th style="width: 140px;">Nomor RFQ</th>
                <th style="width: 130px;">Status</th>
                <th>Pemohon & Instansi</th>
                <th>Kontak</th>
                <th>Total Item</th>
                <th>Tanggal Masuk</th>
                <th style="text-align: right; padding-right: 24px; width: 120px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rfqs as $rfq)
                <tr>
                  <td style="text-align: center;">
                    <input type="checkbox" value="{{ $rfq->id }}" class="form-check-input row-checkbox" style="cursor: pointer;">
                  </td>
                  <td class="cell-code" style="white-space: nowrap;">
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
                    <div class="cell-title">{{ $rfq->name }}</div>
                    <div class="text-secondary small">{{ $rfq->company_name ?: '—' }}</div>
                  </td>
                  <td>
                    <div class="d-flex flex-column gap-1">
                      <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-decoration-none small d-inline-flex align-items-center gap-1" style="color: #16A34A; font-family: var(--font-mono); font-weight: 500;">
                        <x-brand-icon name="whatsapp" size="13" /> {{ $rfq->phone_wa }}
                      </a>
                      <span class="text-secondary small d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                        <i data-lucide="mail" style="width: 12px; height: 12px; color: var(--color-text-muted);"></i>
                        <span class="text-truncate" style="max-width: 180px;">{{ $rfq->email }}</span>
                      </span>
                    </div>
                  </td>
                  <td>
                    <span class="admin-badge admin-badge-muted" style="white-space: nowrap;">
                      {{ $rfq->items->count() }} Produk <span class="text-muted fw-normal">({{ $rfq->items->sum('quantity') }} unit)</span>
                    </span>
                  </td>
                  <td style="white-space: nowrap;">
                    <div class="d-inline-flex align-items-center gap-1 text-secondary small" style="font-size: 0.82rem;">
                      <i data-lucide="calendar" style="width: 13px; height: 13px; color: var(--color-text-muted);"></i>
                      <span>{{ $rfq->created_at ? $rfq->created_at->format('d M Y, H:i') : '—' }}</span>
                    </div>
                  </td>
                  <td style="text-align: right; padding-right: 20px; white-space: nowrap;">
                    <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                      <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="admin-action-link view" title="Buka Detail">
                        <i data-lucide="eye"></i>
                      </a>
                      <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="admin-action-link" style="color: #16A34A;" title="Hubungi via WhatsApp">
                        <x-brand-icon name="whatsapp" size="14" />
                      </a>
                      <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" class="d-inline form-delete m-0" data-name="{{ e($rfq->rfq_number) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-action-link delete" title="Hapus Pengajuan">
                          <i data-lucide="trash-2"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        @if($rfqs->hasPages())
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid var(--color-border);">
            <span style="font-size: 0.72rem; color: var(--color-text-muted); letter-spacing: 0.5px;">
              Halaman <strong style="color: var(--color-text-main);">{{ $rfqs->currentPage() }}</strong> dari <strong style="color: var(--color-text-main);">{{ $rfqs->lastPage() }}</strong>
            </span>
            <nav aria-label="Navigasi halaman">
              <ul class="pagination pagination-sm mb-0">
                <li class="page-item {{ $rfqs->onFirstPage() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $rfqs->previousPageUrl() ?? '#' }}" aria-label="Sebelumnya">
                    <i data-lucide="chevron-left"></i>
                  </a>
                </li>
                @for($i = 1; $i <= $rfqs->lastPage(); $i++)
                  <li class="page-item {{ $rfqs->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $rfqs->url($i) }}">{{ $i }}</a>
                  </li>
                @endfor
                <li class="page-item {{ !$rfqs->hasMorePages() ? 'disabled' : '' }}">
                  <a class="page-link" href="{{ $rfqs->nextPageUrl() ?? '#' }}" aria-label="Berikutnya">
                    <i data-lucide="chevron-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        @endif

      @else
        <x-admin.empty-state
          icon="inbox"
          message="Pengajuan RFQ tidak ditemukan. Coba ubah filter atau kata kunci pencarian."
          :action-url="route('admin.rfqs.index')"
          action-label="Reset Filter"
          action-class="admin-btn-ghost" />
      @endif
    @endif
  </div>

</div>

<x-admin.bulk-action-bar :route="route('admin.rfqs.bulk-destroy')" label="pengajuan RFQ" />

@endsection

@section('admin_scripts')
<script @nonce>
  const sg = document.getElementById('search-group');
  const si = document.getElementById('local-search-input');
  if (sg && si) {
    si.addEventListener('focus', () => sg.style.borderColor = 'var(--color-accent)');
    si.addEventListener('blur',  () => sg.style.borderColor = 'var(--color-border)');
  }

  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      Swal.fire({
        title: 'Hapus Pengajuan RFQ?',
        html: `Hapus pengajuan "<strong>${name}</strong>"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
          confirmButton: 'admin-btn admin-btn-danger mx-2',
          cancelButton: 'admin-btn admin-btn-ghost mx-2'
        },
        buttonsStyling: false
      }).then(r => { if (r.isConfirmed) this.submit(); });
    });
  });
</script>
@endsection
