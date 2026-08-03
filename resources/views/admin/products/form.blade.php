@extends('admin.layout')

@php 
  $isEdit = isset($product) && !empty($product['id']);
  $titleText = $isEdit ? 'Edit Produk: ' . ($product['title'] ?? '') : 'Tambah Produk Baru';
  $actionUrl = $isEdit 
    ? route('admin.products.update', ['id' => $product['id']]) 
    : route('admin.products.store');
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

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-3">
      <!-- Nama Produk -->
      <div class="col-md-6">
        <label for="title" class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $product['title'] ?? '') }}" required placeholder="Contoh: Brewing Specific Media 1">
      </div>

      <!-- Nomor Katalog -->
      <div class="col-md-6">
        <label for="catalog" class="form-label fw-bold">Nomor Katalog (Catalogue No)</label>
        <input type="text" class="form-control" id="catalog" name="catalog" value="{{ old('catalog', $product['catalog'] ?? '') }}" placeholder="Contoh: 610152">
      </div>

      <!-- Harga Produk (Rp) -->
      <div class="col-md-6">
        <label for="price" class="form-label fw-bold">Harga Produk (Rp)</label>
        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $product['price'] ?? 0) }}" placeholder="Contoh: 1500000">
      </div>

      <!-- Stok Produk -->
      <div class="col-md-6">
        <label for="stock" class="form-label fw-bold">Stok Produk (Unit)</label>
        <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', $product['stock'] ?? 0) }}" placeholder="Contoh: 50">
      </div>

      <!-- Kategori Utama -->
      <div class="col-md-6">
        <label for="category" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="admin-category-select" name="category" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="microbiology" {{ old('category', $product['category'] ?? '') === 'microbiology' ? 'selected' : '' }}>Microbiology</option>
          <option value="reference-standards" {{ old('category', $product['category'] ?? '') === 'reference-standards' ? 'selected' : '' }}>Reference Standards</option>
          <option value="device" {{ old('category', $product['category'] ?? '') === 'device' ? 'selected' : '' }}>Device</option>
          <option value="instruments" {{ old('category', $product['category'] ?? '') === 'instruments' ? 'selected' : '' }}>Instruments</option>
        </select>
      </div>

      <!-- Sektor Industri -->
      <div class="col-md-6">
        <label for="sector" class="form-label fw-bold">Sektor Industri</label>
        <select class="form-select" id="sector" name="sector">
          <option value="">-- Umum / Semua Sektor --</option>
          @foreach($sectors as $sec)
            <option value="{{ $sec['id'] }}" {{ old('sector', $product['sector'] ?? '') === $sec['id'] ? 'selected' : '' }}>{{ $sec['name'] }}</option>
          @endforeach
        </select>
      </div>

      <!-- Subkategori (Dependent Dropdown) -->
      <div class="col-12 my-3" id="sub-category-block" style="display: none;">
        <div class="p-3 rounded bg-light border">
          <label for="sub_category" class="form-label fw-bold text-dark">
            <i class="bi bi-diagram-3 text-primary me-2"></i>Subkategori Pilihan <span class="text-danger">*</span>
          </label>
          <select class="form-select bg-white" id="admin-subcategory-select" name="sub_category" data-saved="{{ old('sub_category', $product['sub_category'] ?? '') }}">
            <option value="">-- Pilih Subkategori --</option>
          </select>
          <div class="form-text small text-muted mt-1">Sesuaikan subkategori berdasarkan rumpun produk yang Anda pilih di atas.</div>
        </div>
      </div>

      <!-- Gambar Produk -->
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

      <!-- Deskripsi -->
      <div class="col-12 mt-4">
        <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
        <textarea class="form-control" id="description" name="description" rows="6" placeholder="Tulis rincian deskripsi produk, aplikasi, spesifikasi detail, tabel pendukung, dll...">{{ old('description', $product['description'] ?? '') }}</textarea>
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
  <!-- Summernote Lite Original CDN CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  @vite(['resources/css/summernote-dark.css'])
  <style>
    /* ── File Input (Browse button) Dark Mode ───────────────────────────────── */
    input[type="file"].form-control {
      color: rgba(255,255,255,0.75) !important;
      background-color: var(--color-surface) !important;
      border: 1px solid var(--color-border) !important;
      padding: 0 !important;
      overflow: hidden;
    }
    input[type="file"].form-control::file-selector-button {
      background-color: #2a2a2e !important;
      color: rgba(255,255,255,0.85) !important;
      border: none !important;
      border-right: 1px solid var(--color-border) !important;
      padding: 0.375rem 0.85rem !important;
      margin-right: 0.75rem !important;
      cursor: pointer;
      transition: background 0.2s ease;
      font-family: var(--font-body);
      font-size: 0.875rem;
    }
    input[type="file"].form-control::file-selector-button:hover {
      background-color: rgba(255,73,80,0.15) !important;
      color: var(--color-accent) !important;
    }
  </style>
