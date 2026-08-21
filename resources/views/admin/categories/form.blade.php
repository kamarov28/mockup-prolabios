@extends('admin.layout')

@php
  $isEdit     = isset($category) && $category;
  $titleText  = $isEdit ? 'Edit Kategori' : 'Tambah Kategori';
  $actionUrl  = $isEdit
    ? route('admin.categories.update', $category->id)
    : route('admin.categories.store');
@endphp

@section('title', $isEdit ? 'Edit Kategori: ' . $category->name : 'Tambah Kategori')
@section('page_title', $titleText)

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Konten</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit: <strong style="color: var(--color-text-main);">{{ $category->name }}</strong>
      @else
        Buat kategori utama atau sub-kategori baru untuk katalog produk.
      @endif
    </p>
  </div>
  <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="admin-card" style="max-width: 640px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Kategori</h3>
    </div>
  </div>

  <form action="{{ $actionUrl }}" method="POST" class="admin-card-body">
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

    <div class="d-flex flex-column gap-4">

      {{-- Nama --}}
      <div class="admin-form-group mb-0">
        <label for="name" class="admin-form-label">Nama Kategori <span style="color: var(--color-accent);">*</span></label>
        <input type="text" class="form-control" id="name" name="name"
               value="{{ old('name', $category->name ?? '') }}"
               placeholder="Contoh: Microbiology atau Food Safety"
               required autofocus>
        <p class="form-text mb-0 mt-2">Ditampilkan di sidebar produk dan dropdown form produk.</p>
      </div>

      {{-- Key / Slug --}}
      <div class="admin-form-group mb-0">
        <label for="key" class="admin-form-label">Key (Slug)</label>
        <input type="text" class="form-control font-monospace" id="key" name="key"
               value="{{ old('key', $category->key ?? '') }}"
               placeholder="Dibuat otomatis dari nama jika dikosongkan"
               pattern="[a-z0-9\-]+"
               title="Hanya huruf kecil, angka, dan tanda hubung">
        <p class="form-text mb-0 mt-2">
          Identifier unik. Hanya huruf kecil, angka, dan <code>-</code>.
          Contoh: <code>food-safety</code>. Kosongkan untuk generate otomatis.
        </p>
        @if($isEdit)
          <p class="form-text mb-0 mt-2" style="color: #f59e0b;">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Mengubah key akan ikut meng-update produk yang memakai key lama.
          </p>
        @endif
      </div>

      {{-- Parent / Induk --}}
      <div class="admin-form-group mb-0">
        <label for="parent_id" class="admin-form-label">Tipe</label>
        <select class="form-select" id="parent_id" name="parent_id">
          <option value="">Kategori Utama (tanpa induk)</option>
          @foreach($parents as $parent)
            <option value="{{ $parent->id }}"
              {{ (string)(old('parent_id', $selectedParentId ?? '')) === (string)$parent->id ? 'selected' : '' }}>
              Sub-kategori dari: {{ $parent->name }}
            </option>
          @endforeach
        </select>
        <p class="form-text mb-0 mt-2">
          Pilih "Kategori Utama" untuk tingkat pertama (Microbiology, Device, dst.),
          atau pilih induk jika ini sub-kategori.
        </p>
      </div>

      {{-- Sort Order --}}
      <div class="admin-form-group mb-0" style="max-width: 180px;">
        <label for="sort_order" class="admin-form-label">Urutan Tampil</label>
        <input type="number" class="form-control" id="sort_order" name="sort_order"
               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               min="0" placeholder="0">
        <p class="form-text mb-0 mt-2">Angka lebih kecil tampil lebih awal.</p>
      </div>

    </div>

    {{-- Buttons --}}
    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> Simpan
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_scripts')
<script>
  const nameInput = document.getElementById('name');
  const keyInput  = document.getElementById('key');

  nameInput.addEventListener('input', function () {
    if (keyInput.dataset.edited) return;
    const slug = this.value
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s\-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
    keyInput.value = slug;
  });

  keyInput.addEventListener('input', function () {
    this.dataset.edited = this.value ? '1' : '';
  });

  // On edit: mark key as already set so auto-slug doesn't overwrite
  @if($isEdit)
    keyInput.dataset.edited = '1';
  @endif
</script>
@endsection
