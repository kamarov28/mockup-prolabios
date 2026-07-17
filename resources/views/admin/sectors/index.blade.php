@extends('admin.layout')

@section('title', 'Kelola Sektor')
@section('page_title', 'Sektor')

@section('admin_content')

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Konten</span>
      <h2 class="admin-card-header-title">Sektor Industri</h2>
    </div>
    <a href="{{ route('admin.sectors.create') }}" class="admin-btn admin-btn-primary">
      <i class="bi bi-plus-lg"></i> Tambah Sektor
    </a>
  </div>

  <div class="admin-card-body-flush">
    @if(count($sectors) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 160px;">ID Sektor</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sectors as $sec)
              <tr>
                <td><code>{{ $sec['id'] }}</code></td>
                <td class="cell-title">{{ $sec['name'] }}</td>
                <td class="cell-muted" style="max-width: 460px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  @if(count($sec['description'] ?? []) > 0)
                    {{ implode(' ', $sec['description']) }}
                  @else
                    <em>Tidak ada deskripsi</em>
                  @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <a href="{{ route('admin.sectors.edit', ['id' => $sec['id']]) }}" class="admin-action-link edit" title="Edit">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <form action="{{ route('admin.sectors.destroy', ['id' => $sec['id']]) }}" method="POST" class="d-inline form-delete" data-name="{{ $sec['name'] }}">
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
    @else
      <div class="text-center py-5" style="color: var(--color-text-muted);">
        <i class="bi bi-layers" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        <p style="font-size: 0.88rem;">Belum ada sektor industri.</p>
        <a href="{{ route('admin.sectors.create') }}" class="admin-btn admin-btn-primary">Tambah Sekarang</a>
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
        title: 'Hapus Sektor?',
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
