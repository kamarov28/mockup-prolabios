# 08. Testing & Quality Assurance

Dokumen ini menjelaskan ekosistem pengujian otomatis (*automated testing*), integrasi CI, dan standar kualitas kode.

---

## 🧪 1. Menjalankan Test Suite

Sistem dilengkapi dengan **131 Automated Tests (685 assertions)** yang mencakup 100% pengujian regresi end-to-end:

```bash
# Menjalankan seluruh test suite
php artisan test

# Menjalankan file test spesifik
php artisan test tests/Feature/RfqFlowTest.php

# Menjalankan test dengan filter nama method
php artisan test --filter=test_rfq_submission_workflow
```

---

## 📑 2. Daftar Cakupan Test Suite (`tests/Feature/` & `tests/Unit/`)

| File Test | Cakupan Pengujian |
|---|---|
| `AdminRoleAccessTest.php` | Matriks otorisasi 4 role admin (Super Admin, Sales, Product Specialist, Content Writer) dan respon 403 Forbidden. |
| `RfqFlowTest.php` | Alur checkout RFQ, integrasi transaksi DB, notifikasi email, dan session security. |
| `ProductSearchTest.php` | Akurasi pencarian substring karakter (`LIKE %term%`), nomor katalog, dan multi-kata. |
| `VideoEmbedSanitizerTest.php` | Sanitasi dan preservasi video aman YouTube & Instagram serta pemblokiran iframe berbahaya. |
| `HomepageEntityEscapingTest.php` | Pencegahan double-encoding entitas HTML (`&amp;`) pada judul alur sektor dan editor CMS. |
| `FeaturedProductsTest.php` | Algoritma hybrid highlight 3-tier, prioritas manual `is_featured`, dan trending `search_hits`. |
| `ProductImportTest.php` | Impor Excel, auto-create kategori & sektor baru, pemetaan spesifikasi teknis, dan skip duplikat. |
| `CartTest.php` | Manipulasi keranjang belanja (tambah, update qty, hapus, clear session). |
| `SecurityHardeningTest.php` | Verifikasi header CSP, HSTS, endpoint `/health`, rate limiting, dan anti-XSS. |
| `RealUserSimulationTest.php` | Simulasi end-to-end lifecycle pembeli publik dan persona admin secara komprehensif. |
| `PrincipalManagementTest.php` | Validasi CRUD prinsipal mitra dan keakuratan negara asal manufaktur resmi. |
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
