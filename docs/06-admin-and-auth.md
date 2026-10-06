# 06. Admin Dashboard & Authentication

Dokumen ini menjelaskan alur otentikasi administrator, proteksi route admin, audit trail logging, dan pengelolaan konten (CMS).

---

## 🔐 1. Otentikasi Admin (`AdminController`)

- **Login Endpoint**: `/admin/login`
- **Throttling**: Dibatasi maksimal 5 percobaan gagal per menit per IP (`throttle:admin-login`).
- **Pengecekan Kredensial**:
  - Mendukung login menggunakan **email** atau **username**.
  - Wajib memiliki flag `is_admin = true` (`Auth::user()->isAdmin()`). Jika user non-admin mencoba login, sesi langsung di-logout otomatis dan ditolak.
- **Session Security**:
  - Login berhasil: memanggil `$request->session()->regenerate()` untuk mencegah *Session Fixation*.
  - Logout: memanggil `$request->session()->invalidate()` dan `$request->session()->regenerateToken()`.

---

## 🛡️ 2. Middleware Akses Admin (`AdminAuthenticate`)

Semua route dengan prefix `/admin/*` dilindungi oleh middleware `AdminAuthenticate`:
```php
Route::middleware([AdminAuthenticate::class])->prefix('admin')->group(function () {
    // Dashboard, Produk, Kategori, Sektor, Principal, RFQ, Posts, Settings
});
```
Jika request tidak memiliki session admin yang valid, user otomatis diarahkan kembali ke `/admin/login`.

---

## 👥 3. Multi-Admin Role & Otorisasi (`Gate`)

Sistem menerapkan kontrol akses berbasis peran (*Role-Based Access Control / RBAC*) dengan 4 divisi spesifik pada tabel `users`:
- **`super_admin` (Super Administrator)**: Akses mutlak ke seluruh modul sistem, termasuk pengaturan beranda (`/admin/home`), setelan SEO, migrasi database, dan pengelolaan akun staf admin (`/admin/users`).
- **`sales` (Sales & RFQ Admin)**: Menangani pemrosesan pengajuan penawaran harga pelanggan (`/admin/rfqs`), ekspor spreadsheet penawaran, serta melihat katalog produk secara *Read-Only*. Terkunci dari pengeditan produk dan setelan web.
- **`catalog` (Product Specialist)**: Mengelola katalog produk, taksonomi kategori/subkategori, sektor industri, prinsipal mitra resmi, dan impor spreadsheet massal. Terkunci dari data sensitif RFQ pelanggan dan setelan sistem.
- **`content` (Content Writer)**: Menulis dan menerbitkan artikel berita, regulasi lab, wawasan analitika, serta unggah media gambar/video. Terkunci dari modul produk, RFQ, dan setelan sistem.

### Matriks Gate Otorisasi (`AppServiceProvider`):
```php
Gate::define('manage-system', fn (?User $u) => (bool) $u?->isSuperAdmin());
Gate::define('manage-rfq', fn (?User $u) => (bool) $u?->canManageRfqs());
Gate::define('manage-catalog', fn (?User $u) => (bool) $u?->canManageCatalog());
Gate::define('view-catalog', fn (?User $u) => (bool) $u?->canViewCatalog());
Gate::define('manage-posts', fn (?User $u) => (bool) $u?->canManagePosts());
```

### Fitur Keamanan Pengguna Admin:
- **Anti-Mass Assignment**: Kolom `is_admin` dan `role` dijaga ketat (*Guarded / Non-Fillable*) pada Model `User`.
- **Anti-Self Deletion**: Super Admin yang sedang login dilarang menghapus akunnya sendiri atau menurunkan jabatannya jika menjadi satu-satunya Super Admin yang tersisa.
- **Manajemen Akun Terpusat (`/admin/users`)**: Hanya dapat diakses oleh Super Admin. Dilengkapi metrik ringkasan peran, modal pembuatan & edit akun, serta verifikasi password kuat (min. 8 karakter huruf & angka).
- **Self-Healing Schema**: Method `User::ensureRoleColumnExists()` otomatis menambahkan kolom `role` saat runtime cPanel jika migrasi CLI belum dijalankan.

---

## 📜 4. Audit Trail Logging (`AuditLogger`)

Setiap tindakan krusial administrator dicatat di tabel `audit_logs` melalui `AuditLogger::log()`:
- `admin.login_success` & `admin.login_failed`
- `product.create`, `product.update`, `product.delete`
- `rfq.update` (perubahan status & catatan internal), `rfq.delete`
- Menyimpan: `user_id`, `ip_address`, `action`, `model_type`, `model_id`, dan JSON `payload` perubahan.

---

## 🎛️ 4. Modul Manajemen Admin
- **Produk & Bulk Creator**: Manajemen katalog, input manual per produk atau bulk form cepat.
- **RFQ Manager**: Review rincian penawaran, update status (Pending, Diproses, Selesai, Dibatalkan), dan export follow-up WhatsApp.
- **Kategori & Sektor**: Manajemen kategori produk berjenjang dan sektor industri.
- **Principal**: Manajemen prinsipal/brand mitra manufaktur instrumen laboratorium.
- **Homepage & CMS Setting**: Pengaturan banner hero, teks komersial, kontak sales & teknisi via `HomepageSettingsUpdater`.

---

## 🔗 Referensi Alur Terkait
- [02. Architecture Overview](02-architecture-overview.md)
- [03. B2B RFQ & Cart Flow](03-b2b-rfq-flow.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
