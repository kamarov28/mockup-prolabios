@extends('admin.layout')

@section('title', 'Manajemen Pengguna Admin')
@section('page_title', 'Kelola Admin & Peran')

@section('admin_content')

<x-admin.page-header
  label="Keamanan & Otorisasi"
  title="Daftar Pengguna & Peran Admin"
  description="Kelola akun staf internal, pembagian tugas divisi (Super Admin, Sales, Product Specialist, Content Writer), dan kendali akses modul.">
  <x-slot:actions>
    <button type="button" class="admin-btn admin-btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
      <i data-lucide="user-plus"></i> Tambah Admin Baru
    </button>
  </x-slot:actions>
</x-admin.page-header>

<!-- Role Metrics Overview -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="admin-card p-3 h-100 d-flex flex-column justify-content-between">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Super Admin</span>
        <div style="width: 28px; height: 28px; border-radius: 6px; background: #FEE2E2; color: #991B1B; display: flex; align-items: center; justify-content: center;">
          <i data-lucide="shield-check" style="width: 15px; height: 15px;"></i>
        </div>
      </div>
      <div>
        <h3 class="h4 fw-bold mb-1" style="color: var(--color-text-main);">{{ $roleCounts[\App\Models\User::ROLE_SUPER_ADMIN] ?? 0 }}</h3>
        <p class="small text-muted mb-0" style="font-size: 0.76rem;">Akses penuh seluruh modul</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="admin-card p-3 h-100 d-flex flex-column justify-content-between">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sales & RFQ</span>
        <div style="width: 28px; height: 28px; border-radius: 6px; background: #EFF6FF; color: #1D4ED8; display: flex; align-items: center; justify-content: center;">
          <i data-lucide="file-spreadsheet" style="width: 15px; height: 15px;"></i>
        </div>
      </div>
      <div>
        <h3 class="h4 fw-bold mb-1" style="color: var(--color-text-main);">{{ $roleCounts[\App\Models\User::ROLE_SALES] ?? 0 }}</h3>
        <p class="small text-muted mb-0" style="font-size: 0.76rem;">RFQ & respon pembeli</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="admin-card p-3 h-100 d-flex flex-column justify-content-between">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Product Specialist</span>
        <div style="width: 28px; height: 28px; border-radius: 6px; background: #FEF3C7; color: #B45309; display: flex; align-items: center; justify-content: center;">
          <i data-lucide="package" style="width: 15px; height: 15px;"></i>
        </div>
      </div>
      <div>
        <h3 class="h4 fw-bold mb-1" style="color: var(--color-text-main);">{{ $roleCounts[\App\Models\User::ROLE_CATALOG] ?? 0 }}</h3>
        <p class="small text-muted mb-0" style="font-size: 0.76rem;">Katalog, sektor & mitra</p>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="admin-card p-3 h-100 d-flex flex-column justify-content-between">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">Content Writer</span>
        <div style="width: 28px; height: 28px; border-radius: 6px; background: #F0FDF4; color: #15803D; display: flex; align-items: center; justify-content: center;">
          <i data-lucide="file-text" style="width: 15px; height: 15px;"></i>
        </div>
      </div>
      <div>
        <h3 class="h4 fw-bold mb-1" style="color: var(--color-text-main);">{{ $roleCounts[\App\Models\User::ROLE_CONTENT] ?? 0 }}</h3>
        <p class="small text-muted mb-0" style="font-size: 0.76rem;">Artikel & publikasi lab</p>
      </div>
    </div>
  </div>
</div>

