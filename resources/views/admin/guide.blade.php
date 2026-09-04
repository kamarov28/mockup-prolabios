@extends('admin.layout')

@section('title', 'Panduan Admin')
@section('page_title', 'Panduan Admin')

@section('admin_content')

{{-- Hero --}}
<div class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-body" style="padding: 28px 32px;">
    <span class="admin-badge admin-badge-accent" style="margin-bottom: 12px;">Manual Operasional</span>
    <h1 style="font-family: var(--font-headline); font-size: 1.5rem; font-weight: 700; color: var(--color-text-main); margin: 0 0 8px; letter-spacing: -0.3px;">
      Panduan Admin Prolabios
    </h1>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0; max-width: 640px; line-height: 1.65;">
      Ringkasan cara mengelola portal B2B: RFQ, katalog produk, konten website, dan data pendukung.
      Ikuti alur operasional yang <strong style="color: var(--color-text-main);">sedang aktif</strong> di sistem saat ini.
    </p>
  </div>
</div>

{{-- TOC --}}
<div class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Navigasi</span>
      <h2 class="admin-card-header-title">Daftar Isi</h2>
    </div>
  </div>
  <div class="admin-card-body" style="padding: 16px 20px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 8px;">
      <a href="#rfq" class="guide-toc-link"><i class="bi bi-receipt"></i> RFQ / Penawaran</a>
      <a href="#produk" class="guide-toc-link"><i class="bi bi-box-seam"></i> Produk &amp; Katalog</a>
      <a href="#kategori" class="guide-toc-link"><i class="bi bi-diagram-3"></i> Kategori &amp; Sektor</a>
      <a href="#konten" class="guide-toc-link"><i class="bi bi-sliders"></i> Beranda &amp; Konten</a>
      <a href="#artikel" class="guide-toc-link"><i class="bi bi-file-text"></i> Artikel &amp; Prinsipal</a>
      <a href="#keamanan" class="guide-toc-link"><i class="bi bi-shield-check"></i> Keamanan &amp; Tips</a>
    </div>
  </div>
</div>

{{-- RFQ --}}
<div id="rfq" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Modul utama</span>
      <h2 class="admin-card-header-title"><i class="bi bi-receipt me-2" style="color: var(--color-accent);"></i>Pengajuan RFQ</h2>
    </div>
    <a href="{{ route('admin.rfqs.index') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Buka daftar RFQ</a>
  </div>
  <div class="admin-card-body">
    <p class="guide-lead">
      Portal ini <strong>bukan checkout e-commerce</strong>. Fokusnya: katalog + lead RFQ korporasi.
      Follow-up penawaran dilanjutkan lewat <strong>WhatsApp / Sales</strong>.
    </p>

    <h3 class="guide-h3">Alur yang aktif sekarang</h3>
    <ol class="guide-steps">
      <li>Customer pilih produk → keranjang RFQ (session)</li>
      <li>Isi data perusahaan + PIC → submit</li>
      <li>Sistem simpan RFQ + kirim notifikasi email (jika queue/mail aktif)</li>
      <li>Admin lihat di <code>/admin/rfqs</code> → hubungi via WA</li>
      <li>Ubah status &amp; isi catatan internal sesuai progress</li>
    </ol>

    <h3 class="guide-h3">Status RFQ</h3>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Status</th>
            <th>Arti</th>
            <th>Tindakan admin</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="admin-badge admin-badge-warning">Baru</span></td>
            <td>Pengajuan baru masuk, belum ditindaklanjuti.</td>
            <td>Buka detail → hubungi WA → update status.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-info">Dihubungi</span></td>
            <td>Sudah dihubungi sales / sedang negosiasi.</td>
            <td>Lanjutkan follow-up; catat hasil di catatan internal.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-accent">Quoted</span></td>
            <td>Penawaran harga sudah disampaikan ke customer.</td>
            <td>Tunggu konfirmasi; update ke Selesai bila deal/tidak.</td>
          </tr>
          <tr>
            <td><span class="admin-badge admin-badge-muted">Selesai</span></td>
            <td>Proses ditutup (deal atau tidak dilanjutkan).</td>
            <td>Arsip; bisa dihapus bila data uji/spam.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h3 class="guide-h3">Cara kerja di detail RFQ</h3>
    <ul class="guide-list">
      <li>Tombol <strong>Hubungi via WA</strong> membuka chat dengan pesan siap kirim (nomor RFQ + nama perusahaan).</li>
      <li>Ubah <strong>Status</strong> dan isi <strong>Catatan internal</strong> (hanya terlihat admin), lalu Simpan.</li>
      <li>Filter di daftar: kata kunci, status, rentang tanggal.</li>
    </ul>

    <div class="guide-note">
      <i class="bi bi-info-circle"></i>
      <div>
        <strong>Belum jadi alur utama</strong> (jika nanti dibutuhkan, development terpisah):
        diskon/price override di admin, PDF surat penawaran otomatis, approve online + signed URL, potong stok otomatis.
        Operasional saat ini: <em>submit RFQ → follow-up WA</em>.
      </div>
    </div>
  </div>
