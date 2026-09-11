@extends('layouts.app')

@section('title', 'Pengajuan Berhasil Dikirim | PT. Prolabios Mitra Analitika')

@section('content')
<section class="cart-page-bg">
  <div class="container py-4 text-center">
    <div class="card rfq-success-card mx-auto p-4 p-md-5">

      <!-- Icon Check Circle -->
      <div class="nb-status-icon-box mb-3 mx-auto">
        <i data-lucide="check-circle-2"></i>
      </div>

      <div class="mb-3">
        <span class="nb-badge" style="font-size: 0.72rem;">PENGAJUAN BERHASIL DIKIRIM</span>
      </div>

      <h1 class="profil-section-title mb-2" style="font-size: 2rem !important; color: var(--nb-ink);">Terima Kasih, {{ $rfq->name }}!</h1>
      <p class="profil-body-text mb-4" style="font-size: 0.92rem; line-height: 1.6; color: var(--nb-muted);">
        Pengajuan penawaran harga Anda dengan nomor pengajuan <strong style="color: var(--nb-primary);">{{ $rfq->rfq_number }}</strong> telah berhasil kami terima. Tim sales kami akan segera menghubungi Anda via Email atau WhatsApp.
      </p>

      <!-- Details Box -->
      <div class="rfq-details-box p-4 mb-4 text-start">
        <h3 class="cart-sidebar-title" style="font-size: 1rem; margin-bottom: 16px; color: var(--nb-ink); font-weight: 700;">
          <i data-lucide="file-text" class="text-primary me-2"></i> Detail Pengajuan Penawaran
        </h3>

        <div class="row g-2 small mb-3" style="color: var(--nb-ink);">
          <div class="col-sm-4" style="color: var(--nb-muted);">Nomor Pengajuan:</div>
          <div class="col-sm-8"><strong style="color: var(--nb-primary); font-family: var(--font-mono);">{{ $rfq->rfq_number }}</strong></div>

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

      <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="{{ route('home') }}" class="nb-btn nb-btn-primary">
          <i data-lucide="home" class="me-2"></i> Kembali ke Beranda
        </a>
        <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost">
          Lihat Katalog Produk <i data-lucide="arrow-right" class="ms-2"></i>
        </a>
      </div>

    </div>
  </div>
</section>
@endsection
