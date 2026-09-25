@extends('admin.layout')

@section('title', 'Kelola Sektor Industri')
@section('page_title', 'Sektor Industri')

@section('admin_content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Katalog & Klasifikasi</span>
    <h2 class="admin-page-title mb-1">Manajemen Sektor Industri</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      Kelola bidang industri dan sektor pengguna produk laboratorium untuk pemetaan katalog dan penyaringan RFQ.
    </p>
  </div>
  <a href="{{ route('admin.sectors.create') }}" class="admin-btn admin-btn-primary">
    <i data-lucide="plus"></i> Tambah Sektor
  </a>
</div>

<div class="admin-card">

  {{-- Control Toolbar: Search & Count --}}
  <div class="admin-card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-bottom: 1px solid var(--color-border); background: #FFFFFF;">
    <div class="d-flex align-items-center gap-2" style="flex: 1; min-width: 240px; max-width: 420px;">
      <div style="position: relative; width: 100%;">
        <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); width: 15px; height: 15px; pointer-events: none;"></i>
        <input type="search" id="sector-search-input" class="form-control"
               placeholder="Cari nama atau ID sektor..."
               autocomplete="off"
               style="padding-left: 36px; height: 38px; font-size: 0.85rem; border-radius: 8px;">
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 ms-auto">
      <div class="d-flex align-items-center gap-2 px-3" style="background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: 8px; height: 38px; font-size: 0.82rem; color: var(--color-text-secondary);">
        <i data-lucide="layers" style="width: 15px; height: 15px; color: var(--color-accent);"></i>
        <span id="sector-count-label">
          Total: <strong>{{ count($sectors) }}</strong> Sektor Industri
        </span>
      </div>
    </div>
  </div>

  {{-- Table Body --}}
  <div class="admin-card-body-flush">
    @if(count($sectors) > 0)
      <div class="table-responsive">
        <table class="admin-table" id="sectors-table">
          <thead>
            <tr>
              <th style="width: 40px; text-align: center;">
                <input type="checkbox" class="form-check-input select-all-checkbox" style="cursor: pointer;" title="Pilih Semua">
              </th>
              <th style="width: 76px; text-align: center;">Cover</th>
              <th style="width: 180px;">ID Sektor</th>
              <th>Nama Sektor</th>
              <th>Deskripsi</th>
              <th style="width: 150px; text-align: center;">Produk Terkait</th>
              <th style="text-align: right; padding-right: 20px; width: 110px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sectors as $sec)
              @php
                $thumb = $sec['image'] ?? null;
                if ($thumb && !str_starts_with($thumb, 'http') && !str_starts_with($thumb, 'data:')) {
                  $thumbSrc = asset(ltrim($thumb, '/'));
                } else {
                  $thumbSrc = $thumb;
                }
                $descText = count($sec['description'] ?? []) > 0 ? implode(' ', $sec['description']) : '';
                $countProducts = $productCounts[$sec['id']] ?? 0;
                $searchData = strtolower($sec['id'] . ' ' . $sec['name'] . ' ' . $descText);
              @endphp
              <tr class="sector-row" data-search="{{ e($searchData) }}">
                <td style="text-align: center;">
                  <input type="checkbox" value="{{ $sec['id'] }}" class="form-check-input row-checkbox" style="cursor: pointer;">
                </td>
                {{-- Cover Thumbnail --}}
                <td style="text-align: center; vertical-align: middle;">
                  <div class="admin-post-thumb mx-auto">
                    @if($thumbSrc)
                      <img src="{{ $thumbSrc }}" alt="{{ $sec['name'] }}" loading="lazy"
                           onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; color: var(--color-text-muted);">
                        <i data-lucide="layers" style="width: 18px; height: 18px; opacity: 0.5;"></i>
                      </div>
                    @else
                      <div class="d-flex align-items-center justify-content-center w-100 h-100" style="color: var(--color-text-muted);">
                        <i data-lucide="layers" style="width: 18px; height: 18px; opacity: 0.45;"></i>
                      </div>
                    @endif
                  </div>
                </td>

                {{-- ID Sektor Key Badge --}}
                <td>
                  <span class="cat-key-badge" title="ID Kunci Sektor">
                    #{{ $sec['id'] }}
                  </span>
                </td>

                {{-- Nama Sektor --}}
                <td>
                  <a href="{{ route('admin.sectors.edit', ['id' => $sec['id']]) }}"
                     class="cell-title d-inline-block text-decoration-none"
                     style="color: var(--color-text-main); font-size: 0.92rem; line-height: 1.35; transition: color 0.15s ease;"
                     onmouseover="this.style.color='var(--color-accent)'"
                     onmouseout="this.style.color='var(--color-text-main)'">
                    {{ $sec['name'] }}
                  </a>
                </td>

                {{-- Deskripsi --}}
                <td class="cell-muted" style="max-width: 440px;">
                  @if(!empty($descText))
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $descText }}">
                      {{ $descText }}
                    </div>
                  @else
                    <em class="text-muted" style="font-size: 0.8rem;">Belum ada deskripsi</em>
                  @endif
                </td>

                {{-- Produk Terkait --}}
                <td style="text-align: center; white-space: nowrap;">
                  @if($countProducts > 0)
                    <a href="{{ route('admin.products', ['sector' => $sec['id']]) }}"
                       class="admin-badge admin-badge-info text-decoration-none"
                       title="Lihat katalog produk di sektor {{ $sec['name'] }}">
                      <i data-lucide="package" style="width: 12px; height: 12px;"></i>
                      {{ $countProducts }} Produk
                    </a>
                  @else
                    <span class="admin-badge admin-badge-muted" title="Belum ada produk yang dipetakan ke sektor ini">
                      0 Produk
                    </span>
                  @endif
                </td>

                {{-- Aksi --}}
                <td style="text-align: right; padding-right: 20px; white-space: nowrap;">
                  <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                    <a href="{{ route('admin.sectors.edit', ['id' => $sec['id']]) }}"
                       class="admin-action-link edit" title="Edit Sektor">
                      <i data-lucide="file-edit"></i>
                    </a>
                    <form action="{{ route('admin.sectors.destroy', ['id' => $sec['id']]) }}" method="POST"
                          class="form-delete-sector" data-name="{{ $sec['name'] }}" data-count="{{ $countProducts }}"
                          style="display: contents;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="admin-action-link delete" title="Hapus Sektor">
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

      <p id="sector-empty-filter" class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.88rem; display: none;">
        <i data-lucide="inbox" style="font-size: 2rem; display: block; margin: 0 auto 8px; opacity: 0.4;"></i>
        Tidak ada sektor industri yang cocok dengan kata kunci pencarian.
      </p>
    @else
      <x-admin.empty-state
        icon="layers"
        message="Belum ada data sektor industri."
        :action-url="route('admin.sectors.create')"
        action-label="Tambah Sektor Sekarang" />
    @endif
  </div>

