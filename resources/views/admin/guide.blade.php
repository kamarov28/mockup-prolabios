@extends('admin.layout')

@section('title', 'Buku Panduan Operasional Admin')
@section('page_title', 'Buku Panduan & Manual Operasional Admin')

@section('admin_content')
<style>
  .manual-hero-banner {
    background: linear-gradient(135deg, rgba(255, 73, 80, 0.15) 0%, rgba(14, 14, 16, 1) 100%);
    border: 1px solid rgba(255, 73, 80, 0.3);
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 28px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
  }
  .manual-chapter-card {
    background: #0e0e10;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 28px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
    scroll-margin-top: 80px;
  }
  .manual-chapter-title {
    font-family: var(--font-headline);
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 20px;
  }
  .manual-subchapter-title {
    font-family: var(--font-headline);
    font-size: 1.05rem;
    font-weight: 600;
    color: #ff4950;
    margin-top: 24px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .manual-step-item {
    background: #141416;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 14px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
  }
  .manual-step-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ff4950;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
    box-shadow: 0 0 12px rgba(255, 73, 80, 0.4);
  }
  .manual-callout-info {
    background: rgba(13, 110, 253, 0.08);
    border-left: 4px solid #0d6efd;
    border-radius: 0 8px 8px 0;
    padding: 14px 18px;
    font-size: 0.88rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 16px 0;
  }
  .manual-callout-success {
    background: rgba(46, 125, 50, 0.1);
    border-left: 4px solid #4caf50;
    border-radius: 0 8px 8px 0;
    padding: 14px 18px;
    font-size: 0.88rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 16px 0;
  }
  .manual-toc-item {
    background: #141416;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 12px 16px;
    color: #ffffff;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
  }
  .manual-toc-item:hover {
    border-color: #ff4950;
    color: #ff4950;
    transform: translateX(4px);
  }
  .manual-table {
    width: 100%;
    border-collapse: collapse;
    margin: 14px 0;
    font-size: 0.85rem;
  }
  .manual-table th {
    background: #18191c;
    color: rgba(255, 255, 255, 0.8);
    padding: 10px 14px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-transform: uppercase;
    font-size: 0.78rem;
  }
  .manual-table td {
    padding: 10px 14px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.75);
    background: #111215;
  }
</style>

<div class="max-w-4xl mx-auto">
  
  <!-- Header Banner -->
  <div class="manual-hero-banner">
    <div class="d-flex align-items-center gap-2 mb-2">
      <span class="badge px-3 py-2 text-uppercase fw-bold" style="background: rgba(255, 73, 80, 0.2); color: #ff4950; border: 1px solid rgba(255, 73, 80, 0.4); border-radius: 20px;">
        <i class="bi bi-journal-bookmark-fill me-1"></i> BUKU MANUAL OPERASIONAL ADMIN
      </span>
    </div>
    <h1 class="h2 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Panduan Pengelolaan B2B E-Procurement &amp; Portal Website</h1>
    <p class="text-secondary small mb-0" style="max-width: 680px; line-height: 1.6;">
      Selamat datang di Buku Manual Resmi <strong>PT. Prolabios Mitra Analitika</strong>. Panduan ini berisi petunjuk langkah demi langkah lengkap untuk mengelola pengajuan penawaran B2B (RFQ), penetapan diskon korporasi, pengelolaan katalog produk, hingga pengaturan konten utama website.
    </p>
  </div>

  <!-- Quick Nav / Daftar Isi -->
  <div class="manual-chapter-card">
    <h3 class="h6 fw-bold text-white mb-3 text-uppercase letter-spacing-1 d-flex align-items-center gap-2">
      <i class="bi bi-list-nested text-danger"></i> Daftar Isi Manual Operasional
    </h3>
    <div class="row g-2">
      <div class="col-md-6">
        <a href="#bab-1" class="manual-toc-item">
          <span>BAB 1: Pengelolaan Penawaran B2B (RFQ System)</span>
          <i class="bi bi-arrow-right-short fs-5"></i>
        </a>
      </div>
      <div class="col-md-6">
        <a href="#bab-2" class="manual-toc-item">
          <span>BAB 2: Katalog Produk &amp; Impor Masal (Bulk)</span>
          <i class="bi bi-arrow-right-short fs-5"></i>
        </a>
      </div>
      <div class="col-md-6">
        <a href="#bab-3" class="manual-toc-item">
          <span>BAB 3: Pengaturan Beranda (Homepage CMS)</span>
          <i class="bi bi-arrow-right-short fs-5"></i>
        </a>
      </div>
      <div class="col-md-6">
        <a href="#bab-4" class="manual-toc-item">
          <span>BAB 4: Manajemen Artikel &amp; Prinsipal Lisensi</span>
          <i class="bi bi-arrow-right-short fs-5"></i>
        </a>
      </div>
    </div>
  </div>

  <!-- BAB 1 -->
  <div id="bab-1" class="manual-chapter-card">
    <div class="manual-chapter-title">
      <i class="bi bi-file-earmark-text-fill text-danger fs-4"></i>
      <span>BAB 1: Pengelolaan Pengajuan Penawaran Harga B2B (RFQ System)</span>
    </div>

    <p class="text-secondary small mb-3">
      Sistem *Request for Quotation* (RFQ) menangani seluruh alur transaksi penawaran harga berskala B2B/korporasi dari institusi, laboratorium, dan industri.
    </p>

    <div class="manual-subchapter-title">
      <i class="bi bi-diagram-3-fill"></i> 1.1 Memahami Status Pengajuan RFQ
    </div>
    <p class="text-secondary small mb-2">Terdapat 3 status utama dalam alur pengajuan penawaran:</p>
    <table class="manual-table">
      <thead>
        <tr>
          <th style="width: 140px;">Status</th>
          <th>Deskripsi &amp; Arti Status</th>
          <th>Tindakan Selanjutnya</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge bg-warning text-dark">Pending Review</span></td>
          <td>Pembeli korporasi baru saja selesai mengajukan formulir RFQ dari website. Penawaran belum di-review oleh sales.</td>
          <td>Admin Sales membuka detail &amp; menetapkan harga satuan resmi.</td>
        </tr>
        <tr>
          <td><span class="badge bg-info text-dark">Quotation Sent</span></td>
          <td>Tim Sales telah memasukkan harga satuan, diskon, &amp; menerbitkan PDF Surat Penawaran Resmi ke pembeli.</td>
          <td>Menunggu pembeli melakukan konfirmasi / menyetujui penawaran.</td>
        </tr>
        <tr>
          <td><span class="badge bg-success">Approved</span></td>
          <td>Pembeli telah menyetujui penawaran harga. <strong>Stok produk di basis data otomatis terpotong</strong>.</td>
          <td>Tim Finance &amp; Logistik menerbitkan Invoice &amp; mengirimkan barang.</td>
        </tr>
      </tbody>
    </table>

    <div class="manual-subchapter-title">
      <i class="bi bi-pencil-square"></i> 1.2 Langkah Penyesuaian Harga &amp; Pemberian Diskon Korporasi
    </div>

    <div class="manual-step-item">
      <div class="manual-step-badge">1</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Akses Menu Penawaran (RFQ)</h5>
        <p class="text-muted small mb-0">
          Pada sidebar kiri, klik menu <strong>Penawaran (RFQ)</strong>. Cari pengajuan berdasarkan Nama Perusahaan atau Nomor RFQ (misal: <code>RFQ-202607-XXXXXX</code>), lalu klik tombol <span class="badge bg-danger">Beri Feedback</span>.
        </p>
      </div>
    </div>

    <div class="manual-step-item">
      <div class="manual-step-badge">2</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Mengubah Harga Satuan per Item (Direct Discount)</h5>
        <p class="text-muted small mb-2">
          Pada tabel <em>1. Penyesuaian Harga Penawaran per Item</em>, ubah nominal pada kolom <strong>Harga Satuan (Rp)</strong> untuk item yang ingin didiskon. Subtotal dan total harga penawaran akan otomatis terhitung secara instan.
        </p>
        <div class="manual-callout-info my-2">
          <strong>Skenario Contoh:</strong><br>
          • Harga Katalog Normal MRS Broth: <code>Rp 420.000</code><br>
          • Jika memberikan diskon grosir 5%, ubah angka menjadi <code>399000</code>. Subtotal untuk 2 unit otomatis menjadi <code>Rp 798.000</code>.
        </div>
      </div>
    </div>

    <div class="manual-step-item">
      <div class="manual-step-badge">3</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Mengisi Catatan Sales &amp; Masa Berlaku</h5>
        <p class="text-muted small mb-0">
          Tuliskan rincian diskon atau bonus pada textarea <strong>Catatan Penawaran Sales untuk Korporasi</strong> (contoh: <em>"Termasuk Diskon Volume Kuantitas 5% dan Bebas Biaya Kirim area Jabodetabek"</em>). Tentukan tanggal batas berlaku penawaran pada field <strong>Penawaran Berlaku Sampai Tanggal</strong>.
        </p>
      </div>
    </div>

    <div class="manual-step-item">
      <div class="manual-step-badge">4</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Penerbitan Surat Penawaran &amp; Pengiriman Email</h5>
        <p class="text-muted small mb-0">
          Klik tombol <strong>Simpan &amp; Kirim Feedback Email ke Korporasi</strong>. Sistem akan otomatis menyusun PDF Surat Penawaran Resmi dan mengirimkan notifikasi email ke pembeli.
        </p>
      </div>
    </div>

    <div class="manual-callout-success">
      <i class="bi bi-check-circle-fill me-1"></i> <strong>Otomasi Stok Setelah Approval:</strong> Begitu pembeli menyetujui penawaran di portal pemantauan status, sistem secara otomatis mengalokasikan stok di database sehingga jumlah stok produk di gudang terpotong secara real-time.
    </div>
  </div>

  <!-- BAB 2 -->
  <div id="bab-2" class="manual-chapter-card">
    <div class="manual-chapter-title">
      <i class="bi bi-box-seam-fill text-danger fs-4"></i>
      <span>BAB 2: Manajemen Katalog Produk &amp; Impor Masal (Bulk Import)</span>
    </div>

    <div class="manual-subchapter-title">
      <i class="bi bi-plus-circle-fill"></i> 2.1 Menambah &amp; Mengedit Produk Tunggal
    </div>
    <p class="text-secondary small mb-3">
      Buka menu <strong>Produk</strong> di sidebar, lalu klik <strong>+ Tambah Produk Baru</strong>. Lengkapi parameter berikut:
    </p>
    <ul class="text-muted small ps-3 mb-3" style="line-height: 1.8;">
      <li><strong>Nama Produk:</strong> Nama instrumen / reagen (misal: <em>MRS Broth Granulated</em>).</li>
      <li><strong>Nomor Katalog (CAT Code):</strong> Kode unik pabrikan (misal: <code>CAT. 610025</code>).</li>
      <li><strong>Kategori:</strong> Pilih kategori utama (misal: <em>Culture Media / Microbiology</em>).</li>
      <li><strong>Harga Est. Penawaran:</strong> Price list standar acuan (dapat di-override saat RFQ).</li>
      <li><strong>Gambar Produk:</strong> Upload foto produk format PNG/JPG/WebP resmi pabrikan.</li>
    </ul>

    <div class="manual-subchapter-title">
      <i class="bi bi-file-earmark-spreadsheet-fill"></i> 2.2 Panduan Impor Masal (Bulk CSV / Excel)
    </div>
    <p class="text-secondary small mb-2">
      Untuk mengunggah ratusan produk sekaligus, gunakan fitur <strong>Impor Masal</strong> di menu Produk:
    </p>
    <div class="manual-step-item">
      <div class="manual-step-badge">1</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Unduh Template CSV</h5>
        <p class="text-muted small mb-0">Klik tombol <em>Download Format Template CSV</em> pada halaman Impor Masal.</p>
      </div>
    </div>
    <div class="manual-step-item">
      <div class="manual-step-badge">2</div>
      <div>
        <h5 class="fw-bold text-white small mb-1">Struktur Kolom CSV yang Wajib Diisi</h5>
        <table class="manual-table my-2">
          <thead>
            <tr>
              <th>Nama Kolom</th>
              <th>Status</th>
              <th>Contoh Isi Data</th>
            </tr>
          </thead>
          <tbody>
            <tr><td><code>title</code></td><td><span class="text-danger fw-bold">Wajib</span></td><td>MRS Broth Granulated</td></tr>
            <tr><td><code>catalog</code></td><td>Opsional</td><td>610025</td></tr>
            <tr><td><code>category</code></td><td><span class="text-danger fw-bold">Wajib</span></td><td>Culture Media</td></tr>
            <tr><td><code>price</code></td><td>Opsional</td><td>420000</td></tr>
            <tr><td><code>stock</code></td><td>Opsional</td><td>100</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- BAB 3 -->
  <div id="bab-3" class="manual-chapter-card">
    <div class="manual-chapter-title">
      <i class="bi bi-sliders text-danger fs-4"></i>
      <span>BAB 3: Pengaturan Halaman Beranda (Homepage CMS Editor)</span>
    </div>

    <p class="text-secondary small mb-3">
      Buka menu <strong>Pengaturan Web</strong> pada sidebar admin untuk mengedit tampilan beranda tanpa mengubah kode program:
    </p>

    <div class="manual-subchapter-title">
      <i class="bi bi-images"></i> 3.1 Hero Banner &amp; Slideshow Latar Belakang
    </div>
    <ul class="text-muted small ps-3 mb-3" style="line-height: 1.8;">
      <li><strong>Hero Badge Text:</strong> Teks pill atas (default: <em>PRECISION LABORATORY SOLUTIONS</em>).</li>
      <li><strong>Hero Title:</strong> Judul utama beranda. Gunakan tag <code>&lt;span class="text-accent"&gt;Teks&lt;/span&gt;</code> untuk warna merah dan <code>&lt;span class="typo-outline"&gt;Teks&lt;/span&gt;</code> untuk teks outline transparan.</li>
      <li><strong>Hero Slideshow Images (4 Slot):</strong> Masukkan 4 URL gambar instrumen laboratorium resolusi tinggi. Gambar akan berganti otomatis secara dinamis setiap 5 detik.</li>
    </ul>

    <div class="manual-subchapter-title">
      <i class="bi bi-grid-3x3-gap-fill"></i> 3.2 Bento Grid &amp; Sektor Industri Fokus
    </div>
    <p class="text-secondary small mb-0">
      Anda dapat mengedit judul, deskripsi, dan ikon untuk 4 kartu Bento Keunggulan serta 4 Sektor Industri Utama (Mikrobiologi, Farmasi, Makanan &amp; Minuman, R&amp;D).
    </p>
  </div>

  <!-- BAB 4 -->
  <div id="bab-4" class="manual-chapter-card">
    <div class="manual-chapter-title">
      <i class="bi bi-newspaper text-danger fs-4"></i>
      <span>BAB 4: Manajemen Artikel Blog &amp; Lisensi Prinsipal Global</span>
    </div>

    <div class="manual-subchapter-title">
      <i class="bi bi-file-richtext-fill"></i> 4.1 Mengedit &amp; Mempublikasikan Artikel
    </div>
    <p class="text-secondary small mb-3">
      Pada menu <strong>Artikel</strong>, Anda dapat membuat berita teknis atau edukasi laboratorium. Gunakan fitur *Rich Text Editor* untuk menambahkan gambar pendukung, heading, dan daftar poin.
    </p>

    <div class="manual-subchapter-title">
      <i class="bi bi-award-fill"></i> 4.2 Mengelola Logo Prinsipal / Mitra Lisensi
    </div>
    <p class="text-secondary small mb-0">
      Pada menu <strong>Prinsipal / Mitra</strong>, Anda dapat mengunggah logo lisensi pabrikan global (seperti Oxoid, Thermo Scientific, Merck, Himedia, dll.) yang akan tampil pada banner running marquee di beranda.
    </p>
  </div>

</div>
@endsection
