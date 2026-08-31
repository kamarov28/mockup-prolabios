# 09. Production Deployment Guide

Dokumen ini berisi panduan teknis dan checklist wajib saat melakukan deploy sistem Prolabios ke server production.

---

## 📋 1. Checklist Environment Production (`.env`)

Pastikan konfigurasi berikut diterapkan di server:

```ini
APP_NAME="Prolabios"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://prolabios.com

# Wajib diganti dengan password aman
ADMIN_USERNAME=admin
ADMIN_PASSWORD=isi_password_panjang_dan_aman_disini

# Driver Cache & Queue
CACHE_STORE=redis
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prolabios_prod
DB_USERNAME=prolabios_user
DB_PASSWORD=password_db_yang_kuat
```

---

## 🛠️ 2. Langkah Deployment Server

Jalankan perintah berikut di root project:

```bash
# 1. Update kode & install dependency production
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. Migrasi database & storage link
php artisan migrate --force
php artisan storage:link

# 3. Cache konfigurasi, routes, dan views untuk performa maksimal
php artisan optimize
php artisan view:cache
```

---

## 🌐 3. Konfigurasi Web Server (Nginx)

Pastikan Document Root Nginx mengarah ke direktori `/public` dan memblokir akses ke file `.env` / `.git`:

```nginx
server {
    listen 80;
    server_name prolabios.com www.prolabios.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name prolabios.com www.prolabios.com;

    root /var/www/prolabios/public;
    index index.php index.html;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/prolabios.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/prolabios.com/privkey.pem;

    # Blokir akses ke file tersembunyi
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
}
```

---

## 🔄 4. Queue Worker (Supervisor)

Untuk memproses antrean email RFQ secara berkelanjutan di background, buat konfigurasi Supervisor di `/etc/supervisor/conf.d/prolabios-worker.conf`:

```ini
[program:prolabios-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/prolabios/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/prolabios/storage/logs/worker.log
stopwaitsecs=3600
```

Jalankan perintah Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start prolabios-worker:*
```

---

## 💾 5. Database Backup Otomatis (Cron)

Aktifkan backup database harian terkompresi via Laravel Scheduler di crontab server:
```bash
* * * * * cd /var/www/prolabios && php artisan schedule:run >> /dev/null 2>&1
```
Atau jadwalkan command backup langsung:
```bash
0 2 * * * cd /var/www/prolabios && php artisan backup:database >> /dev/null 2>&1
```

---

## 🔗 Referensi Alur Terkait
- [01. Setup & Developer Workflow](01-setup-and-workflow.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
