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
    <span class="admin-page-label">Konten</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit prinsipal / brand partner.
      @else
        Tambah prinsipal baru ke daftar partner.
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
        <label for="name" class="admin-form-label">Nama <span style="color: var(--color-accent);">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $principal->name ?? '') }}" required autofocus>
      </div>

      <div class="admin-form-group mb-0">
        <label for="website" class="admin-form-label">Website</label>
        <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $principal->website ?? '') }}" placeholder="https://">
      </div>

      <div class="admin-form-group mb-0">
        <label for="logo_file" class="admin-form-label">Logo</label>
        <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*">
        <input type="text" class="form-control mt-2" name="logo_url" value="{{ old('logo_url', $principal->logo ?? '') }}" placeholder="Atau URL logo">
      </div>

      <div class="admin-form-group mb-0">
        <label for="sort_order" class="admin-form-label">Urutan</label>
        <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $principal->sort_order ?? 0) }}">
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
