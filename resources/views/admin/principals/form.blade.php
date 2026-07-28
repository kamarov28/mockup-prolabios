@extends('admin.layout')

@section('title', isset($principal) ? 'Edit Prinsipal' : 'Tambah Prinsipal Baru')
@section('page_title', isset($principal) ? 'Edit Prinsipal' : 'Tambah Prinsipal Baru')

@section('admin_content')
<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Mitra & Partner</span>
      <h2 class="admin-card-header-title">{{ isset($principal) ? 'Edit Data Prinsipal: ' . $principal->name : 'Tambah Prinsipal Baru' }}</h2>
    </div>
    <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-ghost">
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
  </div>

  <div class="admin-card-body">
    <form action="{{ isset($principal) ? route('admin.principals.update', $principal->id) : route('admin.principals.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label fw-bold">Nama Prinsipal / Brand <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $principal->name ?? '') }}" required placeholder="Contoh: LIOFILCHEM, BIOENDO, SIFIN">
        </div>

        <div class="col-md-6">
          <label for="address" class="form-label fw-bold">Negara / Alamat Singkat</label>
          <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $principal->address ?? '') }}" placeholder="Contoh: Italy, Germany, South Korea">
        </div>

        <div class="col-md-6">
          <label for="status" class="form-label fw-bold">Status Publikasi <span class="text-danger">*</span></label>
          <select class="form-select" id="status" name="status" required>
            <option value="online" {{ old('status', $principal->status ?? 'online') === 'online' ? 'selected' : '' }}>Online (Tampilkan di Beranda)</option>
            <option value="draft" {{ old('status', $principal->status ?? 'online') === 'draft' ? 'selected' : '' }}>Draft / Sembunyikan</option>
          </select>
        </div>

        <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-10">
          <label class="form-label fw-bold">Logo Prinsipal</label>
          <div class="row g-3">
            <div class="col-sm-3 text-center">
              <div class="border rounded bg-white p-2 d-flex align-items-center justify-content-center" style="width: 100%; height: 90px;">
                <img id="logo-preview" src="{{ $principal->logo ?? 'https://via.placeholder.com/160x60?text=Logo' }}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
              </div>
            </div>
            <div class="col-sm-9">
              <div class="mb-3">
                <label for="logo_file" class="form-label small fw-bold">Upload File Logo (PNG/JPG/SVG)</label>
                <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*" onchange="previewImage(this)">
              </div>
              <div>
                <label for="logo_url" class="form-label small fw-bold">atau URL Logo</label>
                <input type="url" class="form-control" id="logo_url" name="logo_url" value="{{ old('logo_url', $principal->logo ?? '') }}" placeholder="https://..." onchange="document.getElementById('logo-preview').src = this.value">
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-10 d-flex justify-content-end gap-2">
          <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-ghost">Batal</a>
          <button type="submit" class="admin-btn admin-btn-primary">
            <i class="bi bi-check-lg"></i> {{ isset($principal) ? 'Simpan Perubahan' : 'Tambah Prinsipal' }}
          </button>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection

@section('admin_scripts')
<script>
  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('logo-preview').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection
