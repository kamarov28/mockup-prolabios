@extends('admin.layout')

@section('title', 'Kategori Produk')
@section('page_title', 'Kategori Produk')

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Konten</span>
    <h2 class="admin-page-title mb-1">Manajemen Kategori Produk</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Kelola kategori utama dan sub-kategori. Perubahan langsung tampil di halaman produk publik.
    </p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
    <i class="bi bi-plus-lg"></i> Tambah Kategori
  </a>
</div>

@if($parents->isEmpty())
  <div class="admin-card">
    <div class="admin-card-body text-center py-5">
      <i class="bi bi-diagram-3" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
      <p style="color: var(--color-text-muted); font-size: 0.88rem;">
        Belum ada kategori produk. Klik "Tambah Kategori" untuk mulai.
      </p>
    </div>
  </div>
@else
  {{-- Toolbar: filter + expand/collapse --}}
  <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="position-relative" style="flex: 1; min-width: 200px; max-width: 360px;">
      <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 0.85rem; pointer-events: none;"></i>
      <input type="search" id="category-filter" class="form-control form-control-sm"
             placeholder="Cari nama atau key kategori..."
             autocomplete="off"
             style="padding-left: 36px;">
    </div>
    <button type="button" id="btn-expand-all" class="admin-btn admin-btn-outline" style="padding: 8px 14px;">
      <i class="bi bi-arrows-expand"></i> Buka semua
    </button>
    <button type="button" id="btn-collapse-all" class="admin-btn admin-btn-outline" style="padding: 8px 14px;">
      <i class="bi bi-arrows-collapse"></i> Tutup semua
    </button>
    <span id="category-count" style="color: var(--color-text-muted); font-size: 0.8rem; margin-left: auto;">
      {{ $parents->count() }} kategori utama
    </span>
  </div>

  <div id="category-list" class="d-flex flex-column gap-3">
    @foreach($parents as $parent)
    @php
      $searchBlob = strtolower($parent->name.' '.$parent->key.' '.$parent->children->pluck('name')->implode(' ').' '.$parent->children->pluck('key')->implode(' '));
    @endphp
    <div class="admin-card category-card" data-search="{{ e($searchBlob) }}">

      {{-- Parent header (always visible) — click toggles body --}}
      <div class="admin-card-header category-card-toggle" role="button" tabindex="0"
           aria-expanded="false"
           style="cursor: pointer; user-select: none;">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <i class="bi bi-chevron-right category-chevron" style="color: var(--color-text-muted); transition: transform 0.2s ease; font-size: 0.9rem;"></i>
          <div>
            <span class="admin-card-header-label">Kategori Utama</span>
            <h3 class="admin-card-header-title mb-0">
              {{ $parent->name }}
              <code class="ms-2" style="font-size: 0.7rem; font-weight: 500;">{{ $parent->key }}</code>
            </h3>
          </div>
          <span class="admin-badge admin-badge-muted">{{ $parent->children_count }} sub-kategori</span>
          <span class="admin-badge admin-badge-accent">Urutan: {{ $parent->sort_order }}</span>
        </div>

        <div class="d-flex align-items-center gap-2 flex-shrink-0" onclick="event.stopPropagation()">
          <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
             class="admin-btn admin-btn-outline" title="Tambah sub-kategori">
            <i class="bi bi-plus-lg"></i> Sub-kategori
          </a>
          <div class="d-flex align-items-center gap-1">
            <a href="{{ route('admin.categories.edit', $parent->id) }}"
               class="admin-action-link edit" title="Edit"
               style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; padding: 0;">
              <i class="bi bi-pencil-square"></i>
            </a>
            <form method="POST" action="{{ route('admin.categories.destroy', $parent->id) }}"
                  style="display: contents;">
              @csrf @method('DELETE')
              <button type="submit" class="admin-action-link delete" title="Hapus"
                      style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; padding: 0;">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- Body: collapsed by default (lighter first paint) --}}
      <div class="category-card-body" hidden>
        @if($parent->children->isNotEmpty())
        <div class="admin-card-body-flush">
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Nama Sub-Kategori</th>
                  <th>Key</th>
                  <th style="text-align: center; width: 90px;">Urutan</th>
                  <th style="text-align: right; padding-right: 24px; width: 120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($parent->children as $child)
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <i class="bi bi-arrow-return-right" style="color: var(--color-text-muted); font-size: 0.85rem;"></i>
                      <span class="cell-title">{{ $child->name }}</span>
                    </div>
                  </td>
                  <td>
                    <code class="cell-code">{{ $child->key }}</code>
                  </td>
                  <td style="text-align: center; color: var(--color-text-muted);">
                    {{ $child->sort_order }}
                  </td>
                  <td style="text-align: right; white-space: nowrap;">
                    <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                      <a href="{{ route('admin.categories.edit', $child->id) }}"
                         class="admin-action-link edit" title="Edit"
                         style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form method="POST" action="{{ route('admin.categories.destroy', $child->id) }}"
                            style="display: contents;">
                        @csrf @method('DELETE')
                        <button type="submit" class="admin-action-link delete" title="Hapus"
                                style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0;">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @else
        <div class="admin-card-body text-center py-4">
          <p style="color: var(--color-text-muted); font-size: 0.85rem; margin: 0;">
            <i class="bi bi-info-circle me-1"></i>
            Belum ada sub-kategori.
            <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}"
               style="color: var(--color-accent);">Tambah sekarang</a>
          </p>
        </div>
        @endif
      </div>

    </div>
    @endforeach
  </div>

  <p id="category-empty-filter" class="text-center py-4" style="color: var(--color-text-muted); font-size: 0.88rem; display: none;">
    Tidak ada kategori yang cocok dengan pencarian.
  </p>
@endif

@endsection

@section('admin_scripts')
<script>
(function () {
  const list = document.getElementById('category-list');
  if (!list) return;

  function setOpen(card, open) {
    const body = card.querySelector('.category-card-body');
    const toggle = card.querySelector('.category-card-toggle');
    const chevron = card.querySelector('.category-chevron');
    if (!body || !toggle) return;
    body.hidden = !open;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (chevron) chevron.style.transform = open ? 'rotate(90deg)' : '';
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
        // Saat filter aktif, buka kartu yang cocok supaya sub terlihat
        if (q) setOpen(card, true);
      }
    });
    if (emptyMsg) emptyMsg.style.display = visible === 0 ? '' : 'none';
    if (countEl) {
      countEl.textContent = q
        ? (visible + ' dari ' + total + ' kategori')
        : (total + ' kategori utama');
    }
  });
})();
</script>
@endsection
