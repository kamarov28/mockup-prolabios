# 04. Product & Catalog Engine

Dokumen ini menjelaskan sistem katalog produk, struktur multi-level kategori, sektor laboratorium, strategi caching, dan optimasi performa query.

---

## 🏗️ 1. Struktur Data Produk & Relasi

- **Tabel Utama**: `products`
  - Menyimpan informasi produk: `catalog`, `title`, `slug`, `category`, `sub_category`, `sector`, `principal_id`, `price`, `stock`, `image`, `gallery_images` (JSON), `description` (HTML rich text).
- **Kategori & Subkategori**:
  - Disimpan secara dinamis di tabel `product_categories`.
  - Disusun hierarkis dalam struktur pohon (*tree*) melalui `ProductService::getCategoriesStructure()`.
- **Relasi Multi-Sektor**:
  - Produk dapat berelasi dengan banyak sektor industri (misal: Farmasi, Pangan, Lingkungan) melalui tabel pivot `product_sector`.

---

## ⚡ 2. Optimasi Query & Lean Payload

Untuk menjaga waktu respon halaman katalog tetap di bawah 50ms:
- **Lean Columns (`ProductService::listColumns()`)**:
  - Halaman list publik (`/produk`), card sektor, dan admin table **tidak memuat** kolom `description` (HTML berat) maupun `gallery_images` (JSON panjang).
  - Kolom lengkap hanya dimuat ketika membuka halaman detail spesifik (`/produk/{slug}`).
- **Debounced Live Search & Abort Controller**:
  - Input pencarian di `/produk` menggunakan debounce **250ms**.
  - Menggunakan `AbortController` di frontend: jika pengguna mengetik huruf baru dengan cepat, request AJAX yang lama langsung dibatalkan di browser sebelum membebani server.

---

## 🔍 3. Mesin Pencarian Substring Karakter (`scopeSearch`)

Pencarian produk (`Product::scopeSearch()`) dirancang khusus untuk nomenklatur laboratorium yang sensitif:
- **Pencocokan Substring Huruf (`LIKE %term%`)**: Menghindari kekakuan mesin Full-Text index (misalnya pencarian `yeast` tetap dapat menemukan `Integral System Yeasts Plus`).
- **Multi-Kolom Komprehensif**: Melintasi kolom `title`, `catalog`, `description`, `function`, `reference_method`, dan `packaging`.
- **Dukungan Multi-Kata Independen**: Jika pengguna mengetik kata majemuk dengan urutan terbalik (misal `yeast integral`), sistem tetap dapat menemukan produk yang sesuai.

---

## ⭐ 4. Algoritma Hybrid Highlight & Trending (`getFeaturedProducts`)

Beranda website menggunakan algoritma 3-tier dinamis untuk menampilkan 4 kartu produk unggulan:
1. **Tier 1 (Pilihan Manual)**: Produk yang ditandai bintang oleh admin (`is_featured = true`).
2. **Tier 2 (Trending Populer)**: Jika slot belum terisi penuh, sisa slot diisi otomatis oleh produk dengan pencarian/kunjungan terbanyak (`search_hits DESC`).
3. **Tier 3 (Fallback)**: Mengisi sisa slot kosong dengan produk katalog terbaru.
- **Counter Hits Terproteksi**: Peningkatan skor `search_hits` pada pencarian dan kunjungan detail produk dilindungi dengan *session throttling* untuk mencegah bot/spam inflasi data.
- **Badge Visual**: Kartu produk secara otomatis menampilkan badge `Pilihan` (bintang Ruby) atau `Populer` (ikon trending emas).

---

## 🗄️ 5. Strategi Caching & Auto-Invalidation

Sistem menggunakan caching terversi (*versioned caching*) pada layer `ProductService`:
- `products_list_global`: Cache daftar katalog produk (TTL 3600s).
- `featured_products_v3_*`: Cache produk unggulan beranda (TTL 180s).
- `categories_structure`: Cache struktur menu kategori (TTL 3600s).
- `search_suggestions_v2`: Cache kata kunci pencarian populer.

**Invalidasi Otomatis**:
Ketika admin melakukan tambah, edit, atau hapus produk/kategori di dashboard, sistem otomatis memanggil `ProductService::clearProductsCache()`, yang langsung mereset cache dan menaikkan `products_cache_version`.

---

## 📥 6. Import Spreadsheet Katalog (`ProductImportService`)

Mendukung impor massal produk melalui format spreadsheet Excel (`.xlsx`) / CSV:
- **Auto-Creation Kategori & Subkategori**: Jika nama kategori/subkategori pada spreadsheet belum terdaftar di database, sistem langsung membuatnya seketika lengkap dengan slug dan relasi parent.
- **Auto-Creation Sektor Industri**: Jika kolom sektor memuat nama sektor baru, sistem otomatis mendaftarkannya ke tabel `sectors` dan menghubungkannya via pivot `product_sector`.
- **Skip Duplikat Aman**: Jika judul atau nomor katalog sudah ada di database, baris tersebut otomatis dilewati (*skipped*) tanpa menimpa data master yang telah diubah manual.
- **Sanitasi Deskripsi & Ekstensi URL**: Kolom deskripsi HTML disanitasi dengan `HtmlSanitizer::clean()`. URL gambar mendukung link eksternal (`https://`) maupun path lokal (`/storage/uploads/...`).
- **Self-Healing Schema**: Method `ProductService::ensureSpecificationColumnsExist()` memeriksa dan mengeksekusi `ALTER TABLE` kolom `packaging`, `function`, `reference_method`, dan `search_hits` secara otomatis saat runtime web jika migrasi SSH belum dijalankan.

---

## 🔗 Referensi Alur Terkait
- [03. B2B RFQ & Cart Flow](03-b2b-rfq-flow.md)
- [05. Media & Image Upload Pipeline](05-media-and-uploads.md)
- [06. Admin Dashboard & Authentication](06-admin-and-auth.md)
