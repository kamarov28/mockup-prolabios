# 04. Product & Catalog Engine

Dokumen ini menjelaskan sistem katalog produk, struktur multi-level kategori, sektor laboratorium, strategi caching, dan optimasi performa query.

---

## 🏗️ 1. Struktur Data Produk & Relasi

- **Tabel Utama**: `products`
  - Menyimpan informasi produk: `catalog`, `title`, `slug`, `category`, `sub_category`, `sector`, `principal_id`, `price`, `stock`, `image`, `gallery_images` (JSON), `description` (HTML rich text).
- **Kategori & Subkategori**:
  - Disimpan secara dinamis di tabel `product_categories`.
  - Disusun hierarkis dalam struktur pohon (*tree*) melalui `DataService::getCategoriesStructure()`.
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

## 🗄️ 3. Strategi Caching & Auto-Invalidation

Sistem menggunakan caching terversi (*versioned caching*) pada layer `ProductService`:
- `products_list_global`: Cache daftar katalog produk (TTL 3600s).
- `categories_structure`: Cache struktur menu kategori (TTL 3600s).
- `search_suggestions_v2`: Cache kata kunci pencarian populer.

**Invalidasi Otomatis**:
Ketika admin melakukan tambah, edit, atau hapus produk/kategori di dashboard, sistem otomatis memanggil `ProductService::clearProductsCache()`, yang langsung mereset cache dan menaikkan `products_cache_version`.

---

## 🔗 Referensi Alur Terkait
- [03. B2B RFQ & Cart Flow](03-b2b-rfq-flow.md)
- [05. Media & Image Upload Pipeline](05-media-and-uploads.md)
- [06. Admin Dashboard & Authentication](06-admin-and-auth.md)
