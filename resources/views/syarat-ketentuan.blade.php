@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - PT. Prolabios Mitra Analitika')
@section('meta_description', 'Syarat & Ketentuan Penggunaan dan Pengajuan Penawaran Pengadaan Alat Laboratorium PT. Prolabios Mitra Analitika.')

@section('content')
  <!-- Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Legal &amp; Kepatuhan</span>
      <h1 class="editorial-page-title" style="font-size: 2.4rem; font-family: var(--font-headline);">Syarat &amp; Ketentuan</h1>
      <p class="editorial-page-subtitle">Ketentuan penggunaan platform dan prosedur permohonan penawaran pengadaan resmi</p>
    </div>
  </div>

  <!-- Content Section -->
  <section class="py-5" style="background-color: var(--color-bg-body);">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          
          <div class="p-4 p-md-5" style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: 0px; color: var(--color-text-body);">
            
            <p class="small text-muted mb-4" style="font-family: var(--font-headline); letter-spacing: 1px;">
              BERLAKU EFEKTIF: {{ date('d F Y') }} &bull; STANDAR PENGADAAN B2B &amp; INSTITUSI
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">1. Ketentuan Umum</h2>
            <p class="mb-4 lh-lg">
              Syarat dan Ketentuan ini mengatur akses dan penggunaan situs web <strong>PT. Prolabios Mitra Analitika</strong> ("kami") yang ditujukan untuk memfasilitasi informasi katalog teknis, spesifikasi instrumen laboratorium, serta permohonan pengajuan penawaran harga resmi (<em>Request for Quotation / RFQ</em>) bagi pelanggan korporat, institusi pendidikan, industri farmasi, pangan, riset, dan lembaga pemerintah.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">2. Katalog Produk &amp; Informasi Teknis</h2>
            <p class="mb-4 lh-lg">
              Seluruh spesifikasi produk, foto, brosur katalog, nomor katalog, dan deskripsi aplikasi yang tercantum pada situs web ini disajikan dengan tingkat akurasi sebaik mungkin berdasarkan data prinsipal manufaktur. Kami berhak memperbarui atau merevisi spesifikasi teknis sewaktu-waktu tanpa pemberitahuan sebelumnya guna mencerminkan pembaruan produk dari pabrikan.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">3. Pengajuan Permohonan Penawaran (RFQ)</h2>
            <p class="mb-2 lh-lg">
              Pengiriman daftar produk melalui fitur keranjang (<em>Cart</em>) dan formulir RFQ di situs ini merupakan <strong>permohonan penawaran harga non-mengikat</strong>, bukan transaksi pembayaran langsung secara daring:
            </p>
            <ul class="mb-4 lh-lg ps-3">
              <li>Harga yang tertera pada situs (jika ada) merupakan estimasi referensi dan belum mengikat sebelum diterbitkan Surat Penawaran Resmi (<em>Quotation Letter</em>) oleh staf penjualan resmi kami.</li>
              <li>Status ketersediaan stok dapat berubah sewaktu-waktu. Produk yang berstatus <em>Indent / Pre-Order</em> memerlukan estimasi waktu pabrikasi dan importasi resmi.</li>
              <li>Perjanjian jual beli yang mengikat baru terjadi setelah pihak pembeli menerbitkan <em>Purchase Order</em> (PO) resmi yang telah disetujui bersama sesuai kesepakatan tertulis.</li>
            </ul>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">4. Garansi &amp; Layanan Purna Jual</h2>
            <p class="mb-4 lh-lg">
              Semua instrumen laboratorium yang didistribusikan oleh PT. Prolabios Mitra Analitika dilindungi oleh garansi resmi prinsipal sesuai syarat masa garansi masing-masing merek. Tim teknisi ahli kami siap memberikan dukungan instalasi, kalibrasi, kualifikasi (IQ/OQ/PQ), dan pelatihan operasional bagi pengguna instrumen.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">5. Hak Kekayaan Intelektual</h2>
            <p class="mb-4 lh-lg">
              Seluruh merek dagang, logo prinsipal, konten gambar, teks, dan tata letak grafis pada platform ini adalah hak milik sah PT. Prolabios Mitra Analitika atau masing-masing prinsipal mitra kami. Dilarang menggandakan, menyalin, atau mendistribusikan materi ini untuk tujuan komersial tanpa izin tertulis.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">6. Batasan Tanggung Jawab</h2>
            <p class="mb-4 lh-lg">
              Kami tidak bertanggung jawab atas kerugian tidak langsung atau konsekuensial yang timbul dari kesalahan penanganan reagen/instrumen di luar prosedur standar operasional (SOP) atau penggunaan tanpa tenaga ahli yang bersertifikat.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">7. Hukum yang Berlaku</h2>
            <p class="mb-4 lh-lg">
              Syarat &amp; Ketentuan ini tunduk pada dan ditafsirkan berdasarkan hukum Negara Kesatuan Republik Indonesia. Setiap perselisihan yang timbul akan diselesaikan secara musyawarah untuk mencapai mufakat.
            </p>

            <h2 class="h4 mb-3" style="color: var(--color-text-main); font-family: var(--font-headline);">8. Hubungi Kami</h2>
            <p class="mb-0 lh-lg">
              Jika Anda memerlukan informasi lebih lanjut mengenai ketentuan pengadaan, silakan hubungi tim legal &amp; operasional kami:<br>
              <strong>PT. PROLABIOS MITRA ANALITIKA</strong><br>
              Email: <a href="mailto:marketing@prolabios.com" style="color: var(--color-accent);">marketing@prolabios.com</a><br>
              Telepon: (021) 3874-1447 / (021) 8792-9433
            </p>

          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
