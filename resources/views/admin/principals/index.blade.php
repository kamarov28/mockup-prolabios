@extends('admin.layout')

@section('title', 'Kelola Prinsipal & Mitra')
@section('page_title', 'Prinsipal / Mitra')

@section('admin_content')

<x-admin.page-header
  label="Konten & Partner"
  title="Daftar Prinsipal / Brand Mitra"
  description="Kelola data manufaktur prinsipal, brand instrumen, dan mitra resmi laboratorium."
  action-url="{{ route('admin.principals.create') }}"
  action-text="Tambah Prinsipal Baru"
  action-icon="plus"
/>

<div class="admin-card">

  <div class="admin-card-body p-3" style="border-bottom: 1px solid var(--color-border); background: #FFFFFF;">
    <form action="{{ route('admin.principals') }}" method="GET">
      <div class="row g-2 align-items-center">
        <div class="col-md-5 col-lg-4">
          <x-admin.search-input name="s" :value="$search" placeholder="Cari nama prinsipal atau negara..." :clear-url="route('admin.principals')" />
        </div>
        <div class="col-auto">
          <button type="submit" class="admin-btn admin-btn-primary" style="height: 38px;">
            <i data-lucide="search"></i> Cari
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card-body-flush">
    @if(count($principals) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 40px; text-align: center;">
                <input type="checkbox" class="form-check-input select-all-checkbox" style="cursor: pointer;" title="Pilih Semua">
              </th>
              <th style="width: 50px;">No</th>
              <th>Nama Prinsipal / Brand</th>
              <th>Negara / Alamat</th>
              <th>Logo</th>
              <th>Status</th>
              <th style="text-align: right; padding-right: 24px; width: 120px;">Aksi</th>
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
                <td style="text-align: center;">
                  <input type="checkbox" value="{{ $p->id }}" class="form-check-input row-checkbox" style="cursor: pointer;">
                </td>
                <td>{{ $index + 1 }}</td>
                <td>
                  <strong style="color: var(--color-text-main);">{{ $p->name }}</strong>
                </td>
                <td class="cell-muted">{{ $p->address ?: '—' }}</td>
                <td>
                  <div style="width: 110px; height: 44px; padding: 6px; background: #ffffff; border: 1px solid var(--color-border); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $logoPath ?: asset('images/placeholder.svg') }}" alt="{{ $p->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                  </div>
                </td>
                <td>
                  @if($p->status === 'online')
                    <span class="admin-badge admin-badge-success">Online</span>
                  @else
                    <span class="admin-badge admin-badge-muted">Draft / Sembunyi</span>
                  @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                  <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                    <a href="{{ route('admin.principals.edit', $p->id) }}"
                       class="admin-action-link edit" title="Edit">
                      <i data-lucide="file-edit"></i>
                    </a>
                    <form action="{{ route('admin.principals.destroy', $p->id) }}" method="POST"
                          class="form-delete" data-name="{{ $p->name }}"
                          style="display: contents;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="admin-action-link delete" title="Hapus">
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
    @else
      <x-admin.empty-state 
        icon="award" 
        message="Belum ada data prinsipal." 
        :action-url="route('admin.principals.create')" 
        action-label="Tambah Prinsipal" />
    @endif
  </div>

</div>

<x-admin.bulk-action-bar :route="route('admin.principals.bulk-destroy')" label="prinsipal" />

@endsection

@section('admin_scripts')
<script @nonce>
  document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function (e) {
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
