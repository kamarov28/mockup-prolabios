@extends('admin.layout')

@php
  $isEdit = isset($sector);
  $titleText = $isEdit ? 'Edit Sektor' : 'Tambah Sektor';
  $actionUrl = $isEdit
    ? route('admin.sectors.update', ['id' => $sector['id']])
    : route('admin.sectors.store');
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
        Tambah sektor industri baru.
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
      <div class="admin-form-group mb-0">
        <label for="name" class="admin-form-label">Nama Sektor <span style="color: var(--color-accent);">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $sector['name'] ?? '') }}" required autofocus>
      </div>

      <div class="admin-form-group mb-0">
        <label for="id" class="admin-form-label">ID / Slug <span style="color: var(--color-accent);">*</span></label>
        <input type="text" class="form-control" id="id" name="id" value="{{ old('id', $sector['id'] ?? '') }}" required
               {{ $isEdit ? 'readonly' : '' }}>
        @if($isEdit)
          <p class="form-text mb-0 mt-2">ID tidak bisa diubah saat edit.</p>
        @endif
      </div>

      <div class="admin-form-group mb-0">
        <label for="description" class="admin-form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="6"
                  placeholder="Setiap baris baru = paragraf baru di halaman sektor.">{{ old('description', $isEdit && isset($sector['description']) ? (is_array($sector['description']) ? implode("\n", $sector['description']) : $sector['description']) : '') }}</textarea>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.sectors') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> Simpan
      </button>
    </div>
  </form>
</div>

@endsection
