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
      <span class="badge bg-secondary bg-opacity-20 text-white px-3 py-2 border border-secondary border-opacity-30">
        Total: {{ $rfqs->total() }} Pengajuan
      </span>
    </div>
  </div>

  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.rfqs.index') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-4">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); border-right: 1px solid var(--color-border);">
              <i class="bi bi-search" style="font-size: 0.8rem;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main); font-size: 0.88rem;"
                   placeholder="Cari nomor RFQ, nama, instansi, WA, email..." value="{{ request('s') }}" aria-label="Kata kunci pencarian">
          </div>
        </div>
        <div class="col-md-2">
          <select name="status" class="form-select" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
            <option value="">Semua status</option>
            @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
              <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" aria-label="Dari Tanggal" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
        </div>
        <div class="col-md-2">
          <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" aria-label="Sampai Tanggal" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
        </div>
        <div class="col-md-2 d-flex gap-1">
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center" title="Filter Data">
            <i class="bi bi-funnel"></i>
          </button>
          @if(request('s') || request('status') || request('start_date') || request('end_date'))
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
                  <span class="badge border px-2 py-1 small {{ $rfq->status_badge_class }}">
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

      @if($rfqs->hasPages())
        <div class="p-3 border-top border-secondary border-opacity-10 d-flex justify-content-center">
          {{ $rfqs->links() }}
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
