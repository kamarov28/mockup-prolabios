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
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" inputmode="numeric" class="form-control" id="price" name="price" value="{{ old('price') !== null ? number_format((float) str_replace(['.', ' '], '', old('price')), 0, ',', '.') : (!empty($product['price']) ? number_format((float) $product['price'], 0, ',', '.') : '') }}" placeholder="Contoh: 1.500.000">
            </div>
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
        <div class="col-md-6">
          <div class="admin-form-group mb-0">
            <label class="admin-form-label">Dokumen Spesifikasi Teknis (PDF)</label>
            <div class="p-3" style="border: 2px solid var(--color-border); border-radius: 6px; background: var(--color-surface-2, #EDE8E0);">
              <div class="mb-2">
                <label for="datasheet_file" class="form-label small mb-1" style="font-weight: 600; color: var(--color-text);">
                  <i class="bi bi-upload me-1"></i>Upload File PDF (Maks. 10MB)
                </label>
                <input class="form-control" type="file" id="datasheet_file" name="datasheet_file" accept=".pdf,application/pdf">
              </div>
              <div class="mb-2">
                <label for="datasheet_url" class="form-label small mb-1" style="font-weight: 600; color: var(--color-text);">
                  <i class="bi bi-link-45deg me-1"></i>Atau Masukkan URL PDF Eksternal
                </label>
                <input type="text" class="form-control" id="datasheet_url" name="datasheet_url" value="{{ old('datasheet_url', $product['datasheet_url'] ?? '') }}" placeholder="https://principal.com/datasheet.pdf">
              </div>
              @if(!empty($product['datasheet_url']))
                <div class="d-flex align-items-center gap-2 pt-2 border-top" style="border-color: rgba(30,30,30,0.1) !important;">
                  <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                  <a href="{{ $product['datasheet_url'] }}" target="_blank" rel="noopener noreferrer" class="small fw-bold text-decoration-underline" style="color: var(--color-accent, #A6171C);">
                    Lihat Dokumen Terpasang <i class="bi bi-box-arrow-up-right ms-1"></i>
                  </a>
                </div>
              @endif
            </div>
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
        <div class="d-flex justify-content-between align-items-center mb-1">
          <label class="admin-form-label mb-0">Galeri Foto Tambahan</label>
          <span id="gallery_count_badge" class="badge" style="background: var(--color-surface-2, #EDE8E0); color: var(--color-text-main); border: 1.5px solid #1E1E1E; display: none; font-size: 0.75rem;">0 foto dipilih</span>
        </div>
        <p class="form-text mb-3">Maksimal 10 foto (di luar cover utama). Bisa pilih banyak foto sekaligus atau tambah satu per satu.</p>

        @if(!empty($product['gallery_images']))
          <div class="mb-3 p-3" style="border: 2px solid var(--color-border); border-radius: 6px; background: var(--color-surface-2, #EDE8E0);">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small fw-bold" style="color: var(--color-text-main);">
                <i class="bi bi-images me-1"></i>Foto Galeri Tersimpan ({{ count($product['gallery_images']) }})
              </span>
              <span class="small text-muted">Centang untuk menghapus foto saat disimpan</span>
            </div>
            <div class="row g-2">
              @foreach($product['gallery_images'] as $galleryPath)
                <div class="col-4 col-sm-3 col-md-2">
                  <div class="position-relative" style="aspect-ratio: 1/1; overflow: hidden; border: 2px solid var(--color-border); border-radius: 6px; background: #FFFFFF; box-shadow: 2px 2px 0 #1E1E1E;">
                    <img src="{{ $galleryPath }}" alt="Galeri" style="width: 100%; height: 100%; object-fit: cover;">
                    <label class="position-absolute top-0 end-0 m-1 d-flex align-items-center gap-1" style="cursor: pointer; font-size: 0.7rem; background: var(--color-border, #1E1E1E); color: #FFFFFF; border-radius: 4px; padding: 2px 6px;" title="Hapus foto ini">
                      <input type="checkbox" name="remove_gallery[]" value="{{ $galleryPath }}" class="form-check-input m-0" style="width: 0.9rem; height: 0.9rem;">
                      <i class="bi bi-trash" style="color: #FFFFFF;"></i>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Dropzone & Multi-file picker --}}
        <div id="gallery_dropzone" class="p-4 text-center mb-3" style="border: 2px dashed #1E1E1E; border-radius: 6px; background: #FAF8F5; cursor: pointer; transition: background 0.15s ease;">
          <i class="bi bi-cloud-arrow-up fs-2 d-block mb-1" style="color: var(--color-accent, #A6171C);"></i>
          <span class="fw-bold d-block" style="color: var(--color-text-main); font-size: 0.95rem;">
            + Klik di sini untuk menambah foto galeri
          </span>
          <span class="small text-muted d-block mt-1">
            Dapat memilih beberapa file sekaligus (Ctrl/Shift) atau menambah foto satu per satu (Maks. 5MB per file, format JPG, PNG, WEBP).
          </span>
        </div>

        {{-- Previews of newly selected files --}}
        <div id="gallery_previews_wrapper" class="mb-3" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small fw-bold" style="color: var(--color-text-main);">
              <i class="bi bi-check2-circle text-success me-1"></i>Foto Baru Siap Diunggah:
            </span>
            <button type="button" id="btn_add_more_photos" class="admin-btn admin-btn-outline py-1 px-2" style="font-size: 0.78rem;">
              <i class="bi bi-plus-lg me-1"></i>Tambah Foto Lainnya
            </button>
          </div>
          <div id="gallery_previews" class="row g-2"></div>
        </div>

        {{-- Synced hidden file input holding all staged files for form submission --}}
        <input type="file" id="gallery_files" name="gallery_files[]" accept="image/*" multiple style="display: none;">
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

    // Format ribuan otomatis (titik) untuk input harga
    var priceInput = document.getElementById('price');
    if (priceInput) {
      function formatRupiahInput(val) {
        var clean = val.replace(/\D/g, '');
        return clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
      }

      priceInput.addEventListener('input', function() {
        var cursor = this.selectionStart;
        var prevLen = this.value.length;
        this.value = formatRupiahInput(this.value);
        var diff = this.value.length - prevLen;
        this.setSelectionRange(cursor + diff, cursor + diff);
      });
    }

    // Multi-photo gallery uploader with cumulative selection & instant previews
    (function() {
      var galleryInput = document.getElementById('gallery_files');
      var dropZone = document.getElementById('gallery_dropzone');
      var wrapper = document.getElementById('gallery_previews_wrapper');
      var previewContainer = document.getElementById('gallery_previews');
      var addMoreBtn = document.getElementById('btn_add_more_photos');
      var countBadge = document.getElementById('gallery_count_badge');
      if (!galleryInput || !previewContainer) return;

      var dt = new DataTransfer();
      var maxAllowed = 10;
      var existingCount = {{ !empty($product['gallery_images']) ? count($product['gallery_images']) : 0 }};

      function updateUI() {
        var totalStaged = dt.files.length;
        var totalCombined = existingCount + totalStaged;

        if (countBadge) {
          countBadge.textContent = totalStaged + ' foto baru dipilih (Total: ' + totalCombined + '/' + maxAllowed + ')';
          countBadge.style.display = totalStaged > 0 ? 'inline-block' : 'none';
        }

        if (addMoreBtn) {
          addMoreBtn.style.display = (totalCombined < maxAllowed) ? 'inline-flex' : 'none';
        }

        previewContainer.innerHTML = '';
        if (totalStaged === 0) {
          if (wrapper) wrapper.style.display = 'none';
          return;
        }

        if (wrapper) wrapper.style.display = 'block';

        Array.from(dt.files).forEach(function(file, index) {
          var col = document.createElement('div');
          col.className = 'col-4 col-sm-3 col-md-2';

          var card = document.createElement('div');
          card.className = 'position-relative';
          card.style.cssText = 'aspect-ratio: 1/1; overflow: hidden; border: 2px solid #1E1E1E; border-radius: 6px; background: #FFFFFF; box-shadow: 2px 2px 0 #1E1E1E;';

          var img = document.createElement('img');
          img.src = URL.createObjectURL(file);
          img.alt = file.name;
          img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';

          var delBtn = document.createElement('button');
          delBtn.type = 'button';
          delBtn.className = 'btn btn-sm position-absolute top-0 end-0 m-1 d-flex align-items-center justify-content-center';
          delBtn.style.cssText = 'width: 22px; height: 22px; padding: 0; background: #A6171C; color: #FFFFFF; border: 1.5px solid #1E1E1E; border-radius: 4px;';
          delBtn.title = 'Hapus dari daftar unggah';
          delBtn.innerHTML = '<i class="bi bi-x-lg" style="font-size: 0.65rem;"></i>';
          delBtn.onclick = function() {
            removeStagedPhoto(index);
          };

          var sizeBadge = document.createElement('div');
          sizeBadge.className = 'position-absolute bottom-0 start-0 end-0 p-1 text-truncate';
          sizeBadge.style.cssText = 'background: rgba(30,30,30,0.75); color: #fff; font-size: 0.65rem; font-family: var(--font-mono); font-weight: 600;';
          sizeBadge.textContent = file.size > 1048576 ? (file.size / 1048576).toFixed(1) + 'MB' : Math.round(file.size / 1024) + 'KB';

          card.appendChild(img);
          card.appendChild(delBtn);
          card.appendChild(sizeBadge);
          col.appendChild(card);
          previewContainer.appendChild(col);
        });
      }

      function removeStagedPhoto(index) {
        var newDt = new DataTransfer();
        Array.from(dt.files).forEach(function(f, i) {
          if (i !== index) newDt.items.add(f);
        });
        dt = newDt;
        galleryInput.files = dt.files;
        updateUI();
      }

      function handleFiles(files) {
        var currentCombined = existingCount + dt.files.length;
        var availableSlots = maxAllowed - currentCombined;
        if (availableSlots <= 0) {
          alert('Maksimal ' + maxAllowed + ' foto galeri tercapai.');
          return;
        }

        var toAdd = Array.from(files).slice(0, availableSlots);
        toAdd.forEach(function(file) {
          if (file.type.startsWith('image/')) {
            dt.items.add(file);
          }
        });

        galleryInput.files = dt.files;
        updateUI();
      }

      var tempPicker = document.createElement('input');
      tempPicker.type = 'file';
      tempPicker.accept = 'image/*';
      tempPicker.multiple = true;
      tempPicker.style.display = 'none';
      document.body.appendChild(tempPicker);

      tempPicker.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
          handleFiles(this.files);
          this.value = '';
        }
      });

      if (dropZone) {
        dropZone.addEventListener('click', function() {
          tempPicker.click();
        });
        dropZone.addEventListener('dragover', function(e) {
          e.preventDefault();
          this.style.background = '#EDE8E0';
        });
        dropZone.addEventListener('dragleave', function() {
          this.style.background = '#FAF8F5';
        });
        dropZone.addEventListener('drop', function(e) {
          e.preventDefault();
          this.style.background = '#FAF8F5';
          if (e.dataTransfer && e.dataTransfer.files) {
            handleFiles(e.dataTransfer.files);
          }
        });
      }

      if (addMoreBtn) {
        addMoreBtn.addEventListener('click', function() {
          tempPicker.click();
        });
      }
    })();

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
