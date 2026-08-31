# 📚 Dokumentasi Arsitektur & Panduan Sistem PROLABIOS

Selamat datang di repositori resmi **PT. Prolabios Mitra Analitika** — Platform B2B E-Procurement & Request for Quotation (RFQ) berbasis Laravel 13.x, Tailwind CSS v4, dan Vite.

Dokumentasi ini dirancang modular per alur kerja (*flow-by-flow*) agar mudah dipelajari oleh developer maupun maintainer baru.

---

## 🗺️ Peta Dokumentasi

| Dokumen | Deskripsi Alur / Topik | Target Pembaca |
|---|---|---|
| [**01. Quick Start & Setup**](docs/01-setup-and-workflow.md) | Panduan instalasi lokal, environment, database seeder, dan command CLI penting. | Developer Baru / DevOps |
| [**02. Architecture & Directory Structure**](docs/02-architecture-overview.md) | Penjelasan pola arsitektur (Thin Controllers, Service Layer, Traits), dependensi, dan hirarki folder. | Developer / Lead Eng |
| [**03. B2B RFQ & Cart Flow**](docs/03-b2b-rfq-flow.md) | Alur lengkap mulai dari keranjang belanja session, validasi checkout, antrean email notifikasi, hingga session security. | Backend Dev / Maintainer |
| [**04. Product & Catalog Engine**](docs/04-product-and-catalog.md) | Logika katalog, multi-level kategori/subkategori, sektor laboratorium, lean payload query, dan caching versioning. | Backend / Fullstack Dev |
| [**05. Media & Image Upload Pipeline**](docs/05-media-and-uploads.md) | Pipeline sanitasi gambar, blokir SVG, auto re-encode WebP via GD, stripping metadata, dan storage disk. | Security / Backend Dev |
| [**06. Admin Dashboard & Authentication**](docs/06-admin-and-auth.md) | Guard autentikasi admin, role checking, audit logger, session protection, dan CMS management. | Backend Dev |
| [**07. Security & Hardening Matrix**](docs/07-security-and-hardening.md) | Content Security Policy (CSP), TrustProxies, rate limiting, anti-bot honeypot & CAPTCHA, sanitasi HTML. | DevOps / Security Eng |
| [**08. Testing & Quality Assurance**](docs/08-testing-and-qa.md) | Panduan menjalankan automated test suite (PHPUnit), skenario pengujian, Pint linter, dan Larastan. | QA / Developer |
| [**09. Production Deployment Guide**](docs/09-deployment-guide.md) | Checklist deployment server, reverse proxy/Nginx, SSL/HTTPS, Redis cache, queue worker, dan database backup. | DevOps / SysAdmin |

---

## 🚀 Quick Command Cheatsheet

```bash
# Menjalankan seluruh test suite
php artisan test

# Format kode standar Laravel Pint
./vendor/bin/pint

# Static analysis (PHPStan / Larastan)
./vendor/bin/phpstan analyse

# Membersihkan cache & compile optimasi production
php artisan optimize
```