@endsection

@section('admin_scripts')
  <!-- jQuery & Summernote Lite JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <script>
    // 1. Image Preview Helpers
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

    // 2. Dropdown dynamic mapping logic
    var subCategoriesMap = {
      'microbiology': {
        'food-safety': 'Food Safety',
        'antimicrobial': 'Antimicrobial Susceptibility Testing',
        'identification': 'Microbiological Identification',
        'preservation': 'Microorganisms Preservation System (BactoBank)',
        'staining': 'Microbial Staining & Fixatives',
        'consumables': 'Consumables',
        'mic-test': 'MIC Test Strip',
        'qc-organisms': 'QC Organisms',
        'dip-slide': 'Dip slide',
        'chemical-indicator': 'Chemical Indicator',
        'latex-agglutination': 'Latex Agglutination Kits',
        'ready-culture': 'Ready To Use Culture Media',
        'biological-indicators': 'Biological Indicators',
        'dehydrated-culture': 'Dehydrated Culture Media',
        'immunology': 'Immunology',
        'endotoxin': 'Endotoxin'
      },
      'reference-standards': {
        'pharmaceutical': 'Pharmaceutical Reference Standards',
        'green-standards': 'Green Standards',
        'environmental': 'Environmental Standards',
        'food-beverages': 'Food and Beverages Standards',
        'agro-chemical': 'Agro Chemical Standards'
      },
      'device': {
        'bsc-lfc': 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)',
        'microbiological-instruments': 'Microbiological Instruments',
        'liquid-handling': 'Liquid Handling',
        'thermometer': 'Thermometer'
      },
      'instruments': {
        'liofilchem-giotto-2': 'Liofilchem Giotto 2',
        'agar-filler': 'Agar Filler',
        'agar-preparator': 'Agar Preparator',
        'kinetic-incubating-reader': 'Kinetic Incubating Microplate Reader',
        'mica-diamidex': 'MICA Diamidex - Counting Microorganisms Faster'
      }
    };

    var categorySelect = document.getElementById('admin-category-select');
    var subcategorySelect = document.getElementById('admin-subcategory-select');
    var block = document.getElementById('sub-category-block');

    function updateSubCategories(categoryVal) {
      if (!subcategorySelect || !block) return;

      var savedSubCategory = subcategorySelect.getAttribute('data-saved') || '';
      subcategorySelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';

      if (categoryVal && subCategoriesMap[categoryVal]) {
        var subs = subCategoriesMap[categoryVal];
        
        Object.keys(subs).forEach(function(key) {
          var option = document.createElement('option');
          option.value = key;
          option.textContent = subs[key];
          if (savedSubCategory === key) {
            option.selected = true;
          }
          subcategorySelect.appendChild(option);
        });

        block.style.display = 'block';
        subcategorySelect.required = true;
      } else {
        block.style.display = 'none';
        subcategorySelect.required = false;
        subcategorySelect.value = '';
      }
    }

    if (categorySelect) {
      categorySelect.addEventListener('change', function() {
        updateSubCategories(this.value);
      });
      if (categorySelect.value) {
        updateSubCategories(categorySelect.value);
      }
    }

    // 3. Initialize Summernote
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: 'Tulis rincian deskripsi produk, aplikasi, spesifikasi detail, tabel pendukung, dll...',
        tabsize: 2,
        height: 280,
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