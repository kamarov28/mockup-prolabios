@extends('admin.layout')

@section('title', 'Panduan Operasional Admin')
@section('page_title', 'Panduan Admin')

@section('admin_content')

<x-admin.page-header
  label="Manual Operasional Sistem"
  title="Panduan & Dokumentasi Admin Prolabios"
  description="Panduan komprehensif alur operasional portal B2B PT. Prolabios Mitra Analitika: penanganan RFQ korporasi, pengelolaan katalog produk, impor massal spreadsheet Excel, hierarki taksonomi, publikasi artikel berita, dan tata kelola keamanan sistem."
/>

{{-- Table of Contents (TOC) --}}
<div class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Navigasi Dokumen</span>
      <h2 class="admin-card-header-title">Daftar Isi Panduan</h2>
    </div>
  </div>
  <div class="admin-card-body" style="padding: 16px 20px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px;">
      <a href="#rfq" class="guide-toc-link"><i data-lucide="receipt"></i> 1. RFQ &amp; Pipeline Sales</a>
      <a href="#produk" class="guide-toc-link"><i data-lucide="package"></i> 2. Produk &amp; Impor Excel</a>
      <a href="#kategori" class="guide-toc-link"><i data-lucide="folder-tree"></i> 3. Kategori &amp; Sektor</a>
      <a href="#konten" class="guide-toc-link"><i data-lucide="sliders"></i> 4. Beranda &amp; Konten</a>
      <a href="#artikel" class="guide-toc-link"><i data-lucide="file-text"></i> 5. Artikel &amp; Prinsipal</a>
      <a href="#keamanan" class="guide-toc-link"><i data-lucide="shield-check"></i> 6. Keamanan &amp; Audit Log</a>
    </div>
  </div>
</div>