</div>

{{-- Produk --}}
<div id="produk" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Katalog</span>
      <h2 class="admin-card-header-title"><i class="bi bi-box-seam me-2" style="color: var(--color-accent);"></i>Produk</h2>
    </div>
    <a href="{{ route('admin.products') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Kelola produk</a>
  </div>
  <div class="admin-card-body">
    <ul class="guide-list">
      <li><strong>Tambah / edit</strong> produk: judul, katalog no, harga, stok, kategori, sektor, deskripsi, gambar.</li>
      <li><strong>Bulk import</strong> tersedia lewat menu create bulk (CSV) bila perlu unggah banyak item sekaligus.</li>
      <li>Gambar disimpan di storage publik; pastikan <code>php artisan storage:link</code> sudah dijalankan di server.</li>
      <li>URL publik produk memakai slug; detail legacy by id masih didukung di backend.</li>
    </ul>
  </div>
</div>

{{-- Kategori & Sektor --}}
<div id="kategori" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Taksonomi</span>
      <h2 class="admin-card-header-title"><i class="bi bi-diagram-3 me-2" style="color: var(--color-accent);"></i>Kategori &amp; Sektor</h2>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Kategori produk</h3>
        <p class="guide-lead" style="margin-bottom: 0;">
          Menu <strong>Kategori Produk</strong> — pengelompokan katalog (mis. Microbiology, Instruments).
          Hubungkan ke produk saat create/edit.
        </p>
      </div>
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Sektor industri</h3>
        <p class="guide-lead" style="margin-bottom: 0;">
          Menu <strong>Sektor</strong> — target industri (Hospital, Pharma, Food, dll.) yang tampil di filter/listing publik.
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Beranda --}}
<div id="konten" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Website</span>
      <h2 class="admin-card-header-title"><i class="bi bi-sliders me-2" style="color: var(--color-accent);"></i>Pengaturan Web / Beranda</h2>
    </div>
    <a href="{{ route('admin.home.edit') }}" class="admin-btn admin-btn-ghost admin-btn-sm">Edit beranda</a>
  </div>
  <div class="admin-card-body">
    <p class="guide-lead">Edit konten beranda tanpa ubah kode:</p>
    <ul class="guide-list">
      <li><strong>Hero</strong> — badge text, judul, subtitle, gambar slideshow (jika tersedia di form).</li>
      <li>Bagian keunggulan / bento dan sektor fokus (sesuai field di form Pengaturan Web).</li>
      <li>Simpan perubahan → refresh halaman publik untuk melihat hasil.</li>
    </ul>
  </div>
</div>

