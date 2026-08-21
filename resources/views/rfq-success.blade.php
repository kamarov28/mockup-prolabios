@extends('layouts.app')

@section('title', 'Pengajuan Berhasil Dikirim | PT. Prolabios Mitra Analitika')

@section('content')
<section class="py-5" style="background-color: var(--color-bg-body, #050709); min-height: 85vh; padding-top: 140px !important; padding-bottom: 80px !important;">
  <div class="container py-4 text-center">
    <div class="max-w-2xl mx-auto p-4 p-md-5 border" style="background: #0c0d12; border-color: var(--color-border) !important; max-width: 680px; border-radius: 0;">
      
      <!-- Icon Check Circle -->
      <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 68px; height: 68px; background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.4); border-radius: 0;">
        <i class="bi bi-check2-circle" style="font-size: 2.2rem; color: #4ade80;"></i>
      </div>

      <div class="mb-3">
        <span class="editorial-page-label" style="font-size: 0.68rem; padding: 4px 12px; margin-bottom: 0;">Pengajuan Berhasil Dikirim</span>
      </div>
      
      <h1 class="profil-section-title mb-2" style="font-size: 2rem !important;">Terima Kasih, {{ $rfq->name }}!</h1>
      <p class="profil-body-text mb-4" style="font-size: 0.92rem; line-height: 1.6;">
        Pengajuan penawaran harga Anda dengan nomor pengajuan <strong style="color: var(--color-accent);">{{ $rfq->rfq_number }}</strong> telah berhasil kami terima. Tim sales kami akan segera menghubungi Anda via Email atau WhatsApp.
      </p>

      <!-- Details Box -->
      <div class="p-4 mb-4 text-start" style="background: #050709; border: 1px solid var(--color-border); border-radius: 0;">
        <h3 class="cart-sidebar-title" style="font-size: 0.95rem; margin-bottom: 16px;">
          <i class="bi bi-file-earmark-text" style="color: var(--color-accent); margin-right: 8px;"></i> Detail Pengajuan
        </h3>

        <div class="row g-2 text-white small mb-3">
          <div class="col-sm-4" style="color: var(--color-text-muted);">Nomor RFQ:</div>
          <div class="col-sm-8"><strong style="color: var(--color-accent); font-family: var(--font-headline);">{{ $rfq->rfq_number }}</strong></div>

          <div class="col-sm-4" style="color: var(--color-text-muted);">Nama Instansi:</div>
          <div class="col-sm-8 text-light">{{ $rfq->company_name }}</div>

          <div class="col-sm-4" style="color: var(--color-text-muted);">Email:</div>
          <div class="col-sm-8 text-light">{{ $rfq->email }}</div>

          <div class="col-sm-4" style="color: var(--color-text-muted);">WhatsApp:</div>
          <div class="col-sm-8 text-light">{{ $rfq->phone_wa }}</div>

          @if($rfq->notes)
          <div class="col-sm-4" style="color: var(--color-text-muted);">Catatan:</div>
          <div class="col-sm-8 text-light">{{ $rfq->notes }}</div>
          @endif
        </div>

        <div class="pt-3 border-top" style="border-color: var(--color-border) !important;">
          <div class="fw-semibold small mb-2" style="font-family: var(--font-headline); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted);">Produk yang Diajukan:</div>
          <ul class="list-unstyled mb-0 text-light small ps-2">
            @foreach($rfq->items as $item)
              <li class="py-2 border-bottom d-flex justify-content-between" style="border-color: rgba(255,255,255,0.05) !important;">
                <span>• {{ $item->product_title }} {{ $item->catalog_no ? '(Cat. ' . $item->catalog_no . ')' : '' }}</span>
                <span style="color: var(--color-text-muted); font-family: var(--font-headline);">Qty: {{ $item->quantity }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('home') }}" class="profil-cta-btn" style="border-radius: 0;">
          <i class="bi bi-house me-2"></i> Kembali ke Beranda
        </a>
        <a href="{{ url('/produk') }}" class="profil-cta-btn" style="border-radius: 0; color: var(--color-text-muted) !important; border-color: var(--color-border) !important;">
          Lihat Katalog Produk <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>

    </div>
  </div>
</section>
@endsection