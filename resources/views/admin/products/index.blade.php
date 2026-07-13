@extends('admin.layout')

@section('title', 'Kelola Produk')
@section('page_title', 'Manajemen Katalog Produk')

@section('admin_content')
<div class="card bg-white shadow-sm mb-4">
  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-box-seam text-danger me-2"></i>Daftar Produk</h2>
    <div class="d-inline-flex gap-2">
      <a href="{{ route('admin.products.create.bulk') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-grid-3x3-gap me-1"></i>Tambah Banyak Produk</a>
      <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-danger"><i class="bi bi-plus-lg me-1"></i>Tambah Produk Baru</a>
    </div>
  </div>
  <div class="card-body">
    <!-- Filter Form -->
    <form action="{{ route('admin.products') }}" method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="s" class="form-control bg-light border-start-0" placeholder="Cari catalog, judul, deskripsi..." value="{{ $search }}" aria-label="Kata kunci pencarian">
        </div>
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select bg-light" aria-label="Filter berdasarkan Kategori">
          <option value="">-- Semua Kategori --</option>
          <option value="culture-media" {{ $category === 'culture-media' ? 'selected' : '' }}>Culture Media</option>
          <option value="instruments" {{ $category === 'instruments' ? 'selected' : '' }}>Instruments</option>
          <option value="chemicals" {{ $category === 'chemicals' ? 'selected' : '' }}>Chemicals &amp; Reagents</option>
          <option value="consumables" {{ $category === 'consumables' ? 'selected' : '' }}>Consumables</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="sector" class="form-select bg-light" aria-label="Filter berdasarkan Sektor Industri">
          <option value="">-- Semua Sektor --</option>
          @foreach($sectors as $sec)
            <option value="{{ $sec['id'] }}" {{ $sector === $sec['id'] ? 'selected' : '' }}>{{ $sec['name'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
      </div>
    </form>

    <!-- Table -->
    @if(count($products) > 0)
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Katalog</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Sektor Industri</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $p)
              <tr>
                <td>
                  <div class="rounded border bg-light text-center p-1" style="width: 50px; height: 50px;">
                    <img src="{{ $p['image'] }}" alt="{{ $p['title'] }}" class="w-100 h-100" style="object-fit: contain;">
                  </div>
                </td>
                <td class="text-muted small fw-semibold">{{ $p['catalog'] ?: '-' }}</td>
                <td>
                  <div class="fw-bold text-dark">{{ $p['title'] }}</div>
                  <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $p['description'] ?: 'Tidak ada deskripsi' }}</div>
                </td>
                <td>
                  <span class="badge bg-light text-dark border text-capitalize">
                    {{ str_replace('-', ' ', $p['category']) }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-light text-secondary border text-capitalize">
                    {{ str_replace('-', ' ', $p['sector'] ?: 'Umum') }}
                  </span>
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-2">
                    <a href="{{ url('/produk/detail') }}?id={{ urlencode($p['title']) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat di Web"><i class="bi bi-eye"></i></a>
                    <button type="button" class="btn btn-sm btn-outline-info btn-copy-link" data-url="{{ url('/produk/detail') }}?id={{ urlencode($p['title']) }}" title="Salin Tautan"><i class="bi bi-clipboard"></i></button>
                    <a href="{{ route('admin.products.edit', ['title' => $p['title']]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                    <form action="{{ route('admin.products.destroy', ['title' => $p['title']]) }}" method="POST" class="form-delete" data-name="{{ $p['title'] }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      @if($totalPages > 1)
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-3 border-top mt-3">
          <div class="text-muted small">
            Menampilkan Halaman <strong>{{ $currentPage }}</strong> dari <strong>{{ $totalPages }}</strong>
          </div>
          <nav aria-label="Navigasi Halaman">
            <ul class="pagination pagination-sm mb-0">
              <!-- Prev Button -->
              <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.products', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" aria-label="Sebelumnya">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              
              <!-- Page List -->
              @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ route('admin.products', array_merge(request()->query(), ['page' => $i])) }}">{{ $i }}</a>
                </li>
              @endfor
              
              <!-- Next Button -->
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
      <div class="text-center py-5">
        <i class="bi bi-box-seam display-3 text-muted opacity-50 mb-3"></i>
        <h5 class="fw-bold">Produk Tidak Ditemukan</h5>
        <p class="text-muted">Cobalah mengubah kueri pencarian atau filter kategori/sektor Anda.</p>
        <a href="{{ route('admin.products') }}" class="btn btn-sm btn-secondary">Reset Pencarian</a>
      </div>
    @endif
  </div>
</div>
@endsection

@section('admin_scripts')
<script>
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      Swal.fire({
        title: 'Hapus Produk?',
        text: `Apakah Anda yakin ingin menghapus produk "${name}"? Tindakan ini tidak dapat dibatalkan!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#D32F2F',
        cancelButtonColor: '#6C757D',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>
@endsection
