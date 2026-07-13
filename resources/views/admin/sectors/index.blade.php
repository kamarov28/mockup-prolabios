@extends('admin.layout')

@section('title', 'Kelola Sektor')
@section('page_title', 'Manajemen Sektor Industri')

@section('admin_content')
<div class="card bg-white shadow-sm mb-4">
  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-collection text-info me-2"></i>Daftar Sektor Industri</h2>
    <a href="{{ route('admin.sectors.create') }}" class="btn btn-sm text-white" style="background-color: #005a70;"><i class="bi bi-plus-lg me-1"></i>Tambah Sektor Baru</a>
  </div>
  <div class="card-body p-0">
    @if(count($sectors) > 0)
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 150px;">ID Sektor</th>
              <th>Nama Sektor</th>
              <th>Deskripsi Sektor</th>
              <th class="text-end" style="width: 200px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sectors as $sec)
              <tr>
                <td class="text-muted small fw-semibold"><code class="text-danger">{{ $sec['id'] }}</code></td>
                <td><span class="fw-bold text-dark">{{ $sec['name'] }}</span></td>
                <td>
                  @if(count($sec['description'] ?? []) > 0)
                    <div class="text-muted small text-truncate" style="max-width: 450px;">
                      {{ implode(' ', $sec['description']) }}
                    </div>
                  @else
                    <span class="text-muted small italic">Tidak ada deskripsi</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-inline-flex gap-2 pe-3">
                    <a href="{{ route('admin.sectors.edit', ['id' => $sec['id']]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                    <form action="{{ route('admin.sectors.destroy', ['id' => $sec['id']]) }}" method="POST" class="form-delete" data-name="{{ $sec['name'] }}">
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
    @else
      <div class="text-center py-5">
        <i class="bi bi-collection display-3 text-muted opacity-50 mb-3"></i>
        <h5 class="fw-bold">Belum Ada Sektor</h5>
        <p class="text-muted">Klik tombol di atas untuk menambahkan sektor industri pertama Anda.</p>
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
        title: 'Hapus Sektor?',
        text: `Apakah Anda yakin ingin menghapus sektor industri "${name}"? Tindakan ini tidak dapat dibatalkan!`,
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
