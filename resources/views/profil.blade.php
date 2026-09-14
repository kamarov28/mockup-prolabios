@extends('layouts.app')

@section('title', 'Profil Perusahaan | PT Prolabios Mitra Analitika')
@section('meta_description', 'Profil PT. Prolabios Mitra Analitika — Distributor resmi media kultur mikrobiologi, reagen kimia analitika, dan instrumen laboratorium dengan fasilitas cold-chain di Indonesia.')

@section('content')
  @include('partials.subpage-hero', [
    'badge' => '<i data-lucide="building" class="me-1"></i> TENTANG KAMI',
    'title' => 'Distribusi Reagen & Alat Laboratorium Bergaransi Resmi',
    'subtitle' => '<strong>PT. Prolabios Mitra Analitika (PMA)</strong> menyuplai media kultur mikrobiologi, reagen kimia analitik, dan instrumen uji untuk laboratorium industri pangan, farmasi, universitas, dan fasilitas riset di Indonesia.'
  ])

  <!-- Main Content Layout -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row g-5">

        <!-- Left / Main Column -->
        <div class="col-lg-8 order-1">

          <!-- 1. Operasional & Spesialisasi Kami -->
          <div class="card p-4 p-md-5 mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
              <span class="profil-section-label">Fokus Operasional</span>
              <span class="nb-mono text-muted small">DISTRIBUSI RESMI &amp; COLD-CHAIN</span>
            </div>

            <!-- Hero Image with Clean Precision Frame -->
            <div class="profil-hero-img mb-4">
              <img src="{{ asset('images/sectors/general-purpose.jpg') }}"
                   alt="Fasilitas Penyimpanan dan Distribusi Prolabios"
                   loading="lazy" decoding="async">
            </div>

            <h2 class="profil-section-title">Pasokan Reagen &amp; Instrumen Siap Audit</h2>
            
            <div class="profil-body-text">
              <p>
                Didirikan untuk menjawab kendala pasokan laboratorium di Indonesia, <strong>PT. Prolabios Mitra Analitika (PMA)</strong> berfokus pada penyediaan media mikrobiologi siap pakai, dehidrasi media, indikator biologi, serta instrumen preparasi analitika.
              </p>
              <p>
                Setiap batch produk kami sertakan dengan dokumen ketertelusuran lengkap: <strong>Certificate of Analysis (COA)</strong>, <strong>Material Safety Data Sheet (MSDS)</strong>, dan izin edar resmi dari otoritas terkait. Untuk media kultur sensitif suhu, kami menerapkan sistem penyimpanan dan ekspedisi rantai dingin (<em>cold-chain 2°C – 8°C</em>) dengan pemantau suhu guna menjaga viabilitas media hingga tiba di lab Anda.
              </p>
            </div>

            <!-- Capability Chips -->
            <div class="pt-3 nb-card-foot d-flex flex-wrap gap-2">
              <span class="nb-badge-sm"><i data-lucide="check" class="me-1 text-primary"></i> Cold-Chain 2°C – 8°C</span>
              <span class="nb-badge-sm"><i data-lucide="check" class="me-1 text-primary"></i> COA &amp; MSDS per Nomor Lot</span>
              <span class="nb-badge-sm"><i data-lucide="check" class="me-1 text-primary"></i> Registrasi Kemenkes / AKL-AKD</span>
              <span class="nb-badge-sm"><i data-lucide="check" class="me-1 text-primary"></i> Dukungan Instalasi IQ/OQ</span>
            </div>
          </div>

          <!-- 2. Komitmen Operasional (Menggantikan Visi-Misi Klise) -->
          <div id="visi-misi" class="mb-5">
            <div class="mb-4">
              <span class="profil-section-label">Standar Layanan</span>
              <h2 class="profil-section-title">3 Komitmen Operasional Kami</h2>
              <p class="profil-body-text text-muted">
                Standar kerja harian tim Prolabios dalam mengawal pengadaan material uji Anda.
              </p>
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <div class="profil-mission-card h-100">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">01. MUTU &amp; LOT</span>
                    <i data-lucide="clipboard-check" class="text-primary fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Ketertelusuran Batch</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Seluruh reagen dan media memiliki masa kedaluwarsa panjang serta COA spesifik nomor lot untuk kebutuhan akreditasi ISO 17025.
                  </p>
                </div>
              </div>

              <div class="col-md-4">
                <div class="profil-mission-card h-100">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">02. LOGISTIK</span>
                    <i data-lucide="truck" class="text-primary fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Proteksi Pengiriman</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Pengemasan khusus untuk material cairan, glassware kaca presisi, dan kotak berinsulasi pendingin untuk produk termo-labil.
                  </p>
                </div>
              </div>

              <div class="col-md-4">
                <div class="profil-mission-card h-100">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">03. TEKNIS</span>
                    <i data-lucide="headset" class="text-primary fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Dukungan Aplikasi</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Pendampingan pemilihan formulasi media yang cocok dengan metode uji SNI, AOAC, atau Farmakope Indonesia.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- 3. Nilai Inti Perusahaan (Nilai P-R-O) -->
          <div class="mb-5">
            <div class="mb-4">
              <span class="profil-section-label">Nilai Inti</span>
              <h2 class="profil-section-title">Nilai-Nilai P-R-O</h2>
              <p class="profil-body-text text-muted">
                Tiga pilar karakter kerja yang membentuk budaya pelayanan PT. Prolabios Mitra Analitika.
              </p>
            </div>

            <div class="row g-4">
              <!-- P -->
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter-wrap letter-p">P</div>
                  <h3 class="profil-value-title">Professional</h3>
                  <p class="profil-body-text flex-grow-1" style="font-size: 0.9rem;">
                    Menunjukkan tingkat keahlian teknis yang tinggi, integritas moral, serta standar operasional profesional dalam melayani setiap mitra industri maupun institusi pendidikan.
                  </p>
                  <div class="pt-3 nb-card-foot text-muted small" style="font-weight: 600;">
                    <i data-lucide="check-circle-2" class="text-primary me-1"></i> Integritas &amp; Mutu
                  </div>
                </div>
              </div>

              <!-- R -->
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter-wrap letter-r">R</div>
                  <h3 class="profil-value-title">Robust</h3>
                  <p class="profil-body-text flex-grow-1" style="font-size: 0.9rem;">
                    Tangguh dan sigap menghadapi tantangan distribusi rantai pasok untuk memastikan ketersediaan barang dan stabilitas kualitas reagen yang kami kirimkan.
                  </p>
                  <div class="pt-3 nb-card-foot text-muted small" style="font-weight: 600;">
                    <i data-lucide="check-circle-2" class="text-primary me-1"></i> Rantai Pasok Kuat
                  </div>
                </div>
              </div>

              <!-- O -->
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter-wrap letter-o">O</div>
                  <h3 class="profil-value-title">Offering the Best</h3>
                  <p class="profil-body-text flex-grow-1" style="font-size: 0.9rem;">
                    Berkomitmen menghadirkan produk-produk terbaik dari prinsipal berkelas dunia serta solusi menyeluruh demi memajukan kemampuan riset lab Anda.
                  </p>
                  <div class="pt-3 nb-card-foot text-muted small" style="font-weight: 600;">
                    <i data-lucide="check-circle-2" class="text-primary me-1"></i> Solusi Terdepan
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 4. Komitmen Kepatuhan & Jaminan Mutu -->
          <div class="profil-trust-box">
            <div class="d-flex align-items-center gap-2 mb-3">
              <i data-lucide="shield-alert" class="text-primary fs-4"></i>
              <h3 class="profil-section-title m-0 fs-5">Standar Regulasi &amp; Jaminan Distribusi</h3>
            </div>
            <p class="profil-body-text mb-3" style="font-size: 0.92rem;">
              Setiap produk instrumen, media kultur, dan reagen diagnostik yang kami distribusikan melewati tahapan verifikasi ketat untuk menjamin kesesuaian dengan ketentuan regulasi di Indonesia:
            </p>
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i data-lucide="badge-check" class="text-primary mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Sertifikat Keaslian &amp; COA</strong>
                    <span class="text-muted small">Dokumen batch certificate dan MSDS siap audit.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i data-lucide="badge-check" class="text-primary mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Izin Edar Kemenkes RI</strong>
                    <span class="text-muted small">Registrasi AKL/AKD untuk perlengkapan medis dan diagnostik.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i data-lucide="badge-check" class="text-primary mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Prinsipal Resmi Terakreditasi</strong>
                    <span class="text-muted small">Kemitraan resmi dengan produsen global teruji.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i data-lucide="badge-check" class="text-primary mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Penyimpanan Rantai Dingin</strong>
                    <span class="text-muted small">Fasilitas cold storage terkontrol 2°C – 8°C.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Right / Sidebar Column -->
        <div class="col-lg-4 order-2">

          <!-- Sidebar Card 1: Data Perusahaan -->
          <div class="card p-4 mb-4">
            <h3 class="profil-sidebar-title">
              <i data-lucide="clipboard-list" class="me-1 text-primary"></i> DATA PERUSAHAAN
            </h3>
            <table class="profil-info-table mb-3">
              <tbody>
                <tr>
                  <th>Nama Legal</th>
                  <td>PT. Prolabios Mitra Analitika</td>
                </tr>
                <tr>
                  <th>Sektor</th>
                  <td>Distributor Alat Lab &amp; Reagen Mikrobiologi</td>
                </tr>
                <tr>
                  <th>Slogan</th>
                  <td><em>"Professional, Robust, Offering the best"</em></td>
                </tr>
                <tr>
                  <th>Cakupan</th>
                  <td>Pengiriman ke Seluruh Indonesia</td>
                </tr>
                <tr>
                  <th>Layanan</th>
                  <td>Distribusi, Konsultasi, Purna Jual &amp; Kalibrasi</td>
                </tr>
              </tbody>
            </table>
            <div class="pt-2 nb-card-foot text-center">
              <span class="nb-badge-sm mb-0">TERDAFTAR &amp; RESMI DI INDONESIA</span>
            </div>
          </div>

          <!-- Sidebar Card 2: Saluran Resmi & Media Sosial -->
          <div class="card p-4 mb-4">
            <h3 class="profil-sidebar-title">
              <i data-lucide="share-2" class="me-1 text-primary"></i> TETAP TERHUBUNG
            </h3>
            <p class="profil-body-text text-muted mb-3" style="font-size: 0.85rem;">
              Ikuti publikasi resmi dan update produk melalui saluran media kami:
            </p>
            <x-social-links variant="list" />
          </div>

          <!-- Sidebar Card 3: Direct Consultation & RFQ CTA Box -->
          @include('partials.sidebar-cta', [
            'badge' => 'KONSULTASI PENGADAAN',
            'title' => 'Butuh Penawaran atau Diskusi Teknis?',
            'text' => 'Konsultasikan kebutuhan spek alat, ketersediaan lot reagen, atau penerbitan surat penawaran harga resmi (Quotation) langsung dengan sales kami.',
            'primaryUrl' => url('/kontak'),
            'primaryText' => 'Hubungi Sales',
            'secondaryUrl' => url('/produk'),
            'secondaryText' => 'Katalog Produk',
            'secondaryLucide' => 'package'
          ])

        </div>

      </div>
    </div>
  </section>

  <!-- Unified RFQ Callout Section -->
  @include('partials.rfq-banner', [
    'title' => 'Kirim Daftar Kebutuhan Lab Anda',
    'subtitle' => 'Pilih produk di katalog atau kirim daftar PO/RFQ Anda. Tim sales kami merespons penawaran harga resmi, stok lot, dan estimasi waktu kirim dalam 1-2 jam kerja.'
  ])
@endsection

