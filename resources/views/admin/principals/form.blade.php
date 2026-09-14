@extends('admin.layout')

@php
  $isEdit = isset($principal);
  $titleText = $isEdit ? 'Edit Prinsipal' : 'Tambah Prinsipal';
@endphp

@section('title', $isEdit ? 'Edit Prinsipal' : 'Tambah Prinsipal Baru')
@section('page_title', $titleText)

@section('admin_content')

<x-admin.page-header 
  label="Mitra & Partner"
  :title="$titleText"
  :backUrl="route('admin.principals')">
  @if($isEdit)
    Mengedit: <strong style="color: var(--color-text-main);">{{ $principal->name }}</strong>
  @else
    Tambah brand / prinsipal mitra untuk ditampilkan di beranda.
  @endif
</x-admin.page-header>

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
    @if(!empty($isEdit)) @method('PUT') @endif

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

      <div class="pt-3" style="border-top: 1px solid var(--color-border);">
        <x-admin.image-upload
          label="Logo Prinsipal"
          helpText="Logo ditampilkan di background putih agar brand berwarna gelap tetap terbaca."
          nameFile="logo_file"
          nameUrl="logo_url"
          :valueUrl="old('logo_url', $principal->logo ?? '')"
          previewId="logo-preview"
          :placeholderImage="asset('images/placeholder.svg')"
          boxWidth="100%"
          boxHeight="90px"
        />
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-outline">
        <i data-lucide="arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i data-lucide="check"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Prinsipal' }}
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
