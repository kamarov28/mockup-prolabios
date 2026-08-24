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
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar RFQ
    </a>
    <h1 class="h3 fw-bold text-white mb-2" style="font-family: var(--font-headline);">
      {{ $rfq->rfq_number }}
      <span class="badge border ms-2 px-2 py-1 fs-6 {{ $rfq->status_badge_class }}">{{ $rfq->status_label }}</span>
    </h1>
  </div>
  <div class="d-inline-flex gap-2 flex-wrap">
    <a href="{{ $rfq->whatsapp_url }}"
       target="_blank" rel="noopener" class="admin-btn admin-btn-primary" style="background: #25D366; border-color: #25D366;">
      <i class="bi bi-whatsapp"></i> Hubungi Customer via WA
    </a>
    <a href="mailto:{{ $rfq->email }}?subject=Penawaran%20Resmi%20Prolabios%20-%20{{ $rfq->rfq_number }}" class="admin-btn admin-btn-ghost">
      <i class="bi bi-envelope"></i> Kirim Email
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
        <span class="badge bg-secondary bg-opacity-20 text-white px-3 py-2 border border-secondary border-opacity-30">
          {{ $rfq->items->count() }} Macam Produk
        </span>
      </div>

      <div class="admin-card-body-flush">
        <div class="table-responsive">
          <table class="admin-table">
            <thead>
              <tr>
                <th>No. Katalog</th>
                <th>Nama Produk</th>
                <th>Estimasi Harga</th>
                <th style="text-align: center;">Qty</th>
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
                  <td>
                    <span class="badge bg-dark border border-secondary border-opacity-30 text-light">
                      {{ $item->catalog_no ?: '-' }}
                    </span>
                  </td>
                  <td>
                    <strong class="d-block text-white">{{ $item->product_title }}</strong>
                    @if($item->product)
                      <span class="text-secondary small">Kategori: {{ $item->product->category }}</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-white">
                      {{ $item->original_price > 0 ? 'Rp ' . number_format($item->original_price, 0, ',', '.') : 'Harga Katalog' }}
                    </span>
                  </td>
                  <td style="text-align: center;">
                    <strong class="text-white fs-6">{{ $item->quantity }}</strong> Unit
                  </td>
                  <td>
                    @if(!$isIndent)
                      <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1 small">
                        <i class="bi bi-box-seam me-1"></i> Ready Stock
                      </span>
                    @else
                      <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-2 py-1 small" title="Stok ready {{ $stockVal }} unit">
                        <i class="bi bi-clock-history me-1"></i> Indent (Ready: {{ $stockVal }})
                      </span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="p-3 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center bg-black bg-opacity-20">
          <span class="text-secondary small">Estimasi Subtotal Katalog:</span>
          <strong class="fs-5" style="color: var(--color-accent, #FF4950);">
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
            <label class="form-label text-secondary small">Status pengajuan</label>
            <select name="status" class="form-select" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);">
              @foreach(\App\Models\Rfq::statusOptions() as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $rfq->status ?: 'new') === $value)>{{ $label }}</option>
              @endforeach
            </select>
            @error('status')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label text-secondary small">Catatan internal (tidak terlihat customer)</label>
            <textarea name="admin_notes" rows="4" class="form-control" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border);" placeholder="Mis. sudah telepon 24/08, tunggu PO, dll.">{{ old('admin_notes', $rfq->admin_notes) }}</textarea>
            @error('admin_notes')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
            <i class="bi bi-check2 me-1"></i> Simpan Status
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
        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-10">
          <span class="text-secondary small d-block mb-1">Nama Pemohon:</span>
          <strong class="text-white fs-6">{{ $rfq->name }}</strong>
        </div>

        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-10">
          <span class="text-secondary small d-block mb-1">Nama Instansi / Perusahaan:</span>
          <span class="text-light fw-medium">{{ $rfq->company_name }}</span>
        </div>

        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-10">
          <span class="text-secondary small d-block mb-1">Email:</span>
          <a href="mailto:{{ $rfq->email }}" class="text-light text-decoration-none d-inline-flex align-items-center gap-1">
            <i class="bi bi-envelope text-secondary"></i> {{ $rfq->email }}
          </a>
        </div>

        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-10">
          <span class="text-secondary small d-block mb-1">Nomor WhatsApp:</span>
          <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-success text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
            <i class="bi bi-whatsapp"></i> {{ $rfq->phone_wa }}
          </a>
        </div>

        <div class="mb-3 pb-3 border-bottom border-secondary border-opacity-10">
          <span class="text-secondary small d-block mb-1">Tanggal Masuk:</span>
          <span class="text-light">{{ $rfq->created_at ? $rfq->created_at->format('d F Y, H:i') : '-' }} WIB</span>
        </div>

        <div>
          <span class="text-secondary small d-block mb-1">Catatan Khusus dari Pemohon:</span>
          <div class="p-3 rounded bg-black bg-opacity-40 border border-secondary border-opacity-20 text-light small" style="min-height: 70px;">
            {{ $rfq->notes ?: 'Tidak ada catatan tambahan.' }}
          </div>
        </div>
      </div>
    </div>

    <div class="admin-card">
      <div class="admin-card-body">
        <form action="{{ route('admin.rfqs.destroy', $rfq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="admin-btn admin-btn-danger w-100 justify-content-center">
            <i class="bi bi-trash3 me-1"></i> Hapus Pengajuan Ini
          </button>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection
