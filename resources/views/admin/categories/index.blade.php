@extends('admin.layout')

@section('title', 'Kategori Produk')
@section('page_title', 'Kategori Produk')

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Katalog Produk</span>
    <h2 class="admin-page-title mb-1">Manajemen Kategori & Subkategori</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Kelola struktur hierarki katalog produk, pengurutan, dan pengelompokan. Perubahan langsung aktif di katalog publik.
    </p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
    <i data-lucide="plus"></i> Tambah Kategori
  </a>
</div>

@if($parents->isEmpty())
  <x-admin.empty-state
    icon="folder-tree"
    message="Belum ada kategori produk terdaftar."
    :action-url="route('admin.categories.create')"
    action-label="Tambah Kategori Pertama" />
@else
  @php
    $totalSubcategories = $parents->sum('children_count');
  @endphp

  {{-- Interactive Control Bar: Search & Tree Actions --}}
  <div class="admin-card mb-4" style="background: #FFFFFF;">
    <div class="admin-card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">

      {{-- Search Input --}}
      <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: 240px; max-width: 420px;">
        <div style="position: relative; width: 100%;">
          <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); width: 16px; height: 16px; pointer-events: none;"></i>
          <input type="search" id="category-filter" class="form-control"
                 placeholder="Cari nama kategori atau key..."
                 autocomplete="off"
                 style="padding-left: 36px; height: 38px; font-size: 0.85rem; border-radius: 8px;">
        </div>
      </div>

      {{-- Action Tools: Expand/Collapse & Quick Count --}}
      <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
        <div class="btn-group" role="group" style="box-shadow: var(--shadow-xs); border-radius: 8px; overflow: hidden;">
          <button type="button" id="btn-expand-all" class="admin-btn admin-btn-outline" style="height: 38px; border-top-right-radius: 0; border-bottom-right-radius: 0; border-right: none; font-size: 0.8rem;">
            <i data-lucide="unfold-vertical"></i> Buka Semua
          </button>
          <button type="button" id="btn-collapse-all" class="admin-btn admin-btn-outline" style="height: 38px; border-top-left-radius: 0; border-bottom-left-radius: 0; font-size: 0.8rem;">
            <i data-lucide="fold-vertical"></i> Tutup Semua
          </button>
        </div>

        <div class="d-flex align-items-center gap-2 px-3" style="background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: 8px; height: 38px; font-size: 0.82rem; color: var(--color-text-secondary);">
          <i data-lucide="folder-tree" style="width: 15px; height: 15px; color: var(--color-accent);"></i>
          <span id="category-count">
            <strong>{{ $parents->count() }}</strong> Kategori <span class="text-muted">•</span> <strong>{{ $totalSubcategories }}</strong> Subkategori
          </span>
        </div>
      </div>

    </div>
  </div>

  {{-- Hierarchical Category Cards List --}}
  <div id="category-list" class="d-flex flex-column gap-3">
    @foreach($parents as $parent)
    @php
      $searchBlob = strtolower($parent->name.' '.$parent->key.' '.$parent->children->pluck('name')->implode(' ').' '.$parent->children->pluck('key')->implode(' '));
    @endphp
    <div class="cat-tree-card category-card" data-search="{{ e($searchBlob) }}">

      {{-- Parent Category Header --}}
      <div class="cat-tree-header category-card-toggle" role="button" tabindex="0"
           aria-expanded="false" title="Klik untuk membuka / menutup subkategori">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          {{-- Animated Chevron Toggle --}}
          <span class="cat-toggle-btn">
            <i data-lucide="chevron-right" style="width: 15px; height: 15px;"></i>
          </span>

          {{-- Identity: Name & Key Badge --}}
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-bold" style="font-size: 0.98rem; color: var(--color-text-main);">
              {{ $parent->name }}
            </span>
            <span class="cat-key-badge" title="Key Sistem">{{ $parent->key }}</span>
          </div>

          {{-- Metadata Chips --}}
          <div class="d-inline-flex align-items-center gap-2">
            <span class="admin-badge admin-badge-muted" style="font-size: 0.74rem;">
              <i data-lucide="layers" style="width: 12px; height: 12px;" class="me-1"></i>
              {{ $parent->children_count }} sub-kategori
            </span>
            <span class="cat-order-chip" title="Urutan Posisi">
              #{{ $parent->sort_order }}
            </span>
          </div>
        </div>

        {{-- Row Action Buttons --}}
        <div class="d-flex align-items-center gap-2 flex-shrink-0" onclick="event.stopPropagation()">
          <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
             class="admin-btn admin-btn-outline" style="height: 32px; padding: 0 12px; font-size: 0.78rem;" title="Tambah Sub-kategori baru">
            <i data-lucide="plus"></i> Sub-kategori
          </a>
          <div class="d-inline-flex align-items-center gap-1">
            <a href="{{ route('admin.categories.edit', $parent->id) }}"
               class="admin-action-link edit" title="Edit Kategori">
              <i data-lucide="file-edit"></i>
            </a>
            <form method="POST" action="{{ route('admin.categories.destroy', $parent->id) }}"
                  class="form-delete-category" data-name="{{ $parent->name }}" style="display: contents;">
              @csrf @method('DELETE')
              <button type="submit" class="admin-action-link delete" title="Hapus Kategori">
                <i data-lucide="trash-2"></i>
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- Nested Subcategories Body --}}
      <div class="cat-tree-body category-card-body" hidden>
        @if($parent->children->isNotEmpty())
        <div class="table-responsive" style="border-top: 1px solid var(--color-border);">
          <table class="cat-sub-table">
            <thead>
              <tr>
                <th style="padding-left: 58px;">Nama Subkategori</th>
                <th style="width: 220px;">Key / Slug</th>
                <th style="text-align: center; width: 100px;">Urutan</th>
                <th style="text-align: right; width: 120px; padding-right: 20px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($parent->children as $child)
              <tr>
                <td style="padding-left: 58px;">
                  <div class="d-flex align-items-center gap-2">
                    <i data-lucide="corner-down-right" class="cat-tree-line"></i>
                    <span class="fw-semibold" style="color: var(--color-text-main); font-size: 0.88rem;">{{ $child->name }}</span>
                  </div>
                </td>
                <td>
                  <span class="cat-key-badge">{{ $child->key }}</span>
                </td>
                <td style="text-align: center;">
                  <span class="cat-order-chip">#{{ $child->sort_order }}</span>
                </td>
                <td style="text-align: right; padding-right: 20px; white-space: nowrap;">
                  <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                    <a href="{{ route('admin.categories.edit', $child->id) }}"
                       class="admin-action-link edit" title="Edit Sub-kategori">
                      <i data-lucide="file-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $child->id) }}"
                          class="form-delete-category" data-name="{{ $child->name }}" style="display: contents;">
                      @csrf @method('DELETE')
                      <button type="submit" class="admin-action-link delete" title="Hapus Sub-kategori">
                        <i data-lucide="trash-2"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="p-4" style="border-top: 1px solid var(--color-border); background: var(--color-surface-2);">
          <div style="border: 1px dashed var(--color-border); border-radius: 8px; background: #FFFFFF; padding: 22px; text-align: center;">
            <p class="text-muted small mb-2">
              Belum ada sub-kategori untuk <strong>{{ $parent->name }}</strong>.
            </p>
            <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
               class="admin-btn admin-btn-outline admin-btn-sm d-inline-flex">
              <i data-lucide="plus"></i> Tambah Sub-kategori Pertama
            </a>
          </div>
        </div>
        @endif
      </div>

    </div>
    @endforeach
  </div>

  <p id="category-empty-filter" class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.88rem; display: none;">
    <i data-lucide="search-x" style="font-size: 2rem; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
    Tidak ada kategori atau sub-kategori yang cocok dengan kata kunci pencarian.
  </p>
