@extends('layouts.app')

@section('title', 'Masuk ke Portal Buletin Teknis | PROLABIOS')

@section('content')
<div class="py-5" style="background: var(--nb-bg); min-height: 80vh; display: flex; align-items: center;">
  <div class="container" style="max-width: 480px;">
    
    <div class="text-center mb-4">
      <div class="d-inline-flex align-items-center gap-2 mb-2">
        <span class="badge" style="background: rgba(166, 23, 28, 0.08); color: var(--nb-primary); font-size: 0.75rem; font-weight: 700; border-radius: 4px; padding: 4px 10px;">
          <i data-lucide="lock" style="width: 12px; height: 12px; vertical-align: -2px;"></i> SUBSCRIBER PORTAL
        </span>
      </div>
      <h1 class="h4 fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">Masuk ke Akun Buletin</h1>
      <p class="text-muted small" style="font-size: 0.84rem;">Kelola preferensi sektor industri, riwayat buletin teknis, dan informasi instansi Anda.</p>
    </div>

    <div class="card p-4 p-md-5 border-0" style="background: #FFFFFF; border: 1px solid #E2E8F0 !important; border-radius: 12px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);">

      @if(session('success'))
        <div class="alert alert-success mb-4" role="alert" style="font-size: 0.85rem; border-radius: 6px;">
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger mb-4" role="alert" style="font-size: 0.85rem; border-radius: 6px;">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('newsletter.login.submit') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label small fw-bold" style="color: #334155;">Email Terdaftar <span class="text-danger">*</span></label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="email@institusi.com" style="border-color: #CBD5E1; font-size: 0.9rem;">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label small fw-bold" style="color: #334155;">Kata Sandi <span class="text-danger">*</span></label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Kata sandi akun buletin" style="border-color: #CBD5E1; font-size: 0.9rem;">
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" style="cursor: pointer;">
          <label class="form-check-label small" for="remember" style="cursor: pointer; color: #475569; font-size: 0.82rem;">
            Ingat saya di perangkat ini
          </label>
        </div>

        <button type="submit" class="nb-btn nb-btn-primary w-100 justify-content-center py-2 text-uppercase fw-bold" style="letter-spacing: 0.5px;">
          <i data-lucide="log-in"></i> Masuk ke Portal
        </button>

        <div class="text-center mt-3 pt-3 border-top" style="border-color: #F1F5F9 !important;">
          <span class="small text-muted">Belum terdaftar?</span>
          <a href="{{ route('newsletter.signup') }}" class="small fw-semibold ms-1 text-decoration-underline" style="color: var(--nb-primary);">
            Daftar Buletin Sektor Baru &rarr;
          </a>
        </div>
      </form>

    </div>

  </div>
</div>
@endsection
