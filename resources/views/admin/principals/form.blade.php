@extends('admin.layout')

@php
  $isEdit = isset($principal);
  $titleText = $isEdit ? 'Edit Prinsipal' : 'Tambah Prinsipal';
@endphp

@section('title', $isEdit ? 'Edit Prinsipal' : 'Tambah Prinsipal Baru')
@section('page_title', $titleText)

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Mitra & Partner</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit: <strong style="color: var(--color-text-main);">{{ $principal->name }}</strong>
      @else
        Tambah brand / prinsipal mitra untuk ditampilkan di beranda.
      @endif
    </p>
  </div>
  <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="admin-card" style="max-width: 720px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Prinsipal</h3>
    </div>
  </div>

  <form action="{{ $isEdit ? route('admin.principals.update', $principal->id) : route('admin.principals.store') }}"
        method="POST" enctype="multipart/form-data" class="admin-card-body">
    @csrf

    @if($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="d-flex flex-column gap-4">

      <div class="row g-3">
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="name" class="admin-form-label">Nama Prinsipal / Brand <span style="color: var(--color-accent);">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $principal->name ?? '') }}"
                   required placeholder="Contoh: LIOFILCHEM, BIOENDO" autofocus>
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="address" class="admin-form-label">Negara / Alamat Singkat</label>
            <input type="text" class="form-control" id="address" name="address"
                   value="{{ old('address', $principal->address ?? '') }}"
                   placeholder="Contoh: Italy, Germany, South Korea">
          </div>
        </div>
      </div>

      <div class="admin-form-group mb-0" style="max-width: 280px;">
        <label for="status" class="admin-form-label">Status Publikasi <span style="color: var(--color-accent);">*</span></label>
        <select class="form-select" id="status" name="status" required>
          <option value="online" {{ old('status', $principal->status ?? 'online') === 'online' ? 'selected' : '' }}>Online (Tampil di Beranda)</option>
          <option value="draft" {{ old('status', $principal->status ?? 'online') === 'draft' ? 'selected' : '' }}>Draft / Sembunyikan</option>
        </select>
      </div>

      <div class="admin-form-group mb-0 pt-3" style="border-top: 1px solid var(--color-border);">
        <label class="admin-form-label">Logo Prinsipal</label>
        <div class="row g-3 align-items-center">
          <div class="col-sm-3">
            <div style="width: 100%; height: 90px; border: 1px solid var(--color-border); border-radius: 6px; background: #ffffff; padding: 8px; display: flex; align-items: center; justify-content: center;">
              <img id="logo-preview"
                   src="{{ $principal->logo ?? asset('images/placeholder.svg') }}"
                   alt="Preview"
                   style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
          </div>
          <div class="col-sm-9">
            <div class="mb-3">
              <label for="logo_file" class="admin-form-label">Upload File Logo (PNG/JPG/WEBP)</label>
              <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*" onchange="previewImage(this)">
            </div>
            <div>
              <label for="logo_url" class="admin-form-label">Atau URL Logo</label>
              <input type="url" class="form-control" id="logo_url" name="logo_url"
                     value="{{ old('logo_url', $principal->logo ?? '') }}"
                     placeholder="https://..."
                     onchange="document.getElementById('logo-preview').src = this.value || '{{ asset('images/placeholder.svg') }}'">
            </div>
          </div>
        </div>
        <p class="form-text mb-0 mt-2">Logo ditampilkan di background putih agar brand berwarna gelap tetap terbaca.</p>
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Prinsipal' }}
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_scripts')
<script>
  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('logo-preview').src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection
