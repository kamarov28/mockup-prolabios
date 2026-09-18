@extends('admin.layout')

@section('title', 'Panduan Operasional Admin')
@section('page_title', 'Panduan Admin')

@section('admin_content')

{{-- Hero Header --}}
<div class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-body" style="padding: 28px 32px;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div>
        <span class="admin-badge admin-badge-accent" style="margin-bottom: 12px;">Manual Operasional Sistem</span>
        <h1 style="font-family: var(--font-headline); font-size: 1.5rem; font-weight: 700; color: var(--color-text-main); margin: 0 0 8px; letter-spacing: -0.3px;">
          Panduan &amp; Dokumentasi Admin Prolabios
        </h1>
        <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0; max-width: 680px; line-height: 1.65;">
          Panduan komprehensif alur operasional portal B2B PT. Prolabios Mitra Analitika: penanganan RFQ korporasi, pengelolaan katalog produk, impor massal spreadsheet Excel, hierarki taksonomi, publikasi artikel berita, dan tata kelola keamanan sistem.
        </p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="admin-badge admin-badge-info" style="font-size: 0.8rem; padding: 6px 12px;">
          <i data-lucide="check-circle-2" style="width: 14px; height: 14px;"></i> Versi Sistem Aktif
        </span>
      </div>
    </div>
  </div>
</div>

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
      <li><strong>Penyusunan Keranjang:</strong> Customer memilih produk laboratorium dan menentukan estimasi kuantitas ke keranjang RFQ (disimpan aman di session browser).</li>
      <li><strong>Pengisian Kredensial Korporat:</strong> Customer memasukkan nama instansi/perusahaan, nomor kontak PIC, email kantor (atau email pribadi yang valid), dan catatan kebutuhan.</li>
      <li><strong>Pengiriman &amp; Notifikasi:</strong> Sistem memproses RFQ secara asinkronus (queue jobs), mengirim email tanda terima ke PIC serta notifikasi ke admin internal.</li>
      <li><strong>Tindak Lanjut Cepat (Follow-up WA):</strong> Admin membuka detail RFQ di <code>/admin/rfqs/{id}</code> dan dapat mengklik tombol <strong>Hubungi via WA</strong> untuk membuka pesan WhatsApp terformat otomatis (nomor RFQ + nama perusahaan).</li>
      <li><strong>Pembaruan Status &amp; Catatan Internal:</strong> Admin memperbarui tahapan status penawaran dan mencatat riwayat negosiasi di form Catatan Internal.</li>
      <li><strong>Cetak Ringkasan:</strong> Gunakan tombol <strong>Cetak / Print</strong> di halaman detail untuk mencetak dokumen fisik penawaran internal.</li>
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
          <li><strong>Identitas Unik:</strong> Judul produk, Nomor Katalog (SKU pabrikan), dan slug URL otomatis untuk SEO.</li>
          <li><strong>Harga &amp; Stok:</strong> Harga patokan rupiah dan ketersediaan stok (Tersedia, Terbatas, Habis).</li>
          <li><strong>Relasi Taksonomi:</strong> Hubungkan ke Kategori utama, Subkategori bersarang, dan centang multi-pilihan Sektor Industri.</li>
          <li><strong>Optimasi Gambar:</strong> Gambar produk otomatis dikonversi dan dioptimalkan ke format WebP ringan.</li>
        </ul>
      </div>

      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Impor Spreadsheet Excel (.xlsx / .csv)</h3>
        <ul class="guide-list">
          <li>Unduh template standar melalui tombol <strong>Template Excel</strong> di form impor katalog.</li>
          <li>Mendukung pembersihan otomatis format angka (misal: <code>Rp 150.000</code> otomatis dibersihkan menjadi <code>150000</code>).</li>
          <li>Pencocokan slug kategori dan sektor otomatis dengan validasi baris sebelum penyimpanan ke database.</li>
          <li>Tersedia juga fitur <strong>Input Web Bulk</strong> untuk entry multi-baris langsung lewat browser tanpa membuka file excel.</li>
        </ul>
      </div>
    </div>

    <div class="guide-note">
      <i data-lucide="file-spreadsheet"></i>
      <div>
        <strong>Struktur Kolom Template Impor Excel:</strong>
        <div class="mt-2" style="font-size: 0.82rem; line-height: 1.6;">
          <code>title</code> (Wajib) &bull; <code>catalog_number</code> (Wajib) &bull; <code>price</code> (Wajib, angka murni) &bull; <code>stock</code> (Wajib, angka) &bull; <code>category_slug</code> (Wajib, slug kategori induk) &bull; <code>subcategories</code> (Opsional, slug dipisahkan koma) &bull; <code>sectors</code> (Opsional, ID sektor dipisahkan koma, misal: <code>hospital,pharma</code>).
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
          <li><strong>Live Search Instan:</strong> Penyaringan real-time berdasarkan nama atau ID sektor di tabel tanpa jeda server reload.</li>
          <li><strong>Cover Thumbnail:</strong> Thumbnail visual sektor lab <code>.admin-post-thumb</code> yang rapi dan konsisten.</li>
          <li><strong>Tautan Produk Terkait:</strong> Badge jumlah produk yang langsung menghubungkan admin ke katalog produk terfilter pada sektor tersebut.</li>
          <li><strong>Proteksi Relasional Database:</strong> Dilengkapi proteksi SweetAlert2 yang memblokir penghapusan jika sektor masih terhubung dengan produk aktif.</li>
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
      <li><strong>Hero Section:</strong> Badge promosi atas, headline utama perusahaan, sub-deskripsi, tombol CTA, serta gambar carousel/slideshow.</li>
      <li><strong>Bento Grid Keunggulan:</strong> Poin nilai tambah Prolabios (distribusi cepat, keaslian sertifikasi, technical support lab).</li>
      <li><strong>Statistik Performa:</strong> Jumlah klien aktif, varian katalog produk, dan jangkauan pengiriman seluruh Indonesia.</li>
      <li><strong>Integrasi Kontak WhatsApp:</strong> Konfigurasi nomor WhatsApp sales penerima lead RFQ langsung.</li>
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
      <a href="{{ route('admin.principals.index') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Kelola Prinsipal</a>
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
          <li>Unggah logo brand prinsipal global terpercaya (Oxoid, Merck, Thermo Scientific, dsb.).</li>
          <li>Tampil otomatis di slider marquee mitra beranda situs dan halaman pengenalan perusahaan.</li>
          <li>Gunakan file gambar dengan rasio proporsional dan latar belakang transparan (PNG/WebP) untuk tampilan terbaik.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

