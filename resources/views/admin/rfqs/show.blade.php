@extends('admin.layout')

@section('title', 'Detail Pengajuan ' . $rfq->rfq_number)
@section('page_title', 'Detail Pengajuan RFQ')

@section('admin_styles')
<style>
  .hover-primary:hover {
    color: var(--color-accent, #A6171C) !important;
  }
  @media print {
    #admin-sidebar,
    .sidebar-backdrop,
    #admin-topbar,
    .print-hide,
    .admin-btn,
    .alert {
      display: none !important;
    }
    #admin-wrapper {
      display: block !important;
      min-height: auto !important;
    }
    #admin-content {
      margin-left: 0 !important;
      width: 100% !important;
      padding: 0 !important;
      background: #FFFFFF !important;
    }
    .admin-card {
      box-shadow: none !important;
      border: 1px solid #CBD5E1 !important;
      break-inside: avoid;
      margin-bottom: 20px !important;
    }
    .col-lg-8, .col-lg-4 {
      width: 100% !important;
    }
    .print-only-header {
      display: block !important;
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 2px solid #111827;
    }
  }
  .print-only-header {
    display: none;
  }
</style>
@endsection

@section('admin_content')

@if(session('success'))
  <div class="alert alert-success border-0 mb-4 print-hide" style="background: rgba(25,135,84,0.15); color: #75b798;">
    {{ session('success') }}
  </div>
@endif

{{-- Print-Only Header Banner --}}
<div class="print-only-header">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 style="font-family: var(--font-headline); font-weight: 800; margin: 0; font-size: 1.25rem;">PT PROLABIOS MITRA ANALITIKA</h3>
      <p style="margin: 2px 0 0; font-size: 0.85rem; color: #4B5563;">Lembar Disposisi Permintaan Penawaran Harga (RFQ)</p>
    </div>
    <div style="text-align: right;">
      <strong style="font-family: var(--font-mono); font-size: 1.15rem;">{{ $rfq->rfq_number }}</strong>
      <div style="font-size: 0.82rem; color: #4B5563;">Status: {{ $rfq->status_label }}</div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 print-hide">
  <div>
    <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost mb-2">
      <i data-lucide="arrow-left"></i> Kembali ke Daftar RFQ
    </a>
    <h1 class="h3 fw-bold mb-0 d-flex align-items-center gap-2 flex-wrap" style="font-family: var(--font-headline); color: var(--color-text-main);">
      <span>{{ $rfq->rfq_number }}</span>
      <span class="admin-badge {{ $rfq->status_badge_class }}" style="font-size: 0.75rem;">{{ $rfq->status_label }}</span>
    </h1>
  </div>
  <div class="d-inline-flex gap-2 flex-wrap">
    <a href="{{ $rfq->whatsapp_url }}"
       target="_blank" rel="noopener" class="admin-btn admin-btn-primary d-inline-flex align-items-center gap-1.5" style="background: #16A34A; border-color: #16A34A; color: #FFFFFF !important;">
      <x-brand-icon name="whatsapp" size="16" /> Hubungi via WhatsApp
    </a>
    <button type="button" class="admin-btn admin-btn-outline d-inline-flex align-items-center gap-1.5" onclick="copyWaDraft(this)" id="btn-copy-wa-draft" title="Salin draf rincian pengadaan untuk dikirim via WhatsApp">
      <i data-lucide="copy"></i> <span class="copy-label">Salin Draf WA</span>
    </button>
    <a href="mailto:{{ $rfq->email }}?subject=Penawaran%20Resmi%20Prolabios%20-%20{{ $rfq->rfq_number }}" class="admin-btn admin-btn-outline d-inline-flex align-items-center gap-1.5">
      <i data-lucide="mail"></i> Kirim Email
    </a>
    <button type="button" class="admin-btn admin-btn-outline d-inline-flex align-items-center gap-1.5" onclick="window.print()" title="Cetak Lembar Disposisi RFQ">
      <i data-lucide="printer"></i> Cetak RFQ
    </button>
  </div>
</div>

