# 08. Testing & Quality Assurance

Dokumen ini menjelaskan ekosistem pengujian otomatis (*automated testing*), integrasi CI, dan standar kualitas kode.

---

## 🧪 1. Menjalankan Test Suite

Sistem dilengkapi dengan **38+ Feature & Unit Tests** yang mencakup pengujian regresi end-to-end:

```bash
# Menjalankan seluruh test suite
php artisan test

# Menjalankan file test spesifik
php artisan test tests/Feature/RfqFlowTest.php

# Menjalankan test dengan filter nama method
php artisan test --filter=test_rfq_submission_workflow
```

---

## 📑 2. Daftar Cakupan Test Suite (`tests/Feature/`)

| File Test | Cakupan Pengujian |
|---|---|
| `RfqFlowTest.php` | Alur checkout RFQ, kalkulasi total, integrasi transaksi DB, dan queue jobs. |
| `CartTest.php` | Manipulasi keranjang belanja (tambah, update qty, hapus, clear session). |
| `AdminAuthTest.php` | Login admin, proteksi user non-admin, session regeneration, rate limiter login. |
| `ProductManagementTest.php` | CRUD produk, auto-generate slug, validasi field, dan invalidasi cache. |
| `SectorManagementTest.php` | CRUD sektor industri dan relasi multi-sektor. |
| `AdminRfqTest.php` | Akses view admin RFQ, pembaruan status penawaran, dan penghapusan RFQ. |
| `SecurityHardeningTest.php` | Verifikasi header CSP, HSTS, endpoint `/health`, dan anti-XSS. |
| `ProductSlugTest.php` | Canonical slug routing & fallback ID numeric legasi. |

---

## 🎨 3. Code Formatting & Static Analysis

Sebelum melakukan commit atau pull request, pastikan kode mematuhi standar PSR-12 dan tidak memiliki error analitik:

```bash
# Format kode otomatis menggunakan Laravel Pint
./vendor/bin/pint

# Pengecekan statis menggunakan PHPStan / Larastan
./vendor/bin/phpstan analyse
```

---

## 🔗 Referensi Alur Terkait
- [01. Setup & Developer Workflow](01-setup-and-workflow.md)
- [02. Architecture Overview](02-architecture-overview.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