{{-- 1. Modul RFQ --}}
<div id="rfq" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 01 · Transaksi &amp; Lead</span>
      <h2 class="admin-card-header-title"><i data-lucide="receipt" class="me-2" style="color: var(--color-accent);"></i>Manajemen Pengajuan RFQ (Request for Quotation)</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.rfqs.export') }}" class="admin-btn admin-btn-ghost admin-btn-sm" title="Unduh data RFQ ke format Excel">
        <i data-lucide="file-spreadsheet"></i> Ekspor Excel
      </a>
      <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-primary admin-btn-sm">
        <i data-lucide="external-link"></i> Buka Daftar RFQ
      </a>
    </div>
  </div>
  <div class="admin-card-body">
    <p class="guide-lead">
      Sistem ini beroperasi dengan model <strong>B2B Quotation Lead Management</strong> (bukan checkout instan retail). Pelanggan instansi/laboratorium menyusun daftar kebutuhan barang untuk meminta penawaran harga resmi dari tim Sales Prolabios.
    </p>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
      <div class="guide-feature-box">
        <h4 class="guide-feature-title"><i data-lucide="kanban" style="color: var(--color-accent);"></i> Tampilan Kanban &amp; Tabel</h4>
        <p class="guide-feature-desc">
          Beralih antara mode <strong>Tabel Data</strong> untuk pencarian detail dan filter tanggal, atau mode <strong>Kanban Board</strong> untuk melihat pipeline visual status penawaran per kolom tahapan.
        </p>
      </div>
      <div class="guide-feature-box">
        <h4 class="guide-feature-title"><i data-lucide="file-spreadsheet" style="color: #059669;"></i> Ekspor Spreadsheet (.xlsx)</h4>
        <p class="guide-feature-desc">
          Unduh rekapan transaksi RFQ lengkap via PhpSpreadsheet dengan format terstruktur, badge warna status otomatis, detail kontak PIC, serta ringkasan kuantitas barang.
        </p>
      </div>
    </div>

    <h3 class="guide-h3">Alur Operasional RFQ Aktif</h3>
    <ol class="guide-steps">
      <li><strong>Penyusunan Keranjang:</strong> Customer memilih produk laboratorium dan menentukan estimasi kuantitas ke keranjang RFQ (disimpan aman di session browser hingga 7 hari / 10.080 menit).</li>
      <li><strong>Pengisian Kredensial Korporat:</strong> Customer memasukkan nama instansi/perusahaan, nomor kontak PIC, email kantor (atau email pribadi yang valid), dan catatan kebutuhan.</li>
      <li><strong>Pengiriman &amp; Notifikasi Asinkron:</strong> Sistem memproses RFQ secara asinkronus (queue jobs), mengirim email tanda terima ke PIC serta notifikasi ke admin internal.</li>
      <li><strong>Tindak Lanjut Cepat (Dual-Channel Follow-up):</strong> Admin membuka detail RFQ di <code>/admin/rfqs/{id}</code> dan dapat mengklik tombol <strong>Hubungi via WA</strong> untuk membuka pesan WhatsApp terformat otomatis (nomor RFQ + nama perusahaan), atau mengirim email penawaran resmi.</li>
      <li><strong>Pembaruan Status &amp; Catatan Internal:</strong> Admin memperbarui tahapan status penawaran dan mencatat riwayat negosiasi di form Catatan Internal.</li>
      <li><strong>Format Dokumen Formal &amp; Cetak:</strong> Nomor pengajuan RFQ berformat dokumen formal laboratorium (<code>PRL-YYYY-XXXXX</code>) dengan kotak ringkasan berdesain flat presisi serta tombol <strong>Cetak Bukti RFQ</strong>.</li>
    </ol>

    <h3 class="guide-h3">Klasifikasi Status RFQ</h3>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th style="width: 140px;">Status</th>
            <th>Definisi Operasional</th>
            <th>Tindakan yang Diperlukan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="admin-badge admin-badge-warning">Baru</span></td>
            <td>Pengajuan baru masuk dari formulir website, belum dihubungi oleh tim sales.</td>
            <td>Buka detail RFQ, verifikasi nomor kontak &amp; ketersediaan stok, lalu hubungi PIC via WhatsApp / Email.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-info">Dihubungi</span></td>
            <td>Tim sales telah menghubungi customer dan sedang dalam proses konfirmasi spek/harga.</td>
            <td>Lanjutkan komunikasi, kaji permintaan kustom/diskon kuantitas, dan catat progres di Catatan Internal.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-accent">Quoted</span></td>
            <td>Surat penawaran harga resmi (Quotation) telah diterbitkan dan dikirimkan ke pihak customer.</td>
            <td>Pantau konfirmasi PO (Purchase Order) dari customer hingga ada kesepakatan final.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-muted">Selesai</span></td>
            <td>Proses transaksi selesai (baik kesepakatan deal pembelian ataupun pengajuan dibatalkan).</td>
            <td>Arsipkan RFQ. Jangan menghapus data jika transaksi valid agar riwayat statistik tetap tercatat.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- 2. Modul Produk & Impor Excel --}}
