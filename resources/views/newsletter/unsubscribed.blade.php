@extends('layouts.app')

@section('title', 'Berhenti Berlangganan Buletin | PROLABIOS')

@section('content')
<div class="py-5" style="background: var(--nb-bg); min-height: 75vh; display: flex; align-items: center;">
  <div class="container text-center" style="max-width: 520px;">
    
    <div class="card p-4 p-md-5 border-0" style="background: #FFFFFF; border: 1px solid #E2E8F0 !important; border-radius: 12px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);">
      <div class="mb-3">
        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: #FEF2F2; color: #DC2626;">
          <i data-lucide="check" style="width: 28px; height: 28px;"></i>
        </span>
      </div>

      <h1 class="h4 fw-bold mb-2" style="color: var(--nb-ink); font-family: var(--font-display);">Berhenti Berlangganan Berhasil</h1>
      <p class="text-muted small mb-4" style="line-height: 1.6;">
        Email <strong>{{ $subscriber->email }}</strong> telah dinonaktifkan dari daftar pengiriman buletin sektor otomatis Prolabios.
      </p>

      <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('home') }}" class="nb-btn nb-btn-ghost py-2 px-3 small">
          Kembali ke Beranda
        </a>
        <a href="{{ route('newsletter.login') }}" class="nb-btn nb-btn-primary py-2 px-3 small">
          Masuk untuk Aktifkan Kembali
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
