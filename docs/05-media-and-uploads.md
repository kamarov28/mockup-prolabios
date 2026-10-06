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
5. **Fail-Fast & Integritas Storage**:
   Upload gambar maupun PDF tidak menoleransi kegagalan diam-diam (*silent fallback*). Berkas yang gagal tersimpan atau melebihi limit PHP `upload_max_filesize` langsung melempar `ValidationException` secara eksplisit, dengan opsi `'throw' => true` aktif di `config/filesystems.php`.
6. **Storage Script Lockdown (`storage/app/public/.htaccess`)**:
   Folder upload publik dikunci dengan aturan Apache/LiteSpeed untuk memblokir eksekusi script (`.php`, `.phtml`, `.cgi`, dll.) serta menonaktifkan engine PHP (`php_flag engine off`).

---

## 🎬 3. Dukungan Pemutaran Video (YouTube & Instagram)

Deskripsi produk (`/produk/{slug}`) dan artikel wawasan (`/informasi/{slug}`) mendukung penyematan video interaktif:
- **Platform yang Didukung**:
  - **YouTube**: Link reguler (`youtube.com/watch?v=...`), tautan pendek (`youtu.be/...`), atau embed (`youtube.com/embed/...`).
  - **Instagram**: Postingan feed (`instagram.com/p/...`) dan Instagram Reels (`instagram.com/reel/...`).
- **Normalisasi URL Otomatis**: Helper `HtmlSanitizer::clean()` secara otomatis mengonversi URL video ke format endpoint pemutar `/embed/`.
- **Keamanan Iframe Terisolasi**:
  - Tag `<iframe>` dikunci menggunakan `HTML.SafeIframe` dan regex domain ketat pada `config/purifier.php`. Seluruh domain asing atau iframe dengan skema `javascript:` langsung dibuang.
  - Directive CSP `frame-src` pada `SecurityHeaders` mengizinkan origin resmi YouTube dan Instagram.
- **Tampilan Responsif 16:9**:
  - Diatur melalui CSS `.profil-body-text iframe` dan `.blog-detail-body iframe` dengan `aspect-ratio: 16 / 9`, lebar responsif 100%, dan sudut 4px presisi agar tidak melorot di layar seluler.

---

## 🔗 Referensi Alur Terkait
- [04. Product & Catalog Engine](04-product-and-catalog.md)
- [07. Security & Hardening Matrix](07-security-and-hardening.md)
