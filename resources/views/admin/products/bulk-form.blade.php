@extends('admin.layout')

@section('title', 'Tambah Banyak Produk sekaligus')
@section('page_title', 'Input Massal Produk')

@section('admin_content')

<x-admin.page-header
  label="Katalog"
  title="Input Massal Produk"
  description="Setiap kartu mewakili satu produk lengkap (harga, stok, kategori, prinsipal, sektor, datasheet, dan deskripsi rich text). Kolom <span style='color: var(--color-accent);'>*</span> wajib diisi."
  back-url="{{ route('admin.products') }}"
  back-text="Kembali"
/>

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

{{-- Catatan Batas Kapasitas Upload File Fisik --}}
<div class="p-3 mb-4 rounded" style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #D97706;">
  <div class="d-flex align-items-start gap-3">
    <div style="width: 32px; height: 32px; border-radius: 6px; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: #B45309; flex-shrink: 0; margin-top: 2px;">
      <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i>
    </div>
    <div class="flex-grow-1">
      <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
        <strong style="font-size: 0.88rem; color: #92400E;">Rekomendasi Kapasitas: Ideal Maksimal 10 Produk (Batas Aman Kritis: 15 Produk)</strong>
        <span class="badge" style="background: #FEF3C7; color: #92400E; font-size: 0.7rem; border: 1px solid #FDE68A;">PHP max_file_uploads = 20</span>
      </div>
      <p class="mb-0" style="font-size: 0.82rem; line-height: 1.55; color: #78350F;">
        <strong>Mengapa dibatasi?</strong> Setiap produk memiliki 2 slot berkas fisik (<em>Cover Gambar</em> dan <em>Datasheet PDF</em>). Server PHP membatasi maksimal 20 berkas per satu kali kirim form (<code>max_file_uploads</code>). Jika Anda mengisi lebih dari 10 produk dan semuanya melampirkan berkas fisik, berkas pada produk selebihnya berisiko diabaikan oleh server.
        <br>
        <span class="d-inline-block mt-1">💡 <em>Untuk upload lebih dari 15 produk sekaligus, sangat disarankan menggunakan menu <strong>Impor Spreadsheet Excel (.xlsx)</strong> di atas.</em></span>
      </p>
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

          {{-- Row: Featured Switch --}}
          <div class="col-12">
            <div class="p-2 px-3 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
              <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                <input class="form-check-input" type="checkbox" role="switch" id="is_featured___INDEX__" name="is_featured[__INDEX__]" value="1" style="cursor: pointer; width: 2.25rem; height: 1.25rem;">
                <label class="form-check-label fw-semibold mb-0" for="is_featured___INDEX__" style="cursor: pointer; color: var(--color-text-main); font-size: 0.85rem;">
                  <i data-lucide="star" class="me-1" style="width: 14px; height: 14px; color: #D97706; fill: #D97706;"></i>
                  Tampilkan sebagai Produk Unggulan di Beranda
                </label>
              </div>
            </div>
          </div>

          {{-- Row 4: Sektor Industri Terkait (Multi-select Checkboxes) --}}
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
              <label class="admin-form-label mb-0">
                <i data-lucide="layers" class="me-1" style="color: var(--color-accent);"></i> Sektor Industri Terkait
              </label>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;" onclick="toggleCardSectors('__INDEX__', true)">Pilih Semua</button>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;" onclick="toggleCardSectors('__INDEX__', false)">Hapus Semua</button>
              </div>
            </div>
            <div class="p-3" id="card-sectors-__INDEX__" style="background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: 8px;">
              <div class="row g-2">
                @foreach($sectors as $sec)
                  <div class="col-6 col-sm-3">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                      <input class="form-check-input sector-cb-__INDEX__" type="checkbox" name="sectors[__INDEX__][]" value="{{ $sec['id'] }}" id="sec___INDEX___{{ $sec['id'] }}">
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
                <div class="form-text mt-1 small" style="color: var(--color-text-muted);">Upload PDF lokal (Maks. 10MB) <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.68rem;">Direkomendasikan</span></div>
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
        ],
        callbacks: {
          onImageUpload: function(files) {
            for (let i = 0; i < files.length; i++) {
              window.uploadSummernoteImage(files[i], this);
            }
          }
        }
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

    function toggleCardSectors(index, checked) {
      const cbs = document.querySelectorAll('.sector-cb-' + index);
      cbs.forEach(function(cb) { cb.checked = checked; });
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
        if (cards.length > 15) {
          totalBadge.innerHTML = `Jumlah: <strong style="color: #DC2626;">${cards.length} formulir produk</strong> <span class="badge bg-danger-subtle text-danger border border-danger ms-1" style="font-size: 0.72rem;">Melebihi batas aman (15)</span>`;
        } else if (cards.length > 10) {
          totalBadge.innerHTML = `Jumlah: <strong style="color: #D97706;">${cards.length} formulir produk</strong> <span class="badge bg-warning-subtle text-warning border border-warning ms-1" style="font-size: 0.72rem;">Mendekati batas aman (maks 15)</span>`;
        } else {
          totalBadge.innerText = `Jumlah: ${cards.length} formulir produk (Batas aman: 10–15 produk)`;
        }
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
