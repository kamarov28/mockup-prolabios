@extends('admin.layout')

@php
  $isEdit     = isset($category) && $category;
  $titleText  = $isEdit ? 'Edit Kategori: ' . $category->name : 'Tambah Kategori / Sub-Kategori';
  $actionUrl  = $isEdit
    ? route('admin.categories.update', $category->id)
    : route('admin.categories.store');
@endphp

@section('title', $titleText)
@section('page_title', $titleText)

@section('admin_content')
<div class="card bg-white shadow-sm" style="max-width: 640px; margin: 0 auto;">
  <div class="card-header">
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-diagram-3 text-danger me-2"></i>{{ $titleText }}</h5>
  </div>

  <form action="{{ $actionUrl }}" method="POST" class="card-body p-4">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-3">
      {{-- Nama --}}
      <div class="col-12">
        <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
               value="{{ old('name', $category->name ?? '') }}"
               placeholder="Contoh: Microbiology atau Food Safety"
               required autofocus>
        <div class="form-text">Nama ini akan ditampilkan di sidebar produk dan di dropdown form produk.</div>
      </div>

      {{-- Key / Slug --}}
      <div class="col-12">
        <label for="key" class="form-label fw-bold">Key (Slug)</label>
        <input type="text" class="form-control font-monospace" id="key" name="key"
               value="{{ old('key', $category->key ?? '') }}"
               placeholder="Dibuat otomatis dari nama jika dikosongkan"
               pattern="[a-z0-9\-]+"
               title="Hanya huruf kecil, angka, dan tanda hubung">
        <div class="form-text">Identifier unik. Hanya huruf kecil, angka, dan <code>-</code>. Contoh: <code>food-safety</code>. Jika dikosongkan, akan dibuat otomatis dari nama.</div>
        @if($isEdit)
          <div class="form-text text-warning">
            <i class="bi bi-exclamation-triangle me-1"></i>Mengubah key akan memutus hubungan produk-produk yang sudah menggunakan key lama.
          </div>
        @endif
      </div>

      {{-- Parent / Induk --}}
      <div class="col-12">
        <label for="parent_id" class="form-label fw-bold">Tipe</label>
        <select class="form-select" id="parent_id" name="parent_id">
          <option value="">📁 Kategori Utama (tanpa induk)</option>
          @foreach($parents as $parent)
            <option value="{{ $parent->id }}"
              {{ (string)(old('parent_id', $selectedParentId ?? '')) === (string)$parent->id ? 'selected' : '' }}>
              └─ Sub-kategori dari: {{ $parent->name }}
            </option>
          @endforeach
        </select>
        <div class="form-text">Pilih "Kategori Utama" jika ini adalah kategori tingkat pertama (Microbiology, Device, dst.), atau pilih induknya jika ini adalah sub-kategori.</div>
      </div>

      {{-- Sort Order --}}
      <div class="col-md-4">
        <label for="sort_order" class="form-label fw-bold">Urutan Tampil</label>
        <input type="number" class="form-control" id="sort_order" name="sort_order"
               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               min="0" placeholder="0">
        <div class="form-text">Angka lebih kecil tampil lebih awal di sidebar.</div>
      </div>
    </div>

    {{-- Buttons --}}
    <div class="mt-4 border-top pt-3 d-flex justify-content-between">
      <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Batal
      </a>
      <button type="submit" class="btn btn-success px-4">
        <i class="bi bi-check-lg me-1"></i> Simpan
      </button>
    </div>
  </form>
</div>
@endsection

@section('admin_scripts')
<script>
  // Auto-generate key dari name kalau key kosong
  const nameInput = document.getElementById('name');
  const keyInput  = document.getElementById('key');

  nameInput.addEventListener('input', function () {
    if (keyInput.dataset.edited) return; // User sudah manual edit key
    const slug = this.value
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s\-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
    keyInput.value = slug;
  });

  keyInput.addEventListener('input', function () {
    // Tandai bahwa user sudah manual edit
    this.dataset.edited = this.value ? '1' : '';
  });
</script>
@endsection
