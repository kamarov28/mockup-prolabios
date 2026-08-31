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

## 📜 3. Audit Trail Logging (`AuditLogger`)

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
