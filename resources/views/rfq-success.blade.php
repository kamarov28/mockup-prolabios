@extends('layouts.app')

@section('title', 'Pengajuan Berhasil Dikirim | PT. Prolabios Mitra Analitika')

@section('content')
<section class="cart-page-bg">
  <div class="container py-4 text-center">
    <div class="card rfq-success-card mx-auto p-4 p-md-5">

      <!-- Formal Letterhead (Visible Only When Printing) -->
      <div class="d-none d-print-block text-start border-bottom pb-3 mb-4" style="border-bottom: 2px solid #0F172A !important;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h2 class="h5 fw-bold mb-1" style="color: #0F172A; letter-spacing: 0.5px;">PT. PROLABIOS MITRA ANALITIKA</h2>
            <p class="small mb-0 text-muted" style="font-size: 0.8rem; line-height: 1.4;">
              Distributor Resmi Media Mikrobiologi, Reagen Analitika &amp; Instrumen Laboratorium<br>
              Telp: 021-3874-1447 / 021-8792-9433 &bull; Email: marketing@prolabios.com &bull; Web: www.prolabios.com
            </p>
          </div>
          <div class="text-end">
            <span class="badge border border-dark text-dark px-2 py-1" style="font-size: 0.75rem;">BUKTI PENGAJUAN RFQ</span>
            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Tgl: {{ $rfq->created_at ? $rfq->created_at->format('d/m/Y H:i') : date('d/m/Y') }} WIB</div>
          </div>
        </div>
      </div>

      <!-- Icon Check Circle -->
      <div class="nb-status-icon-box mb-3 mx-auto d-print-none">
        <i data-lucide="check-circle-2"></i>
      </div>

      <div class="mb-3 d-print-none">
        <span class="nb-badge" style="font-size: 0.72rem;">PENGAJUAN BERHASIL DIKIRIM</span>
      </div>

      <h1 class="profil-section-title mb-2 d-print-none" style="font-size: 2rem !important; color: var(--nb-ink);">Terima Kasih, {{ $rfq->name }}!</h1>
      <p class="profil-body-text mb-4 d-print-none" style="font-size: 0.92rem; line-height: 1.6; color: var(--nb-muted);">
        Pengajuan penawaran harga Anda dengan nomor pengajuan <strong style="color: var(--nb-primary);">{{ $rfq->rfq_number }}</strong> telah berhasil kami terima. Tim sales kami akan segera menghubungi Anda via Email atau WhatsApp.
      </p>

      <!-- Details Box -->
      <div class="rfq-details-box p-4 mb-4 text-start">
        <h3 class="cart-sidebar-title" style="font-size: 1rem; margin-bottom: 16px; color: var(--nb-ink); font-weight: 700;">
          <i data-lucide="file-text" class="text-primary me-2"></i> Detail Pengajuan Penawaran
        </h3>

        <div class="row g-2 small mb-3" style="color: var(--nb-ink);">
          <div class="col-sm-4" style="color: var(--nb-muted);">Nomor Pengajuan:</div>
          <div class="col-sm-8 d-flex align-items-center gap-2">
            <strong style="color: var(--nb-primary); font-family: var(--font-mono); font-size: 1.05rem;">{{ $rfq->rfq_number }}</strong>
            <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 d-inline-flex align-items-center gap-1 copy-rfq-btn" onclick="copyRfqNumber('{{ $rfq->rfq_number }}', this)" title="Salin nomor pengajuan RFQ" style="font-size: 0.75rem; border-radius: 0;">
              <i data-lucide="copy" style="width: 12px; height: 12px;"></i> <span class="copy-label">Salin</span>
            </button>
          </div>

          <div class="col-sm-4" style="color: var(--nb-muted);">Nama Instansi:</div>
          <div class="col-sm-8 fw-semibold">{{ $rfq->company_name }}</div>

          <div class="col-sm-4" style="color: var(--nb-muted);">Email:</div>
          <div class="col-sm-8">{{ $rfq->email }}</div>

          <div class="col-sm-4" style="color: var(--nb-muted);">WhatsApp:</div>
          <div class="col-sm-8">{{ $rfq->phone_wa }}</div>

          @if($rfq->notes)
          <div class="col-sm-4" style="color: var(--nb-muted);">Catatan:</div>
          <div class="col-sm-8">{{ $rfq->notes }}</div>
          @endif
        </div>

        <div class="pt-3 border-top" style="border-color: rgba(30,30,30,0.15) !important;">
          <div class="fw-semibold small mb-2" style="font-family: var(--font-mono); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--nb-ink);">Produk yang Diajukan:</div>
          <ul class="list-unstyled mb-0 small ps-2">
            @foreach($rfq->items as $item)
              <li class="py-2 border-bottom d-flex justify-content-between" style="border-color: rgba(30,30,30,0.1) !important; color: var(--nb-ink);">
                <span>• {{ $item->product_title }} {{ $item->catalog_no ? '(Cat. ' . $item->catalog_no . ')' : '' }}</span>
                <span class="fw-bold" style="font-family: var(--font-mono); color: var(--nb-primary);">Qty: {{ $item->quantity }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <!-- Formal Footer (Visible Only When Printing) -->
      <div class="d-none d-print-block text-start pt-3 mt-4 border-top small text-muted" style="border-top: 1px dashed #CCCCCC !important; font-size: 0.75rem;">
        <div class="row">
          <div class="col-8">
            <em>Dokumen ini merupakan bukti resmi permohonan pengajuan penawaran harga (RFQ) yang diterbitkan secara elektronik melalui sistem katalog resmi PT Prolabios Mitra Analitika. Surat penawaran harga resmi (Quotation) bertanda tangan dan bermeterai akan dikirimkan oleh Tim Sales melalui email terdaftar.</em>
          </div>
          <div class="col-4 text-end">
            <div style="height: 40px;"></div>
            <strong class="d-block" style="color: #0F172A;">PT. Prolabios Mitra Analitika</strong>
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3 print-hide">
        <button type="button" onclick="window.print()" class="nb-btn nb-btn-primary">
          <i data-lucide="printer" class="me-2"></i> Cetak Bukti RFQ
        </button>
        <a href="{{ route('home') }}" class="nb-btn nb-btn-ghost">
          <i data-lucide="home" class="me-2"></i> Kembali ke Beranda
        </a>
        <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost">
          Lihat Katalog Produk <i data-lucide="arrow-right" class="ms-2"></i>
        </a>
      </div>

    </div>
  </div>
</section>

<script @nonce>
  try {
    localStorage.removeItem('prolabios_cart_backup');
  } catch(e) {}

  function copyRfqNumber(text, btn) {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(function() {
        var label = btn.querySelector('.copy-label');
        if (label) {
          var original = label.textContent;
          label.textContent = 'Tersalin!';
          setTimeout(function() { label.textContent = original; }, 2000);
        }
      });
    }
  }
</script>
@endsection
