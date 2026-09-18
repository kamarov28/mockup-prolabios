@extends('admin.layout')

@section('title', 'Tambah Banyak Produk sekaligus')
@section('page_title', 'Input Massal Produk')

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Katalog</span>
    <h2 class="admin-page-title mb-1">Input Massal Produk</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Setiap kartu mewakili satu produk lengkap (harga, stok, kategori, prinsipal, sektor, datasheet, dan deskripsi rich text). Kolom <span style="color: var(--color-accent);">*</span> wajib diisi.
    </p>
  </div>
  <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">
    <i data-lucide="arrow-left"></i> Kembali
  </a>
</div>

{{-- Banner Ajakan Impor Excel untuk Skala Besar --}}
<div class="admin-card mb-4" style="background: linear-gradient(135deg, var(--color-surface-1) 0%, var(--color-surface-2) 100%); border-left: 4px solid var(--color-accent);">
  <div class="admin-card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 42px; height: 42px; border-radius: 8px; background: rgba(166, 23, 28, 0.1); display: flex; align-items: center; justify-content: center; color: var(--color-accent); flex-shrink: 0;">
        <i data-lucide="file-spreadsheet"></i>
      </div>
      <div>
        <h4 class="mb-0 fw-bold" style="font-size: 0.95rem; color: var(--color-text-main);">Punya puluhan hingga ratusan produk?</h4>
        <p class="mb-0 text-muted" style="font-size: 0.82rem;">Gunakan impor spreadsheet Excel (.xlsx) untuk upload data produk skala besar tanpa batas form browser.</p>
      </div>
    </div>
    <div class="d-inline-flex gap-2 align-items-center">
      <a href="{{ route('admin.products.import.template') }}" class="admin-btn admin-btn-outline">
        <i data-lucide="download"></i> Download Template Excel
      </a>
      <a href="{{ route('admin.products', ['import' => 1]) }}" class="admin-btn admin-btn-primary">
        <i data-lucide="cloud-upload"></i> Buka Menu Impor
      </a>
    </div>
  </div>
</div>

