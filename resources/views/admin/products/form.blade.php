@extends('admin.layout')

@php 
  $isEdit = isset($product);
  $titleText = $isEdit ? 'Edit Produk: ' . $product['title'] : 'Tambah Produk Baru';
  $actionUrl = $isEdit ? route('admin.products.update', ['title' => $product['title']]) : route('admin.products.store');
@endphp

@section('title', $titleText)
@section('page_title', $titleText)

@section('admin_content')
<div class="card bg-white shadow-sm max-w-4xl mx-auto">
  <div class="card-header">
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-box-seam text-danger me-2"></i>Formulir Data Produk</h5>
  </div>
  
  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
    @csrf

    <div class="row g-3">
      <!-- Title -->
      <div class="col-md-6">
        <label for="title" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $product['title'] ?? '') }}" required placeholder="Contoh: Brewing Specific Media 1">
      </div>

      <!-- Catalog Number -->
      <div class="col-md-6">
        <label for="catalog" class="form-label fw-bold">Nomor Katalog (Catalogue No)</label>
        <input type="text" class="form-control" id="catalog" name="catalog" value="{{ old('catalog', $product['catalog'] ?? '') }}" placeholder="Contoh: 610152">
      </div>

      <!-- Category -->
      <div class="col-md-6">
        <label for="category" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="category" name="category" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="culture-media" {{ old('category', $product['category'] ?? '') === 'culture-media' ? 'selected' : '' }}>Culture Media</option>
          <option value="instruments" {{ old('category', $product['category'] ?? '') === 'instruments' ? 'selected' : '' }}>Instruments</option>
          <option value="chemicals" {{ old('category', $product['category'] ?? '') === 'chemicals' ? 'selected' : '' }}>Chemicals &amp; Reagents</option>
          <option value="consumables" {{ old('category', $product['category'] ?? '') === 'consumables' ? 'selected' : '' }}>Consumables</option>
        </select>
      </div>

      <!-- Sector -->
      <div class="col-md-6">
        <label for="sector" class="form-label fw-bold">Sektor Industri</label>
        <select class="form-select" id="sector" name="sector">
          <option value="">-- Umum / Semua Sektor --</option>
          @foreach($sectors as $sec)
            <option value="{{ $sec['id'] }}" {{ old('sector', $product['sector'] ?? '') === $sec['id'] ? 'selected' : '' }}>{{ $sec['name'] }}</option>
          @endforeach
        </select>
      </div>

      <!-- Image Area -->
      <div class="col-12 mt-4">
        <label class="form-label fw-bold">Gambar Produk</label>
        <div class="row g-3">
          <div class="col-sm-3 text-center">
            <div class="border rounded bg-light p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
              <img id="image-preview" src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="Preview" class="w-100 h-100" style="object-fit: contain;">
            </div>
          </div>
          <div class="col-sm-9">
            <div class="mb-3">
              <label for="image_file" class="form-label small fw-bold">Upload Gambar Baru</label>
              <input class="form-control" type="file" id="image_file" name="image_file" accept="image/*" onchange="previewLocalImage(this)">
            </div>
            <div>
              <label for="image_url" class="form-label small fw-bold">Atau Gunakan URL Gambar Eksternal</label>
              <input type="text" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $product['image'] ?? '') }}" placeholder="https://example.com/image.jpg" oninput="previewUrlImage(this.value)">
            </div>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="col-12 mt-4">
        <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Tulis rincian deskripsi produk, aplikasi, spesifikasi, dll.">{{ old('description', $product['description'] ?? '') }}</textarea>
      </div>
    </div>

    <!-- Buttons -->
    <div class="mt-4 border-top pt-3 d-flex justify-content-between">
      <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Batal</a>
      <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i> Simpan Data</button>
    </div>
  </form>
</div>
@endsection

@section('admin_styles')
  <!-- Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  <style>
    /* Styling Summernote container alignment inside premium card */
    .note-editor.note-frame {
      border: 1px solid var(--admin-border) !important;
      border-radius: 0.5rem !important;
      overflow: hidden;
    }
  </style>
@endsection

@section('admin_scripts')
  <!-- jQuery (Required by Summernote) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <!-- Summernote JS -->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <script>
    function previewLocalImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('image-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    
    function previewUrlImage(url) {
      if (url.trim() !== '') {
        document.getElementById('image-preview').src = url;
      }
    }

    // Initialize Summernote for product description
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: 'Tulis rincian deskripsi produk, aplikasi, spesifikasi detail, tabel pendukung, dll...',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    });
  </script>
@endsection