@endif

@endsection

@section('admin_scripts')
<script @nonce>
(function () {
  const list = document.getElementById('category-list');
  if (!list) return;

  function setOpen(card, open) {
    const body = card.querySelector('.category-card-body');
    const toggle = card.querySelector('.category-card-toggle');
    if (!body || !toggle) return;
    body.hidden = !open;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    card.classList.toggle('is-open', open);
  }

  list.querySelectorAll('.category-card-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      const card = toggle.closest('.category-card');
      const open = toggle.getAttribute('aria-expanded') !== 'true';
      setOpen(card, open);
    });
    toggle.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggle.click();
      }
    });
  });

  document.getElementById('btn-expand-all')?.addEventListener('click', function () {
    list.querySelectorAll('.category-card').forEach(function (c) {
      if (c.style.display === 'none') return;
      setOpen(c, true);
    });
  });

  document.getElementById('btn-collapse-all')?.addEventListener('click', function () {
    list.querySelectorAll('.category-card').forEach(function (c) { setOpen(c, false); });
  });

  const filterInput = document.getElementById('category-filter');
  const emptyMsg = document.getElementById('category-empty-filter');
  const countEl = document.getElementById('category-count');
  const total = list.querySelectorAll('.category-card').length;

  filterInput?.addEventListener('input', function () {
    const q = (filterInput.value || '').trim().toLowerCase();
    let visible = 0;
    list.querySelectorAll('.category-card').forEach(function (card) {
      const hay = card.getAttribute('data-search') || '';
      const match = !q || hay.indexOf(q) !== -1;
      card.style.display = match ? '' : 'none';
      if (match) {
        visible++;
        if (q) setOpen(card, true);
      }
    });
    if (emptyMsg) emptyMsg.style.display = visible === 0 ? '' : 'none';
    if (countEl) {
      countEl.innerHTML = q
        ? `<strong>${visible}</strong> dari ${total} kategori ditemukan`
        : `<strong>${total}</strong> Kategori <span class="text-muted">•</span> Subkategori`;
    }
  });

  // SweetAlert2 Confirmation on Category / Subcategory Delete
  document.querySelectorAll('.form-delete-category').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Hapus Kategori?',
          html: `Hapus "<strong>${name}</strong>"?<br><small class="text-muted">Jika kategori memiliki subkategori, data terkait dapat terpengaruh.</small>`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'admin-btn admin-btn-danger mx-2',
            cancelButton: 'admin-btn admin-btn-ghost mx-2'
          },
          buttonsStyling: false
        }).then(function (result) {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      } else {
        if (confirm(`Hapus "${name}"?`)) {
          form.submit();
        }
      }
    });
  });
})();
</script>
@endsection
