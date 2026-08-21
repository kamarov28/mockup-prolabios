@extends('admin.layout')

@php
  $isEdit = isset($sector);
  $titleText = $isEdit ? 'Edit Sektor' : 'Tambah Sektor';
  $actionUrl = $isEdit
    ? route('admin.sectors.update', ['id' => $sector['id']])
    : route('admin.sectors.store');
  $previewImage = old('image_url', $sector['image'] ?? 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
@endphp

@section('title', $isEdit ? 'Edit Sektor: ' . $sector['name'] : 'Tambah Sektor')
@section('page_title', $titleText)

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Konten</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit: <strong style="color: var(--color-text-main);">{{ $sector['name'] }}</strong>
      @else
        Tambah sektor industri baru untuk halaman sektor publik.
      @endif
    </p>
  </div>
  <a href="{{ route('admin.sectors') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="admin-card" style="max-width: 720px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Sektor</h3>
    </div>
  </div>

  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="admin-card-body">
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
            <label for="id" class="admin-form-label">ID Sektor <span style="color: var(--color-accent);">*</span></label>
            <input type="text" class="form-control font-monospace" id="id" name="id"
                   value="{{ old('id', $sector['id'] ?? '') }}"
                   required placeholder="Contoh: pharmaceutical"
                   {{ $isEdit ? 'readonly' : '' }}>
            <p class="form-text mb-0 mt-2">Hanya huruf, angka, dan tanda hubung.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="name" class="admin-form-label">Nama Sektor <span style="color: var(--color-accent);">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $sector['name'] ?? '') }}"
                   required placeholder="Contoh: Pharmaceutical" autofocus>
          </div>
        </div>
      </div>

      <div class="admin-form-group mb-0">
        <label for="description" class="admin-form-label">Deskripsi Sektor</label>
        <textarea class="form-control" id="description" name="description" rows="8"
                  placeholder="Setiap baris baru = paragraf baru di halaman sektor.">{{ old('description', $isEdit && isset($sector['description']) ? implode("\n", $sector['description']) : '') }}</textarea>
        <p class="form-text mb-0 mt-2">Pisahkan paragraf dengan Enter.</p>
      </div>

      <div class="admin-form-group mb-0 pt-3" style="border-top: 1px solid var(--color-border);">
        <label class="admin-form-label">Gambar Utama Sektor</label>
        <div class="row g-3 align-items-center">
          <div class="col-md-3">
            <div style="width: 100%; aspect-ratio: 16/9; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden; background: rgba(255,255,255,0.03);">
              <img id="image_preview" src="{{ $previewImage }}" alt="Preview"
                   style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-3">
              <label for="image_file" class="admin-form-label">Upload File Lokal</label>
              <input type="file" id="image_file" class="form-control" name="image_file" accept="image/*">
            </div>
            <div>
              <label for="image_url" class="admin-form-label">Atau URL Gambar</label>
              <input type="text" id="image_url" class="form-control" name="image_url"
                     value="{{ old('image_url', $sector['image'] ?? '') }}"
                     placeholder="https://...">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.sectors') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> Simpan Sektor
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_scripts')
<script>
  const fileInput = document.getElementById('image_file');
  if (fileInput) {
    fileInput.addEventListener('change', function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          document.getElementById('image_preview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  const urlInput = document.getElementById('image_url');
  if (urlInput) {
    urlInput.addEventListener('input', function () {
      const val = this.value.trim();
      if (val !== '') document.getElementById('image_preview').src = val;
    });
  }
</script>
@endsection
