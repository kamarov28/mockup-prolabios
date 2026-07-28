@extends('admin.layout')

@section('title', 'Feedback Penawaran ' . $rfq->rfq_number)
@section('page_title', 'Respon Penawaran RFQ')

@section('admin_content')
<div class="card bg-white shadow-sm max-w-4xl mx-auto">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-bold text-dark">
      <i class="bi bi-file-earmark-text text-danger me-2"></i> Feedback Penawaran RFQ: {{ $rfq->rfq_number }}
    </h5>
    <a href="{{ route('rfq.pdf', ['number' => $rfq->rfq_number]) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-printer me-1"></i> Preview PDF Quotation
    </a>
  </div>

  <div class="card-body p-4">

    <!-- Information Grid -->
    <div class="row g-3 mb-4 p-3 rounded bg-light border">
      <div class="col-md-6">
        <label class="text-muted small fw-bold d-block">PERUSAHAAN KORPORASI:</label>
        <div class="fw-bold fs-6 text-dark">{{ $rfq->company_name }}</div>
        <div class="small text-muted">NIB/NPWP: {{ $rfq->company_tax_id ?: '-' }}</div>
        <div class="small text-muted">Alamat: {{ $rfq->address }}</div>
      </div>
      <div class="col-md-6">
        <label class="text-muted small fw-bold d-block">PIC PROCUREMENT:</label>
        <div class="fw-bold fs-6 text-dark">{{ $rfq->pic_name }} ({{ $rfq->pic_position ?: 'Staff' }})</div>
        <div class="small text-muted">Email: <a href="mailto:{{ $rfq->email }}">{{ $rfq->email }}</a></div>
        <div class="small text-muted">WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}" target="_blank">{{ $rfq->phone_wa }}</a></div>
      </div>
    </div>

    @if($rfq->notes)
      <div class="alert alert-warning py-2 small mb-4">
        <strong>Catatan Spesifik Pembeli:</strong> {{ $rfq->notes }}
      </div>
    @endif

    <form action="{{ route('admin.rfq.update', ['id' => $rfq->id]) }}" method="POST">
      @csrf

      <h6 class="fw-bold text-dark mb-3">1. Penyesuaian Harga Penawaran per Item:</h6>
      <div class="table-responsive mb-4">
        <table class="table table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>No. Katalog</th>
              <th>Nama Produk</th>
              <th style="width: 100px; text-align: center;">Jumlah</th>
              <th style="width: 180px;">Harga Satuan (Rp)</th>
              <th style="width: 180px; text-align: right;">Subtotal (Rp)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rfq->items as $item)
              <tr>
                <td><span class="badge bg-secondary">{{ $item->catalog_no ?: '-' }}</span></td>
                <td class="fw-semibold text-dark">{{ $item->product_title }}</td>
                <td class="text-center fw-bold">{{ $item->quantity }} Unit</td>
                <td>
                  <input type="number" step="0.01" min="0" 
                         name="items[{{ $item->id }}][offered_price]" 
                         value="{{ old('items.'.$item->id.'.offered_price', $item->offered_price) }}" 
                         class="form-control form-control-sm price-input" 
                         data-qty="{{ $item->quantity }}" 
                         data-item-id="{{ $item->id }}" required>
                </td>
                <td class="text-end fw-bold text-danger item-subtotal" id="subtotal-{{ $item->id }}">
                  Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="table-light">
              <td colspan="4" class="text-end fw-bold">TOTAL HARGA PENAWARAN:</td>
              <td class="text-end fw-bold fs-5 text-danger" id="grand-total-display">
                Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <h6 class="fw-bold text-dark mb-3">2. Parameter Penawaran &amp; Feedback:</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label for="valid_until" class="form-label fw-bold">Penawaran Berlaku Sampai Tanggal <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="valid_until" name="valid_until" 
                 value="{{ old('valid_until', $rfq->valid_until ? $rfq->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}" required>
        </div>

        <div class="col-md-12">
          <label for="admin_response_notes" class="form-label fw-bold">Catatan Penawaran Sales untuk Korporasi</label>
          <textarea class="form-control" id="admin_response_notes" name="admin_response_notes" rows="3" 
                    placeholder="Contoh: Penawaran harga mencakup diskon grosir 5%. Estimasi waktu pengiriman 3-5 hari kerja setelah penerbitan Purchase Order (PO).">{{ old('admin_response_notes', $rfq->admin_response_notes) }}</textarea>
        </div>
      </div>

      <!-- Submit & Buttons -->
      <div class="border-top pt-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.rfq') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-success px-4 py-2 fw-bold">
          <i class="bi bi-send-fill me-1"></i> Simpan &amp; Kirim Feedback Email ke Korporasi
        </button>
      </div>

    </form>
  </div>
</div>
@endsection

@section('admin_scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.price-input');
    const grandTotalDisplay = document.getElementById('grand-total-display');

    function calculateTotals() {
      let grandTotal = 0;
      inputs.forEach(input => {
        const qty = parseInt(input.getAttribute('data-qty')) || 1;
        const price = parseFloat(input.value) || 0;
        const itemId = input.getAttribute('data-item-id');
        const subtotal = qty * price;
        grandTotal += subtotal;

        const subEl = document.getElementById('subtotal-' + itemId);
        if (subEl) {
          subEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
      });

      if (grandTotalDisplay) {
        grandTotalDisplay.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
      }
    }

    inputs.forEach(input => {
      input.addEventListener('input', calculateTotals);
    });
  });
</script>
@endsection
