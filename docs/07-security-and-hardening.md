# 07. Security & Hardening Matrix

Dokumen ini mendokumentasikan seluruh lapisan pertahanan keamanan (*defense-in-depth*) yang diterapkan pada sistem Prolabios.

---

## 🛡️ Matriks Lapisan Keamanan

| Layer Keamanan | Implementasi / Lokasi | Perlindungan Terhadap |
|---|---|---|
| **Security Headers** | `App\Http\Middleware\SecurityHeaders` | Clickjacking, MIME Sniffing, XSS, Unsafe Framing |
| **Reverse Proxy IP Trust** | `bootstrap/app.php` (`trustProxies`) | Spoofing IP client, ketepatan Rate Limiting di balik Cloudflare/Nginx |
| **HTTPS Enforcement** | `ForceHttps` & `URL::forceScheme('https')` | Man-in-the-Middle (MitM) & sniffing data sensitif |
| **Input Sanitization** | `App\Helpers\HtmlSanitizer` | Stored XSS pada rich text (strip tag berbahaya & event `on*`) |
| **Image Sanitization** | `HandlesImageUploads` (GD WebP Re-encode) | File upload RCE, polyglot files, EXIF leak, SVG XSS |
| **Database Backup Safety** | `App\Console\Commands\DatabaseBackup` | Mencegah kebocoran password MySQL di process list OS |
| **Anti-Bot & Spam** | `CaptchaService` + Honeypot Field | Scraping otomatis, brute-force spam submission |
| **Rate Limiting** | `AppServiceProvider` (Login, RFQ, Kontak) | Brute-force serangan login & denial-of-service form |
| **CSRF Protection** | Laravel CSRF Token (seluruh POST/PUT/DELETE) | Cross-Site Request Forgery |
| **SQL Injection Prevention** | Eloquent ORM & PDO Parameterized Query | SQL Injection |

---

## 🔒 1. Content Security Policy (CSP)

Dikonfigurasi melalui `SecurityHeaders`:
- `default-src 'self'`: Resource hanya dimuat dari origin sendiri kecuali domain tepercaya (Google Maps, Fonts, CDN JS).
- `frame-ancestors 'self'`: Mencegah situs dibungkus dalam `<iframe>` di website asing (Anti-Clickjacking).
- `Strict-Transport-Security (HSTS)`: Menginstruksikan browser hanya mengakses via HTTPS (`max-age=31536000`).

---

## 🧼 2. Sanitasi HTML Rich-Text (`HtmlSanitizer`)

Digunakan pada konten deskripsi produk dan artikel blog sebelum disimpan:
```php
$cleanHtml = HtmlSanitizer::clean($request->input('description'));
```
- Mempertahankan tag semantik aman: `<p>, <br>, <strong>, <table>, <img>, <ul>, <li>, <h3>, dll`.
- Menghapus paksa seluruh atribut JavaScript (misal: `onload=`, `onerror=`, `onclick=`).
- Memblokir skema URL berbahaya seperti `javascript:`, `vbscript:`, atau `data:text/html`.

---

## 🔗 Referensi Alur Terkait
- [05. Media & Image Upload Pipeline](05-media-and-uploads.md)
- [06. Admin Dashboard & Authentication](06-admin-and-auth.md)
- [09. Production Deployment Guide](09-deployment-guide.md)