{{-- Artikel & Prinsipal --}}
<div id="artikel" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Konten &amp; mitra</span>
      <h2 class="admin-card-header-title"><i class="bi bi-file-text me-2" style="color: var(--color-accent);"></i>Artikel &amp; Prinsipal</h2>
    </div>
  </div>
  <div class="admin-card-body">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Artikel</h3>
        <ul class="guide-list">
          <li>Buat/edit berita atau edukasi lab.</li>
          <li>Editor rich text (Summernote) untuk heading, list, gambar.</li>
          <li>Publikasikan agar tampil di halaman informasi.</li>
        </ul>
      </div>
      <div>
        <h3 class="guide-h3" style="margin-top: 0;">Prinsipal / Mitra</h3>
        <ul class="guide-list">
          <li>Unggah logo pabrikan/mitra (Oxoid, Merck, dll.).</li>
          <li>Tampil di area mitra / marquee beranda (sesuai template situs).</li>
        </ul>
      </div>
    </div>
  </div>
</div>

{{-- Keamanan --}}
<div id="keamanan" class="admin-card" style="margin-bottom: 20px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Operasional aman</span>
      <h2 class="admin-card-header-title"><i class="bi bi-shield-check me-2" style="color: var(--color-accent);"></i>Keamanan &amp; tips</h2>
    </div>
  </div>
  <div class="admin-card-body">
    <ul class="guide-list">
      <li>Jangan bagikan akun admin. User harus <code>is_admin = true</code>.</li>
      <li>Production: <code>APP_DEBUG=false</code>, HTTPS, session secure cookie, CAPTCHA diisi.</li>
      <li>Upload: validasi MIME, SVG diblok, prefer WebP; file di <code>storage/app/public</code>.</li>
      <li>Rate limit aktif di login admin, form kontak, dan submit RFQ.</li>
      <li>Hapus RFQ uji/spam dari daftar bila perlu; data penting sebaiknya diarsip lewat status <strong>Selesai</strong>.</li>
    </ul>
  </div>
</div>

<style>
  .guide-toc-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border: 2px solid #1E1E1E;
    border-radius: 4px;
    background: #FFFFFF;
    box-shadow: 2px 2px 0 #1E1E1E;
    font-family: var(--font-headline);
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--color-text-main);
    text-decoration: none;
    transition: transform 0.1s ease, box-shadow 0.1s ease, background-color 0.1s ease;
  }
  .guide-toc-link i { color: var(--color-accent, #A6171C); }
  .guide-toc-link:hover {
    color: var(--color-text-main);
    background-color: var(--color-surface-2, #EDE8E0);
    transform: translate(1px, 1px);
    box-shadow: 1px 1px 0 #1E1E1E;
  }
  .guide-lead {
    font-size: 0.92rem;
    color: var(--color-text-main);
    line-height: 1.65;
    margin: 0 0 18px;
  }
  .guide-h3 {
    font-family: var(--font-headline);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--color-text-main);
    margin: 24px 0 12px;
  }
  .guide-steps {
    margin: 0 0 12px;
    padding-left: 1.25rem;
    color: var(--color-text-secondary, #3F3F46);
    font-size: 0.88rem;
    line-height: 1.75;
  }
  .guide-steps li { margin-bottom: 6px; }
  .guide-list {
    margin: 0;
    padding-left: 1.15rem;
    color: var(--color-text-secondary, #3F3F46);
    font-size: 0.88rem;
    line-height: 1.75;
  }
  .guide-list li { margin-bottom: 6px; }
  .guide-note {
    display: flex;
    gap: 14px;
    margin-top: 24px;
    padding: 16px 18px;
    border-radius: 4px;
    border: 2px solid #1E1E1E;
    box-shadow: 3px 3px 0 #1E1E1E;
    background: #FEF3C7;
    color: #1E1E1E;
    font-size: 0.85rem;
    line-height: 1.6;
  }
  .guide-note i { color: #D97706; font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }
  .guide-note strong { color: #1E1E1E; }
  code {
    font-family: var(--font-mono, 'JetBrains Mono', Consolas, monospace);
    font-size: 0.85em;
    font-weight: 600;
    color: var(--color-accent, #A6171C);
    background: var(--color-surface-2, #EDE8E0);
    border: 1px solid var(--color-border, #1E1E1E);
    padding: 2px 6px;
    border-radius: 3px;
  }
  @media (max-width: 768px) {
    .admin-card-body > div[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

@endsection
