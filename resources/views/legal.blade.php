@extends('layouts.app')

@section('title', 'Pusat Legalitas & Kepatuhan - PT. Prolabios Mitra Analitika')
@section('meta_description', 'Kebijakan Privasi (UU PDP No. 27/2022) dan Syarat & Ketentuan Pengadaan Alat Laboratorium PT. Prolabios Mitra Analitika.')

@section('content')
  <x-subpage-hero
    badge='<i data-lucide="shield-check" class="me-1"></i> LEGAL &amp; KEPATUHAN'
    title="Pusat Legalitas & Kepatuhan"
    subtitle="Informasi resmi mengenai kebijakan privasi perlindungan data serta ketentuan operasional pengadaan B2B PT Prolabios Mitra Analitika."
  />

  <!-- Content Section with Segmented Tab Switcher -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">

          <!-- Segmented Tab Switcher -->
          <div class="d-flex justify-content-center mb-4">
            <div class="catalog-view-switcher p-1 bg-white border shadow-sm rounded-3 d-inline-flex gap-1" role="tablist" aria-label="Pilih Dokumen Legal">
              <button type="button" 
                      class="catalog-view-toggle px-4 py-2 border-0 rounded-2 d-inline-flex align-items-center gap-2 {{ ($activeTab ?? 'privacy') === 'privacy' ? 'active' : '' }}" 
                      id="tab-privacy-btn" 
                      role="tab" 
                      aria-controls="panel-privacy" 
                      aria-selected="{{ ($activeTab ?? 'privacy') === 'privacy' ? 'true' : 'false' }}">
                <i data-lucide="shield-check" style="width: 16px; height: 16px;"></i>
                <span>Kebijakan Privasi (UU PDP)</span>
              </button>
              <button type="button" 
                      class="catalog-view-toggle px-4 py-2 border-0 rounded-2 d-inline-flex align-items-center gap-2 {{ ($activeTab ?? 'privacy') === 'terms' ? 'active' : '' }}" 
                      id="tab-terms-btn" 
                      role="tab" 
                      aria-controls="panel-terms" 
                      aria-selected="{{ ($activeTab ?? 'privacy') === 'terms' ? 'true' : 'false' }}">
                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
                <span>Syarat &amp; Ketentuan Pengadaan</span>
              </button>
            </div>
          </div>

          <!-- Document Card Container -->
          <div class="card p-4 p-md-5 border-0 shadow-sm" style="border: 1px solid var(--color-border) !important; border-radius: 12px; background: #FFFFFF;">

            <!-- ============================================================== -->
            <!-- PANEL 1: KEBIJAKAN PRIVASI                                     -->
            <!-- ============================================================== -->
            <div id="panel-privacy" role="tabpanel" aria-labelledby="tab-privacy-btn" style="display: {{ ($activeTab ?? 'privacy') === 'privacy' ? 'block' : 'none' }};">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom" style="border-color: #E5E7EB !important;">
                <span class="nb-badge mb-0" style="font-size: 0.7rem; padding: 4px 10px;">
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
                Telepon: (021) 3874-1447 / (021) 8792-9433
              </p>
            </div>

            <!-- ============================================================== -->
            <!-- PANEL 2: SYARAT & KETENTUAN PENGADAAN                          -->
            <!-- ============================================================== -->
            <div id="panel-terms" role="tabpanel" aria-labelledby="tab-terms-btn" style="display: {{ ($activeTab ?? 'privacy') === 'terms' ? 'block' : 'none' }};">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 pb-3 border-bottom" style="border-color: #E5E7EB !important;">
                <span class="nb-badge mb-0" style="font-size: 0.7rem; padding: 4px 10px;">
                  <i data-lucide="badge-check" class="me-1"></i> STANDAR PENGADAAN B2B
                </span>
                <span class="text-muted small" style="font-family: var(--font-mono); font-weight: 600;">
                  <i data-lucide="calendar" class="me-1"></i> Berlaku Efektif: {{ date('d F Y') }}
                </span>
              </div>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">1. Ketentuan Umum</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Syarat dan Ketentuan ini mengatur akses dan penggunaan situs web <strong>PT. Prolabios Mitra Analitika</strong> ("kami") yang ditujukan untuk memfasilitasi informasi katalog teknis, spesifikasi instrumen laboratorium, serta permohonan pengajuan penawaran harga resmi (<em>Request for Quotation / RFQ</em>) bagi pelanggan korporat, institusi pendidikan, industri farmasi, pangan, riset, dan lembaga pemerintah.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">2. Katalog Produk &amp; Informasi Teknis</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Seluruh spesifikasi produk, foto, brosur katalog, nomor katalog, dan deskripsi aplikasi yang tercantum pada situs web ini disajikan dengan tingkat akurasi sebaik mungkin berdasarkan data prinsipal manufaktur. Kami berhak memperbarui atau merevisi spesifikasi teknis sewaktu-waktu tanpa pemberitahuan sebelumnya guna mencerminkan pembaruan produk dari pabrikan.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">3. Pengajuan Permohonan Penawaran</h2>
              <p class="mb-2 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Pengiriman daftar produk melalui fitur keranjang (<em>Cart</em>) dan formulir pengajuan penawaran di situs ini merupakan <strong>permohonan penawaran harga non-mengikat</strong>, bukan transaksi pembayaran langsung secara daring:
              </p>
              <ul class="mb-4 lh-lg ps-3" style="color: var(--nb-ink); font-size: 0.95rem;">
                <li>Harga yang tertera pada situs (jika ada) merupakan estimasi referensi dan belum mengikat sebelum diterbitkan Surat Penawaran Resmi (<em>Quotation Letter</em>) oleh staf penjualan resmi kami.</li>
                <li>Status ketersediaan stok dapat berubah sewaktu-waktu. Produk yang berstatus <em>pesanan khusus (indent)</em> memerlukan estimasi waktu pabrikasi dan importasi resmi.</li>
                <li>Perjanjian jual beli yang mengikat baru terjadi setelah pihak pembeli menerbitkan <em>Purchase Order</em> (PO) resmi yang telah disetujui bersama sesuai kesepakatan tertulis.</li>
              </ul>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">4. Garansi &amp; Layanan Purna Jual</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Semua instrumen laboratorium yang didistribusikan oleh PT. Prolabios Mitra Analitika dilindungi oleh garansi resmi prinsipal sesuai syarat masa garansi masing-masing merek. Tim teknisi ahli kami siap memberikan dukungan instalasi, kalibrasi, kualifikasi (IQ/OQ/PQ), dan pelatihan operasional bagi pengguna instrumen.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">5. Hak Kekayaan Intelektual</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Seluruh merek dagang, logo prinsipal, konten gambar, teks, dan tata letak grafis pada platform ini adalah hak milik sah PT. Prolabios Mitra Analitika atau masing-masing prinsipal mitra kami. Dilarang menggandakan, menyalin, atau mendistribusikan materi ini untuk tujuan komersial tanpa izin tertulis.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">6. Batasan Tanggung Jawab</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Kami tidak bertanggung jawab atas kerugian tidak langsung atau konsekuensial yang timbul dari kesalahan penanganan reagen/instrumen di luar prosedur standar operasional (SOP) atau penggunaan tanpa tenaga ahli yang bersertifikat.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">7. Hukum yang Berlaku</h2>
              <p class="mb-4 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Syarat &amp; Ketentuan ini tunduk pada dan ditafsirkan berdasarkan hukum Negara Kesatuan Republik Indonesia. Setiap perselisihan yang timbul akan diselesaikan secara musyawarah untuk mencapai mufakat.
              </p>

              <h2 class="fs-5 fw-bold mb-3" style="color: var(--nb-ink); font-family: var(--font-display);">8. Hubungi Kami</h2>
              <p class="mb-0 lh-lg" style="color: var(--nb-ink); font-size: 0.95rem;">
                Jika Anda memerlukan informasi lebih lanjut mengenai ketentuan pengadaan, silakan hubungi tim legal &amp; operasional kami:<br>
                <strong>PT. PROLABIOS MITRA ANALITIKA</strong><br>
                GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914<br>
                Email: <a href="mailto:marketing@prolabios.com" style="color: var(--nb-primary); font-weight: 600;">marketing@prolabios.com</a><br>
                Telepon: (021) 3874-1447 / (021) 8792-9433
              </p>
            </div>

          </div>

        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script @nonce>
  document.addEventListener('DOMContentLoaded', () => {
    const btnPrivacy = document.getElementById('tab-privacy-btn');
    const btnTerms = document.getElementById('tab-terms-btn');
    const panelPrivacy = document.getElementById('panel-privacy');
    const panelTerms = document.getElementById('panel-terms');

    function switchLegalTab(tab) {
      const isPrivacy = (tab === 'privacy');

      if (btnPrivacy && btnTerms && panelPrivacy && panelTerms) {
        btnPrivacy.classList.toggle('active', isPrivacy);
        btnPrivacy.setAttribute('aria-selected', isPrivacy ? 'true' : 'false');

        btnTerms.classList.toggle('active', !isPrivacy);
        btnTerms.setAttribute('aria-selected', !isPrivacy ? 'true' : 'false');

        panelPrivacy.style.display = isPrivacy ? 'block' : 'none';
        panelTerms.style.display = !isPrivacy ? 'block' : 'none';

        const newUrl = new URL(window.location.href);
        newUrl.searchParams.set('tab', tab);
        window.history.replaceState({}, '', newUrl.toString());

        if (window.lucide) {
          window.lucide.createIcons();
        }
      }
    }

    if (btnPrivacy) {
      btnPrivacy.addEventListener('click', () => switchLegalTab('privacy'));
    }
    if (btnTerms) {
      btnTerms.addEventListener('click', () => switchLegalTab('terms'));
    }

    // Check hash or URL query on load
    const urlParams = new URLSearchParams(window.location.search);
    const hash = window.location.hash.replace('#', '');
    if (urlParams.get('tab') === 'terms' || hash === 'terms' || hash === 'syarat-ketentuan') {
      switchLegalTab('terms');
    } else if (urlParams.get('tab') === 'privacy' || hash === 'privacy' || hash === 'kebijakan-privasi') {
      switchLegalTab('privacy');
    }
  });
</script>
@endpush
