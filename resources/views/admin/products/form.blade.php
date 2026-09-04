@extends('admin.layout')

@php
  $isEdit = isset($product) && !empty($product['id']);
  $titleText = $isEdit ? 'Edit Produk' : 'Tambah Produk';
  $actionUrl = $isEdit
    ? route('admin.products.update', ['id' => $product['id']])
    : route('admin.products.store');
@endphp

@section('title', $isEdit ? 'Edit Produk: ' . ($product['title'] ?? '') : 'Tambah Produk Baru')
@section('page_title', $titleText)

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Katalog</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit: <strong style="color: var(--color-text-main);">{{ $product['title'] ?? '' }}</strong>
      @else
        Tambah produk baru ke katalog publik.
      @endif
    </p>
  </div>
  <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="admin-card" style="max-width: 900px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Produk</h3>
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

      <div class="row g-3">
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="title" class="admin-form-label">Nama Produk <span style="color: var(--color-accent);">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $product['title'] ?? '') }}" required placeholder="Contoh: Brewing Specific Media 1" autofocus>
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="catalog" class="admin-form-label">Nomor Katalog</label>
            <input type="text" class="form-control" id="catalog" name="catalog" value="{{ old('catalog', $product['catalog'] ?? '') }}" placeholder="Contoh: 610152">
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="price" class="admin-form-label">Harga Produk (Rp)</label>
            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price', $product['price'] ?? 0) }}" placeholder="Contoh: 1500000">
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="stock" class="admin-form-label">Stok (Unit)</label>
            <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock', $product['stock'] ?? 0) }}" placeholder="Contoh: 50">
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="admin-category-select" class="admin-form-label">Kategori <span style="color: var(--color-accent);">*</span></label>
            <select class="form-select" id="admin-category-select" name="category" required
                    data-api-url="{{ route('admin.api.subcategories') }}">
              <option value="">-- Pilih Kategori --</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->key }}"
                        data-id="{{ $cat->id }}"
                        {{ old('category', $product['category'] ?? '') === $cat->key ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
            <p class="form-text mb-0 mt-2">
              <a href="{{ route('admin.categories.index') }}" target="_blank" style="color: var(--color-text-muted);">
                <i class="bi bi-diagram-3 me-1"></i>Kelola kategori
              </a>
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="principal_id" class="admin-form-label">Prinsipal / Manufaktur Asal</label>
            <select class="form-select" id="principal_id" name="principal_id">
              <option value="">-- Tanpa Prinsipal Khusus --</option>
              @foreach($principals as $pr)
                <option value="{{ $pr->id }}" {{ (string) old('principal_id', $product['principal_id'] ?? '') === (string) $pr->id ? 'selected' : '' }}>
                  {{ $pr->name }} @if(!empty($pr->address)) ({{ $pr->address }}) @endif
                </option>
              @endforeach
            </select>
            <p class="form-text mb-0 mt-2">
              <a href="{{ route('admin.principals.index') }}" target="_blank" style="color: var(--color-text-muted);">
                <i class="bi bi-building me-1"></i>Kelola data prinsipal
              </a>
            </p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label for="sector" class="admin-form-label">Sektor Industri</label>
            <select class="form-select" id="sector" name="sector">
              <option value="">-- Umum / Semua Sektor --</option>
              @foreach($sectors as $sec)
                <option value="{{ $sec['id'] }}" {{ old('sector', $product['sector'] ?? '') === $sec['id'] ? 'selected' : '' }}>{{ $sec['name'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div id="sub-category-block" style="display: none;">
        <div class="admin-form-group mb-0 p-3" style="border: 2px solid var(--color-border); border-radius: 6px; background: var(--color-surface-2, #EDE8E0);">
          <label for="admin-subcategory-select" class="admin-form-label">
            <i class="bi bi-diagram-3 me-1" style="color: var(--color-accent);"></i>Subkategori <span style="color: var(--color-accent);">*</span>
          </label>
          <select class="form-select" id="admin-subcategory-select" name="sub_category"
                  data-saved="{{ old('sub_category', $product['sub_category'] ?? '') }}">
            <option value="">-- Pilih Subkategori --</option>
          </select>
          <p class="form-text mb-0 mt-2">Sesuaikan subkategori berdasarkan kategori yang dipilih.</p>
        </div>
      </div>

      <div class="admin-form-group mb-0 pt-3" style="border-top: 1px solid var(--color-border);">
        <label class="admin-form-label">Gambar Utama / Cover</label>
        <p class="form-text mb-3">Thumbnail katalog, kartu, dan PDF penawaran.</p>
        <div class="row g-3 align-items-center">
          <div class="col-sm-3 text-center">
            <div style="width: 120px; height: 120px; margin: 0 auto; border: 2px solid var(--color-border); border-radius: 6px; background: var(--color-surface-2, #EDE8E0); display: flex; align-items: center; justify-content: center; overflow: hidden;">
              <img id="image-preview" src="{{ $product['image'] ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80' }}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
          </div>
          <div class="col-sm-9">
            <div class="mb-3">
              <label for="image_file" class="admin-form-label">Upload Gambar Baru</label>
              <input class="form-control" type="file" id="image_file" name="image_file" accept="image/*" onchange="previewLocalImage(this)">
            </div>
            <div>
              <label for="image_url" class="admin-form-label">Atau URL Gambar</label>
              <input type="text" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $product['image'] ?? '') }}" placeholder="https://example.com/image.jpg" oninput="previewUrlImage(this.value)">
            </div>
          </div>
        </div>
      </div>

      <div class="admin-form-group mb-0">
        <label class="admin-form-label">Galeri Foto Tambahan</label>
        <p class="form-text mb-3">Maks. 10 foto (di luar cover).</p>

        @if(!empty($product['gallery_images']))
          <div class="row g-2 mb-3">
            @foreach($product['gallery_images'] as $galleryPath)
              <div class="col-4 col-sm-2">
                <div class="position-relative" style="aspect-ratio: 1/1; overflow: hidden; border: 2px solid var(--color-border); border-radius: 6px; background: var(--color-surface-2, #EDE8E0);">
                  <img src="{{ $galleryPath }}" alt="Galeri" style="width: 100%; height: 100%; object-fit: cover;">
                  <label class="position-absolute top-0 end-0 m-1 d-flex align-items-center gap-1" style="cursor: pointer; font-size: 0.7rem; background: var(--color-border, #1E1E1E); color: #FFFFFF; border-radius: 4px; padding: 2px 6px;" title="Hapus foto ini">
                    <input type="checkbox" name="remove_gallery[]" value="{{ $galleryPath }}" class="form-check-input m-0" style="width: 0.9rem; height: 0.9rem;">
                    <i class="bi bi-trash" style="color: #FFFFFF;"></i>
                  </label>
                </div>
              </div>
            @endforeach
          </div>
          <p class="form-text mb-2" style="color: var(--color-accent, #A6171C); font-weight: 600;">Centang foto yang ingin dihapus, lalu simpan.</p>
        @endif

        <input class="form-control" type="file" id="gallery_files" name="gallery_files[]" accept="image/*" multiple>
      </div>

      <div class="admin-form-group mb-0">
        <label for="description" class="admin-form-label">Deskripsi Produk</label>
        <textarea class="form-control" id="description" name="description" rows="6" placeholder="Deskripsi, aplikasi, spesifikasi...">{{ old('description', $product['description'] ?? '') }}</textarea>
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> Simpan Data
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_styles')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('admin_scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
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

    var categorySelect    = document.getElementById('admin-category-select');
    var subcategorySelect = document.getElementById('admin-subcategory-select');
    var block             = document.getElementById('sub-category-block');
    var apiUrl            = categorySelect ? categorySelect.getAttribute('data-api-url') : '';

    function updateSubCategories(selectedOption) {
      if (!subcategorySelect || !block) return;

      var categoryId  = selectedOption ? selectedOption.getAttribute('data-id') : null;
      var savedSubKey = subcategorySelect.getAttribute('data-saved') || '';

      subcategorySelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';

      if (!categoryId) {
        block.style.display   = 'none';
        subcategorySelect.required = false;
        return;
      }

      block.style.display = 'block';
      subcategorySelect.disabled = true;
      subcategorySelect.innerHTML = '<option value="">Memuat sub-kategori...</option>';

      fetch(apiUrl + '?parent_id=' + encodeURIComponent(categoryId))
        .then(function(r) { return r.json(); })
        .then(function(subs) {
          subcategorySelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';

          if (subs.length === 0) {
            block.style.display = 'none';
            subcategorySelect.required = false;
          } else {
            subs.forEach(function(sub) {
              var opt = document.createElement('option');
              opt.value       = sub.key;
              opt.textContent = sub.name;
              if (savedSubKey === sub.key) opt.selected = true;
              subcategorySelect.appendChild(opt);
            });
            block.style.display        = 'block';
            subcategorySelect.required = true;
          }
          subcategorySelect.disabled = false;
        })
        .catch(function() {
          subcategorySelect.innerHTML = '<option value="">Gagal memuat sub-kategori</option>';
          subcategorySelect.disabled  = false;
          block.style.display = 'none';
        });
    }

    if (categorySelect) {
      categorySelect.addEventListener('change', function() {
        subcategorySelect.removeAttribute('data-saved');
        updateSubCategories(this.options[this.selectedIndex]);
      });

      if (categorySelect.value) {
        updateSubCategories(categorySelect.options[categorySelect.selectedIndex]);
      }
    }

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
