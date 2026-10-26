@extends('layouts.app')

@section('title', 'Preferensi Akun Buletin Teknis | PROLABIOS')

@section('content')
<div class="py-5" style="background: var(--nb-bg); min-height: 85vh;">
  <div class="container" style="max-width: 760px;">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <h1 class="h3 fw-bold mb-1" style="color: var(--nb-ink); font-family: var(--font-display);">Portal Akun Buletin</h1>
        <p class="text-muted small mb-0">Kelola sektor minat pembaruan dan profil institusi Anda.</p>
      </div>
      <div>
        <form action="{{ route('newsletter.logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="nb-btn nb-btn-ghost py-1.5 px-3" style="font-size: 0.8rem;">
            <i data-lucide="log-out"></i> Keluar
          </button>
        </form>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mb-4" role="alert" style="font-size: 0.85rem; border-radius: 6px;">
        {{ session('success') }}
      </div>
    @endif

    <div class="card p-4 p-md-5 border-0" style="background: #FFFFFF; border: 1px solid #E2E8F0 !important; border-radius: 12px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);">

      <form action="{{ route('newsletter.account.update') }}" method="POST">
        @csrf

        <div class="mb-4 pb-3 border-bottom" style="border-color: #F1F5F9 !important;">
          <h2 class="fs-6 fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px;">
            <i data-lucide="user" class="me-1 text-primary"></i> Data Instansi &amp; Kontak
          </h2>

          <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Email Terdaftar (ID Akun)</label>
            <input type="text" class="form-control" value="{{ $subscriber->email }}" readonly style="background: #F8FAFC; border-color: #CBD5E1; font-size: 0.9rem;">
            <div class="form-text small" style="font-size: 0.78rem;">Email tidak dapat diubah untuk menjaga validitas izin komunikasi PDP.</div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label for="first_name" class="form-label small fw-bold" style="color: #334155;">Nama Depan <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $subscriber->first_name) }}" required style="border-color: #CBD5E1;">
            </div>
            <div class="col-sm-6">
              <label for="last_name" class="form-label small fw-bold" style="color: #334155;">Nama Belakang</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $subscriber->last_name) }}" style="border-color: #CBD5E1;">
            </div>
          </div>

          <div class="row g-3">
            <div class="col-sm-6">
              <label for="company" class="form-label small fw-bold" style="color: #334155;">Nama Perusahaan / Rumah Sakit <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $subscriber->company) }}" required style="border-color: #CBD5E1;">
            </div>
            <div class="col-sm-6">
              <label for="city" class="form-label small fw-bold" style="color: #334155;">Kota / Domisili</label>
              <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $subscriber->city) }}" style="border-color: #CBD5E1;">
            </div>
          </div>
        </div>

        {{-- Sektor Terpilih --}}
        <div class="mb-4 pb-3 border-bottom" style="border-color: #F1F5F9 !important;">
          <h2 class="fs-6 fw-bold text-dark text-uppercase mb-2" style="letter-spacing: 0.5px;">
            <i data-lucide="layers" class="me-1 text-primary"></i> Sektor Minat Pembaruan Anda
          </h2>
          <p class="small text-muted mb-3" style="font-size: 0.8rem;">
            Pilih sektor laboratorium yang ingin Anda terima notifikasi rilis produk, regulasi mutu, dan buletin teknisnya:
          </p>

          <div class="row row-cols-1 row-cols-sm-2 g-3">
            @foreach($sectors as $sec)
              @php 
                $checked = in_array($sec->id, old('sectors', $selectedSectorIds)); 
                $secImage = !empty($sec->image) ? $sec->image : (file_exists(public_path('images/sectors/' . $sec->id . '.jpg')) ? 'images/sectors/' . $sec->id . '.jpg' : 'images/sectors/general-purpose.jpg');
              @endphp
              <div class="col">
                <label class="sector-choice-card d-flex flex-column justify-content-between rounded-3 h-100 position-relative overflow-hidden" 
                       style="cursor: pointer; background-image: url('{{ asset($secImage) }}');">
                  <div class="sector-choice-overlay"></div>
                  
                  {{-- Checkbox pinned cleanly at Top-Right --}}
                  <div class="position-relative z-1 d-flex justify-content-end w-100">
                    <input type="checkbox" name="sectors[]" value="{{ $sec->id }}" class="form-check-input sector-choice-input" {{ $checked ? 'checked' : '' }} style="cursor: pointer;">
                  </div>

                  {{-- Text pinned at Bottom-Left across full width --}}
                  <div class="position-relative z-1 w-100 pt-3">
                    <strong class="d-block text-white fw-bold mb-1" style="font-size: 0.98rem; text-shadow: 0 1px 4px rgba(0,0,0,0.85); line-height: 1.25;">
                      {{ $sec->name }}
                    </strong>
                    <span class="d-block small lh-sm text-white-50" style="font-size: 0.78rem; text-shadow: 0 1px 2px rgba(0,0,0,0.7);">
                      Pembaruan reagen &amp; standar {{ strtolower($sec->name) }}
                    </span>
                  </div>
                </label>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Ubah Password (Opsional) --}}
        <div class="mb-4">
          <h2 class="fs-6 fw-bold text-dark text-uppercase mb-2" style="letter-spacing: 0.5px;">
            <i data-lucide="key" class="me-1 text-primary"></i> Ubah Kata Sandi (Opsional)
          </h2>
          <p class="small text-muted mb-3" style="font-size: 0.8rem;">Biarkan kosong jika tidak ingin mengubah kata sandi Anda.</p>

          <div class="row g-3">
            <div class="col-sm-6">
              <label for="password" class="form-label small fw-bold" style="color: #334155;">Kata Sandi Baru</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" style="border-color: #CBD5E1;">
            </div>
            <div class="col-sm-6">
              <label for="password_confirmation" class="form-label small fw-bold" style="color: #334155;">Konfirmasi Kata Sandi Baru</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" style="border-color: #CBD5E1;">
            </div>
          </div>
        </div>

        <button type="submit" class="nb-btn nb-btn-primary py-2 px-4 fw-bold">
          <i data-lucide="save"></i> Simpan Pembaruan Preferensi
        </button>
      </form>

    </div>

  </div>
</div>
@endsection
