@extends('admin.layout')

@section('title', 'Tambah Banyak Produk sekaligus')
@section('page_title', 'Input Massal Katalog Produk')

@section('admin_content')
<div class="max-w-4xl mx-auto mb-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="h4 mb-0 fw-bold text-dark"><i class="bi bi-grid-3x3-gap text-danger me-2"></i>Input Massal Produk (Multi-Form)</h2>
    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
  </div>

  <div class="alert alert-info small mb-4 shadow-sm bg-white border-start border-primary border-3">
    <div class="d-flex gap-2">
      <i class="bi bi-info-circle-fill text-primary fs-5"></i>
      <div>
        <h6 class="fw-bold mb-1 text-dark">Petunjuk Pengisian Massal:</h6>
        <p class="mb-0 text-secondary">Setiap kartu di bawah mewakili satu data produk. Pilih kategori utama terlebih dahulu untuk memunculkan pilihan subkategori yang sesuai. Pastikan kolom bertanda <span class="text-danger">*</span> wajib diisi.</p>
      </div>
    </div>
  </div>

  <form action="{{ route('admin.products.store-bulk') }}" method="POST" enctype="multipart/form-data" id="bulk-form">
    @csrf
    
    <!-- Forms Container -->
    <div id="bulk-cards-container" class="d-flex flex-column gap-4">
      <!-- Dynamic product cards will be appended here -->
    </div>

    <!-- Add Form Button -->
    <div class="text-center my-4">
      <button type="button" class="btn btn-outline-danger px-4 py-2 fw-semibold" onclick="addNewProductCard()">
        <i class="bi bi-plus-circle me-1"></i> Tambah Formulir Produk Baru
      </button>
    </div>

    <!-- Final Submission Buttons -->
    <div class="card bg-white shadow-sm border-top p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
      <span class="text-muted small" id="total-forms-badge">Jumlah: 0 formulir produk</span>
      <div class="d-inline-flex gap-2">
        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-success px-4 fw-semibold"><i class="bi bi-check-lg me-1"></i> Simpan Semua Produk</button>
      </div>
    </div>
  </form>
</div>

