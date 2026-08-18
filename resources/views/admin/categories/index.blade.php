@extends('admin.layout')

@section('title', 'Kategori Produk')
@section('page_title', 'Kategori Produk')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-diagram-3 text-danger me-2"></i>Manajemen Kategori Produk</h5>
    <p class="text-muted small mb-0 mt-1">Kelola kategori utama dan sub-kategori produk. Perubahan langsung tampil di halaman produk publik.</p>
  </div>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-danger">
    <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if($parents->isEmpty())
  <div class="card bg-white shadow-sm">
    <div class="card-body text-center py-5">
      <i class="bi bi-diagram-3" style="font-size: 2.5rem; color: var(--color-text-muted);"></i>
      <p class="mt-3 text-muted">Belum ada kategori produk. Klik "Tambah Kategori" untuk mulai.</p>
    </div>
  </div>
@else
  <div class="row g-4">
    @foreach($parents as $parent)
    <div class="col-12">
      <div class="card bg-white shadow-sm border-0">
        {{-- Category Header --}}
        <div class="card-header d-flex align-items-center justify-content-between py-3 px-4" style="background: var(--color-surface, #1e1e22); border-bottom: 1px solid var(--color-border, #2a2a2e);">
          <div class="d-flex align-items-center gap-3">
            <i class="bi bi-grid-3x3-gap text-danger" style="font-size: 1.1rem;"></i>
            <div>
              <span class="fw-bold text-dark fs-6">{{ $parent->name }}</span>
              <code class="ms-2 small text-muted">{{ $parent->key }}</code>
            </div>
            <span class="badge bg-secondary ms-1">{{ $parent->children_count }} sub-kategori</span>
            <span class="badge" style="background: rgba(255,73,80,0.12); color: var(--color-accent, #FF4950);">Urutan: {{ $parent->sort_order }}</span>
          </div>
          <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}" class="btn btn-sm btn-outline-primary" title="Tambah sub-kategori">
              <i class="bi bi-plus-lg me-1"></i>Sub-kategori
            </a>
            <a href="{{ route('admin.categories.edit', $parent->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            <form method="POST" action="{{ route('admin.categories.destroy', $parent->id) }}" onsubmit="return confirm('Hapus kategori \"{{ addslashes($parent->name) }}\" beserta semua sub-kategorinya?\n\nPerhatian: pastikan tidak ada produk yang masih menggunakan kategori ini.')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>

        {{-- Subcategories List --}}
        @if($parent->children->isNotEmpty())
        <div class="card-body p-0">
          <table class="table table-hover mb-0" style="font-size: 0.875rem;">
            <thead style="background: rgba(0,0,0,0.03);">
              <tr>
                <th class="px-4 py-2 text-muted fw-normal">Nama Sub-Kategori</th>
                <th class="px-3 py-2 text-muted fw-normal">Key</th>
                <th class="px-3 py-2 text-muted fw-normal text-center" style="width: 80px;">Urutan</th>
                <th class="px-4 py-2 text-muted fw-normal text-end" style="width: 120px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($parent->children as $child)
              <tr>
                <td class="px-4 py-2 align-middle">
                  <i class="bi bi-arrow-return-right text-muted me-2"></i>{{ $child->name }}
                </td>
                <td class="px-3 py-2 align-middle"><code class="small text-muted">{{ $child->key }}</code></td>
                <td class="px-3 py-2 align-middle text-center text-muted">{{ $child->sort_order }}</td>
                <td class="px-4 py-2 align-middle text-end">
                  <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.categories.edit', $child->id) }}" class="btn btn-xs btn-outline-warning py-0 px-2" style="font-size: 0.75rem;" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $child->id) }}" onsubmit="return confirm('Hapus sub-kategori \"{{ addslashes($child->name) }}\"?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" title="Hapus">
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
        @else
        <div class="card-body text-center py-3">
          <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Belum ada sub-kategori. <a href="{{ route('admin.categories.create', ['parent_id' => $parent->id]) }}">Tambah sekarang</a></p>
        </div>
        @endif
      </div>
    </div>
    @endforeach
  </div>
@endif

@endsection
