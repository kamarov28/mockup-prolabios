@extends('layouts.app')

@section('title', 'Pendaftaran Buletin Teknis Laboratorium | PROLABIOS')
@section('meta_description', 'Daftarkan institusi laboratorium Anda untuk menerima pembaruan berkala spesifikasi produk, rilis reagen, standar ISO/BPOM, dan buletin teknis tersegmentasi.')

@section('content')
<div class="py-5" style="background: var(--nb-bg); min-height: 85vh;">
  <div class="container" style="max-width: 720px;">
    
    <!-- Header Block (Liofilchem Formal Style) -->
    <div class="text-center mb-4">
      <div class="d-inline-flex align-items-center gap-2 mb-2">
        <span class="badge" style="background: rgba(166, 23, 28, 0.08); color: var(--nb-primary); font-size: 0.75rem; font-weight: 700; border-radius: 4px; padding: 4px 10px;">
          <i data-lucide="mail" style="width: 13px; height: 13px; vertical-align: -2px;"></i> SECTOR-BASED NEWSLETTER
        </span>
      </div>
      <h1 class="h3 fw-bold" style="color: var(--nb-ink); font-family: var(--font-display);">Sign Up to Stay in Touch!</h1>
      <p class="text-muted small mx-auto" style="max-width: 540px; line-height: 1.6;">
        Daftarkan akun institusi Anda untuk menerima pembaruan rilis reagen, instrumen analitika, serta panduan regulasi mutu (ISO &amp; BPOM) sesuai dengan sektor spesialisasi laboratorium Anda.
      </p>
    </div>

    <!-- Main Card -->
    <div class="card p-4 p-md-5 border-0" style="background: #FFFFFF; border: 1px solid #E2E8F0 !important; border-radius: 12px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);">

      @if($errors->any())
        <div class="alert alert-danger mb-4" role="alert" style="font-size: 0.85rem; border-radius: 6px;">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('newsletter.signup.submit') }}" method="POST">
        @csrf

        {{-- 1. Identitas Akun --}}
        <div class="mb-3">
          <label for="email" class="form-label small fw-bold" style="color: #334155;">Email Kantor / Institusi <span class="text-danger">*</span></label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="analyst@laboratory.co.id" style="border-color: #CBD5E1; font-size: 0.9rem;">
          <div class="form-text small text-muted" style="font-size: 0.78rem;">Gunakan email resmi instansi atau perusahaan laboratorium Anda.</div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label for="first_name" class="form-label small fw-bold" style="color: #334155;">Nama Depan (First Name) <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required placeholder="Contoh: Rian" style="border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
          <div class="col-sm-6">
            <label for="last_name" class="form-label small fw-bold" style="color: #334155;">Nama Belakang (Last Name)</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Contoh: Pratama" style="border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
        </div>

        <div class="mb-3">
          <label for="company" class="form-label small fw-bold" style="color: #334155;">Nama Perusahaan / Rumah Sakit / Instansi Lab <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}" required placeholder="PT. Farmasi Sehat / RSUD Sentra Lab" style="border-color: #CBD5E1; font-size: 0.9rem;">
        </div>

        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label for="city" class="form-label small fw-bold" style="color: #334155;">Kota / Domisili Laboratorium</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="Jakarta / Surabaya / Bandung" style="border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
          <div class="col-sm-6">
            <label for="country" class="form-label small fw-bold" style="color: #334155;">Negara</label>
            <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Indonesia') }}" readonly style="background: #F8FAFC; border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
        </div>

        {{-- 2. Pilihan Sektor Minat (Liofilchem Segmented Cards) --}}
        <div class="mb-4">
          <label class="form-label small fw-bold d-block mb-1" style="color: #334155;">
            Pilih Sektor Minat Pembaruan Anda <span class="text-danger">*</span>
          </label>
          <p class="small text-muted mb-3" style="font-size: 0.8rem;">
            Pilih minimal satu sektor. Anda hanya akan menerima notifikasi email teknis yang berkaitan dengan sektor yang Anda centang:
          </p>

          <div class="row row-cols-1 row-cols-sm-2 g-3">
            @foreach($sectors as $sec)
              @php 
                $checked = is_array(old('sectors')) && in_array($sec->id, old('sectors'));
                $secImage = !empty($sec->image) ? $sec->image : (file_exists(public_path('images/sectors/' . $sec->id . '.jpg')) ? 'images/sectors/' . $sec->id . '.jpg' : 'images/sectors/general-purpose.jpg');
              @endphp
              <div class="col">
                <label class="sector-choice-card d-flex flex-column justify-content-between p-3.5 rounded-3 h-100 position-relative overflow-hidden" 
                       style="cursor: pointer; min-height: 125px; background-image: url('{{ asset($secImage) }}');">
                  <div class="sector-choice-overlay"></div>
                  
                  {{-- Checkbox pinned cleanly at Top-Right --}}
                  <div class="position-relative z-1 d-flex justify-content-end w-100 mb-2">
                    <input type="checkbox" name="sectors[]" value="{{ $sec->id }}" class="form-check-input sector-choice-input" {{ $checked ? 'checked' : '' }} style="cursor: pointer;">
                  </div>

                  {{-- Text pinned at Bottom-Left across full width --}}
                  <div class="position-relative z-1 w-100">
                    <strong class="d-block text-white fw-bold mb-1" style="font-size: 0.95rem; text-shadow: 0 1px 4px rgba(0,0,0,0.8); line-height: 1.25;">
                      {{ $sec->name }}
                    </strong>
                    <span class="d-block small lh-sm text-white-50" style="font-size: 0.76rem; text-shadow: 0 1px 2px rgba(0,0,0,0.6);">
                      Pembaruan reagen &amp; standar {{ strtolower($sec->name) }}
                    </span>
                  </div>
                </label>
              </div>
            @endforeach
          </div>
        </div>

        {{-- 3. Kata Sandi Akun --}}
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label for="password" class="form-label small fw-bold" style="color: #334155;">Kata Sandi Akun <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 8 karakter" style="border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
          <div class="col-sm-6">
            <label for="password_confirmation" class="form-label small fw-bold" style="color: #334155;">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi" style="border-color: #CBD5E1; font-size: 0.9rem;">
          </div>
        </div>

        {{-- 4. Kepatuhan Privasi --}}
        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" name="privacy_consent" id="privacy_consent" value="1" required {{ old('privacy_consent', 1) ? 'checked' : '' }} style="cursor: pointer;">
          <label class="form-check-label small" for="privacy_consent" style="cursor: pointer; color: #475569; font-size: 0.82rem;">
            Saya telah membaca dan menyetujui <a href="{{ route('privacy') }}" target="_blank" class="fw-semibold text-decoration-underline" style="color: var(--nb-primary);">Kebijakan Privasi</a> PT. Prolabios Mitra Analitika.
          </label>
        </div>

        <button type="submit" class="nb-btn nb-btn-primary w-100 justify-content-center py-2.5 text-uppercase fw-bold" style="letter-spacing: 0.5px;">
          <i data-lucide="mail"></i> Sign Up for Our Newsletter
        </button>

        <div class="text-center mt-3 pt-3 border-top" style="border-color: #F1F5F9 !important;">
          <span class="small text-muted">Sudah memiliki akun buletin?</span>
          <a href="{{ route('newsletter.login') }}" class="small fw-semibold ms-1 text-decoration-underline" style="color: var(--nb-primary);">
            Masuk ke Portal Buletin &rarr;
          </a>
        </div>
      </form>

    </div>

  </div>
</div>
@endsection