<div id="produk" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 02 · Katalog &amp; Stok</span>
      <h2 class="admin-card-header-title"><i data-lucide="package" class="me-2" style="color: var(--color-accent);"></i>Manajemen Produk &amp; Impor Massal Excel</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.products.import.template') }}" class="admin-btn admin-btn-ghost admin-btn-sm" title="Unduh template Excel untuk input produk">
        <i data-lucide="download"></i> Template Excel
      </a>
      <a href="{{ route('admin.products.create.bulk') }}" class="admin-btn admin-btn-ghost admin-btn-sm">
        <i data-lucide="table-properties"></i> Input Web Bulk
      </a>
      <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-primary admin-btn-sm">
        <i data-lucide="package"></i> Kelola Produk
      </a>
    </div>
  </div>
  <div class="admin-card-body">
    <p class="guide-lead">
      Katalog produk mendukung pengelolaan item tunggal maupun impor data massal ratusan item sekaligus menggunakan spreadsheet Excel (.xlsx) atau CSV.
    </p>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Pengelolaan Produk Tunggal</h3>
        <ul class="guide-list">
          <li><strong>Identitas Unik:</strong> Judul produk, Nomor Katalog (SKU pabrikan), dan slug URL kanonikal otomatis untuk SEO.</li>
          <li><strong>Harga &amp; Stok:</strong> Harga patokan rupiah dan ketersediaan stok riil (otomatis badge indikator hijau/merah).</li>
          <li><strong>Relasi Taksonomi:</strong> Hubungkan ke Kategori utama, Subkategori dinamis, dan centang multi-pilihan Sektor Industri (dilengkapi tombol pintas <em>Pilih Semua / Hapus Semua</em>).</li>
          <li><strong>Produk Unggulan:</strong> Toggle switch <em>Produk Unggulan</em> untuk menampilkan item pada 4 slot kartu prioritas beranda situs.</li>
          <li><strong>Multi-Foto Galeri:</strong> Dilengkapi dropzone untuk mengunggah hingga 10 foto galeri pendukung selain Cover Utama.</li>
          <li><strong>Dokumen Spesifikasi PDF:</strong> Opsi unggah file PDF langsung ke server lokal (direkomendasikan) atau tautan URL eksternal.</li>
        </ul>
      </div>

      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Impor Spreadsheet Excel (.xlsx / .csv)</h3>
        <ul class="guide-list">
          <li>Unduh template standar multi-sheet melalui tombol <strong>Template Excel</strong> di form impor katalog.</li>
          <li><strong>Sheet 1 (Data Produk):</strong> Kolom pengisian data terstruktur lengkap dengan pembersihan otomatis format angka (misal: <code>Rp 1.500.000</code> otomatis menjadi <code>1500000</code>).</li>
          <li><strong>Sheet 2 (Panduan &amp; Referensi):</strong> Daftar master data resmi Kategori, Sektor, dan Prinsipal yang disinkronkan langsung dari database aktif.</li>
          <li><strong>Mekanisme Auto-Upsert:</strong> Jika Nama Produk sudah ada di database, data diperbarui otomatis; jika belum ada, sistem membuat produk baru.</li>
          <li><strong>Input Web Bulk (Alternatif Browser):</strong> Formulir multi-kartu dinamis langsung di browser. Disarankan <strong>maksimal 10 produk</strong> (batas aman kritis <strong>15 produk</strong>) jika melampirkan berkas fisik cover &amp; PDF karena batasan PHP <code>max_file_uploads = 20</code>.</li>
        </ul>
      </div>
    </div>

    <div class="guide-note">
      <i data-lucide="file-spreadsheet"></i>
      <div>
        <strong>Struktur Kolom Template Impor Excel (Sheet 1 - Data Produk):</strong>
        <div class="mt-2" style="font-size: 0.82rem; line-height: 1.6;">
          <code>A: Nomor Katalog</code> &bull; <code>B: Nama Produk *</code> (Wajib) &bull; <code>C: Kategori *</code> (Wajib, kode/nama kategori) &bull; <code>D: Subkategori</code> &bull; <code>E: Harga (Rp)</code> (Angka) &bull; <code>F: Stok</code> (Angka) &bull; <code>G: Prinsipal</code> (Nama/ID) &bull; <code>H: Sektor Industri</code> (Dipisahkan koma) &bull; <code>I: URL Cover Gambar</code> &bull; <code>J: URL Datasheet PDF</code> &bull; <code>K: Deskripsi Produk</code> (Mendukung teks deskripsi/HTML).
        </div>
      </div>
    </div>
  </div>
</div>

