@extends('admin.layout')

@section('title', 'Kelola Artikel')
@section('page_title', 'Artikel')

@section('admin_content')

<div class="admin-card">

  {{-- Header --}}
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Konten</span>
      <h2 class="admin-card-header-title">Daftar Artikel</h2>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">
      <i class="bi bi-plus-lg"></i> Tulis Artikel
    </a>
  </div>

  {{-- Filter Form --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.posts') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-5">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden; transition: border-color 0.25s ease;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); background: transparent; border-right: 1px solid var(--color-border);">
              <i class="bi bi-search" style="font-size: 0.8rem;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main); font-family: var(--font-body); font-size: 0.88rem;"
                   placeholder="Cari judul atau isi artikel..." value="{{ $search }}" aria-label="Kata kunci pencarian">
          </div>
        </div>
        <div class="col-md-4">
          <select name="category" class="form-select" aria-label="Filter Kategori">
            <option value="">Semua Kategori</option>
            <option value="Berita"   {{ $category === 'Berita' ? 'selected' : '' }}>Berita</option>
            <option value="Kegiatan" {{ $category === 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
            <option value="Event"    {{ $category === 'Event' ? 'selected' : '' }}>Event</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="button" class="admin-btn admin-btn-ghost w-100 justify-content-center"
                  data-bs-toggle="collapse" data-bs-target="#advancedPostFilterBlock"
                  aria-expanded="{{ ($sort !== 'newest' || $start_date || $end_date) ? 'true' : 'false' }}"
                  aria-controls="advancedPostFilterBlock">
            <i class="bi bi-sliders"></i> Filter Lanjutan
          </button>
        </div>
      </div>

      {{-- Advanced Collapse --}}
      <div class="collapse {{ ($sort !== 'newest' || $start_date || $end_date) ? 'show' : '' }} mt-3" id="advancedPostFilterBlock">
        <div style="border: 1px solid var(--color-border); border-radius: 6px; padding: 16px;">
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="admin-form-label" for="sort">Urutkan</label>
              <select name="sort" id="sort" class="form-select">
                <option value="newest"     {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru Diterbitkan</option>
                <option value="oldest"     {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama Diterbitkan</option>
                <option value="title_asc"  {{ $sort === 'title_asc' ? 'selected' : '' }}>Judul (A–Z)</option>
                <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>Judul (Z–A)</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="admin-form-label" for="start_date">Dari Tanggal</label>
              <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $start_date }}">
            </div>
            <div class="col-md-3">
              <label class="admin-form-label" for="end_date">Sampai Tanggal</label>
              <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $end_date }}">
            </div>
            <div class="col-md-2">
              <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
                <i class="bi bi-funnel-fill"></i> Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="admin-card-body-flush">
    @if(count($posts) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Judul Artikel</th>
              <th>Kategori</th>
              <th>Status Publish</th>
              <th>Tanggal</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($posts as $post)
              <tr>
                <td>
                  <div style="width: 60px; height: 42px; border: 1px solid var(--color-border); border-radius: 5px; overflow: hidden; background: rgba(255,255,255,0.02);">
                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                </td>
                <td>
                  <div class="cell-title">{{ $post['title'] }}</div>
                  <div class="cell-muted">slug: <code>{{ $post['slug'] }}</code></div>
                </td>
                <td><span class="admin-badge admin-badge-muted text-capitalize">{{ $post['category'] }}</span></td>
                <td>
                  <div class="d-flex flex-column gap-1 align-items-start">
                    @if(($post['status'] ?? 'online') === 'online')
                      <span class="admin-badge admin-badge-success">
                        Published
                      </span>
                    @else
                      <span class="admin-badge admin-badge-warning">
                        Draft / Offline
                      </span>
                    @endif
                    @if(!empty($post['is_featured']))
                      <span class="admin-badge admin-badge-warning">
                        <i class="bi bi-star-fill me-1"></i> Highlight
                      </span>
                    @endif
                  </div>
                </td>
                <td class="cell-muted" style="white-space: nowrap;">
                  <i class="bi bi-calendar3 me-1"></i>{{ $post['date'] }}
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}" target="_blank" class="admin-action-link view" title="Lihat">
                    <i class="bi bi-eye"></i>
                  </a>
                  <button type="button" class="admin-action-link btn-copy-link" data-url="{{ url('/informasi') }}?detail={{ $post['slug'] }}" title="Salin link">
                    <i class="bi bi-clipboard"></i>
                  </button>
                  <a href="{{ route('admin.posts.edit', ['slug' => $post['slug']]) }}" class="admin-action-link edit" title="Edit">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <form action="{{ route('admin.posts.destroy', ['slug' => $post['slug']]) }}" method="POST" class="d-inline form-delete" data-name="{{ $post['title'] }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-action-link delete" title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($totalPages > 1)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid var(--color-border);">
          <span style="font-size: 0.72rem; color: var(--color-text-muted); letter-spacing: 0.5px;">
            Halaman <strong style="color: var(--color-text-main);">{{ $currentPage }}</strong> dari <strong style="color: var(--color-text-main);">{{ $totalPages }}</strong>
          </span>
          <nav aria-label="Navigasi halaman artikel">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" aria-label="Sebelumnya">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                </li>
              @endfor
              <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" aria-label="Berikutnya">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      @endif

    @else
      <div class="text-center py-5" style="color: var(--color-text-muted);">
        <i class="bi bi-file-text" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        <p style="font-size: 0.88rem;">Belum ada artikel diterbitkan.</p>
        <a href="{{ route('admin.posts.create') }}" class="admin-btn admin-btn-primary">Tulis Sekarang</a>
      </div>
    @endif
  </div>

</div>
@endsection

@section('admin_scripts')
<script>
  const sg = document.getElementById('search-group');
  const si = document.getElementById('local-search-input');
  if (sg && si) {
    si.addEventListener('focus', () => sg.style.borderColor = 'var(--color-accent)');
    si.addEventListener('blur',  () => sg.style.borderColor = 'var(--color-border)');
  }

  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      Swal.fire({
        title: 'Hapus Artikel?',
        html: `Hapus "<strong>${name}</strong>"? Tidak bisa dibatalkan.`,
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
    });
  });
</script>
@endsection
