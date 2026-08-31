# 03. B2B RFQ & Cart Flow

Dokumen ini menjelaskan alur kerja utama (*core domain*) pemesanan B2B: Request for Quotation (RFQ) dan manajemen keranjang belanja.

---

## 🔄 Diagram Alur RFQ

```
[Katalog Produk] ──> [Tambah ke Cart (Session)] ──> [/rfq/checkout]
                                                          │
                                                (Validasi + Anti-Bot)
                                                          │
                                                          ▼
                                             [RfqController::store()]
                                                          │
                                          ┌───────────────┴───────────────┐
                                          ▼                               ▼
                             [DB Transaction (RFQ & Items)]     [AuditLogger::log()]
                                          │                               │
                                          ▼                               ▼
                              [Dispatch Async Queue Jobs]       [Set Session Token]
                                (Email Admin & Buyer)                     │
                                                                          ▼
                                                               [/rfq/success/{number}]
```

---

## 🛒 1. Keranjang Belanja (`CartController`)
- **Penyimpanan**: Disimpan di session pengunjung (`session('cart')`).
- **Data Item**: Menyimpan `id`, `title`, `price`, `quantity`, dan `image`.
- **Integritas Harga**: Pada saat checkout (`/rfq/checkout`) dan submit (`/rfq/submit`), controller selalu me-resolve produk ke database (`$this->resolveProduct()`) dan mengambil harga asli dari database, sehingga harga tidak bisa dimanipulasi dari sisi client.

---

## 📝 2. Pengajuan RFQ (`RfqController::store`)
- **Validasi**: Menggunakan `StoreRfqRequest` (nama PIC, email korporat, nama perusahaan, nomor WhatsApp aktif, catatan).
- **Proteksi Anti-Bot**:
  - Honeypot: Field tersembunyi `_hp_website`. Jika diisi bot, request langsung di-drop dengan aman.
  - CAPTCHA: Diverifikasi via `CaptchaService::verify($request)`.
  - Rate Limiting: Dibatasi maksimal 3 pengajuan per menit per IP (`throttle:rfq-submission`).
- **Nomor RFQ**: Dibuat unik dengan format `RFQ-YYYYMM-XXXXXX` (misal: `RFQ-202608-A9K3F2`).

---

## ⚡ 3. Pemrosesan Asinkron & Notifikasi
Setelah data RFQ dan item tersimpan di database via database transaction:
1. `SendRfqSubmittedEmailJob`: Mengirim detail penawaran ke email internal tim sales/admin.
2. `SendRfqCustomerReceiptEmailJob`: Mengirim tanda terima ringkasan pengajuan ke email pembeli.
3. Notifikasi dikirim melalui antrean Laravel (`jobs` table) agar respon halaman ke user tetap instan (< 100ms).

---

## 🔒 4. Keamanan Halaman Sukses (`/rfq/success/{number}`)
- Halaman sukses dilindungi oleh token session `session('submitted_rfq_number')`.
- Pengunjung yang mencoba membuka URL `/rfq/success/RFQ-XXXXXX` secara langsung tanpa submit dari sesi yang sama akan dialihkan ke halaman utama untuk mencegah kebocoran data informasi perusahaan lain (*Direct Object Reference protection*).

---

## 🔗 Referensi Alur Terkait
- [04. Product & Catalog Engine](04-product-and-catalog.md)
- [06. Admin Dashboard & Authentication](06-admin-and-auth.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
