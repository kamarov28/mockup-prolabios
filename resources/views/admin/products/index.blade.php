@extends('admin.layout')

@section('title', 'Kelola Produk')
@section('page_title', 'Produk')

@section('admin_content')

<div class="admin-card">

  {{-- Header --}}
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Katalog</span>
      <h2 class="admin-card-header-title">Daftar Produk</h2>
    </div>
    <div class="d-inline-flex gap-2">
      <a href="{{ route('admin.products.create.bulk') }}" class="admin-btn admin-btn-ghost">
        <i class="bi bi-grid-3x3-gap"></i> Bulk
      </a>
      <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah
      </a>
    </div>
  </div>

  {{-- Filter Form --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.products') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-4">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden; transition: border-color 0.25s ease;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted); background: transparent; border-right: 1px solid var(--color-border);">
              <i class="bi bi-search" style="font-size: 0.8rem;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main); font-family: var(--font-body); font-size: 0.88rem;"
                   placeholder="Cari nama, katalog..." value="{{ $search }}" aria-label="Kata kunci pencarian">
          </div>
        </div>
        <div class="col-md-3">
          <select name="category" class="form-select" aria-label="Filter Kategori">
            <option value="">Semua Kategori</option>
            <option value="microbiology"       {{ $category === 'microbiology' ? 'selected' : '' }}>Microbiology</option>
            <option value="reference-standards" {{ $category === 'reference-standards' ? 'selected' : '' }}>Reference Standards</option>
            <option value="device"             {{ $category === 'device' ? 'selected' : '' }}>Device</option>
            <option value="instruments"        {{ $category === 'instruments' ? 'selected' : '' }}>Instruments</option>
            <option value="culture-media"      {{ $category === 'culture-media' ? 'selected' : '' }}>Culture Media</option>
          </select>
        </div>
        <div class="col-md-3">
          <select name="sector" class="form-select" aria-label="Filter Sektor">
            <option value="">Semua Sektor</option>
            @foreach($sectors as $sec)
              <option value="{{ $sec['id'] }}" {{ $sector === $sec['id'] ? 'selected' : '' }}>{{ $sec['name'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" class="admin-btn admin-btn-ghost w-100 justify-content-center"
                  data-bs-toggle="collapse" data-bs-target="#advancedFilterBlock"
                  aria-expanded="{{ ($sort !== 'newest' || $start_date || $end_date) ? 'true' : 'false' }}"
                  aria-controls="advancedFilterBlock">
            <i class="bi bi-sliders"></i> Filter
          </button>
        </div>
      </div>

      {{-- Advanced Collapse --}}
      <div class="collapse {{ ($sort !== 'newest' || $start_date || $end_date) ? 'show' : '' }} mt-3" id="advancedFilterBlock">
        <div style="border: 1px solid var(--color-border); border-radius: 6px; padding: 16px;">
          <div class="row g-3 align-items-end">
            <div class="col-md-4">
              <label class="admin-form-label" for="sort">Urutkan</label>
              <select name="sort" id="sort" class="form-select">
                <option value="newest"    {{ $sort === 'newest' ? 'selected' : '' }}>Terbaru Dibuat</option>
                <option value="oldest"    {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama Dibuat</option>
                <option value="name_asc"  {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama (A–Z)</option>
                <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama (Z–A)</option>
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
    @if(count($products) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Katalog</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Sektor</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $p)
              <tr>
                <td>
                  <div style="width: 44px; height: 44px; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden; background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                  </div>
                </td>
                <td class="cell-code">{{ $p['catalog'] ?: '—' }}</td>
                <td>
                  <div class="cell-title">{{ $p['title'] }}</div>
                  <div class="cell-muted" style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ strip_tags(html_entity_decode($p['description'] ?? '')) ?: 'Tidak ada deskripsi' }}
                  </div>
                </td>
                <td><span class="admin-badge admin-badge-muted text-capitalize">{{ str_replace('-', ' ', $p['category']) }}</span></td>
                <td><span class="admin-badge admin-badge-muted text-capitalize">{{ str_replace('-', ' ', $p['sector'] ?: 'Umum') }}</span></td>
                <td style="text-align: right; white-space: nowrap;">
                  <a href="{{ url('/produk/detail') }}?id={{ urlencode($p['title']) }}" target="_blank" class="admin-action-link view" title="Lihat">
                    <i class="bi bi-eye"></i>
                  </a>
                  <button type="button" class="admin-action-link btn-copy-link" data-url="{{ url('/produk/detail') }}?id={{ urlencode($p['title']) }}" title="Salin link">
                    <i class="bi bi-clipboard"></i>
                  </button>
                  <a href="{{ route('admin.products.edit', ['title' => $p['title']]) }}" class="admin-action-link edit" title="Edit">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <form action="{{ route('admin.products.destroy', ['title' => $p['title']]) }}" method="POST" class="d-inline form-delete" data-name="{{ $p['title'] }}">
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
          <nav aria-label="Navigasi halaman">
            <ul class="pagination pagination-sm mb-0">
              <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.products', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" aria-label="Sebelumnya">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ route('admin.products', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                </li>
              @endfor
              <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.products', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" aria-label="Berikutnya">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      @endif

    @else
      <div class="text-center py-5" style="color: var(--color-text-muted);">
        <i class="bi bi-box-seam" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        <p style="font-size: 0.88rem;">Produk tidak ditemukan. Coba ubah filter atau kata kunci pencarian.</p>
        <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-ghost">Reset Filter</a>
      </div>
    @endif
  </div>

</div>
@endsection

@section('admin_scripts')
<script>
  // Focus style on search group
  const sg = document.getElementById('search-group');
  const si = document.getElementById('local-search-input');
  if (sg && si) {
    si.addEventListener('focus', () => sg.style.borderColor = 'var(--color-accent)');
    si.addEventListener('blur',  () => sg.style.borderColor = 'var(--color-border)');
  }

  // Delete confirmations
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      Swal.fire({
        title: 'Hapus Produk?',
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