{{-- 3. Modul Kategori & Sektor --}}
<div id="kategori" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 03 · Taksonomi &amp; Pemetaan</span>
      <h2 class="admin-card-header-title"><i data-lucide="folder-tree" class="me-2" style="color: var(--color-accent);"></i>Hierarki Kategori &amp; Sektor Industri</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Kategori</a>
      <a href="{{ route('admin.sectors') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Sektor</a>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">
          <i data-lucide="folder-tree" class="me-1" style="color: var(--color-accent); width: 16px; height: 16px;"></i> Hierarki Kategori &amp; Subkategori
        </h3>
        <p class="guide-lead" style="margin-bottom: 12px; font-size: 0.88rem;">
          Struktur klasifikasi 2-tingkat (Parent Category dan Subkategori) untuk katalog produk laboratorium:
        </p>
        <ul class="guide-list">
          <li><strong>Tree Accordion UI:</strong> Menampilkan relasi bertingkat antara kategori induk (Level 1) dan subkategori spesifik (Level 2) yang dapat dibuka/tutup interaktif.</li>
          <li><strong>Slug Key Badge:</strong> Dilengkapi tanda pengenal unik <span class="cat-key-badge">#slug-kategori</span> untuk kemudahan mapping excel/impor.</li>
          <li><strong>Counter Relasi Produk:</strong> Menampilkan indikator jumlah produk yang terhubung secara riil.</li>
          <li><strong>Proteksi Hapus:</strong> Kategori yang masih memiliki subkategori aktif atau produk terkait tidak dapat dihapus sembarangan guna mencegah broken relationship.</li>
        </ul>
      </div>

      <div>
        <h3 class="guide-h3" style="margin-top: 0;">
          <i data-lucide="layers" class="me-1" style="color: var(--color-accent); width: 16px; height: 16px;"></i> Manajemen Sektor Industri
        </h3>
        <p class="guide-lead" style="margin-bottom: 12px; font-size: 0.88rem;">
          Pemetaan target bidang bisnis pengguna produk untuk filter publik dan navigasi industri:
        </p>
        <ul class="guide-list">
          <li><strong>Bilah Pencarian Terstandar:</strong> Dilengkapi komponen <code>&lt;x-admin.search-input&gt;</code> dengan padding ikon presisi dan tombol reset instan.</li>
          <li><strong>Live Search Instan:</strong> Penyaringan real-time berdasarkan nama atau ID sektor di tabel tanpa jeda server reload.</li>
          <li><strong>Cover Thumbnail:</strong> Thumbnail visual sektor lab <code>.admin-post-thumb</code> yang rapi dan konsisten.</li>
          <li><strong>Integritas Relasi Pivot:</strong> Setiap penugasan sektor disinkronkan ke tabel pivot <code>product_sector</code>, dan otomatis dibersihkan saat produk dihapus massal guna mencegah orphan data.</li>
          <li><strong>Proteksi Hapus Sektor:</strong> Dilengkapi proteksi konfirmasi SweetAlert2 yang memblokir penghapusan jika sektor masih terhubung dengan produk aktif.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

{{-- 4. Modul Beranda & Konten --}}
<div id="konten" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 04 · Visual &amp; Marketing</span>
      <h2 class="admin-card-header-title"><i data-lucide="sliders" class="me-2" style="color: var(--color-accent);"></i>Pengaturan Halaman Depan / Beranda</h2>
    </div>
    <a href="{{ route('admin.home.edit') }}" class="admin-btn admin-btn-ghost admin-btn-sm">
      <i data-lucide="edit-3"></i> Buka Editor Beranda
    </a>
  </div>
  <div class="admin-card-body">
    <p class="guide-lead">
      Pengaturan komponen visual dan teks kredibilitas pada halaman beranda publik tanpa perlu menyentuh kode program:
    </p>
    <ul class="guide-list">
      <li><strong>Hero Section &amp; Aksen Teks:</strong> Headline utama mendukung penyisipan tag khusus seperti <code>&lt;span class="text-accent"&gt;Kata Kunci&lt;/span&gt;</code> atau <code>&lt;span&gt;</code> agar kata tersebut otomatis menyala dengan warna merah Ruby khas Prolabios.</li>
      <li><strong>4 Slot Produk Unggulan:</strong> Beranda menampilkan 4 produk unggulan terbaru yang dicentang (<code>is_featured = true</code>). Jika produk berbintang kurang dari 4, sistem memiliki auto-fallback cerdas yang otomatis melengkapi slot kosong dari katalog terbaru agar tampilan grid tetap simetris.</li>
      <li><strong>Bento Grid Keunggulan:</strong> Poin nilai tambah Prolabios (distribusi rantai dingin teruji, keaslian sertifikasi COA/lot, technical support lab).</li>
      <li><strong>Statistik Performa:</strong> Jumlah klien aktif, varian katalog produk, dan jangkauan pengiriman seluruh Indonesia.</li>
      <li><strong>Integrasi Kontak WhatsApp:</strong> Konfigurasi nomor WhatsApp sales penerima lead RFQ langsung.</li>
      <li><strong>Tipografi Mandiri (Self-Hosted):</strong> Font IBM Plex Sans &amp; Mono telah di-host secara lokal di server Prolabios, bebas dari latensi DNS Google Fonts dan memiliki masa cache 1 tahun.</li>
    </ul>
  </div>
