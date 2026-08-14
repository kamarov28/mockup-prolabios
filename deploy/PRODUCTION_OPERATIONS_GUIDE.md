# 🚀 Panduan Operasional & Deployment Produksi (Prolabios)

Dokumen ini adalah panduan lengkap untuk tim operasional dan DevOps saat merilis aplikasi **Prolabios** ke server produksi.

---

## 1. Konfigurasi Environment (`.env.production`)

Pastikan variabel environment berikut disetel dengan benar:

```env
APP_NAME="PT. Prolabios Mitra Analitika"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://prolabios.com

# Database Production
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prolabios_prod
DB_USERNAME=prolabios_user
DB_PASSWORD="STRONG_SECRET_PASSWORD"

# Queue Driver
QUEUE_CONNECTION=database

# Mail SMTP (Google Workspace / Mailgun / SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=marketing@prolabios.com
MAIL_PASSWORD="GMAIL_APP_PASSWORD"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="marketing@prolabios.com"
MAIL_FROM_NAME="PT. Prolabios Mitra Analitika"

# Bot Protection (Pilih salah satu: reCAPTCHA v3 atau Cloudflare Turnstile)
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=

# atau Cloudflare Turnstile
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

---

## 2. Supervisor Queue Worker (Auto-Restart Daemon)

Agar email RFQ dan pesan kontak selalu terkirim tanpa khawatir queue mati, pasang file konfigurasi supervisor:

```bash
# 1. Salin konfigurasi worker
sudo cp deploy/supervisor/prolabios-worker.conf /etc/supervisor/conf.d/prolabios-worker.conf

# 2. Perbarui path direktori /var/www/mockup-prolabios jika berbeda

# 3. Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start prolabios-worker:*
sudo supervisorctl status
```

---

## 3. Health & Uptime Monitoring Endpoint

Aplikasi dilengkapi endpoint `/health` untuk memantau status database, backlog queue, dan storage:

```bash
curl -I https://prolabios.com/health
```

Contoh response `200 OK`:
```json
{
  "status": "healthy",
  "environment": "production",
  "timestamp": "2026-08-14T16:35:00+07:00",
  "checks": {
    "database": "connected",
    "queue": {
      "pending": 0,
      "failed": 0,
      "status": "normal"
    },
    "cache": "operational",
    "storage": {
      "views_writable": true
    }
  }
}
```

*Dapat diintegrasikan dengan Uptime Kuma, BetterUptime, Datadog, atau Prometheus.*

---

## 4. Audit Logging

Aktivitas krusial administrator (Login, Logout, Create/Update/Delete Produk, Hapus RFQ) otomatis tercatat di:
`storage/logs/audit-YYYY-MM-DD.log`

Log format menyertakan user ID, nama aktor, jenis target, perubahan parameter, serta IP & User Agent.

---

## 5. Script Backup Database Harian Otomatis

Tambahkan entri cronjob di server Linux untuk mencadangkan database setiap hari jam 02:00 pagi:

```bash
crontab -e
```

Tambahkan baris:
```cron
0 2 * * * mysqldump -u prolabios_user -p'STRONG_SECRET_PASSWORD' prolabios_prod | gzip > /var/backups/prolabios/db_$(date +\%Y\%m\%d).sql.gz
```

---

## 6. Verifikasi DNS Email (SPF, DKIM, DMARC)

Untuk memastikan email RFQ tidak masuk ke spam folder pelanggan:
- **SPF**: `v=spf1 include:_spf.google.com ~all` (jika memakai Google Workspace)
- **DKIM**: Aktifkan Google Workspace DKIM TXT record di DNS Domain Registrar
- **DMARC**: `v=DMARC1; p=none; rua=mailto:admin@prolabios.com`
