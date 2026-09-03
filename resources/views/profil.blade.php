@extends('layouts.app')

@section('title', 'Profil Perusahaan | PT Prolabios Mitra Analitika')
@section('meta_description', 'Mengenal lebih dekat PT. Prolabios Mitra Analitika (PMA) — Distributor terpercaya instrumen laboratorium, media mikrobiologi, dan perlengkapan pengujian analitika di Indonesia.')

@section('content')
  <!-- Profil Hero Banner -->
  <section class="profil-hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9">
          <span class="nb-badge">
            <i class="bi bi-building me-1"></i> TENTANG KAMI
          </span>
          <h1 class="profil-main-title">
            Membangun Kepercayaan Melalui Standar &amp; Keandalan Lab
          </h1>
          <p class="profil-main-subtitle">
            <strong>PT. Prolabios Mitra Analitika (PMA)</strong> adalah distributor B2B instrumen analitika, media kultur mikrobiologi, dan perlengkapan pengujian laboratorium dengan standar mutu internasional dan kepatuhan regulasi teruji di Indonesia.
          </p>
        </div>
      </div>

      <!-- Quick Fast Stats Strip -->
      <div class="profil-stats-strip">
        <div class="profil-stat-box">
          <div class="profil-stat-num">100%</div>
          <div class="profil-stat-label">Produk Original &amp; Bersertifikat COA</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">2°C – 8°C</div>
          <div class="profil-stat-label">Logistik Rantai Dingin (Cold-Chain)</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">38+ Provinsi</div>
          <div class="profil-stat-label">Cakupan Distribusi &amp; Logistik Nasional</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">AKL / AKD</div>
          <div class="profil-stat-label">Kepatuhan Izin Edar Kemenkes RI</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content Layout -->
  <section class="section-spacious nb-section">
    <div class="container">
      <div class="row g-5">

        <!-- Left / Main Column -->
        <div class="col-lg-8 order-1">

          <!-- 1. Cerita Kami (Our Story) -->
          <div class="card p-4 p-md-5 mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
              <span class="profil-section-label">Cerita Kami</span>
              <span class="nb-mono text-muted small">ESTABLISHED &amp; TRUSTED</span>
            </div>

            <!-- Hero Image with Neo-Brutal Frame -->
            <div class="profil-hero-img mb-4">
              <img src="{{ asset('images/sectors/general-purpose.jpg') }}"
                   alt="Laboratorium Pengujian dan Fasilitas Distribusi Prolabios"
                   loading="lazy" decoding="async">
            </div>

            <h2 class="profil-section-title">Dedikasi untuk Ekosistem Laboratorium Indonesia</h2>
            
            <div class="profil-body-text">
              <p>
                <strong>PT. Prolabios Mitra Analitika (PMA)</strong> didirikan dengan komitmen teguh untuk menjadi salah satu distributor instrumen dan reagen laboratorium terkemuka di Indonesia. Kami hadir untuk menjembatani kebutuhan fasilitas laboratorium modern—mulai dari industri farmasi, makanan &amp; minuman, kosmetik, rumah sakit, universitas, hingga lembaga riset independen—dengan teknologi pengujian mutakhir berskala global.
              </p>
              <p>
                Kami percaya bahwa keberhasilan suatu pengujian analitis dan mikrobiologi bergantung pada kepastian mutu material uji. Oleh karena itu, PMA tidak hanya mendistribusikan produk, tetapi juga mengawal seluruh proses pengadaan: mulai dari seleksi formulasi media, kepastian rantai pasok dingin (<em>cold-chain logistics</em>), penyediaan dokumen ketertelusuran lengkap (COA, MSDS, Izin Edar Kemenkes), hingga pendampingan teknis purna jual.
              </p>
              <p class="mb-4">
                Tolak ukur kesuksesan kami diukur dari keberhasilan penelitian mitra, pertumbuhan keahlian tim laboratorium, kesejahteraan karyawan, serta kepuasan pelanggan yang berkelanjutan.
              </p>
            </div>

            <!-- Capability Chips -->
            <div class="pt-3 nb-card-foot d-flex flex-wrap gap-2">
              <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Reagen Mikrobiologi</span>
              <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Media Kultur Siap Pakai</span>
              <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Biological Indicator</span>
              <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Instrumen Analitika</span>
              <span class="nb-badge-sm"><i class="bi bi-check-circle me-1 text-primary"></i> Jasa Kalibrasi &amp; Servis</span>
            </div>
          </div>

          <!-- 2. Visi & Misi (Vision & Mission) -->
          <div id="visi-misi" class="mb-5">
            <div class="mb-4">
              <span class="profil-section-label">Prinsip Kami</span>
              <h2 class="profil-section-title">Visi &amp; Misi Perusahaan</h2>
              <p class="profil-body-text text-muted">
                Fondasi arah langkah kami dalam memberikan kontribusi nyata bagi dunia sains dan kendali mutu di Indonesia.
              </p>
            </div>

            <!-- Visi Lead Anchor Card -->
            <div class="card p-4 p-md-5 mb-4 highlight-card" style="border: 2.5px solid var(--nb-primary); box-shadow: 5px 5px 0 var(--nb-primary);">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="profil-value-letter-wrap letter-p m-0" style="width: 44px; height: 44px; font-size: 1.25rem;">
                  <i class="bi bi-eye"></i>
                </div>
                <h3 class="profil-section-title m-0 fs-4">Visi Kami</h3>
              </div>
              <p class="profil-body-text fs-5 fw-medium mb-0" style="line-height: 1.6; color: var(--nb-ink);">
                "Menjadi perusahaan terdepan dalam memenuhi kebutuhan laboratorium, meningkatkan kompetensi pengguna, dan menjadi mitra solusi terbaik bagi pelanggan di seluruh Indonesia."
              </p>
            </div>

            <!-- 4 Modular Mission Cards (2x2 Grid) -->
            <div class="row g-3">
              <div class="col-md-6">
                <div class="profil-mission-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">01. PRODUK</span>
                    <i class="bi bi-box-seam text-accent fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Kualitas &amp; Ketersediaan</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Menyediakan produk instrumen, reagen, dan media kultur berstandar farmakope mutu tertinggi dengan harga kompetitif dan manfaat maksimal.
                  </p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="profil-mission-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">02. PENGGUNA</span>
                    <i class="bi bi-people text-accent fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Edukasi &amp; Solusi Teknis</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Menjadi mitra aktif dalam transfer pengetahuan produk, pelatihan aplikasi instrumen, serta pemecahan kendala teknis analisis harian.
                  </p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="profil-mission-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">03. PENGADAAN</span>
                    <i class="bi bi-file-earmark-check text-accent fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Keandalan Pengadaan B2B</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Menjadi mitra pengadaan yang tertib dokumen regulasi (COA, MSDS, AKL/AKD), transparan, dan menjamin ketepatan jadwal pasokan.
                  </p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="profil-mission-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="nb-badge-sm">04. LAYANAN</span>
                    <i class="bi bi-tools text-accent fs-5"></i>
                  </div>
                  <h4 class="profil-mission-title">Layanan Purna Jual &amp; Kalibrasi</h4>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">
                    Menyediakan dukungan teknis purna jual yang terpercaya melalui instalasi kualifikasi IQ/OQ/PQ, pemeliharaan preventif, dan ketersediaan suku cadang.
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
                  <div class="pt-3 nb-card-foot nb-mono text-muted small">
                    <i class="bi bi-check2-circle text-accent me-1"></i> Integritas &amp; Mutu
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
                  <div class="pt-3 nb-card-foot nb-mono text-muted small">
                    <i class="bi bi-check2-circle text-accent me-1"></i> Rantai Pasok Kuat
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
                  <div class="pt-3 nb-card-foot nb-mono text-muted small">
                    <i class="bi bi-check2-circle text-accent me-1"></i> Solusi Terdepan
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 4. Komitmen Kepatuhan & Jaminan Mutu -->
          <div class="profil-trust-box">
            <div class="d-flex align-items-center gap-2 mb-3">
              <i class="bi bi-shield-lock-fill text-accent fs-4"></i>
              <h3 class="profil-section-title m-0 fs-5">Standar Regulasi &amp; Jaminan Distribusi</h3>
            </div>
            <p class="profil-body-text mb-3" style="font-size: 0.92rem;">
              Setiap produk instrumen, media kultur, dan reagen diagnostik yang kami distribusikan melewati tahapan verifikasi ketat untuk menjamin kesesuaian dengan ketentuan regulasi di Indonesia:
            </p>
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-patch-check-fill text-accent mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Sertifikat Keaslian &amp; COA</strong>
                    <span class="text-muted small">Dokumen batch certificate dan MSDS siap audit.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-patch-check-fill text-accent mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Izin Edar Kemenkes RI</strong>
                    <span class="text-muted small">Registrasi AKL/AKD untuk perlengkapan medis dan diagnostik.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-patch-check-fill text-accent mt-1"></i>
                  <div>
                    <strong class="d-block text-ink" style="font-size: 0.88rem;">Prinsipal Resmi Terakreditasi</strong>
                    <span class="text-muted small">Kemitraan resmi dengan produsen global teruji.</span>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-patch-check-fill text-accent mt-1"></i>
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
              <i class="bi bi-card-list me-1 text-accent"></i> DATA PERUSAHAAN
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
              <span class="nb-mono text-muted small">TERDAFTAR &amp; RESMI DI INDONESIA</span>
            </div>
          </div>

          <!-- Sidebar Card 2: Saluran Resmi & Media Sosial -->
          <div class="card p-4 mb-4">
            <h3 class="profil-sidebar-title">
              <i class="bi bi-share me-1 text-accent"></i> TETAP TERHUBUNG
            </h3>
            <p class="profil-body-text text-muted mb-3" style="font-size: 0.85rem;">
              Ikuti publikasi resmi dan update produk melalui saluran media kami:
            </p>
            <div class="d-flex flex-column gap-2">
              <a href="https://web.facebook.com/PT-Prolabios-Mitra-Analitika-1787666991553394/"
                 target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-facebook text-primary fs-5"></i>
                <span>Facebook Resmi PMA</span>
              </a>
              <a href="https://www.instagram.com/prolabios.id"
                 target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-instagram text-danger fs-5"></i>
                <span>Instagram @prolabios.id</span>
              </a>
              <a href="https://www.linkedin.com/company/pt-prolabios-mitra-analitika/posts/?feedView=all"
                 target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-linkedin text-primary fs-5"></i>
                <span>LinkedIn Company Page</span>
              </a>
              <a href="https://wa.me/6281211118744"
                 target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-whatsapp text-success fs-5"></i>
                <span>WhatsApp B2B Support</span>
              </a>
            </div>
          </div>

          <!-- Sidebar Card 3: Direct Consultation & RFQ CTA Box -->
          <div class="profil-cta-box p-4" style="background: var(--nb-primary, #A6171C); color: #FFFFFF; border: 2px solid #1E1E1E; border-radius: 8px; box-shadow: 4px 4px 0 #1E1E1E;">
            <span class="nb-badge mb-3" style="background: var(--nb-accent, #F1C045); color: #FFFFFF;">
              B2B CONSULTATION
            </span>
            <h3 class="profil-sidebar-title" style="color: #FFFFFF !important; border-bottom-color: rgba(255,255,255,0.3) !important;">
              Butuh Penawaran atau Diskusi Teknis?
            </h3>
            <p style="font-size: 0.88rem; color: #FFFFFF !important; margin-bottom: 20px; line-height: 1.6;">
              Tim representatif teknis kami siap mendampingi pemilihan instrumen, reagen, atau penerbitan surat penawaran harga resmi (Quotation) untuk institusi Anda.
            </p>
            <div class="d-flex flex-column gap-2">
              <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="background: var(--nb-accent, #F1C045); color: #1E1E1E !important;">
                Hubungi Kami <i class="bi bi-arrow-right ms-1"></i>
              </a>
              <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="background: #FFFFFF; color: #1E1E1E !important;">
                <i class="bi bi-box-seam me-1"></i> Lihat Katalog Produk
              </a>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- Unified RFQ Callout Section -->
  <section class="nb-rfq-section">
    <div class="container">
      <div class="nb-rfq-box">
        <span class="nb-badge">B2B PROCUREMENT</span>
        <h2 class="nb-rfq-title">Siap Memulai Pengadaan Laboratorium Anda?</h2>
        <p class="nb-rfq-sub">
          Ajukan permintaan penawaran harga resmi (RFQ) untuk reagen, instrumen, maupun konsumabel. Tim kami akan segera merespons dengan ketersediaan stok, harga khusus, dan dokumen kepatuhan lengkap.
        </p>
        <div class="nb-rfq-actions">
          <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-primary">
            Hubungi Sales / Minta Penawaran
            <i class="bi bi-arrow-right ms-1"></i>
          </a>
          <a href="{{ url('/cart') }}" class="nb-btn nb-btn-ghost">
            <i class="bi bi-cart3 me-1"></i> Buka Keranjang RFQ
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  @include('partials.gsap-loader')
@endpush

