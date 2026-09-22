@extends('admin.layout')

@section('title', 'Detail Pengajuan ' . $rfq->rfq_number)
@section('page_title', 'Detail Pengajuan RFQ')

@section('admin_content')

@if(session('success'))
  <div class="alert alert-success border-0 mb-4" style="background: rgba(25,135,84,0.15); color: #75b798;">
    {{ session('success') }}
  </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <div>
    <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost mb-2">
      <i data-lucide="arrow-left"></i> Kembali ke Daftar RFQ
    </a>
    <h1 class="h3 fw-bold mb-2" style="font-family: var(--font-headline); color: var(--color-text-main);">
      {{ $rfq->rfq_number }}
      <span class="admin-badge {{ $rfq->status_badge_class }} ms-2" style="font-size: 0.75rem; vertical-align: middle;">{{ $rfq->status_label }}</span>
    </h1>
  </div>
  <div class="d-inline-flex gap-2 flex-wrap">
    <a href="{{ $rfq->whatsapp_url }}"
       target="_blank" rel="noopener" class="admin-btn admin-btn-primary d-inline-flex align-items-center gap-1" style="background: #16A34A; border-color: #16A34A; color: #FFFFFF !important;">
      <x-brand-icon name="whatsapp" size="16" /> Hubungi via WhatsApp
    </a>
    <a href="mailto:{{ $rfq->email }}?subject=Penawaran%20Resmi%20Prolabios%20-%20{{ $rfq->rfq_number }}" class="admin-btn admin-btn-outline">
      <i data-lucide="mail"></i> Kirim Email
    </a>
  </div>
</div>

<div class="row g-4">

  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Daftar Kebutuhan</span>
          <h2 class="admin-card-header-title">Item Produk yang Diajukan</h2>
        </div>
        <span class="admin-badge admin-badge-muted px-3 py-2">
          {{ $rfq->items->count() }} Macam Produk
        </span>
      </div>

      <div class="admin-card-body-flush">
        <div class="table-responsive">
          <table class="admin-table">
            <thead>
              <tr>
                <th style="width: 130px;">No. Katalog</th>
                <th>Nama Produk</th>
                <th>Estimasi Harga</th>
                <th style="text-align: center; width: 90px;">Qty</th>
                <th>Status Stok</th>
              </tr>
            </thead>
            <tbody>
              @php $totalEst = 0; @endphp
              @foreach($rfq->items as $item)
                @php
                  $lineTotal = $item->original_price * $item->quantity;
                  $totalEst += $lineTotal;
                  $stockVal = $item->product ? (int)$item->product->stock : 0;
                  $isIndent = $item->product ? ($item->quantity > $stockVal) : true;
                @endphp
                <tr>
                  <td class="cell-code">{{ $item->catalog_no ?: '—' }}</td>
                  <td>
                    <div class="cell-title">{{ $item->product_title }}</div>
                    @if($item->product)
                      <span class="text-secondary small">Kategori: {{ $item->product->category }}</span>
                    @endif
                  </td>
                  <td style="white-space: nowrap; font-family: var(--font-mono); font-size: 0.88rem; color: var(--color-text-main);">
                    {{ $item->original_price > 0 ? 'Rp ' . number_format($item->original_price, 0, ',', '.') : 'Hubungi Kami' }}
                  </td>
                  <td style="text-align: center; white-space: nowrap;">
                    <strong style="color: var(--color-text-main); font-size: 0.95rem;">{{ $item->quantity }}</strong> <span class="text-muted small">Unit</span>
                  </td>
                  <td>
                    @if(!$isIndent)
                      <span class="admin-badge admin-badge-success">
                        <i data-lucide="package" class="me-1"></i> Ready Stock
                      </span>
                    @else
                      <span class="admin-badge admin-badge-warning" title="Stok ready {{ $stockVal }} unit">
                        <i data-lucide="history" class="me-1"></i> Indent (Ready: {{ $stockVal }})
                      </span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="p-3 border-top d-flex justify-content-between align-items-center" style="background-color: var(--color-surface-2); border-color: var(--color-border) !important;">
          <span class="text-secondary small">Estimasi Subtotal Katalog:</span>
          <strong class="fs-5" style="color: var(--color-accent, #A6171C); font-family: var(--font-mono);">
            {{ $totalEst > 0 ? 'Rp ' . number_format($totalEst, 0, ',', '.') : 'Est. Penawaran' }}
          </strong>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Follow-up Sales</span>
          <h2 class="admin-card-header-title">Status & Catatan Internal</h2>
        </div>
      </div>
      <div class="admin-card-body">
        <form action="{{ route('admin.rfqs.update', $rfq->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="admin-form-label" for="rfq_status">Status Pengajuan</label>
            <select name="status" id="rfq_status" class="form-select">
              @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $rfq->status ?: 'new') === $value)>{{ $label }}</option>
              @endforeach
            </select>
            @error('status')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="admin-form-label" for="admin_notes">Catatan Internal (Privat)</label>
            <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control" placeholder="Mis. sudah telepon 24/08, tunggu PO, dll.">{{ old('admin_notes', $rfq->admin_notes) }}</textarea>
            @error('admin_notes')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
            <i data-lucide="check" class="me-1"></i> Simpan Status
          </button>
        </form>
      </div>
    </div>

    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Profil Pemohon</span>
          <h2 class="admin-card-header-title">Data Kontak & Instansi</h2>
        </div>
      </div>

      <div class="admin-card-body">
        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1">Nama Pemohon:</span>
          <strong class="fs-6" style="color: var(--color-text-main);">{{ $rfq->name }}</strong>
        </div>

        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1">Nama Instansi / Perusahaan:</span>
          <span class="fw-medium" style="color: var(--color-text-main);">{{ $rfq->company_name ?: '—' }}</span>
        </div>

        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1">Email:</span>
          <a href="mailto:{{ $rfq->email }}" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--color-text-main);">
            <i data-lucide="mail" class="text-secondary" style="width: 14px; height: 14px;"></i> {{ $rfq->email }}
          </a>
        </div>

        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1">Nomor WhatsApp:</span>
          <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-decoration-none fw-semibold d-inline-flex align-items-center gap-1" style="color: #16A34A; font-family: var(--font-mono);">
            <x-brand-icon name="whatsapp" size="15" /> {{ $rfq->phone_wa }}
          </a>
        </div>

        <div class="mb-3 pb-3 border-bottom" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1">Tanggal Masuk:</span>
          <span class="fw-medium" style="color: var(--color-text-main);">{{ $rfq->created_at ? $rfq->created_at->format('d F Y, H:i') : '—' }} WIB</span>
        </div>

        <div>
          <span class="text-secondary small d-block mb-1">Catatan Khusus dari Pemohon:</span>
          <div class="p-3 rounded border small" style="background-color: var(--color-surface-2); border-color: var(--color-border) !important; color: var(--color-text-main); min-height: 70px;">
            {{ $rfq->notes ?: 'Tidak ada catatan tambahan.' }}
          </div>
        </div>
      </div>
    </div>

    <div class="admin-card">
      <div class="admin-card-body">
        <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" class="form-delete m-0" data-name="{{ e($rfq->rfq_number) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="admin-btn admin-btn-danger w-100 justify-content-center">
            <i data-lucide="trash-2" class="me-1"></i> Hapus Pengajuan Ini
          </button>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection

@section('admin_scripts')
<script @nonce>
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