{{-- 6. Modul Keamanan & Tips --}}
<div id="keamanan" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul 06 · Kepatuhan &amp; Proteksi</span>
      <h2 class="admin-card-header-title"><i data-lucide="shield-check" class="me-2" style="color: var(--color-accent);"></i>Keamanan Sistem &amp; Audit Log</h2>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <ul class="guide-list">
        <li><strong>Hak Akses Admin:</strong> Pastikan user memiliki flag <code>is_admin = true</code>. Jangan berbagi akun login antar personil.</li>
        <li><strong>Audit Logging:</strong> Setiap tindakan kritis (pembuatan produk, perubahan status RFQ, modifikasi kategori/sektor) dicatat otomatis di log audit internal.</li>
        <li><strong>Proteksi Rate Limit:</strong> Form login admin, formulir kontak publik, dan pengajuan RFQ dilindungi oleh rate limiter ketat terhadap serangan brute-force.</li>
      </ul>
      <ul class="guide-list">
        <li><strong>Validasi Berkas Unggahan:</strong> Sistem secara otomatis menolak berkas file berbahaya (.svg dengan script, .exe, dsb.) dan mengonversi aset ke format WebP terstandarisasi.</li>
        <li><strong>Penyimpanan Publik:</strong> Pastikan symlink storage terpasang dengan benar di server melalui perintah <code>php artisan storage:link</code>.</li>
        <li><strong>Pengarsipan Data:</strong> Hindari menghapus transaksi RFQ yang valid. Gunakan status <strong>Selesai</strong> untuk pengarsipan historis.</li>
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
