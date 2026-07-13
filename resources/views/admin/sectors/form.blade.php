@extends('admin.layout')

@php 
  $isEdit = isset($sector);
  $titleText = $isEdit ? 'Edit Sektor: ' . $sector['name'] : 'Tambah Sektor Baru';
  $actionUrl = $isEdit ? route('admin.sectors.update', ['id' => $sector['id']]) : route('admin.sectors.store');
@endphp

@section('title', $titleText)
@section('page_title', $titleText)

@section('admin_content')
<div class="card bg-white shadow-sm max-w-4xl mx-auto">
  <div class="card-header">
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-collection text-info me-2"></i>Formulir Data Sektor</h5>
  </div>
  
  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
    @csrf

    <div class="row g-3">
      <!-- Sector ID -->
      <div class="col-md-6">
        <label for="id" class="form-label fw-bold">ID Sektor <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="id" name="id" value="{{ old('id', $sector['id'] ?? '') }}" required placeholder="Contoh: pharmaceutical" {{ $isEdit ? 'readonly' : '' }}>
        <div class="form-text">ID unik sektor (hanya huruf, angka, dan tanda hubung, misal: <code>food-safety</code>).</div>
      </div>

      <!-- Sector Name -->
      <div class="col-md-6">
        <label for="name" class="form-label fw-bold">Nama Sektor Industri <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $sector['name'] ?? '') }}" required placeholder="Contoh: Pharmaceutical">
      </div>

      <!-- Description -->
      <div class="col-12 mt-4">
        <label for="description" class="form-label fw-bold">Deskripsi Sektor</label>
        <textarea class="form-control" id="description" name="description" rows="8" placeholder="Tulis deskripsi rinci mengenai sektor industri ini. Setiap baris baru akan dianggap sebagai paragraf baru di halaman sektor.">{{ old('description', $isEdit && isset($sector['description']) ? implode("\n", $sector['description']) : '') }}</textarea>
        <div class="form-text">Pisahkan antar paragraf dengan menekan tombol Enter (baris baru).</div>
      </div>

      <!-- Sector Image -->
      <div class="col-12 mt-4 border-top pt-3">
        <label class="form-label fw-bold">Gambar Utama Sektor</label>
        <div class="row align-items-center">
          <div class="col-md-3 text-center mb-3 mb-md-0">
            <div class="rounded border bg-light overflow-hidden mx-auto" style="width: 100%; aspect-ratio: 16/9; max-height: 120px;">
              <img id="image_preview" src="{{ old('image_url', $sector['image'] ?? 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') }}" alt="Preview" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="image_file" class="form-label small fw-bold">Upload File Lokal</label>
              <input type="file" id="image_file" class="form-control form-control-sm" name="image_file" accept="image/*">
            </div>
            <div>
              <label for="image_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
              <input type="text" id="image_url" class="form-control form-control-sm" name="image_url" value="{{ old('image_url', $sector['image'] ?? '') }}" placeholder="https://unsplash.com/...">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Buttons -->
    <div class="mt-4 border-top pt-3 d-flex justify-content-between">
      <a href="{{ route('admin.sectors') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Batal</a>
      <button type="submit" class="btn btn-info text-white px-4"><i class="bi bi-check-lg me-1"></i> Simpan Sektor</button>
    </div>
  </form>
</div>
@endsection

@section('admin_scripts')
<script>
  const fileInput = document.getElementById('image_file');
  if (fileInput) {
    fileInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('image_preview').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  const urlInput = document.getElementById('image_url');
  if (urlInput) {
    urlInput.addEventListener('input', function() {
      const val = this.value.trim();
      if (val !== '') {
        document.getElementById('image_preview').src = val;
      }
    });
  }
</script>
@endsection
