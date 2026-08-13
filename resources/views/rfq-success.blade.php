@extends('layouts.app')

@section('title', 'Pengajuan Berhasil Dikirim | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body, #070708); min-height: 85vh; padding-top: 140px !important;">
  <div class="container py-4 text-center">
    <div class="max-w-2xl mx-auto p-4 p-md-5 rounded-4 border shadow-lg" style="background: var(--color-surface, #0e0e10); border-color: var(--color-border) !important; max-width: 680px;">
      
      <!-- Icon Check Circle -->
      <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 76px; height: 76px; background: rgba(46, 125, 50, 0.15); border: 1px solid rgba(46, 125, 50, 0.4); box-shadow: 0 0 20px rgba(46, 125, 50, 0.25);">
        <i class="bi bi-check2-circle" style="font-size: 2.5rem; color: #4caf50;"></i>
      </div>

      <div class="mb-3">
        <span class="badge px-3 py-2 text-uppercase tracking-wider fw-semibold" style="background: rgba(255, 73, 80, 0.12); color: #ff4950; border: 1px solid rgba(255, 73, 80, 0.3); border-radius: 20px; font-size: 0.75rem; letter-spacing: 1px;">Pengajuan Berhasil Dikirim</span>
      </div>
      
      <h1 class="h2 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Terima Kasih, {{ $rfq->name }}!</h1>
      <p class="text-secondary small mb-4" style="font-size: 0.92rem; line-height: 1.6;">
        Pengajuan penawaran harga Anda dengan nomor pengajuan <strong class="text-white">{{ $rfq->rfq_number }}</strong> telah berhasil kami terima. Tim sales kami akan segera menghubungi Anda via Email atau WhatsApp.
      </p>

      <!-- Details Box -->
      <div class="p-3 mb-4 rounded-3 text-start" style="background: #070708; border: 1px solid var(--color-border);">
        <h3 class="h6 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-20">
          <i class="bi bi-file-earmark-text text-danger me-2"></i> Detail Pengajuan
        </h3>

        <div class="row g-2 text-white small mb-3">
          <div class="col-sm-4 text-secondary">Nomor RFQ:</div>
          <div class="col-sm-8"><strong style="color: #ff4950;">{{ $rfq->rfq_number }}</strong></div>

          <div class="col-sm-4 text-secondary">Nama Instansi:</div>
          <div class="col-sm-8 text-light">{{ $rfq->company_name }}</div>

          <div class="col-sm-4 text-secondary">Email:</div>
          <div class="col-sm-8 text-light">{{ $rfq->email }}</div>

          <div class="col-sm-4 text-secondary">WhatsApp:</div>
          <div class="col-sm-8 text-light">{{ $rfq->phone_wa }}</div>

          @if($rfq->notes)
          <div class="col-sm-4 text-secondary">Catatan:</div>
          <div class="col-sm-8 text-light">{{ $rfq->notes }}</div>
          @endif
        </div>

        <div class="pt-2 border-top border-secondary border-opacity-20">
          <div class="fw-semibold text-secondary small mb-2">Produk yang Diajukan:</div>
          <ul class="list-unstyled mb-0 text-light small ps-2">
            @foreach($rfq->items as $item)
              <li class="py-1 border-bottom border-secondary border-opacity-10 d-flex justify-content-between">
                <span>• {{ $item->product_title }} {{ $item->catalog_no ? '(Cat. ' . $item->catalog_no . ')' : '' }}</span>
                <span class="text-secondary">Qty: {{ $item->quantity }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('home') }}" class="btn btn-danger px-4 py-2 fw-semibold rounded-pill" style="background: #ff4950; border-color: #ff4950; box-shadow: 0 4px 14px rgba(255, 73, 80, 0.4);">
          <i class="bi bi-house me-2"></i> Kembali ke Beranda
        </a>
        <a href="{{ url('/produk') }}" class="btn btn-outline-light px-4 py-2 rounded-pill" style="border-color: rgba(255,255,255,0.2);">
          Lihat Katalog Produk
        </a>
      </div>

    </div>
  </div>
</section>
@endsection