</div>

{{-- 5. Modul Artikel & Prinsipal --}}
<div id="artikel" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 05 · Publikasi &amp; Kemitraan</span>
      <h2 class="admin-card-header-title"><i data-lucide="file-text" class="me-2" style="color: var(--color-accent);"></i>Artikel Berita &amp; Prinsipal Laboratorium</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Kelola Artikel</a>
      <a href="{{ route('admin.principals') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Kelola Prinsipal</a>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">
          <i data-lucide="file-text" class="me-1" style="color: var(--color-accent); width: 16px; height: 16px;"></i> Artikel Berita &amp; Edukasi Lab
        </h3>
        <ul class="guide-list">
          <li><strong>Toolbar Pencarian &amp; Filter:</strong> Filter instan berdasarkan judul/konten, kategori berita, status publikasi (Published / Draft), serta rentang tanggal rilis.</li>
          <li><strong>Status Publikasi:</strong> Gunakan status <span class="admin-badge admin-badge-success" style="font-size: 0.72rem;">Online</span> untuk rilis publik, atau <span class="admin-badge admin-badge-warning" style="font-size: 0.72rem;">Draft</span> saat masih disunting.</li>
          <li><strong>Label Unggulan:</strong> Centang artikel sebagai <span class="admin-badge admin-badge-accent" style="font-size: 0.72rem;">Unggulan</span> agar tampil sebagai sorotan utama (featured article).</li>
          <li><strong>Editor Rich Text:</strong> Menggunakan Summernote dengan pembersihan sanitasi tag HTML berbahaya untuk mencegah injeksi script.</li>
        </ul>
      </div>

      <div>
        <h3 class="guide-h3" style="margin-top: 0;">
          <i data-lucide="award" class="me-1" style="color: var(--color-accent); width: 16px; height: 16px;"></i> Prinsipal / Mitra Manufaktur
        </h3>
        <ul class="guide-list">
          <li>Unggah logo brand prinsipal global terpercaya (Liofilchem, Bioendo, Terragene, Scharlau, dsb.).</li>
          <li>Tampil otomatis di slider marquee mitra beranda situs dan halaman katalog produk.</li>
          <li>Gunakan file gambar dengan rasio proporsional dan latar belakang transparan (PNG/WebP) untuk tampilan terbaik.</li>
          <li><strong>Proteksi Integritas Katalog:</strong> Sistem menolak penghapusan prinsipal jika masih terhubung dengan data produk aktif untuk mencegah kerusakan relasi.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

