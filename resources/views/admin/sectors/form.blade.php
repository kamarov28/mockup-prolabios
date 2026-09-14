@extends('admin.layout')

@php
  $isEdit = isset($sector);
  $titleText = $isEdit ? 'Edit Sektor' : 'Tambah Sektor';
  $actionUrl = $isEdit
    ? route('admin.sectors.update', ['id' => $sector['id']])
    : route('admin.sectors.store');
  $previewImage = old('image_url', $sector['image'] ?? 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=600&q=80');
@endphp

@section('title', $isEdit ? 'Edit Sektor: ' . $sector['name'] : 'Tambah Sektor')
@section('page_title', $titleText)

@section('admin_content')

<x-admin.page-header 
  label="Konten"
  :title="$titleText"
  :backUrl="route('admin.sectors')">
  @if($isEdit)
    Mengedit: <strong style="color: var(--color-text-main);">{{ $sector['name'] }}</strong>
  @else
    Tambah sektor industri baru.
  @endif
</x-admin.page-header>

<div class="admin-card" style="max-width: 720px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Sektor</h3>
    </div>
  </div>

  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="admin-card-body">
    @csrf
    @if(!empty($isEdit)) @method('PUT') @endif

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
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
                  placeholder="Setiap baris baru = paragraf baru di halaman sektor.">{{ old('description', $isEdit && isset($sector['description']) ? (is_array($sector['description']) ? implode("\n", $sector['description']) : $sector['description']) : '') }}</textarea>
        <p class="form-text mb-0 mt-2">Pisahkan paragraf dengan Enter.</p>
      </div>

      <div class="pt-3" style="border-top: 1px solid var(--color-border);">
        <x-admin.image-upload
          label="Gambar Utama Sektor"
          nameFile="image_file"
          nameUrl="image_url"
          :valueUrl="old('image_url', $sector['image'] ?? '')"
          previewId="image_preview"
          :placeholderImage="$previewImage"
          aspectRatio="16/9"
          boxWidth="100%"
          boxHeight="130px"
        />
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.sectors') }}" class="admin-btn admin-btn-outline">
        <i data-lucide="arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i data-lucide="check"></i> Simpan
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_scripts')
<script>
  document.getElementById('image_file')?.addEventListener('change', function () {
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = e => { document.getElementById('image_preview').src = e.target.result; };
      reader.readAsDataURL(this.files[0]);
    }
  });
  document.getElementById('image_url')?.addEventListener('input', function () {
    if (this.value.trim()) document.getElementById('image_preview').src = this.value.trim();
  });
</script>
@endsection