<form action="{{ route('admin.products.store-bulk') }}" method="POST" enctype="multipart/form-data" id="bulk-form">
  @csrf

  <div id="bulk-cards-container" class="d-flex flex-column gap-4">
    {{-- Dynamic product cards populated by JavaScript --}}
  </div>

  <div class="text-center my-4">
    <button type="button" class="admin-btn admin-btn-outline" onclick="addNewProductCard()">
      <i data-lucide="plus-circle"></i> Tambah Formulir Produk Lainnya
    </button>
  </div>

  <div class="admin-card">
    <div class="admin-card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
      <span id="total-forms-badge" style="color: var(--color-text-muted); font-size: 0.88rem;">Jumlah: 0 formulir produk</span>
      <div class="d-inline-flex gap-2">
        <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-outline">Batal</a>
        <button type="submit" class="admin-btn admin-btn-primary">
          <i data-lucide="check"></i> Simpan Semua Produk
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
          <i data-lucide="trash-2"></i> Hapus
        </button>
      </div>
      <div class="admin-card-body">
        <div class="row g-3">

          {{-- Row 1: Judul & Katalog --}}
          <div class="col-md-6">
            <label class="admin-form-label">Nama Produk <span style="color: var(--color-accent);">*</span></label>
            <input type="text" name="title[__INDEX__]" class="form-control" placeholder="Contoh: Brewing Specific Media 1" required>
          </div>
          <div class="col-md-6">
            <label class="admin-form-label">Nomor Katalog</label>
            <input type="text" name="catalog[__INDEX__]" class="form-control" placeholder="Contoh: 610152">
          </div>

          {{-- Row 2: Harga & Stok --}}
          <div class="col-md-6">
            <label class="admin-form-label">Harga Produk (Rp)</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" inputmode="numeric" name="price[__INDEX__]" class="form-control bulk-price-input" placeholder="Contoh: 1.500.000">
            </div>
          </div>
          <div class="col-md-6">
            <label class="admin-form-label">Stok (Unit)</label>
            <input type="number" min="0" name="stock[__INDEX__]" class="form-control" value="0" placeholder="0">
          </div>

          {{-- Row 3: Kategori, Subkategori & Prinsipal --}}
          <div class="col-md-4">
            <label class="admin-form-label">Kategori <span style="color: var(--color-accent);">*</span></label>
            <select name="category[__INDEX__]" class="form-select bulk-category-select" data-id="__INDEX__" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($categoriesStructure as $catKey => $catData)
                <option value="{{ $catKey }}">{{ $catData['name'] ?? $catKey }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4" id="sub-wrapper-__INDEX__" style="display: none;">
            <label class="admin-form-label">Subkategori <span style="color: var(--color-accent);">*</span></label>
            <select name="sub_category[__INDEX__]" id="bulk-subcategory-select-__INDEX__" class="form-select">
              <option value="">-- Pilih Subkategori --</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="admin-form-label">Prinsipal / Manufaktur</label>
            <select name="principal_id[__INDEX__]" class="form-select">
              <option value="">-- Tanpa Prinsipal Khusus --</option>
              @if(!empty($principals))
                @foreach($principals as $pr)
                  <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                @endforeach
              @endif
            </select>
          </div>

          {{-- Row 4: Sektor Industri Terkait (Multi-select Checkboxes) --}}
          <div class="col-12">
            <label class="admin-form-label mb-2">
              <i data-lucide="layers" class="me-1" style="color: var(--color-accent);"></i> Sektor Industri Terkait
            </label>
            <div class="p-3" style="background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: 8px;">
              <div class="row g-2">
                @foreach($sectors as $sec)
                  <div class="col-6 col-sm-3">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                      <input class="form-check-input" type="checkbox" name="sectors[__INDEX__][]" value="{{ $sec['id'] }}" id="sec___INDEX___{{ $sec['id'] }}">
                      <label class="form-check-label small mb-0" for="sec___INDEX___{{ $sec['id'] }}" style="cursor: pointer; user-select: none; color: var(--color-text-secondary);">
                        {{ $sec['name'] }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Row 5: Dokumen Spesifikasi Teknis (PDF / Datasheet) --}}
          <div class="col-12">
            <label class="admin-form-label mb-1">
              <i data-lucide="file-text" class="me-1" style="color: var(--color-accent);"></i> Dokumen Spesifikasi Teknis (PDF)
            </label>
            <div class="row g-2 align-items-center">
              <div class="col-md-6">
                <input class="form-control form-control-sm" type="file" name="datasheet_file[__INDEX__]" accept=".pdf,application/pdf">
                <div class="form-text mt-1 small" style="color: var(--color-text-muted);">Upload PDF (Maks. 10MB)</div>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" name="datasheet_url[__INDEX__]" placeholder="Atau URL PDF Eksternal (https://...)">
                <div class="form-text mt-1 small" style="color: var(--color-text-muted);">Link PDF publik eksternal</div>
              </div>
            </div>
          </div>

          {{-- Row 6: Gambar Utama / Cover Produk --}}
          <div class="col-12 mt-2">
            <label class="admin-form-label mb-1">Gambar Utama / Cover</label>
            <div class="row g-3 align-items-center">
              <div class="col-sm-2 text-center">
                <div style="width: 80px; height: 80px; margin: 0 auto; border: 1px solid var(--color-border); border-radius: 8px; background: #FFFFFF; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: var(--shadow-xs);">
                  <img id="preview-img-__INDEX__" src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
              </div>
              <div class="col-sm-5">
                <label class="admin-form-label small mb-1">Upload File Cover</label>
                <input class="form-control form-control-sm" type="file" name="image_file[__INDEX__]" accept="image/*" onchange="previewLocalImage(this, 'preview-img-__INDEX__')">
              </div>
              <div class="col-sm-5">
                <label class="admin-form-label small mb-1">Atau URL Gambar</label>
                <input type="text" name="image_url[__INDEX__]" class="form-control form-control-sm" placeholder="https://..." oninput="previewUrlImage(this.value, 'preview-img-__INDEX__')">
              </div>
            </div>
          </div>

          {{-- Row 7: Deskripsi Produk (Summernote WYSIWYG) --}}
          <div class="col-12">
            <label class="admin-form-label">Deskripsi / Spesifikasi Produk</label>
            <textarea name="description[__INDEX__]" class="form-control bulk-summernote" rows="5" placeholder="Deskripsi, aplikasi, spesifikasi detail..."></textarea>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('admin_styles')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  <style>
    .note-editor.note-frame {
      border: 1px solid var(--color-border) !important;
      border-radius: 6px !important;
      background: #FFFFFF !important;
    }
    .note-editor.note-frame .note-toolbar {
      background: var(--color-surface-2) !important;
      border-bottom: 1px solid var(--color-border) !important;
      border-radius: 6px 6px 0 0 !important;
    }
  </style>
@endsection

@section('admin_scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" @nonce></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js" @nonce></script>

  <script @nonce>
    const subCategoriesMap = @json(collect($categoriesStructure)->mapWithKeys(fn($item, $key) => [$key => $item['subs'] ?? []]));

    function formatRupiahInput(val) {
      var clean = val.replace(/\D/g, '');
      return clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
    }

    // Auto-format ribuan rupiah pada input harga di semua kartu formulir
    document.addEventListener('input', function(e) {
      if (e.target && e.target.classList.contains('bulk-price-input')) {
        var cursor = e.target.selectionStart;
        var prevLen = e.target.value.length;
        e.target.value = formatRupiahInput(e.target.value);
        var diff = e.target.value.length - prevLen;
        e.target.setSelectionRange(cursor + diff, cursor + diff);
      }
    });

    function initSummernoteForCard(cardElement) {
      if (!window.jQuery || !$.fn.summernote) return;

      $(cardElement).find('.bulk-summernote').summernote({
        placeholder: 'Tulis rincian deskripsi produk, aplikasi, spesifikasi detail, tabel pendukung, dll...',
        tabsize: 2,
        height: 180,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']],
          ['view', ['fullscreen', 'codeview']]
        ]
      });
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

      // Inisialisasi Summernote pada textarea kartu yang baru dibuat
      initSummernoteForCard(newCard);

      // Refresh Lucide icons untuk icon di kartu baru
      if (window.lucide) {
        lucide.createIcons({ root: newCard });
      }

      // Pasang handler pergantian kategori -> subkategori
      const categorySelect = newCard.querySelector('.bulk-category-select');
      if (categorySelect) {
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
      }

      updateIndexes();
    }

    function removeProductCard(button) {
      const card = button.closest('.bulk-product-card');
      if (card) {
        if (window.jQuery && $.fn.summernote) {
          $(card).find('.bulk-summernote').summernote('destroy');
        }
        card.remove();
        updateIndexes();
      }
    }

    function updateIndexes() {
      const cards = document.querySelectorAll('#bulk-cards-container .bulk-product-card');
      const totalBadge = document.getElementById('total-forms-badge');

      cards.forEach((card, index) => {
        const indexEl = card.querySelector('.index-num');
        if (indexEl) indexEl.innerText = index + 1;
        const removeBtn = card.querySelector('.btn-remove-card');
        if (removeBtn) removeBtn.disabled = (cards.length <= 1);
      });

      if (totalBadge) {
        totalBadge.innerText = `Jumlah: ${cards.length} formulir produk`;
      }
    }

    function previewLocalImage(input, previewId) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById(previewId);
          if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function previewUrlImage(url, previewId) {
      if (url.trim() !== '') {
        const img = document.getElementById(previewId);
        if (img) img.src = url;
      }
    }

    // Pastikan kode Summernote tersinkronisasi ke textarea saat form disubmit
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('bulk-form');
      if (form) {
        form.addEventListener('submit', function() {
          if (window.jQuery && $.fn.summernote) {
            $('.bulk-summernote').each(function() {
              if ($(this).data('summernote')) {
                $(this).val($(this).summernote('code'));
              }
            });
          }
        });
      }

      // Buat default 2 kartu formulir saat halaman dimuat
      for (let i = 0; i < 2; i++) {
        addNewProductCard();
      }
    });
  </script>
@endsection