<div class="row g-4">

  {{-- Left Column: Client Profile & Requested Products --}}
  <div class="col-lg-8">

    {{-- Card 1: Profil Kontak & Instansi Pemohon --}}
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Profil Pemohon</span>
          <h2 class="admin-card-header-title">Data Kontak &amp; Instansi</h2>
        </div>
        <span class="text-secondary small d-inline-flex align-items-center gap-1">
          <i data-lucide="calendar" style="width: 14px; height: 14px;"></i>
          {{ $rfq->created_at ? $rfq->created_at->format('d F Y, H:i') : '—' }} WIB
        </span>
      </div>

      <div class="admin-card-body">
        <div class="row g-3">
          <div class="col-sm-6">
            <span class="text-secondary small d-block mb-1">Nama Instansi / Perusahaan:</span>
            <strong class="fs-6 d-block" style="color: var(--color-text-main);">
              {{ $rfq->company_name ?: '— (Perorangan / Mandiri)' }}
            </strong>
          </div>
          <div class="col-sm-6">
            <span class="text-secondary small d-block mb-1">Nama Pemohon:</span>
            <span class="fw-semibold fs-6" style="color: var(--color-text-main);">{{ $rfq->name }}</span>
          </div>
          <div class="col-sm-6">
            <span class="text-secondary small d-block mb-1">Email Resmi:</span>
            <a href="mailto:{{ $rfq->email }}?subject=Penawaran%20Resmi%20Prolabios%20-%20{{ $rfq->rfq_number }}" class="text-decoration-none d-inline-flex align-items-center gap-1.5 fw-medium" style="color: var(--color-accent, #A6171C);">
              <i data-lucide="mail" style="width: 14px; height: 14px;" class="text-secondary"></i> {{ $rfq->email }}
            </a>
          </div>
          <div class="col-sm-6">
            <span class="text-secondary small d-block mb-1">Nomor WhatsApp:</span>
            <a href="{{ $rfq->whatsapp_url }}" target="_blank" rel="noopener" class="text-decoration-none fw-semibold d-inline-flex align-items-center gap-1.5" style="color: #16A34A; font-family: var(--font-mono);">
              <x-brand-icon name="whatsapp" size="15" /> {{ $rfq->phone_wa }}
            </a>
          </div>
        </div>

        <div class="mt-3 pt-3 border-top" style="border-color: var(--color-border) !important;">
          <span class="text-secondary small d-block mb-1 fw-semibold">
            <i data-lucide="message-square" style="width: 14px; height: 14px; vertical-align: -2px;" class="me-1 text-primary"></i> Catatan Khusus dari Pemohon:
          </span>
          <div class="p-3 rounded border small" style="background-color: var(--color-surface-2); border-color: var(--color-border) !important; color: var(--color-text-main); line-height: 1.6;">
            {{ $rfq->notes ?: 'Tidak ada catatan tambahan.' }}
          </div>
        </div>
      </div>
    </div>

    {{-- Card 2: Item Produk yang Diajukan --}}
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
                    <div class="cell-title">
                      @if($item->product)
                        <a href="{{ route('produk.detail', ['slug' => $item->product->slug]) }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none fw-bold text-dark hover-primary d-inline-flex align-items-center gap-1" title="Buka produk di katalog live">
                          {{ $item->product_title }}
                          <i data-lucide="external-link" style="width: 12px; height: 12px;" class="text-secondary flex-shrink-0"></i>
                        </a>
                      @else
                        {{ $item->product_title }}
                      @endif
                    </div>
                    @if($item->product && !empty($item->product->category))
                      <span class="text-secondary small d-block mt-0.5">Kategori: {{ $item->product->category }}</span>
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
          <span class="text-secondary small fw-medium">Estimasi Subtotal Katalog:</span>
          <strong class="fs-5" style="color: var(--color-accent, #A6171C); font-family: var(--font-mono);">
            {{ $totalEst > 0 ? 'Rp ' . number_format($totalEst, 0, ',', '.') : 'Est. Penawaran' }}
          </strong>
        </div>
      </div>
    </div>
  </div>

  {{-- Right Column: Sales Follow-up & Actions --}}
  <div class="col-lg-4">

    {{-- Card 1: Follow-up Sales (Status & Internal Notes) --}}
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Follow-up Sales</span>
          <h2 class="admin-card-header-title">Status &amp; Catatan Internal</h2>
        </div>
        <span class="admin-badge {{ $rfq->status_badge_class }}">{{ $rfq->status_label }}</span>
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
            <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control" placeholder="Mis. sudah telepon 24/08, tunggu konfirmasi PO dari lab, nomor lot COA terlampir.">{{ old('admin_notes', $rfq->admin_notes) }}</textarea>
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

    {{-- Card 2: Lembar Disposisi / Print SPH --}}
    <div class="admin-card mb-4 print-hide">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Dokumen &amp; Arsip</span>
          <h2 class="admin-card-header-title">Lembar Disposisi SPH</h2>
        </div>
      </div>
      <div class="admin-card-body">
        <p class="text-secondary small mb-3" style="line-height: 1.55;">
          Cetak berkas disposisi RFQ ini untuk arsip fisik pengadaan atau lampiran verifikasi ketersediaan lot reagen.
        </p>
        <button type="button" class="admin-btn admin-btn-outline w-100 justify-content-center" onclick="window.print()">
          <i data-lucide="printer" class="me-1"></i> Cetak Lembar RFQ
        </button>
      </div>
    </div>

    {{-- Card 3: Zona Bahaya / Danger Zone --}}
    <div class="admin-card print-hide">
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

<textarea id="rfq-wa-draft-content" class="d-none" readonly aria-hidden="true">{{ $rfq->whatsapp_followup_text }}</textarea>

@endsection

@section('admin_scripts')
<script @nonce>
  function copyWaDraft(btn) {
    const el = document.getElementById('rfq-wa-draft-content');
    if (!el) return;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(el.value).then(function() {
        const label = btn.querySelector('.copy-label');
        if (label) {
          const original = label.textContent;
          label.textContent = 'Draf Tersalin!';
          setTimeout(function() { label.textContent = original; }, 2000);
        }
      });
    }
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