<!-- Card Template (Hidden) -->
<div class="d-none">
  <div id="card-template">
    <div class="card bg-white shadow-sm border-0 bulk-product-card mb-4">
      <div class="card-header bg-light d-flex align-items-center justify-content-between py-3 border-bottom">
        <h5 class="mb-0 fw-bold text-dark card-index-title">
          <i class="bi bi-box-seam text-danger me-2"></i>Data Produk #<span class="index-num">1</span>
        </h5>
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-card" onclick="removeProductCard(this)">
          <i class="bi bi-trash me-1"></i>Hapus Formulir Ini
        </button>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <!-- Title -->
          <div class="col-md-6">
            <label class="form-label small fw-bold text-secondary">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" name="title[__INDEX__]" class="form-control" placeholder="Contoh: Brewing Specific Media 1" required>
          </div>

          <!-- Catalog Number -->
          <div class="col-md-6">
            <label class="form-label small fw-bold text-secondary">Nomor Katalog (Catalogue No)</label>
            <input type="text" name="catalog[__INDEX__]" class="form-control" placeholder="Contoh: 610152">
          </div>

          <!-- Category -->
          <div class="col-md-6">
            <label class="form-label small fw-bold text-secondary">Kategori <span class="text-danger">*</span></label>
            <select name="category[__INDEX__]" class="form-select text-capitalize bulk-category-select" data-id="__INDEX__" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($categoriesStructure as $catKey => $catData)
                <option value="{{ $catKey }}">{{ $catData['name'] ?? $catKey }}</option>
              @endforeach
            </select>
          </div>

          <!-- Sub-category (Dependent Dropdown Container) -->
          <div class="col-md-6" id="sub-wrapper-__INDEX__" style="display: none;">
            <label class="form-label small fw-bold text-secondary">Subkategori <span class="text-danger">*</span></label>
            <select name="sub_category[__INDEX__]" id="bulk-subcategory-select-__INDEX__" class="form-select">
              <option value="">-- Pilih Subkategori --</option>
            </select>
          </div>

          <!-- Sector -->
          <div class="col-md-6">
            <label class="form-label small fw-bold text-secondary">Sektor Industri</label>
            <select name="sector[__INDEX__]" class="form-select text-capitalize">
              <option value="">-- Umum / Semua Sektor --</option>
              @foreach($sectors as $sec)
                <option value="{{ $sec['id'] }}">{{ $sec['name'] }}</option>
              @endforeach
            </select>
          </div>

          <!-- Image Area (File + URL Option) -->
          <div class="col-12 mt-3">
            <label class="form-label small fw-bold text-secondary">Gambar Produk</label>
            <div class="row g-3 align-items-center">
              <div class="col-sm-2 text-center">
                <div class="border rounded bg-light p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                  <img id="preview-img-__INDEX__" src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80" alt="Preview" class="w-100 h-100" style="object-fit: contain;">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="form-label small text-muted mb-1">Upload File Gambar</label>
                <input class="form-control form-control-sm" type="file" name="image_file[__INDEX__]" accept="image/*" onchange="previewLocalImage(this, 'preview-img-__INDEX__')">
              </div>
              <div class="col-sm-5">
                <label class="form-label small text-muted mb-1">Atau Gunakan URL Gambar</label>
                <input type="text" name="image_url[__INDEX__]" class="form-control form-control-sm" placeholder="https://..." oninput="previewUrlImage(this.value, 'preview-img-__INDEX__')">
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="col-12 mt-3">
            <label class="form-label small fw-bold text-secondary">Deskripsi / Aplikasi Produk</label>
            <textarea name="description[__INDEX__]" class="form-control" rows="3" placeholder="Tulis rincian deskripsi produk, aplikasi, spesifikasi, dll..."></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Peta data subkategori global (dari database)
  const subCategoriesMap = @json(collect($categoriesStructure)->mapWithKeys(fn($item, $key) => [$key => $item['subs'] ?? []]));

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      for (let i = 0; i < 2; i++) {
        addNewProductCard();
      }
    });
  } else {
    for (let i = 0; i < 2; i++) {
      addNewProductCard();
    }
  }

  function addNewProductCard() {
    const uniqueId = 'prod_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    let template = document.getElementById('card-template').innerHTML;
    template = template.replaceAll('__INDEX__', uniqueId);
    
    const container = document.getElementById('bulk-cards-container');
    const div = document.createElement('div');
    div.innerHTML = template;
    
    const newCard = div.firstElementChild;
    container.appendChild(newCard);

    // Pasang event listener dependent dropdown khusus untuk select category di baris ini
    const categorySelect = newCard.querySelector('.bulk-category-select');
    categorySelect.addEventListener('change', function() {
      const currentId = this.getAttribute('data-id');
      const subSelect = document.getElementById(`bulk-subcategory-select-${currentId}`);
      const wrapper = document.getElementById(`sub-wrapper-${currentId}`);
      const val = this.value;

      subSelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';

      if (val && subCategoriesMap[val] && Object.keys(subCategoriesMap[val]).length > 0) {
        const subs = subCategoriesMap[val];
        for (const [k, name] of Object.entries(subs)) {
          const opt = document.createElement('option');
          opt.value = k;
          opt.textContent = name;
          subSelect.appendChild(opt);
        }
        wrapper.style.display = 'block';
        subSelect.required = true;
      } else {
        wrapper.style.display = 'none';
        subSelect.required = false;
        subSelect.value = '';
      }
    });

    updateIndexes();
  }

  function removeProductCard(button) {
    const card = button.closest('.bulk-product-card');
    card.remove();
    updateIndexes();
  }

  function updateIndexes() {
    const cards = document.querySelectorAll('#bulk-cards-container .bulk-product-card');
    const totalBadge = document.getElementById('total-forms-badge');
    
    cards.forEach((card, index) => {
      card.querySelector('.index-num').innerText = index + 1;
      const removeBtn = card.querySelector('.btn-remove-card');
      removeBtn.disabled = (cards.length <= 1);
    });

    totalBadge.innerText = `Jumlah: ${cards.length} formulir produk`;
  }

  function previewLocalImage(input, previewId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(previewId).src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewUrlImage(url, previewId) {
    if (url.trim() !== '') {
      document.getElementById(previewId).src = url;
    }
  }
</script>
@endsection