<div class="admin-card">
  <!-- Toolbar Filter & Search -->
  <div class="admin-card-body p-3" style="border-bottom: 1px solid var(--color-border); background: #FFFFFF;">
    <form action="{{ route('admin.users.index') }}" method="GET">
      <div class="row g-2 align-items-center">
        <div class="col-md-5 col-lg-4">
          <x-admin.search-input name="s" :value="$search" placeholder="Cari nama atau email admin..." :clear-url="route('admin.users.index')" />
        </div>
        <div class="col-md-4 col-lg-3">
          <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="height: 38px;">
            <option value="">Semua Peran Otorisasi</option>
            <option value="super_admin" {{ $roleFilter === 'super_admin' ? 'selected' : '' }}>Super Admin (Penuh)</option>
            <option value="sales" {{ $roleFilter === 'sales' ? 'selected' : '' }}>Sales & RFQ Admin</option>
            <option value="catalog" {{ $roleFilter === 'catalog' ? 'selected' : '' }}>Product Specialist</option>
            <option value="content" {{ $roleFilter === 'content' ? 'selected' : '' }}>Content Writer</option>
          </select>
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
    @if(count($users) > 0)
      <div class="table-responsive">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Nama Pengguna</th>
              <th>Alamat Email</th>
              <th>Peran Otorisasi</th>
              <th>Dibuat Pada</th>
              <th style="text-align: right; padding-right: 24px; width: 120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $index => $u)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--color-surface-2); border: 1px solid var(--color-border); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--color-text-main); font-size: 0.8rem;">
                      {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div>
                      <strong class="d-block text-dark">{{ $u->name }}</strong>
                      @if($u->id === auth()->id())
                        <span class="badge" style="background: rgba(166, 23, 28, 0.1); color: #A6171C; font-size: 0.65rem; padding: 1px 5px; border-radius: 3px;">Akun Anda</span>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="cell-code">{{ $u->email }}</td>
                <td>
                  <span class="badge" style="{{ $u->role_badge_style }} font-size: 0.74rem; font-weight: 600; padding: 4px 8px; border-radius: 4px;">
                    {{ $u->role_label }}
                  </span>
                </td>
                <td class="text-muted small">
                  {{ $u->created_at ? $u->created_at->format('d M Y') : '—' }}
                </td>
                <td style="text-align: right; padding-right: 24px;">
                  <div class="d-inline-flex align-items-center gap-1 justify-content-end">
                    <!-- Edit Button Trigger -->
                    <button type="button" class="admin-action-link edit" title="Edit Akun" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}">
                      <i data-lucide="edit-3"></i>
                    </button>

                    <!-- Delete Form -->
                    @if($u->id !== auth()->id())
                      <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-action-link delete" title="Hapus Pengguna">
                          <i data-lucide="trash-2"></i>
                        </button>
                      </form>
                    @endif
                  </div>

                  <!-- Edit Modal for this user -->
                  <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $u->id }}" aria-hidden="true" style="text-align: left;">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                        <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-header px-4 pt-4 pb-2 border-0">
                            <h5 class="modal-title fw-bold" id="editUserModalLabel{{ $u->id }}">Edit Akun Admin: {{ $u->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                          </div>
                          <div class="modal-body px-4 py-3">
                            <div class="mb-3">
                              <label class="form-label small fw-bold">Nama Lengkap</label>
                              <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label small fw-bold">Alamat Email</label>
                              <input type="email" name="email" class="form-control" value="{{ old('email', $u->email) }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label small fw-bold">Peran Otorisasi (Role)</label>
                              <select name="role" class="form-select" required>
                                <option value="super_admin" {{ old('role', $u->role) === 'super_admin' ? 'selected' : '' }}>Super Admin — Akses Penuh Sistem & Pengaturan</option>
                                <option value="sales" {{ old('role', $u->role) === 'sales' ? 'selected' : '' }}>Sales & RFQ — Pengajuan Penawaran & Pelanggan</option>
                                <option value="catalog" {{ old('role', $u->role) === 'catalog' ? 'selected' : '' }}>Product Specialist — Produk, Kategori, Sektor & Mitra</option>
                                <option value="content" {{ old('role', $u->role) === 'content' ? 'selected' : '' }}>Content Writer — Artikel Berita & Edukasi</option>
                              </select>
                            </div>
                            <div class="mb-2">
                              <label class="form-label small fw-bold">Ganti Kata Sandi (Opsional)</label>
                              <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah kata sandi" minlength="8">
                              <span class="text-muted small">Minimal 8 karakter (kombinasi huruf & angka).</span>
                            </div>
                          </div>
                          <div class="modal-footer px-4 pb-4 pt-2 border-0">
                            <button type="button" class="admin-btn admin-btn-ghost" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="admin-btn admin-btn-primary">Simpan Perubahan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="p-5 text-center">
        <p class="text-muted mb-0">Tidak ada data pengguna admin yang cocok dengan kriteria pencarian.</p>
      </div>
    @endif
  </div>
</div>

<!-- Modal Create User -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-header px-4 pt-4 pb-2 border-0">
          <h5 class="modal-title fw-bold" id="createUserModalLabel">Tambah Akun Admin Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body px-4 py-3">
          <div class="mb-3">
            <label class="form-label small fw-bold">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Alamat Email Perusahaan</label>
            <input type="email" name="email" class="form-control" placeholder="nama@prolabios.com" value="{{ old('email') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Peran Otorisasi (Role)</label>
            <select name="role" class="form-select" required>
              <option value="sales" {{ old('role') === 'sales' ? 'selected' : '' }}>Sales & RFQ — Pengajuan Penawaran & Pelanggan</option>
              <option value="catalog" {{ old('role') === 'catalog' ? 'selected' : '' }}>Product Specialist — Produk, Kategori, Sektor & Mitra</option>
              <option value="content" {{ old('role') === 'content' ? 'selected' : '' }}>Content Writer — Artikel Berita & Edukasi</option>
              <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin — Akses Penuh Sistem & Pengaturan</option>
            </select>
            <span class="text-muted small">Hak akses modul akan otomatis terkunci sesuai divisi yang dipilih.</span>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Kata Sandi Awal</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter huruf & angka" minlength="8" required>
          </div>
        </div>
        <div class="modal-footer px-4 pb-4 pt-2 border-0">
          <button type="button" class="admin-btn admin-btn-ghost" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="admin-btn admin-btn-primary">Daftarkan Admin</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
