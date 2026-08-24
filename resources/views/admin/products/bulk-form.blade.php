@extends('admin.layout')

@section('title', 'Tambah Banyak Produk sekaligus')
@section('page_title', 'Input Massal Produk')

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Katalog</span>
    <h2 class="admin-page-title mb-1">Input Massal Produk</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Setiap kartu = satu produk. Pilih kategori dulu agar subkategori muncul. Kolom <span style="color: var(--color-accent);">*</span> wajib.
    </p>
  </div>
  <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<form action="{{ route('admin.products.store-bulk') }}" method="POST" enctype="multipart/form-data" id="bulk-form">
  @csrf

  <div id="bulk-cards-container" class="d-flex flex-column gap-4">
    {{-- Dynamic product cards --}}
  </div>

  <div class="text-center my-4">
    <button type="button" class="admin-btn admin-btn-outline" onclick="addNewProductCard()">
      <i class="bi bi-plus-circle"></i> Tambah Formulir Produk
    </button>
  </div>

  <div class="admin-card">
    <div class="admin-card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
      <span id="total-forms-badge" style="color: var(--color-text-muted); font-size: 0.88rem;">Jumlah: 0 formulir produk</span>
      <div class="d-inline-flex gap-2">
        <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn admin-btn-primary">
          <i class="bi bi-check-lg"></i> Simpan Semua
        </button>
      </div>
    </div>
  </div>
</form>

{{-- Card Template (Hidden) --}}
<div class="d-none">
  <div id="card-template">
    <div class="admin-card bulk-product-card">
      <div class="admin-card-header">
        <div>
          <span class="admin-card-header-label">Produk</span>
          <h3 class="admin-card-header-title mb-0 card-index-title">
            Data Produk #<span class="index-num">1</span>
          </h3>
        </div>
        <button type="button" class="admin-btn admin-btn-outline btn-remove-card" onclick="removeProductCard(this)">
          <i class="bi bi-trash"></i> Hapus
        </button>
      </div>
      <div class="admin-card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="admin-form-label">Nama Produk <span style="color: var(--color-accent);">*</span></label>
            <input type="text" name="title[__INDEX__]" class="form-control" placeholder="Contoh: Brewing Specific Media 1" required>
          </div>
          <div class="col-md-6">
            <label class="admin-form-label">Nomor Katalog</label>
            <input type="text" name="catalog[__INDEX__]" class="form-control" placeholder="Contoh: 610152">
          </div>
          <div class="col-md-6">
            <label class="admin-form-label">Kategori <span style="color: var(--color-accent);">*</span></label>
            <select name="category[__INDEX__]" class="form-select bulk-category-select" data-id="__INDEX__" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($categoriesStructure as $catKey => $catData)
                <option value="{{ $catKey }}">{{ $catData['name'] ?? $catKey }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6" id="sub-wrapper-__INDEX__" style="display: none;">
            <label class="admin-form-label">Subkategori <span style="color: var(--color-accent);">*</span></label>
            <select name="sub_category[__INDEX__]" id="bulk-subcategory-select-__INDEX__" class="form-select">
              <option value="">-- Pilih Subkategori --</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="admin-form-label">Sektor Industri</label>
            <select name="sector[__INDEX__]" class="form-select">
              <option value="">-- Umum / Semua Sektor --</option>
              @foreach($sectors as $sec)
                <option value="{{ $sec['id'] }}">{{ $sec['name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 mt-2">
            <label class="admin-form-label">Gambar Produk</label>
            <div class="row g-3 align-items-center">
              <div class="col-sm-2 text-center">
                <div style="width: 80px; height: 80px; margin: 0 auto; border: 1px solid var(--color-border); border-radius: 6px; background: rgba(255,255,255,0.03); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                  <img id="preview-img-__INDEX__" src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="admin-form-label">Upload File</label>
                <input class="form-control form-control-sm" type="file" name="image_file[__INDEX__]" accept="image/*" onchange="previewLocalImage(this, 'preview-img-__INDEX__')">
              </div>
              <div class="col-sm-5">
                <label class="admin-form-label">Atau URL</label>
                <input type="text" name="image_url[__INDEX__]" class="form-control form-control-sm" placeholder="https://..." oninput="previewUrlImage(this.value, 'preview-img-__INDEX__')">
              </div>
            </div>
          </div>
          <div class="col-12">
            <label class="admin-form-label">Deskripsi / Aplikasi</label>
            <textarea name="description[__INDEX__]" class="form-control" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('admin_styles')
<style>
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
    font-size: 0.875rem;
  }
</style>
@endsection

@section('admin_scripts')
<script>
  const subCategoriesMap = @json(collect($categoriesStructure)->mapWithKeys(fn($item, $key) => [$key => $item['subs'] ?? []]));

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      for (let i = 0; i < 2; i++) addNewProductCard();
    });
  } else {
    for (let i = 0; i < 2; i++) addNewProductCard();
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
