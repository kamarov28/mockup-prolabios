@extends('layouts.app')

@section('title', 'Kebijakan Privasi - PT. Prolabios Mitra Analitika')
@section('meta_description', 'Kebijakan Privasi PT. Prolabios Mitra Analitika sesuai dengan UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP).')

@section('content')
  @include('partials.subpage-hero', [
    'badge' => '<i data-lucide="shield-alert" class="me-1"></i> LEGAL &amp; KEPATUHAN',
    'title' => 'Kebijakan Privasi',
    'subtitle' => 'Komitmen kami dalam melindungi kerahasiaan dan keamanan data institusi serta pelanggan Anda sesuai UU No. 27 Tahun 2022 (UU PDP).'
  ])

  <!-- Content Section -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">

          <div class="card p-4 p-md-5">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom" style="border-color: rgba(30,30,30,0.12) !important;">
              <span class="nb-badge mb-0" style="font-size: 0.7rem; padding: 3px 8px;">
                <i data-lucide="badge-check" class="me-1"></i> UU NO. 27 TAHUN 2022 (UU PDP)
              </span>
              <span class="text-muted small" style="font-family: var(--font-mono); font-weight: 600;">
                <i data-lucide="calendar" class="me-1"></i> Terakhir Diperbarui: {{ date('d F Y') }}
              </span>
            </div>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">1. Pendahuluan</h2>
            <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              Selamat datang di situs web resmi <strong>PT. Prolabios Mitra Analitika</strong> ("kami", "perusahaan"). Kami sangat menghargai privasi dan menjunjung tinggi perlindungan data pribadi serta informasi bisnis Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi yang Anda berikan saat menggunakan layanan atau mengajukan permohonan penawaran harga (<em>Request for Quotation / RFQ</em>) melalui situs ini.
            </p>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">2. Data yang Kami Kumpulkan</h2>
            <p class="mb-2 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">Kami mengumpulkan informasi yang Anda berikan secara sukarela saat berinteraksi dengan platform kami, antara lain:</p>
            <ul class="mb-4 lh-lg ps-3" style="color: var(--nb-ink); font-size: 0.95rem;">
              <li><strong>Informasi Identitas &amp; Kontak:</strong> Nama lengkap, alamat email institusi/perusahaan, nomor telepon/WhatsApp, dan nama instansi/perusahaan/laboratorium Anda.</li>
              <li><strong>Informasi Pengadaan &amp; Penawaran:</strong> Daftar produk yang diminati, jumlah kuantitas, catatan spesifikasi khusus, serta riwayat permintaan penawaran resmi.</li>
              <li><strong>Data Teknis &amp; Penggunaan:</strong> Alamat Protokol Internet (IP address), jenis peramban (browser), informasi log keamanan, dan data analitik interaksi web non-identitas pribadi.</li>
            </ul>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">3. Tujuan Penggunaan Informasi</h2>
            <p class="mb-2 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">Data yang kami kumpulkan dipergunakan semata-mata untuk keperluan profesional B2B, meliputi:</p>
            <ul class="mb-4 lh-lg ps-3" style="color: var(--nb-ink); font-size: 0.95rem;">
              <li>Memproses dan menerbitkan surat penawaran harga resmi (<em>official quotation</em>) sesuai kebutuhan pengadaan lab Anda.</li>
              <li>Menghubungi representasi resmi instansi Anda untuk klarifikasi teknis instrumen, reagen, ketersediaan stok, atau jadwal pengiriman.</li>
              <li>Mengirimkan informasi teknis produk, rilis katalog baru, sertifikat analisis (CoA), atau permohonan pemeliharaan purna jual.</li>
              <li>Mencegah aktivitas penipuan siber, bot spam, serta memastikan kepatuhan terhadap regulasi hukum di Republik Indonesia.</li>
            </ul>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">4. Keamanan dan Penyimpanan Data</h2>
            <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              Kami menerapkan standar keamanan teknis dan organisasional yang ketat, termasuk enkripsi protokol TLS/HTTPS, firewall aplikasi web, kontrol akses terautentikasi bertingkat, dan pencatatan audit log transaksi untuk melindungi data Anda dari akses tanpa hak, pengubahan, pengungkapan, atau pemusnahan yang tidak sah.
            </p>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">5. Kerahasiaan &amp; Pembagian Pihak Ketiga</h2>
            <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              PT. Prolabios Mitra Analitika <strong>tidak pernah dan tidak akan menjual, menyewakan, atau memperdagangkan</strong> data pribadi atau institusi Anda kepada pihak ketiga mana pun. Data hanya dapat dibagikan kepada prinsipal manufaktur resmi atau mitra ekspedisi logistik semata-mata untuk kelancaran pengiriman barang dan sertifikasi resmi garansi alat.
            </p>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">6. Penggunaan Cookie &amp; Analitik</h2>
            <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              Situs kami menggunakan cookie esensial untuk mengelola sesi keranjang pengadaan (cart) dan cookie analitik anonim untuk memahami bagaimana pengunjung menavigasi katalog kami. Anda dapat mengatur peramban Anda untuk menolak cookie, namun beberapa fitur interaktif mungkin tidak beroperasi secara optimal.
            </p>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">7. Hak Subjek Data</h2>
            <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              Sesuai ketentuan UU No. 27 Tahun 2022 (UU PDP), Anda memiliki hak untuk mengakses, memperbarui, mengoreksi ketidakakuratan, atau meminta penghapusan data kontak Anda dari basis data sistem kami kapan saja dengan menghubungi Tim Data Protection kami.
            </p>

            <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">8. Kontak Resmi</h2>
            <p class="mb-0 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
              Apabila Anda memiliki pertanyaan, klarifikasi, atau permohonan terkait data privasi, silakan hubungi kami melalui:<br>
              <strong>PT. PROLABIOS MITRA ANALITIKA</strong><br>
              GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914<br>
              Email: <a href="mailto:marketing@prolabios.com" style="color: var(--nb-primary); font-weight: 600;">marketing@prolabios.com</a><br>
              Telepon: (021) 3874-1447
            </p>

          </div>

        </div>
      </div>
    </div>
  </section>
@endsection
