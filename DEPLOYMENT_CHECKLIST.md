# Production go-live checklist (Prolabios)

Checklist singkat sebelum domain production live. Detail operasional: `deploy/PRODUCTION_OPERATIONS_GUIDE.md`.

## A. Environment (wajib)

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false` (**jangan** true di public)
- [ ] `APP_KEY` unik (`php artisan key:generate` sekali, jangan commit `.env`)
- [ ] `APP_URL=https://your-domain` (HTTPS)
- [ ] `LOG_LEVEL=warning` atau `error` (bukan `debug`)
- [ ] Database **MySQL/MariaDB** (bukan SQLite production)
- [ ] Password DB + admin kuat; `ADMIN_PASSWORD` diganti dari default example
- [ ] Session: `SESSION_DRIVER=database` atau `redis`, `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true`
- [ ] Cache: `CACHE_STORE=redis` (disarankan) atau `database` / `file`
- [ ] Queue: `QUEUE_CONNECTION=database` atau `redis` + **worker jalan** (Supervisor)
- [ ] Mail SMTP valid (RFQ + kontak)
- [ ] CAPTCHA: isi **satu** dari reCAPTCHA **atau** Turnstile (production fail-closed jika diisi)

## B. Server & PHP

- [ ] PHP 8.2+ dengan extensions: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `redis` (jika pakai Redis)
- [ ] Document root = `public/` (bukan root repo)
- [ ] `storage/` dan `bootstrap/cache/` writable oleh user web (`www-data`)
- [ ] `php artisan storage:link`
- [ ] OPcache enabled di production
- [ ] TLS certificate valid (Let’s Encrypt / panel)

## C. Deploy sequence (setiap rilis)

```bash
cd /var/www/mockup-prolabios   # sesuaikan path
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan products:sync-sectors   # jika ada data sector CSV lama
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart           # jika worker supervisor
```

Atau: `bash deploy/release.sh` (lihat skrip).

## D. Verifikasi pasca-deploy

- [ ] `curl -sS https://your-domain/health` → `"status":"healthy"`, DB connected
- [ ] Beranda, `/produk`, `/sektor` load normal
- [ ] Detail produk URL `/produk/{slug}` (bukan hanya `?id=`)
- [ ] Legacy `/produk/detail?id=N` → 301 ke slug
- [ ] Submit RFQ (cart → checkout → success) + cek email / `failed_jobs`
- [ ] Login admin + ubah status RFQ
- [ ] `/sitemap.xml` dan `/robots.txt` OK
- [ ] `php artisan test --testsuite=Feature` hijau di CI atau staging

## E. Keamanan

- [ ] `/admin` tidak di-index (robots sudah `Disallow: /admin/`)
- [ ] Rate limit login admin + RFQ + kontak aktif
- [ ] Honeypot / CAPTCHA form publik aktif
- [ ] Tidak ada `.env` atau backup DB di webroot
- [ ] HTTPS force (app sudah `URL::forceScheme('https')` di production)
- [ ] Security headers middleware aktif (jika dipakai di stack)

## F. Operasional

- [ ] Supervisor queue worker (`deploy/supervisor/`)
- [ ] Backup DB harian (cron / managed backup)
- [ ] Log rotasi `storage/logs/`
- [ ] Uptime monitor ke `/health`
- [ ] SPF/DKIM/DMARC untuk domain email RFQ

## G. Yang tidak wajib di v1

- CDN / multi-server / Elasticsearch
- Payment gateway
- Rewrite frontend ke SPA

---

**Setelah centang A–F**, aplikasi siap production-grade untuk katalog B2B + RFQ.
