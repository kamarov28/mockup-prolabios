@extends('admin.layout')

@php 
  $isEdit = isset($product) && !empty($product['title']);
  $titleText = $isEdit ? 'Edit Produk: ' . $product['title'] : 'Tambah Produk Baru';
  $actionUrl = $isEdit 
    ? route('admin.products.update', ['title' => $product['title']]) 
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
    /* ── Summernote Dark Mode ────────────────────────────────────────────────── */
    .note-editor.note-frame {
      border: 1px solid var(--color-border) !important;
      border-radius: 0.5rem !important;
      overflow: hidden;
      background-color: var(--color-surface) !important;
    }

    /* Toolbar */
    .note-editor .note-toolbar {
      background-color: var(--color-surface-2) !important;
      border-bottom: 1px solid var(--color-border) !important;
      padding: 6px 8px !important;
    }

    /* Toolbar buttons */
    .note-editor .note-toolbar .note-btn {
      background-color: transparent !important;
      border: 1px solid transparent !important;
      color: rgba(255,255,255,0.75) !important;
      transition: background 0.15s, color 0.15s;
    }
    .note-editor .note-toolbar .note-btn:hover,
    .note-editor .note-toolbar .note-btn:focus {
      background-color: rgba(255,255,255,0.08) !important;
      border-color: var(--color-border) !important;
      color: #fff !important;
    }
    .note-editor .note-toolbar .note-btn.active {
      background-color: rgba(255,73,80,0.2) !important;
      border-color: var(--color-accent) !important;
      color: var(--color-accent) !important;
    }

    /* Dropdown menus */
    .note-editor .dropdown-menu,
    .note-editor .note-dropdown-menu {
      background-color: var(--color-surface-2) !important;
      border: 1px solid var(--color-border) !important;
      box-shadow: 0 8px 24px rgba(0,0,0,0.5) !important;
    }
    .note-editor .dropdown-item,
    .note-editor .note-dropdown-item {
      color: rgba(255,255,255,0.8) !important;
    }
    .note-editor .dropdown-item:hover,
    .note-editor .note-dropdown-item:hover {
      background-color: rgba(255,255,255,0.07) !important;
      color: #fff !important;
    }

    /* Editing area */
    .note-editor .note-editable {
      background-color: var(--color-surface) !important;
      color: rgba(255,255,255,0.9) !important;
      caret-color: #fff;
    }
    .note-editor .note-editable[data-placeholder]:empty:before {
      color: rgba(255,255,255,0.3) !important;
    }

    /* Status bar */
    .note-editor .note-statusbar {
      background-color: var(--color-surface-2) !important;
      border-top: 1px solid var(--color-border) !important;
    }
    .note-editor .note-statusbar .note-resizebar .note-icon-bar {
      border-top-color: rgba(255,255,255,0.2) !important;
    }

    /* ── File Input (Browse button) Dark Mode ───────────────────────────────── */
    /* Hide the native file input and replace with custom styled button */
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
  <!-- jQuery & Summernote JS -->
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

    // Category and Subcategory selectors
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
      // Initial check if page loaded with a category selected
      if (categorySelect.value) {
        updateSubCategories(categorySelect.value);
      }
    }

    // 3. Summernote Rich Editor Init
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