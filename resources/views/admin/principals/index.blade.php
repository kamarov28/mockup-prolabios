@extends('admin.layout')

@section('title', 'Kelola Prinsipal & Mitra')
@section('page_title', 'Prinsipal / Mitra')

@section('admin_content')

<div class="admin-card">

  {{-- Header --}}
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Konten & Partner</span>
      <h2 class="admin-card-header-title">Daftar Prinsipal / Brand Mitra</h2>
    </div>
    <a href="{{ route('admin.principals.create') }}" class="admin-btn admin-btn-primary">
      <i class="bi bi-plus-lg"></i> Tambah Prinsipal Baru
    </a>
  </div>

  {{-- Filter Form --}}
  <div class="admin-card-body" style="border-bottom: 1px solid var(--color-border);">
    <form action="{{ route('admin.principals') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-6">
          <div style="display: flex; border: 1px solid var(--color-border); border-radius: 6px; overflow: hidden;" id="search-group">
            <span style="display: flex; align-items: center; padding: 0 12px; color: var(--color-text-muted);">
              <i class="bi bi-search" style="font-size: 0.8rem;"></i>
            </span>
            <input type="text" name="s" id="local-search-input"
                   style="flex: 1; background: transparent; border: none; outline: none; padding: 10px 14px; color: var(--color-text-main); font-size: 0.88rem;"
                   placeholder="Cari nama prinsipal atau negara..." value="{{ $search }}">
          </div>
        </div>
        <div class="col-md-2">
          <button type="submit" class="admin-btn admin-btn-primary w-100 justify-content-center">
            <i class="bi bi-funnel-fill"></i> Cari
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- Table --}}
  <div class="admin-card-body-flush">
    @if(count($principals) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Nama Prinsipal / Brand</th>
              <th>Negara / Alamat</th>
              <th>Logo</th>
              <th>Status</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($principals as $index => $p)
              @php
                $logoPath = $p->logo;
                if (!empty($logoPath) && !file_exists(public_path(ltrim($logoPath, '/')))) {
                    if (str_contains(strtolower($p->name), 'bioendo') && file_exists(public_path('images/vendor/Bioendo-labs.png'))) {
                        $logoPath = '/images/vendor/Bioendo-labs.png';
                    }
                }
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <strong style="color: var(--color-text-main);">{{ $p->name }}</strong>
                </td>
                <td class="cell-muted">{{ $p->address ?: '—' }}</td>
                <td>
                  <div style="width: 110px; height: 44px; padding: 6px; background: #ffffff; border: 1px solid var(--color-border); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $logoPath ?: 'https://via.placeholder.com/120x40?text=No+Logo' }}" alt="{{ $p->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                  </div>
                </td>
                <td>
                  @if($p->status === 'online')
                    <span class="admin-badge admin-badge-success">
                      Online
                    </span>
                  @else
                    <span class="admin-badge admin-badge-muted">
                      Draft / Sembunyi
                    </span>
                  @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <a href="{{ route('admin.principals.edit', $p->id) }}" class="admin-action-link edit" title="Edit">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <form action="{{ route('admin.principals.destroy', $p->id) }}" method="POST" class="d-inline form-delete" data-name="{{ $p->name }}">
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
        <i class="bi bi-award" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
        <p style="font-size: 0.88rem;">Belum ada data prinsipal.</p>
        <a href="{{ route('admin.principals.create') }}" class="admin-btn admin-btn-primary">Tambah Prinsipal</a>
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
        title: 'Hapus Prinsipal?',
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
