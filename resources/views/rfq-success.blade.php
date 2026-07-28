@extends('layouts.app')

@section('title', 'Pengajuan Penawaran Berhasil | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body); min-height: 80vh;">
  <div class="container py-5 text-center">
    <div class="max-w-2xl mx-auto p-5 rounded-3 border border-secondary border-opacity-20 shadow-sm" style="background: rgba(255, 255, 255, 0.02);">
      
      <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-20 text-success rounded-circle mb-4" style="width: 80px; height: 80px;">
        <i class="bi bi-check-lg" style="font-size: 2.5rem;"></i>
      </div>

      <span class="badge bg-danger bg-opacity-20 text-danger px-3 py-2 text-uppercase tracking-wider fw-semibold mb-3 d-inline-block">Pengajuan RFQ Terkirim</span>
      
      <h1 class="h2 fw-bold text-white mb-2">Terima Kasih, {{ $rfq->company_name }}!</h1>
      <p class="text-secondary small mb-4">Pengajuan penawaran harga Anda telah berhasil diterima oleh Tim Sales &amp; Procurement PT. Prolabios Mitra Analitika.</p>

      <div class="p-3 mb-4 rounded bg-dark border border-secondary border-opacity-20 text-start">
        <div class="row g-2 text-white small">
          <div class="col-sm-4 text-muted">Nomor RFQ:</div>
          <div class="col-sm-8"><strong class="text-danger">{{ $rfq->rfq_number }}</strong></div>

          <div class="col-sm-4 text-muted">Nama PIC:</div>
          <div class="col-sm-8">{{ $rfq->pic_name }} ({{ $rfq->email }})</div>

          <div class="col-sm-4 text-muted">Status:</div>
          <div class="col-sm-8"><span class="badge bg-warning text-dark">Menunggu Penawaran (Pending Review)</span></div>
        </div>
      </div>

      <p class="text-secondary small mb-4">
        <i class="bi bi-envelope-check text-info me-1"></i>
        Tim Sales kami sedang mengevaluasi rincian barang, harga grosir, &amp; stok. Notifikasi email otomatis dan link <strong>Surat Penawaran Resmi (Quotation PDF)</strong> akan dikirimkan ke <strong>{{ $rfq->email }}</strong>.
      </p>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('rfq.track', ['number' => $rfq->rfq_number]) }}" class="btn btn-danger px-4 py-2 fw-semibold">
          <i class="bi bi-eye me-2"></i> Pantau Status Penawaran
        </a>
        <a href="{{ url('/produk') }}" class="btn btn-outline-secondary px-4 py-2">
          Kembali ke Katalog
        </a>
      </div>

    </div>
  </div>
</section>
@endsection