</div>

<x-admin.bulk-action-bar :route="route('admin.sectors.bulk-destroy')" label="sektor" />

@endsection

@section('admin_scripts')
<script @nonce>
  // Live Instant Search Filter for Sectors
  const searchInput = document.getElementById('sector-search-input');
  const emptyMsg = document.getElementById('sector-empty-filter');
  const countLabel = document.getElementById('sector-count-label');
  const rows = document.querySelectorAll('.sector-row');
  const total = rows.length;

  searchInput?.addEventListener('input', function () {
    const q = (this.value || '').trim().toLowerCase();
    let visible = 0;

    rows.forEach(row => {
      const hay = row.getAttribute('data-search') || '';
      const match = !q || hay.indexOf(q) !== -1;
      row.style.display = match ? '' : 'none';
      if (match) visible++;
    });

    if (emptyMsg) emptyMsg.style.display = (visible === 0) ? '' : 'none';
    if (countLabel) {
      countLabel.innerHTML = q
        ? `<strong>${visible}</strong> dari ${total} Sektor ditemukan`
        : `Total: <strong>${total}</strong> Sektor Industri`;
    }
  });

  // SweetAlert2 confirmation on delete
  document.querySelectorAll('.form-delete-sector').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = this.getAttribute('data-name');
      const count = parseInt(this.getAttribute('data-count') || '0', 10);

      if (count > 0) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Tidak Dapat Menghapus Sektor',
            html: `Sektor "<strong>${name}</strong>" masih terhubung dengan <strong>${count}</strong> produk katalog.<br><small class="text-muted">Ubah atau hapus pemetaan produk terkait terlebih dahulu di menu Produk.</small>`,
            icon: 'error',
            confirmButtonText: 'Mengerti',
            customClass: {
              confirmButton: 'admin-btn admin-btn-primary'
            },
            buttonsStyling: false
          });
        } else {
          alert(`Sektor "${name}" masih terhubung dengan ${count} produk katalog.`);
        }
        return;
      }

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Hapus Sektor?',
          html: `Hapus sektor "<strong>${name}</strong>"?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
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
        }).then(r => {
          if (r.isConfirmed) this.submit();
        });
      } else {
        if (confirm(`Hapus sektor "${name}"?`)) {
          this.submit();
        }
      }
    });
  });
</script>
@endsection
