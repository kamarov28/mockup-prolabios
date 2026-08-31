# 05. Media & Image Upload Pipeline

Dokumen ini menjelaskan alur upload media, standar kompresi WebP, dan mitigasi keamanan file upload.

---

## 🛡️ Pipeline Keamanan & Kompresi Gambar

Seluruh upload gambar (produk utama, galeri produk, banner sektor, dan artikel blog) ditangani secara terpusat oleh trait `App\Traits\HandlesImageUploads`:

```
[ File Input dari Admin ]
           │
           ▼
[ 1. Ukuran Maksimal & Status File ] (Max 5MB, isValid)
           │
           ▼
[ 2. Ekstensi & MIME Whitelist ] (jpg, jpeg, png, webp, gif) 
           │ ──> ⚠️ Ekstensi .svg secara eksplisit DIBLOKIR (Anti-XSS)
           ▼
[ 3. GD Re-encoding & Resize ]
           │ ──> Mengubah dimensi > 1920px menjadi max 1920px (rasio proporsional)
           │ ──> Konversi format menjadi WebP (Quality 82%)
           │ ──> Membersihkan seluruh EXIF metadata & script payload
           ▼
[ 4. Penyimpanan di Public Disk ] (storage/app/public/{folder}/...)
           │
           ▼
[ URL Output ] (/storage/{folder}/{timestamp}_{random}.webp)
```

---

## ⚙️ Fitur Utama Pipeline

1. **Anti-XSS (Blokir SVG)**:
   File SVG dilarang keras untuk diunggah karena format XML SVG dapat disisipi tag `<script>` jahat.
2. **Sanitasi Metadata (Stripping EXIF)**:
   Dengan melakukan *decode-and-re-encode* via `imagecreatefromstring()` dan `imagewebp()`, semua metadata berbahaya atau informasi lokasi kamera otomatis terhapus dari binary file.
3. **Optimasi Ukuran**:
   File WebP terkompresi dengan kualitas 82% menghasilkan ukuran file 60–80% lebih kecil dibanding JPEG asli, mempercepat Loading Card (LCP) pada web.
4. **Dukungan Multiple Upload (Galeri Produk)**:
   Method `handleMultipleImageUploads` membatasi maksimal 10 gambar per batch dengan validasi yang identik.

---

## 🔗 Referensi Alur Terkait
- [04. Product & Catalog Engine](04-product-and-catalog.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
