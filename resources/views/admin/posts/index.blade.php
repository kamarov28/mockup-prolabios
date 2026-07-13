@extends('admin.layout')

@section('title', 'Kelola Artikel')
@section('page_title', 'Manajemen Berita & Artikel')

@section('admin_content')
<div class="card bg-white shadow-sm mb-4">
  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-newspaper text-success me-2"></i>Daftar Artikel Diterbitkan</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg me-1"></i>Tulis Artikel Baru</a>
  </div>
  <div class="card-body p-0">
    @if(count($posts) > 0)
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Judul Artikel</th>
              <th>Kategori</th>
              <th>Tanggal Rilis</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($posts as $post)
              <tr>
                <td>
                  <div class="rounded border overflow-hidden bg-light" style="width: 70px; height: 50px;">
                    <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-100 h-100" style="object-fit: cover;">
                  </div>
                </td>
                <td>
                  <div class="fw-bold text-dark">{{ $post['title'] }}</div>
                  <div class="text-muted small">Slug: <code class="text-danger">{{ $post['slug'] }}</code></div>
                </td>
                <td>
                  <span class="badge bg-light text-success border text-capitalize">
                    {{ $post['category'] }}
                  </span>
                </td>
                <td class="text-muted small fw-semibold">
                  <i class="bi bi-calendar3 me-1"></i> {{ $post['date'] }}
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-2 pe-3">
                    <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat di Web"><i class="bi bi-eye"></i></a>
                    <button type="button" class="btn btn-sm btn-outline-info btn-copy-link" data-url="{{ url('/informasi') }}?detail={{ $post['slug'] }}" title="Salin Tautan"><i class="bi bi-clipboard"></i></button>
                    <a href="{{ route('admin.posts.edit', ['slug' => $post['slug']]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                    <form action="{{ route('admin.posts.destroy', ['slug' => $post['slug']]) }}" method="POST" class="form-delete" data-name="{{ $post['title'] }}">
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
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 border-top">
          <div class="text-muted small">
            Menampilkan Halaman <strong>{{ $currentPage }}</strong> dari <strong>{{ $totalPages }}</strong>
          </div>
          <nav aria-label="Navigasi Halaman Artikel">
            <ul class="pagination pagination-sm mb-0">
              <!-- Prev Button -->
              <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', ['page' => $currentPage - 1]) }}" aria-label="Sebelumnya">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              
              <!-- Page List -->
              @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                  <a class="page-link" href="{{ route('admin.posts', ['page' => $i]) }}">{{ $i }}</a>
                </li>
              @endfor
              
              <!-- Next Button -->
              <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="{{ route('admin.posts', ['page' => $currentPage + 1]) }}" aria-label="Berikutnya">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      @endif
    @else
      <div class="text-center py-5">
        <i class="bi bi-newspaper display-3 text-muted opacity-50 mb-3"></i>
        <h5 class="fw-bold">Belum Ada Artikel</h5>
        <p class="text-muted">Klik tombol di atas untuk menerbitkan artikel/berita pertama Anda.</p>
      </div>
    @endif
  </div>
</div>

@section('admin_scripts')
<script>
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      Swal.fire({
        title: 'Hapus Artikel?',
        text: `Apakah Anda yakin ingin menghapus artikel "${name}"? Tindakan ini tidak dapat dibatalkan!`,
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
@endsection
