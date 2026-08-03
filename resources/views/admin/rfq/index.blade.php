@extends('admin.layout')

@section('title', 'Kelola Penawaran B2B (RFQ)')
@section('page_title', 'Penawaran B2B')

@section('admin_content')

<div class="admin-card">

  {{-- Header --}}
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Procurement B2B</span>
      <h2 class="admin-card-header-title">Pengajuan Penawaran (RFQ)</h2>
    </div>
  </div>

  {{-- Filter Form --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.rfq') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-6">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted);">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main);"
                   placeholder="Cari No. RFQ, Nama PT, PIC, Email..." value="{{ request('s') }}">
          </div>
        </div>
        <div class="col-md-4">
          <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review (Baru)</option>
            <option value="quotation_sent" {{ request('status') === 'quotation_sent' ? 'selected' : '' }}>Penawaran Terkirim</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
          </select>
        </div>
        <div class="col-md-2">
          <a href="{{ route('admin.rfq') }}" class="admin-btn admin-btn-ghost w-100 justify-content-center">Reset</a>
        </div>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="admin-card-body-flush">
    @if(count($rfqs) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>No. RFQ</th>
              <th>Nama Perusahaan</th>
              <th>PIC &amp; Kontak</th>
              <th>Status</th>
              <th>Total Penawaran</th>
              <th>Tanggal</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rfqs as $rfq)
              <tr>
                <td class="cell-code fw-bold text-danger">{{ $rfq->rfq_number }}</td>
                <td>
                  <div class="cell-title">{{ $rfq->company_name }}</div>
                  <div class="cell-muted">NIB/NPWP: {{ $rfq->company_tax_id ?: '—' }}</div>
                </td>
                <td>
                  <div><strong>{{ $rfq->pic_name }}</strong> ({{ $rfq->pic_position ?: 'Procurement' }})</div>
                  <div class="cell-muted">{{ $rfq->email }} • WA: {{ $rfq->phone_wa }}</div>
                </td>
                <td>
                  @if($rfq->status === 'pending_review')
                    <span class="admin-badge admin-badge-warning">Pending Review</span>
                  @elseif($rfq->status === 'quotation_sent')
                    <span class="admin-badge admin-badge-info">Penawaran Sent</span>
                  @elseif($rfq->status === 'approved')
                    <span class="admin-badge admin-badge-success">Disetujui</span>
                  @else
                    <span class="admin-badge admin-badge-muted">{{ ucfirst($rfq->status) }}</span>
                  @endif
                </td>
                <td style="white-space: nowrap; font-weight: 600; color: var(--color-accent);">
                  Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}
                </td>
                <td class="cell-muted" style="white-space: nowrap;">
                  {{ $rfq->created_at ? $rfq->created_at->format('d/m/Y H:i') : '—' }}
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  @if($rfq->status === 'pending_review')
                    <a href="{{ route('admin.rfq.respond', ['id' => $rfq->id]) }}" class="admin-btn admin-btn-primary py-1 px-3" title="Beri Feedback Penawaran">
                      <i class="bi bi-pencil-square me-1"></i> Feedback &amp; Respon
                    </a>
                  @else
                    <a href="{{ route('admin.rfq.respond', ['id' => $rfq->id]) }}" class="admin-btn admin-btn-ghost py-1 px-3 text-info border-info border-opacity-25" title="Lihat atau Kirim Revisi Penawaran">
                      <i class="bi bi-eye me-1"></i> Lihat / Edit Revisi
                    </a>
                  @endif
                  <form action="{{ route('admin.rfq.destroy', ['id' => $rfq->id]) }}" method="POST" class="d-inline form-delete" data-name="{{ $rfq->rfq_number }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-action-link delete" title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($rfqs->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid var(--color-border);">
          {{ $rfqs->links() }}
        </div>
      @endif

    @else
      <div class="text-center py-5" style="color: var(--color-text-muted);">
        <i class="bi bi-file-earmark-text" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        <p style="font-size: 0.88rem;">Belum ada pengajuan penawaran (RFQ) dari korporasi.</p>
      </div>
    @endif
  </div>

</div>
@endsection
