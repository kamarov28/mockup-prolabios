@extends('layouts.app')

@section('title', 'Pengajuan Penawaran Berhasil | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body, #070708); min-height: 85vh; padding-top: 140px !important;">
  <div class="container py-4 text-center">
    <div class="max-w-2xl mx-auto p-4 p-md-5 rounded-4 border border-secondary border-opacity-20 shadow-lg" style="background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(16px); max-width: 680px;">
      
      <!-- Icon Shield Check -->
      <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 76px; height: 76px; background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(46, 125, 50, 0.4); box-shadow: 0 0 20px rgba(46, 125, 50, 0.25);">
        <i class="bi bi-check2-circle" style="font-size: 2.5rem; color: #4caf50;"></i>
      </div>

      <div class="mb-3">
        <span class="badge px-3 py-2 text-uppercase tracking-wider fw-semibold" style="background: rgba(255, 73, 80, 0.12); color: #ff4950; border: 1px solid rgba(255, 73, 80, 0.3); border-radius: 20px; font-size: 0.75rem; letter-spacing: 1px;">Pengajuan RFQ Terkirim</span>
      </div>
      
      <h1 class="h2 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Terima Kasih, {{ $rfq->company_name }}!</h1>
      <p class="text-secondary small mb-4" style="font-size: 0.92rem; line-height: 1.6;">Pengajuan penawaran harga Anda telah berhasil diterima oleh Tim Sales &amp; Procurement PT. Prolabios Mitra Analitika.</p>

      <!-- Details Box -->
      <div class="p-3 mb-4 rounded-3 text-start" style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.08);">
        <div class="row g-2 text-white small">
          <div class="col-sm-4 text-secondary">Nomor RFQ:</div>
          <div class="col-sm-8"><strong style="color: #ff4950;">{{ $rfq->rfq_number }}</strong></div>

          <div class="col-sm-4 text-secondary">Nama PIC:</div>
          <div class="col-sm-8 text-light">{{ $rfq->pic_name }} (<span class="text-info">{{ $rfq->email }}</span>)</div>

          <div class="col-sm-4 text-secondary">Status:</div>
          <div class="col-sm-8">
            <span class="badge px-2 py-1" style="background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); font-weight: 600;">Menunggu Penawaran (Pending Review)</span>
          </div>
        </div>
      </div>

      <p class="text-secondary small mb-4" style="line-height: 1.6;">
        <i class="bi bi-envelope-check text-info me-1"></i>
        Tim Sales kami sedang mengevaluasi rincian barang, harga grosir, &amp; stok. Notifikasi email otomatis dan link <strong>Surat Penawaran Resmi (Quotation PDF)</strong> akan dikirimkan ke <strong class="text-light">{{ $rfq->email }}</strong>.
      </p>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('rfq.track', ['number' => $rfq->rfq_number]) }}" class="btn btn-danger px-4 py-2 fw-semibold rounded-pill" style="background: #ff4950; border-color: #ff4950; box-shadow: 0 4px 14px rgba(255, 73, 80, 0.4);">
          <i class="bi bi-eye me-2"></i> Pantau Status Penawaran
        </a>
        <a href="{{ url('/produk') }}" class="btn btn-outline-light px-4 py-2 rounded-pill" style="border-color: rgba(255,255,255,0.2);">
          Kembali ke Katalog
        </a>
      </div>

    </div>
  </div>
</section>
@endsection
