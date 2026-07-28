@extends('admin.layout')

@section('title', 'Feedback Penawaran ' . $rfq->rfq_number)
@section('page_title', 'Respon Penawaran RFQ')

@section('admin_content')
<style>
  .admin-rfq-card {
    background: #0e0e10;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
  }
  .admin-info-grid {
    background: #141416;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
  }
  .admin-rfq-table {
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 10px;
    overflow: hidden;
  }
  .admin-rfq-table th {
    background: #141416 !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    padding: 12px 16px !important;
  }
  .admin-rfq-table td {
    background: #0e0e10 !important;
    color: #ffffff !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
    padding: 12px 16px !important;
    vertical-align: middle;
  }
  .admin-rfq-table tr:hover td {
    background: #141416 !important;
  }
  .admin-price-input {
    background: #18191c !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 0 8px 8px 0 !important;
    padding: 6px 12px !important;
  }
  .admin-price-input:focus {
    border-color: #ff4950 !important;
    box-shadow: 0 0 8px rgba(255, 73, 80, 0.4) !important;
    background: #1f2025 !important;
    color: #ffffff !important;
  }
  .admin-form-control {
    background: #141416 !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 8px !important;
  }
  .admin-form-control:focus {
    border-color: #ff4950 !important;
    box-shadow: 0 0 8px rgba(255, 73, 80, 0.3) !important;
    color: #ffffff !important;
    background: #18191c !important;
  }
</style>

<div class="admin-rfq-card max-w-4xl mx-auto p-4">
  <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
    <h5 class="mb-0 fw-bold text-white fs-5">
      <i class="bi bi-file-earmark-text text-danger me-2"></i> Feedback Penawaran RFQ: <span class="text-danger">{{ $rfq->rfq_number }}</span>
    </h5>
    <a href="{{ route('rfq.pdf', ['number' => $rfq->rfq_number]) }}" target="_blank" class="btn btn-outline-light btn-sm px-3 rounded-pill">
      <i class="bi bi-printer me-1"></i> Preview PDF Quotation
    </a>
  </div>

  <!-- Information Grid -->
  <div class="admin-info-grid mb-4">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="text-danger small fw-bold d-block text-uppercase letter-spacing-1 mb-1">PERUSAHAAN KORPORASI:</label>
        <div class="fw-bold fs-6 text-white">{{ $rfq->company_name }}</div>
        <div class="small text-muted">NIB/NPWP: {{ $rfq->company_tax_id ?: '-' }}</div>
        <div class="small text-muted">Alamat: {{ $rfq->address }}</div>
      </div>
      <div class="col-md-6">
        <label class="text-danger small fw-bold d-block text-uppercase letter-spacing-1 mb-1">PIC PROCUREMENT:</label>
        <div class="fw-bold fs-6 text-white">{{ $rfq->pic_name }} ({{ $rfq->pic_position ?: 'Staff' }})</div>
        <div class="small text-muted">Email: <a href="mailto:{{ $rfq->email }}" class="text-info">{{ $rfq->email }}</a></div>
        <div class="small text-muted">WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}" target="_blank" class="text-info">{{ $rfq->phone_wa }}</a></div>
      </div>
    </div>
  </div>

  @if($rfq->notes)
    <div class="p-3 mb-4 rounded" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.25); color: #ffc107;">
      <strong>Catatan Spesifik Pembeli:</strong> {{ $rfq->notes }}
    </div>
  @endif

  <form action="{{ route('admin.rfq.update', ['id' => $rfq->id]) }}" method="POST">
    @csrf

    <h6 class="fw-bold text-white mb-3">1. Penyesuaian Harga Penawaran per Item:</h6>
    <div class="table-responsive mb-4">
      <table class="table admin-rfq-table align-middle">
        <thead>
          <tr>
            <th>No. Katalog</th>
            <th>Nama Produk</th>
            <th style="width: 110px; text-align: center;">Jumlah</th>
            <th style="width: 220px;">Harga Satuan (Rp)</th>
            <th style="width: 180px; text-align: right;">Subtotal (Rp)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rfq->items as $item)
            <tr>
              <td>
                <span class="badge bg-dark border border-secondary text-light px-2 py-1">{{ $item->catalog_no ?: '-' }}</span>
              </td>
              <td>
                <span class="fw-semibold text-white d-block" style="font-size: 0.95rem;">{{ $item->product_title }}</span>
              </td>
              <td class="text-center fw-bold text-light">{{ $item->quantity }} Unit</td>
              <td>
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-dark text-muted border-secondary">Rp</span>
                  <input type="number" step="0.01" min="0" 
                         name="items[{{ $item->id }}][offered_price]" 
                         value="{{ old('items.'.$item->id.'.offered_price', $item->offered_price) }}" 
                         class="form-control form-control-sm admin-price-input price-input" 
                         data-qty="{{ $item->quantity }}" 
                         data-item-id="{{ $item->id }}" required>
                </div>
              </td>
              <td class="text-end fw-bold text-danger item-subtotal" id="subtotal-{{ $item->id }}">
                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background: #141416 !important;">
            <td colspan="4" class="text-end fw-bold text-white" style="border-bottom: none !important;">TOTAL HARGA PENAWARAN:</td>
            <td class="text-end fw-bold fs-5 text-danger" id="grand-total-display" style="border-bottom: none !important;">
              Rp {{ number_format($rfq->total_offered_amount, 0, ',', '.') }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    <h6 class="fw-bold text-white mb-3">2. Parameter Penawaran &amp; Feedback:</h6>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label for="valid_until" class="form-label fw-bold text-light">Penawaran Berlaku Sampai Tanggal <span class="text-danger">*</span></label>
        <input type="date" class="form-control admin-form-control" id="valid_until" name="valid_until" 
               value="{{ old('valid_until', $rfq->valid_until ? $rfq->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}" required>
      </div>

      <div class="col-md-12">
        <label for="admin_response_notes" class="form-label fw-bold text-light">Catatan Penawaran Sales untuk Korporasi</label>
        <textarea class="form-control admin-form-control" id="admin_response_notes" name="admin_response_notes" rows="3" 
                  placeholder="Contoh: Penawaran harga mencakup diskon grosir 5%. Estimasi waktu pengiriman 3-5 hari kerja setelah penerbitan Purchase Order (PO).">{{ old('admin_response_notes', $rfq->admin_response_notes) }}</textarea>
      </div>
    </div>

    <!-- Submit & Buttons -->
    <div class="border-top border-secondary border-opacity-25 pt-3 d-flex justify-content-between align-items-center">
      <a href="{{ route('admin.rfq') }}" class="btn btn-outline-secondary px-4 rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Batal
      </a>
      <button type="submit" class="btn btn-danger px-4 py-2 fw-bold rounded-pill">
        <i class="bi bi-send-fill me-1"></i> Simpan &amp; Kirim Feedback Email ke Korporasi
      </button>
    </div>

  </form>
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
