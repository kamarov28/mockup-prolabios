@extends('admin.layout')

@section('title', 'Detail Pengajuan ' . $rfq->rfq_number)
@section('page_title', 'Detail Pengajuan RFQ')

@section('admin_content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost mb-2">
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar RFQ
    </a>
    <h1 class="h3 fw-bold text-white mb-0" style="font-family: var(--font-headline);">
      {{ $rfq->rfq_number }}
    </h1>
  </div>
  <div class="d-inline-flex gap-2">
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}?text=Halo%20{{ urlencode($rfq->name) }}%20dari%20{{ urlencode($rfq->company_name) }},%20kami%20dari%20Tim%20Sales%20Prolabios%20terkait%20pengajuan%20penawaran%20{{ $rfq->rfq_number }}" 
       target="_blank" class="admin-btn admin-btn-primary" style="background: #25D366; border-color: #25D366;">
      <i class="bi bi-whatsapp"></i> Hubungi Customer via WA
    </a>
    <a href="mailto:{{ $rfq->email }}?subject=Penawaran%20Resmi%20Prolabios%20-%20{{ $rfq->rfq_number }}" class="admin-btn admin-btn-ghost">
      <i class="bi bi-envelope"></i> Kirim Email
    </a>
  </div>
</div>

<div class="row g-4">

  {{-- Left Column: Products List --}}
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

  {{-- Right Column: Customer Info & Notes --}}
  <div class="col-lg-4">
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Profil Pemohon</span>
          <h2 class="admin-card-header-title">Data Kontak &amp; Instansi</h2>
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
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rfq->phone_wa) }}" target="_blank" class="text-success text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
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

    {{-- Delete Action --}}
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
