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
  <div class="d-flex flex-column gap-4">
    @foreach($parents as $parent)
    <div class="admin-card">

      {{-- Parent Category Header --}}
      <div class="admin-card-header">
        <div class="d-flex align-items-center gap-3 flex-wrap">
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

        <div class="d-flex align-items-center gap-2 flex-shrink-0">
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

      {{-- Subcategories Table --}}
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
    @endforeach
  </div>
@endif

@endsection
