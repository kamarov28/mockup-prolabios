@extends('layouts.app')

@section('title', 'Status Penawaran ' . $rfq->rfq_number . ' | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body); min-height: 80vh;">
  <div class="container py-4">
    
    <div class="max-w-4xl mx-auto">

      @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-20 alert-dismissible fade show mb-4" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('info'))
        <div class="alert alert-info bg-info bg-opacity-10 text-info border-info border-opacity-20 alert-dismissible fade show mb-4" role="alert">
          <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card border-0 p-4 rounded-3 shadow-sm mb-4" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--color-border) !important;">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-20">
          <div>
            <span class="badge bg-danger bg-opacity-20 text-danger px-3 py-1 text-uppercase tracking-wider fw-semibold mb-1">Status Pengajuan RFQ</span>
            <h1 class="h3 fw-bold text-white mb-0">{{ $rfq->rfq_number }}</h1>
          </div>
          <div>
            @if($rfq->status === 'pending_review')
              <span class="badge bg-warning text-dark fs-6 px-3 py-2">Menunggu Penawaran Admin</span>
            @elseif($rfq->status === 'quotation_sent')
              <span class="badge bg-primary text-white fs-6 px-3 py-2">Penawaran Resmi Diterbitkan</span>
            @elseif($rfq->status === 'approved')
              <span class="badge bg-success text-white fs-6 px-3 py-2"><i class="bi bi-check-all me-1"></i> Penawaran Disetujui (Order Processed)</span>
            @else
              <span class="badge bg-secondary text-white fs-6 px-3 py-2">{{ ucfirst($rfq->status) }}</span>
            @endif
          </div>
        </div>

        <div class="row g-3 text-white small mb-4">
          <div class="col-md-6">
            <div class="p-3 rounded bg-dark border border-secondary border-opacity-20">
              <strong class="text-muted d-block mb-1">Data Perusahaan Korporasi:</strong>
              <div class="fw-bold text-white">{{ $rfq->company_name }}</div>
              <div class="text-secondary">NIB/NPWP: {{ $rfq->company_tax_id ?: '-' }}</div>
              <div class="text-secondary">PIC: {{ $rfq->pic_name }} ({{ $rfq->pic_position ?: 'Procurement' }})</div>
              <div class="text-secondary">Email: {{ $rfq->email }} • WA: {{ $rfq->phone_wa }}</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3 rounded bg-dark border border-secondary border-opacity-20">
              <strong class="text-muted d-block mb-1">Lokasi Pengiriman:</strong>
              <div class="text-secondary mb-2">{{ $rfq->address }}</div>
              @if($rfq->valid_until)
                <div class="text-info"><i class="bi bi-clock me-1"></i> Penawaran berlaku s/d: <strong>{{ $rfq->valid_until->format('d F Y') }}</strong></div>
              @endif
            </div>
          </div>
        </div>

        @if($rfq->admin_response_notes)
          <div class="p-3 mb-4 rounded bg-info bg-opacity-10 text-info border border-info border-opacity-20 small">
            <strong><i class="bi bi-chat-left-text me-1"></i> Catatan Penawaran dari Tim Sales Prolabios:</strong><br>
            {{ $rfq->admin_response_notes }}
          </div>
        @endif

        <h3 class="h6 fw-bold text-white mb-3">Rincian Item &amp; Harga Penawaran:</h3>
        <div class="table-responsive mb-4">
          <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                <th>No. Katalog</th>
                <th>Item Produk</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: right;">Harga Satuan (Rp)</th>
                <th style="text-align: right;">Subtotal (Rp)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rfq->items as $item)
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                  <td style="color: var(--color-text-muted); font-size: 0.85rem;">{{ $item->catalog_no ?: '—' }}</td>
                  <td class="fw-semibold text-white">{{ $item->product_title }}</td>
                  <td style="text-align: center;">{{ $item->quantity }} Unit</td>
                  <td style="text-align: right; color: var(--color-text-muted);">
                    Rp {{ number_format($item->offered_price, 0, ',', '.') }}
                  </td>
                  <td style="text-align: right; font-weight: 600; color: var(--color-accent);">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-end fw-bold text-white pt-3">TOTAL PENAWARAN HARGA RESMI:</td>
                <td class="text-end fw-bold fs-5 text-danger pt-3">
                  Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top border-secondary border-opacity-20">
          @if(in_array($rfq->status, ['quotation_sent', 'approved']))
            <a href="{{ route('rfq.pdf', ['number' => $rfq->rfq_number]) }}" target="_blank" class="btn btn-outline-light rounded-pill px-4">
              <i class="bi bi-printer me-2"></i> Cetak / Download Official Quotation PDF
            </a>
          @else
            <div class="text-secondary small d-flex align-items-center gap-2">
              <i class="bi bi-hourglass-split text-warning fs-5"></i>
              <span>Dokumen Surat Penawaran Resmi (PDF) akan dapat diunduh setelah disetujui &amp; diterbitkan oleh Tim Sales.</span>
            </div>
          @endif

          @if($rfq->status === 'quotation_sent')
            <form action="{{ \Illuminate\Support\Facades\URL::signedRoute('rfq.approve', ['number' => $rfq->rfq_number]) }}" method="POST" onsubmit="confirmApproveRFQ(event, this);">
              @csrf
              <button type="submit" class="btn btn-success px-4 py-2 fw-bold rounded-pill" style="background: #2e7d32; border-color: #2e7d32; box-shadow: 0 4px 14px rgba(46, 125, 50, 0.4);">
                <i class="bi bi-check-circle-fill me-2"></i> Setujui Penawaran &amp; Proses PO
              </button>
            </form>
          @elseif($rfq->status === 'approved')
            <div class="d-flex flex-wrap align-items-center gap-3">
              <span class="badge px-3 py-2 text-success bg-success bg-opacity-15 border border-success border-opacity-30 rounded-pill small fw-semibold">
                <i class="bi bi-check-circle-fill me-1"></i> Penawaran Disetujui &amp; Stok Teralokasi
              </span>
              <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Tim Sales Prolabios, kami telah menyetujui Penawaran RFQ: ' . $rfq->rfq_number . '. Mohon informasi alur pengiriman dan penerbitan Invoice.') }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 fw-semibold">
                <i class="bi bi-whatsapp me-1"></i> Konfirmasi Pengiriman &amp; Invoice via WA
              </a>
            </div>
          @endif
        </div>

      </div>

    </div>
  </div>
</section>

@push('scripts')
<script>
  function confirmApproveRFQ(e, form) {
    e.preventDefault();
    if (typeof Swal === 'undefined') {
      if (confirm('Apakah Anda menyetujui penawaran harga resmi ini? Stok akan otomatis berkurang untuk memproses pesanan Anda.')) {
        form.submit();
      }
      return;
    }

    Swal.fire({
      title: 'Setujui Penawaran & Proses PO?',
      text: 'Stok produk akan otomatis teralokasi dan tim sales kami akan menerbitkan invoice & jadwal pengiriman.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#2e7d32',
      cancelButtonColor: 'rgba(255, 255, 255, 0.15)',
      confirmButtonText: 'Ya, Setujui Penawaran!',
      cancelButtonText: 'Batal',
      background: '#0f172a',
      color: '#ffffff'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  }
</script>
@endpush
@endsection
