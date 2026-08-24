# Prolabios — B2B Catalog & RFQ Portal

Portal katalog dan pengajuan penawaran (RFQ) untuk **PT. Prolabios Mitra Analitika**.

Stack: **Laravel 13** · **PHP 8.3+** · **Blade** · **Tailwind CSS v4** · **Vite**

---

## Apa ini?

Website B2B untuk:

- Menampilkan katalog produk laboratorium (reagen, media, instrumen, dll.)
- Keranjang pengajuan RFQ
- Submit permintaan penawaran → data masuk admin + email
- **Follow-up penawaran dilanjutkan lewat WhatsApp / Sales** (bukan checkout e-commerce penuh)

Bukan marketplace retail. Fokusnya katalog + lead RFQ korporasi.

---

## Alur RFQ (yang jalan sekarang)

```
Katalog / detail produk
        →  Keranjang (session)
        →  Form data perusahaan + PIC
        →  Submit → nomor RFQ + simpan DB + email job
        →  Halaman sukses
        →  Sales follow-up via WhatsApp
```

Admin melihat pengajuan di **`/admin/rfqs`**.

### Belum diaktifkan sebagai alur utama

- Diskon / price override di admin  
- PDF surat penawaran otomatis  
- Approve online + signed URL  
- Potong stok otomatis  

Kalau nanti dibutuhkan, itu development terpisah. Saat ini **submit RFQ → WA** sudah sesuai operasional.

---

## Struktur aplikasi (singkat)

| Area | Isi |
|------|-----|
| Publik | Home, produk, sektor, layanan, artikel, kontak, cart, RFQ |
| Admin | Produk, kategori, sektor, prinsipal, artikel, RFQ, editor homepage/SEO |
| Service | `DataService` (facade) → Product / Post / Sector / Homepage |
| Model | `Product`, `ProductCategory`, `Post`, `Sector`, `Rfq`, `RfqItem`, `User`, … |
| Upload | `storage/app/public` via disk `public` (`php artisan storage:link`) |

---

## Requirements

- PHP **8.3+** (disarankan extension `gd` untuk konversi WebP)
- Composer
- Node.js **18+** & npm
- SQLite (default dev) atau MySQL/MariaDB

---

## Setup lokal

```bash
git clone https://github.com/kamarov28/mockup-prolabios.git
cd mockup-prolabios

cp .env.example .env
composer install
npm install

php artisan key:generate
php artisan migrate --seed
php artisan storage:link

npm run build
# atau untuk dev:
# npm run dev

php artisan serve
```

Buka [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Environment penting

```env
APP_NAME="Prolabios"
APP_URL=http://127.0.0.1:8000

# Database (contoh SQLite default di .env.example — sesuaikan jika MySQL)
DB_CONNECTION=sqlite

# Session (di production wajib secure)
SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true   # false jika local HTTP

# Admin awal — lihat seeder / buat user is_admin=true

# CAPTCHA (opsional di local; wajib diisi saat production)
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
# atau Cloudflare Turnstile:
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

### Admin

- URL: `/admin/login`
- User harus `is_admin = true` di tabel `users`
- Ikuti seeder project atau buat manual via tinker

---

## Scripts berguna

```bash
# Frontend
npm run dev          # Vite HMR
npm run build        # production assets

# Backend
php artisan migrate
php artisan storage:link
php artisan queue:work    # jika email pakai queue

# Tes
php artisan test
```

Feature test yang ada: auth admin, cart, RFQ flow, security hardening.

---

## Keamanan (ringkas)

- CSRF di form state-changing
- Middleware admin + `is_admin`
- Rate limit: login admin, kontak, submit RFQ
- Honeypot + CAPTCHA (fail-closed di production jika secret di-set)
- Upload: validasi MIME, block SVG, re-encode WebP, path di storage publik
- Session encrypt; secure cookie di production
- Sanitasi HTML untuk konten rich text (Summernote)

**Production wajib:**

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
```

---

## Deploy checklist

1. Set `.env` production (debug off, URL HTTPS, DB, mail, CAPTCHA)
2. `composer install --no-dev -o`
3. `npm ci && npm run build`
4. `php artisan migrate --force`
5. `php artisan storage:link`
6. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
7. Pastikan queue worker jalan jika `QUEUE_CONNECTION` bukan `sync`
8. Backup DB secara berkala

---

## Catatan teknis

- **URL detail produk** memakai id numerik: `/produk/detail?id=12` (title lama masih di-fallback di backend untuk bookmark).
- **Upload baru** ke `storage/app/public/uploads` → URL `/storage/uploads/...`. Path legacy `/uploads/...` masih didukung.
- **RFQ success page** hanya bisa dibuka oleh session yang baru saja submit nomor tersebut.

---

## Lisensi / kepemilikan

Kode dan konten untuk keperluan **PT. Prolabios Mitra Analitika**.  
© 2026 PT. Prolabios Mitra Analitika.
