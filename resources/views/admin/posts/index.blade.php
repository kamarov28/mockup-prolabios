@extends('admin.layout')

@section('title', 'Kelola Artikel & Berita')
@section('page_title', 'Artikel & Berita')

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Konten Publikasi</span>
    <h2 class="admin-page-title mb-1">Manajemen Artikel & Berita</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Kelola artikel ilmiah, rilis berita, kegiatan lab, dan dokumentasi event Prolabios.
    </p>
  </div>
  <div class="d-inline-flex align-items-center gap-2">
    <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
      <i data-lucide="plus"></i> Tulis Artikel Baru
    </a>
  </div>
</div>

@php
  $hasActiveFilters = !empty($search) || !empty($category) || !empty($status) || !empty($start_date) || !empty($end_date) || ($sort !== 'newest' && !empty($sort));
@endphp

<div class="admin-card">

  {{-- Filter Toolbar --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border); background: #FFFFFF;">
    <form action="{{ route('admin.posts') }}" method="GET" id="filter-posts-form">
      <div class="row g-2 align-items-center">

        {{-- Search Input --}}
        <div class="col-lg-4 col-md-12">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 8px; overflow: hidden; background: #FFFFFF; transition: border-color 0.2s ease;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); background: #F8FAFC; border-right: 1px solid var(--color-border);">
              <i data-lucide="search" style="width: 15px; height: 15px;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 0 12px; color: var(--color-text-main); font-family: var(--font-body); font-size: 0.88rem; height: 38px;"
                   placeholder="Cari judul atau isi artikel..." value="{{ $search }}" aria-label="Kata kunci pencarian">
            @if($search)
              <a href="{{ route('admin.posts', request()->except('s')) }}" style="display: flex; align-items: center; padding: 0 10px; color: var(--color-text-muted); text-decoration: none;" title="Hapus pencarian">
                <i data-lucide="x" style="width: 14px; height: 14px;"></i>
              </a>
            @endif
          </div>
        </div>

        {{-- Category Dropdown --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
          <select name="category" class="form-select" style="height: 38px; font-size: 0.85rem;" aria-label="Filter Kategori" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($availableCategories as $cat)
              <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>

        {{-- Status Dropdown --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
          <select name="status" class="form-select" style="height: 38px; font-size: 0.85rem;" aria-label="Filter Status" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="online" {{ $status === 'online' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft / Offline</option>
          </select>
        </div>

        {{-- Advanced Filter Toggle --}}
        <div class="col-lg-2 col-md-2 col-sm-6">
          <button type="button" class="admin-btn admin-btn-outline w-100 {{ ($sort !== 'newest' || $start_date || $end_date) ? 'active' : '' }}"
                  data-bs-toggle="collapse" data-bs-target="#advancedPostFilterBlock"
                  aria-expanded="{{ ($sort !== 'newest' || $start_date || $end_date) ? 'true' : 'false' }}"
                  aria-controls="advancedPostFilterBlock" style="height: 38px; font-size: 0.82rem;">
            <i data-lucide="sliders"></i> Lanjutan
            @if($sort !== 'newest' || $start_date || $end_date)
              <span class="badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 2px 5px;">•</span>
            @endif
          </button>
        </div>

        {{-- Submit Search --}}
        <div class="col-lg-2 col-md-2 col-sm-6">
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center" style="height: 38px;">
            <i data-lucide="search"></i> Cari
          </button>
        </div>

      </div>

      {{-- Advanced Filter Collapse --}}
      <div class="collapse {{ ($sort !== 'newest' || $start_date || $end_date) ? 'show' : '' }} mt-3" id="advancedPostFilterBlock">
        <div style="border: 1px solid var(--color-border); border-radius: 10px; padding: 16px; background: var(--color-surface-2);">
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="admin-form-label" for="sort">Urutkan Berdasarkan</label>
              <select name="sort" id="sort" class="form-select" style="height: 38px; font-size: 0.85rem;">
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru (Tanggal Dibuat)</option>
                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Judul (A–Z)</option>
                <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>Judul (Z–A)</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="admin-form-label" for="start_date">Dari Tanggal</label>
              <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}" style="height: 38px; font-size: 0.85rem;">
            </div>
            <div class="col-md-3">
              <label class="admin-form-label" for="end_date">Sampai Tanggal</label>
              <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}" style="height: 38px; font-size: 0.85rem;">
            </div>
            <div class="col-md-2">
              <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center" style="height: 38px;">
                <i data-lucide="filter"></i> Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Active Filter Tags Row --}}
      @if($hasActiveFilters)
        <div class="d-flex align-items-center gap-2 flex-wrap pt-3 mt-3" style="border-top: 1px dashed var(--color-border); font-size: 0.8rem;">
          <span class="text-muted d-inline-flex align-items-center gap-1">
            <i data-lucide="filter" style="width: 13px; height: 13px;"></i> Filter aktif:
          </span>
          @if($search)
            <span class="admin-badge admin-badge-muted">
              Pencarian: "{{ $search }}"
              <a href="{{ route('admin.posts', request()->except('s')) }}" class="text-muted ms-1" style="text-decoration: none;">&times;</a>
            </span>
          @endif
          @if($category)
            <span class="admin-badge admin-badge-muted">
              Kategori: {{ $category }}
              <a href="{{ route('admin.posts', request()->except('category')) }}" class="text-muted ms-1" style="text-decoration: none;">&times;</a>
            </span>
          @endif
          @if($status)
            <span class="admin-badge admin-badge-muted">
              Status: {{ $status === 'online' ? 'Published' : 'Draft' }}
              <a href="{{ route('admin.posts', request()->except('status')) }}" class="text-muted ms-1" style="text-decoration: none;">&times;</a>
            </span>
          @endif
          @if($start_date || $end_date)
            <span class="admin-badge admin-badge-muted">
              Rentang: {{ $start_date ?: 'Awal' }} s/d {{ $end_date ?: 'Sekarang' }}
              <a href="{{ route('admin.posts', request()->except(['start_date', 'end_date'])) }}" class="text-muted ms-1" style="text-decoration: none;">&times;</a>
            </span>
          @endif
          @if($sort && $sort !== 'newest')
            <span class="admin-badge admin-badge-muted">
              Urutan: {{ $sort }}
              <a href="{{ route('admin.posts', request()->except('sort')) }}" class="text-muted ms-1" style="text-decoration: none;">&times;</a>
            </span>
          @endif
          <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-ghost admin-btn-sm ms-auto text-danger" style="height: 26px; padding: 0 10px; font-size: 0.74rem;">
            <i data-lucide="x"></i> Reset Semua Filter
          </a>
        </div>
      @endif

    </form>
  </div>

  {{-- Table Body --}}
  <div class="admin-card-body-flush">
    @if(count($posts) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 40px; text-align: center;">
                <input type="checkbox" class="form-check-input select-all-checkbox" style="cursor: pointer;" title="Pilih Semua">
              </th>
              <th style="width: 76px; text-align: center;">Cover</th>
              <th>Judul & Info Artikel</th>
              <th style="width: 140px;">Kategori</th>
              <th style="width: 140px;">Status</th>
              <th style="width: 140px;">Tanggal</th>
              <th style="text-align: right; width: 120px; padding-right: 20px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($posts as $post)
              @php
                $thumb = $post['image'] ?? null;
                if ($thumb && !str_starts_with($thumb, 'http') && !str_starts_with($thumb, 'data:')) {
                  $thumbSrc = asset(ltrim($thumb, '/'));
                } else {
                  $thumbSrc = $thumb;
                }

                $catLower = strtolower($post['category'] ?? '');
                $catBadge = match(true) {
                  str_contains($catLower, 'berita') => 'admin-badge-info',
                  str_contains($catLower, 'event') || str_contains($catLower, 'seminar') => 'admin-badge-accent',
                  str_contains($catLower, 'iptek') || str_contains($catLower, 'ilmiah') => 'admin-badge-success',
                  default => 'admin-badge-muted',
                };

                $rawDate = $post['date'] ?? $post['created_at'] ?? null;
                $dateDisplay = '-';
                if ($rawDate) {
                  try {
                    $dateDisplay = \Carbon\Carbon::parse($rawDate)->translatedFormat('d M Y');
                  } catch (\Throwable $e) {
                    $dateDisplay = (string) $rawDate;
                  }
                }
              @endphp
              <tr>
                <td style="text-align: center;">
                  <input type="checkbox" value="{{ $post['id'] }}" class="form-check-input row-checkbox" style="cursor: pointer;">
                </td>
                {{-- Cover Thumbnail --}}
                <td style="text-align: center; vertical-align: middle;">
                  <div class="admin-post-thumb mx-auto">
                    @if($thumbSrc)
                      <img src="{{ $thumbSrc }}" alt="{{ $post['title'] }}" loading="lazy"
                           onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; color: var(--color-text-muted);">
                        <i data-lucide="image" style="width: 18px; height: 18px; opacity: 0.5;"></i>
                      </div>
                    @else
                      <div class="d-flex align-items-center justify-content-center w-100 h-100" style="color: var(--color-text-muted);">
                        <i data-lucide="newspaper" style="width: 18px; height: 18px; opacity: 0.45;"></i>
                      </div>
                    @endif
                  </div>
                </td>

                {{-- Judul & Info --}}
                <td>
                  <a href="{{ route('admin.posts.edit', ['slug' => $post['slug']]) }}"
                     class="cell-title d-inline-block text-decoration-none"
                     style="color: var(--color-text-main); font-size: 0.92rem; line-height: 1.35; transition: color 0.15s ease;"
                     onmouseover="this.style.color='var(--color-accent)'"
                     onmouseout="this.style.color='var(--color-text-main)'">
                    {{ $post['title'] }}
                  </a>
                  <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                    <span class="cat-key-badge" title="Slug URL">
                      <i data-lucide="link" style="width: 10px; height: 10px; opacity: 0.7;"></i>
                      /{{ $post['slug'] }}
                    </span>
                  </div>
                </td>

                {{-- Kategori --}}
                <td>
                  <span class="admin-badge {{ $catBadge }}">
                    <i data-lucide="tag" style="width: 11px; height: 11px;"></i>
                    {{ $post['category'] ?: 'Uncategorized' }}
                  </span>
                </td>

                {{-- Status --}}
                <td>
                  <div class="d-inline-flex flex-column gap-1 align-items-start">
                    @if(($post['status'] ?? 'online') === 'online')
                      <span class="admin-badge admin-badge-success">
                        <i data-lucide="check-circle-2" style="width: 12px; height: 12px;"></i> Published
                      </span>
                    @else
                      <span class="admin-badge admin-badge-warning">
                        <i data-lucide="clock" style="width: 12px; height: 12px;"></i> Draft
                      </span>
                    @endif

                    @if(!empty($post['is_featured']))
                      <span class="admin-badge admin-badge-warning" style="background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;" title="Artikel Unggulan">
                        <i data-lucide="star" style="width: 11px; height: 11px; fill: currentColor;"></i> Unggulan
                      </span>
                    @endif
                  </div>
                </td>

                {{-- Tanggal --}}
                <td>
                  <div class="d-inline-flex align-items-center gap-1" style="font-size: 0.82rem; color: var(--color-text-secondary); white-space: nowrap;">
                    <i data-lucide="calendar" style="width: 13px; height: 13px; color: var(--color-text-muted);"></i>
                    <span>{{ $dateDisplay }}</span>
                  </div>
                </td>

                {{-- Aksi --}}
                <td style="text-align: right; padding-right: 20px; white-space: nowrap;">
                  <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                    <a href="{{ url('/informasi') }}?detail={{ urlencode($post['slug']) }}" target="_blank"
                       class="admin-action-link view" title="Lihat di Web">
                      <i data-lucide="external-link"></i>
                    </a>
                    <a href="{{ route('admin.posts.edit', ['slug' => $post['slug']]) }}"
                       class="admin-action-link edit" title="Edit Artikel">
                      <i data-lucide="file-edit"></i>
                    </a>
                    <form action="{{ route('admin.posts.destroy', ['slug' => $post['slug']]) }}" method="POST"
                          class="form-delete-post" data-name="{{ $post['title'] }}" style="display: contents;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="admin-action-link delete" title="Hapus Artikel">
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

      {{-- Pagination Footer --}}
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-top: 1px solid var(--color-border); flex-wrap: wrap; gap: 12px;">
        <span style="font-size: 0.78rem; color: var(--color-text-muted);">
          Halaman <strong style="color: var(--color-text-main);">{{ $currentPage }}</strong> dari <strong style="color: var(--color-text-main);">{{ $totalPages }}</strong>
          @if(isset($totalPosts))
            <span class="text-muted">•</span> Total <strong style="color: var(--color-text-main);">{{ $totalPosts }}</strong> artikel
          @endif
        </span>

        @if($totalPages > 1)
          <nav aria-label="Navigasi halaman">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" aria-label="Sebelumnya">
                  <i data-lucide="chevron-left"></i>
                </a>
              </li>
              @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                </li>
              @endfor
              <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" aria-label="Berikutnya">
                  <i data-lucide="chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        @endif
      </div>

    @else
      @if($hasActiveFilters)
        <div class="text-center py-5 px-3">
          <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--color-surface-2); display: inline-flex; align-items: center; justify-content: center; color: var(--color-text-muted); margin-bottom: 12px;">
            <i data-lucide="inbox" style="width: 24px; height: 24px;"></i>
          </div>
          <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-main); margin-bottom: 4px;">Tidak ada artikel yang cocok</h4>
          <p style="font-size: 0.84rem; color: var(--color-text-muted); margin-bottom: 16px;">
            Coba sesuaikan kata kunci pencarian, ubah kategori, atau reset filter yang dipilih.
          </p>
          <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-outline admin-btn-sm">
            <i data-lucide="x"></i> Reset Semua Filter
          </a>
        </div>
      @else
        <x-admin.empty-state
          icon="newspaper"
          message="Belum ada artikel atau berita yang dibuat."
          :action-url="route('admin.posts.create')"
          action-label="Tulis Artikel Pertama" />
      @endif
    @endif
  </div>

</div>

<x-admin.bulk-action-bar :route="route('admin.posts.bulk-destroy')" label="artikel" />

@endsection

@section('admin_scripts')
<script @nonce>
  const sg = document.getElementById('search-group');
  const si = document.getElementById('local-search-input');
  if (sg && si) {
    si.addEventListener('focus', () => sg.style.borderColor = 'var(--color-accent)');
    si.addEventListener('blur',  () => sg.style.borderColor = 'var(--color-border)');
  }

  document.querySelectorAll('.form-delete-post').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Hapus Artikel?',
          html: `Hapus "<strong>${name}</strong>"?<br><small class="text-muted">Artikel yang dihapus tidak dapat dipulihkan kembali.</small>`,
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
        }).then(r => { if (r.isConfirmed) this.submit(); });
      } else {
        if (confirm(`Hapus "${name}"? Tidak bisa dibatalkan.`)) {
          this.submit();
        }
      }
    });
  });
</script>
@endsection