{{-- 6. Modul Keamanan & Tips --}}
<div id="keamanan" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 06 · Kepatuhan, Infrastruktur &amp; Proteksi</span>
      <h2 class="admin-card-header-title"><i data-lucide="shield-check" class="me-2" style="color: var(--color-accent);"></i>Keamanan Sistem, Server &amp; Audit Log</h2>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <ul class="guide-list">
        <li><strong>Hak Akses Admin:</strong> Pastikan user memiliki flag <code>is_admin = true</code>. Jangan berbagi akun login antar personil.</li>
        <li><strong>Audit Logging:</strong> Setiap tindakan kritis (pembuatan produk, perubahan status RFQ, modifikasi kategori/sektor, import excel) dicatat otomatis di log audit internal.</li>
        <li><strong>Proteksi Rate Limit:</strong> Form login admin (<code>throttle:admin-login</code>), formulir kontak publik, dan pengajuan RFQ dilindungi oleh rate limiter ketat terhadap serangan brute-force.</li>
        <li><strong>Sanitasi HTMLPurifier:</strong> Seluruh input konten kaya (deskripsi produk &amp; artikel) dibersihkan dari tag berbahaya seperti <code>&lt;script&gt;</code> dan <code>&lt;iframe&gt;</code>.</li>
      </ul>
      <ul class="guide-list">
        <li><strong>Lockdown Folder Uploads:</strong> Folder publik (<code>storage/app/public/.htaccess</code>) dikunci ketat dengan <code>php_flag engine off</code> dan pemblokiran eksekusi skrip (.php, .phtml, .cgi) untuk mencegah ancaman web-shell.</li>
        <li><strong>Izin Berkas Kredensial (.env):</strong> Di server hosting cPanel DomaiNesia, pastikan berkas <code>.env</code> selalu diatur dengan izin hak akses aman <code>chmod 600</code> atau <code>640</code>.</li>
        <li><strong>Otomasi Cronjob cPanel:</strong> Pastikan cronjob cPanel <code>php artisan schedule:run</code> berjalan tiap menit untuk memproses antrean email RFQ secara otomatis serta mencadangkan basis data harian jam 02:00.</li>
        <li><strong>Pengarsipan Data Transaksi:</strong> Hindari menghapus transaksi RFQ yang valid. Gunakan status <strong>Selesai</strong> untuk pengarsipan historis.</li>
      </ul>
    </div>
  </div>
</div>

<style>
  .guide-toc-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border: 1px solid var(--color-border, #E5E7EB);
    border-radius: 8px;
    background: #FFFFFF;
    box-shadow: var(--shadow-xs);
    font-family: var(--font-body);
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--color-text-main);
    text-decoration: none;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
  }
  .guide-toc-link i { color: var(--color-accent, #A6171C); font-size: 1rem; }
  .guide-toc-link:hover {
    color: var(--color-accent);
    background-color: var(--color-surface-2, #F3F4F6);
    border-color: #D1D5DB;
    transform: translateY(-1px);
  }
  .guide-lead {
    font-size: 0.92rem;
    color: var(--color-text-main);
    line-height: 1.65;
    margin: 0 0 18px;
  }
  .guide-h3 {
    font-family: var(--font-headline);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--color-text-muted);
    margin: 24px 0 12px;
    display: flex;
    align-items: center;
  }
  .guide-steps {
    margin: 0 0 16px;
    padding-left: 1.25rem;
    color: var(--color-text-secondary, #374151);
    font-size: 0.88rem;
    line-height: 1.75;
  }
  .guide-steps li { margin-bottom: 8px; }
  .guide-list {
    margin: 0;
    padding-left: 1.15rem;
    color: var(--color-text-secondary, #374151);
    font-size: 0.88rem;
    line-height: 1.75;
  }
  .guide-list li { margin-bottom: 6px; }
  .guide-feature-box {
    background: var(--color-surface-2, #F9FAFB);
    border: 1px solid var(--color-border, #E5E7EB);
    border-radius: 10px;
    padding: 16px 18px;
  }
  .guide-feature-title {
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0 0 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--color-text-main);
  }
  .guide-feature-desc {
    font-size: 0.84rem;
    color: var(--color-text-secondary);
    margin: 0;
    line-height: 1.55;
  }
  .guide-note {
    display: flex;
    gap: 14px;
    margin-top: 20px;
    padding: 16px 18px;
    border-radius: 10px;
    border: 1px solid #FEF3C7;
    background: #FFFBEB;
    color: #92400E;
    font-size: 0.85rem;
    line-height: 1.6;
  }
  .guide-note i { color: #D97706; font-size: 1.2rem; flex-shrink: 0; margin-top: 2px; }
  .guide-note strong { color: #78350F; }
  code {
    font-family: var(--font-mono, 'JetBrains Mono', Consolas, monospace);
    font-size: 0.82em;
    font-weight: 500;
    color: var(--color-accent, #A6171C);
    background: var(--color-surface-2, #F3F4F6);
    border: 1px solid var(--color-border, #E5E7EB);
    padding: 2px 6px;
    border-radius: 6px;
  }
  @media (max-width: 768px) {
    .admin-card-body > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

@endsection
