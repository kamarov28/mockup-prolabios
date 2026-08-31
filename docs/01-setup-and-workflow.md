# 01. Setup & Developer Workflow

Dokumen ini menjelaskan langkah-langkah persiapan lingkungan pengembangan lokal dari nol.

---

## 📋 Prasyarat Sistem
- **PHP**: >= 8.3 dengan ekstensi `pdo`, `mbstring`, `openssl`, `gd`, `fileinfo`, `zip`.
- **Composer**: >= 2.x
- **Node.js**: >= 18.x & NPM
- **Database**: MySQL 8.x / MariaDB 10.x atau SQLite untuk lokal.

---

## 🛠️ Langkah Instalasi

### 1. Clone Repository & Install Dependensi
```bash
git clone <repository-url> prolabios
cd prolabios

# Install dependensi PHP
composer install

# Install dependensi Frontend
npm install
```

### 2. Konfigurasi Environment (`.env`)
Salin template konfigurasi:
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi koneksi database di `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prolabios_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeding Data
Jalankan migrasi database beserta data awal (admin user, produk, kategori, setting homepage):
```bash
php artisan migrate --seed
```
*Catatan: Akun admin default akan dibuat sesuai konfigurasi seeder (`admin` / password tertera di log/console).*

### 4. Link Storage untuk Media
Buat symlink direktori upload public:
```bash
php artisan storage:link
```

---

## 🚀 Menjalankan Server Lokal

### Opsi A: Menjalankan Sekaligus (Rekomendasi)
Gunakan satu perintah untuk menjalankan web server, queue listener, log viewer, dan Vite:
```bash
composer dev
```

### Opsi B: Menjalankan Secara Terpisah
```bash
# Terminal 1: Laravel Web Server
php artisan serve

# Terminal 2: Vite Hot Reload Asset
npm run dev

# Terminal 3: Asynchronous Queue Worker (untuk job email RFQ)
php artisan queue:listen --tries=1 --timeout=0
```

---

## 🔗 Referensi Alur Terkait
- [02. Architecture Overview](02-architecture-overview.md)
- [08. Testing & Quality Assurance](08-testing-and-qa.md)
