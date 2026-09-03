@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')

@section('content')
  <!-- Hero Banner (Soft Neo-Brutalism) -->
  <section class="profil-hero-banner">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-9">
          <span class="nb-badge">
            <i class="bi bi-gear-wide-connected me-1"></i> LAYANAN TEKNIS
          </span>
          <h1 class="profil-main-title">
            Layanan Purna Jual &amp; Rekayasa Laboratorium
          </h1>
          <p class="profil-main-subtitle">
            Solusi komprehensif mulai dari pemeliharaan instrumen, kalibrasi internal, perancangan layout lab berstandar ISO/GLP, hingga pelatihan terakreditasi prinsipal.
          </p>
        </div>
      </div>

      <!-- Quick Fast Stats Strip -->
      <div class="profil-stats-strip">
        <div class="profil-stat-box">
          <div class="profil-stat-num">Teknisi Ahli</div>
          <div class="profil-stat-label">Tersertifikasi Langsung oleh Prinsipal</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Spare Parts</div>
          <div class="profil-stat-label">100% Suku Cadang Orisinal &amp; Bergaransi</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Standar K3/GLP</div>
          <div class="profil-stat-label">Desain Sesuai Regulasi &amp; Ergonomi Lab</div>
        </div>
        <div class="profil-stat-box">
          <div class="profil-stat-num">Respon Cepat</div>
          <div class="profil-stat-label">Dukungan Darurat &amp; Kontrak Perawatan Rutin</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Layanan Content -->
  <section class="section-spacious nb-section" id="service-nav">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Sidebar / Navigation -->
        <div class="col-lg-4 col-md-5 order-2 order-md-1">
          @php $activeService = request()->get('s') ?? 'maintenance'; @endphp

          <div class="card p-4 mb-4" style="background: var(--nb-card); border: var(--nb-border); border-radius: var(--nb-radius-lg); box-shadow: var(--nb-shadow);">
            <h3 class="profil-sidebar-title"><i class="bi bi-list-nested me-2"></i>Pilih Layanan</h3>
            <nav class="layanan-sidebar-nav d-flex flex-column gap-2">
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="profil-social-link {{ $activeService == 'maintenance' ? 'is-active' : '' }}" style="{{ $activeService == 'maintenance' ? 'background: var(--nb-accent) !important;' : '' }}">
                <i class="bi bi-tools text-danger"></i> Perawatan &amp; Perbaikan
              </a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="profil-social-link {{ $activeService == 'labdesign' ? 'is-active' : '' }}" style="{{ $activeService == 'labdesign' ? 'background: var(--nb-accent) !important;' : '' }}">
                <i class="bi bi-building-gear text-primary"></i> Desain &amp; Pembangunan Lab
              </a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="profil-social-link {{ $activeService == 'consultation' ? 'is-active' : '' }}" style="{{ $activeService == 'consultation' ? 'background: var(--nb-accent) !important;' : '' }}">
                <i class="bi bi-mortarboard text-success"></i> Konsultasi &amp; Pelatihan
              </a>
            </nav>
          </div>

          <div class="profil-trust-box d-none d-md-block">
            <h3 class="profil-sidebar-title"><i class="bi bi-headset me-2"></i>Hubungi Tim Teknis</h3>
            <p style="font-size: 0.88rem; color: var(--nb-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan permintaan servis rutin, instalasi baru, atau konsultasi instrumen bersama konsultan kami.</p>
            <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-primary d-flex justify-content-center mb-3">
              Formulir Kontak <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <a href="tel:02138741447" class="profil-social-link justify-content-center"><i class="bi bi-telephone me-2"></i> 021-3874-1447</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-md-7 order-1 order-md-2">

          <!-- Service Block: Maintenance & Repair -->
          <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-4">
              <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Layanan Perawatan" class="w-100" style="object-fit: cover; height: 340px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label"><i class="bi bi-wrench me-1"></i> Layanan 01</span>
            <h2 class="profil-section-title">Perawatan &amp; Perbaikan Instrumen</h2>
            <p class="profil-body-text">Sebagai komitmen kami sebagai penyedia <em>layanan purna jual</em> terpercaya, PT Prolabios Mitra Analitika memastikan investasi Anda pada instrumen laboratorium tetap berkinerja prima. Teknisi kami terlatih langsung dari pabrikan internasional.</p>

            <div class="row g-3 mt-3">
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-shield-check"></i></div>
                  <h3 class="layanan-feature-title">Pemeliharaan Preventif</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Pembersihan, lubrikasi, dan penggantian komponen habis pakai terencana untuk mencegah downtime mendadak.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-gear"></i></div>
                  <h3 class="layanan-feature-title">Troubleshooting Cepat</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Diagnosa masalah elektrikal &amp; mekanikal cepat menggunakan 100% suku cadang orisinal prinsipal.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-speedometer2"></i></div>
                  <h3 class="layanan-feature-title">Verifikasi &amp; Kalibrasi</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Pengujian akurasi berkala berstandar baku mutu agar hasil analisis pengujian lab Anda selalu valid.</p>
                </div>
              </div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Jadwalkan Kunjungan Teknisi</h3>
              <p class="profil-body-text mb-4">Mengalami kendala pada instrumen analitika atau alat ukur Anda? Tim servis kami siap melakukan inspeksi langsung ke fasilitas Anda.</p>
              <a href="{{ url('/kontak') }}?subjek=service" class="nb-btn nb-btn-primary">
                Formulir Permintaan Servis <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Service Block: Lab Design & Build -->
          <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-4">
              <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100" style="object-fit: cover; height: 340px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label"><i class="bi bi-layout-wtf me-1"></i> Layanan 02</span>
            <h2 class="profil-section-title">Desain &amp; Pembangunan Laboratorium</h2>
            <p class="profil-body-text">Kami merancang dan merealisasikan laboratorium modern yang memenuhi standar K3, alur kerja efisien, serta regulasi nasional dan internasional (ISO 17025 / GLP). Tim ahli kami mendampingi dari tahap blueprint hingga commissioning.</p>

            <div class="row g-3 mt-3">
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-grid-1x2"></i></div>
                  <h3 class="layanan-feature-title">Layout &amp; Ergonomi</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Penataan meja lab anti-kimia, lemari asam (fume hood), biosafety cabinet, dan sirkulasi udara bertekanan.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-patch-check"></i></div>
                  <h3 class="layanan-feature-title">Kepatuhan Regulasi</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Memastikan rancangan fasilitas lab Anda lolos audit standar K3, ISO 17025, dan regulasi dinas lingkungan.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-pip"></i></div>
                  <h3 class="layanan-feature-title">Instalasi Utilitas Lab</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Pemasangan instalasi gas murni (high purity), sistem suplai listrik stabil terisolasi, dan saluran netralisasi limbah.</p>
                </div>
              </div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Mulai Rencana Pengembangan Lab Anda</h3>
              <p class="profil-body-text mb-4">Konsultasikan kebutuhan ekspansi atau renovasi laboratorium Anda bersama konsultan arsitektur lab kami.</p>
              <a href="{{ url('/kontak') }}?subjek=labdesign" class="nb-btn nb-btn-primary">
                Konsultasi Desain Lab <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Service Block: Konsultasi & Pelatihan -->
          <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-4">
              <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Konsultasi & Pelatihan" class="w-100" style="object-fit: cover; height: 340px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label"><i class="bi bi-award me-1"></i> Layanan 03</span>
            <h2 class="profil-section-title">Konsultasi Metode &amp; Pelatihan Analis</h2>
            <p class="profil-body-text">Tingkatkan kompetensi analis laboratorium Anda melalui pelatihan pengoperasian instrumen, interpretasi data spektrum/kromatografi, serta optimasi pemilihan metode uji analitika.</p>

            <div class="row g-3 mt-3">
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-people"></i></div>
                  <h3 class="layanan-feature-title">Pelatihan Alat Baru</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Sesi workshop langsung (hands-on) di lokasi fasilitas Anda dipandu oleh spesialis aplikasi instrumen.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-sliders"></i></div>
                  <h3 class="layanan-feature-title">Optimasi Metode Uji</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Panduan pemilihan reagen dan parameter pengujian untuk efisiensi biaya serta akurasi hasil analisis.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="layanan-feature-card">
                  <div class="profil-stat-num mb-2"><i class="bi bi-file-earmark-check"></i></div>
                  <h3 class="layanan-feature-title">Jaminan Mutu (QA/QC)</h3>
                  <p class="profil-body-text mb-0" style="font-size: 0.88rem;">Pendampingan standarisasi SOP, logbook instrumen, dan penyiapan berkas verifikasi harian.</p>
                </div>
              </div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Butuh Sesi Pelatihan In-House?</h3>
              <p class="profil-body-text mb-4">Ajukan workshop spesifik sesuai jenis instrumen dan modul yang ingin didalami oleh tim laboratorium Anda.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="nb-btn nb-btn-primary">
                Hubungi Tim Pelatihan <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>

          <!-- Mobile-only CTA Box -->
          <div class="profil-trust-box d-md-none mt-5">
            <h3 class="profil-sidebar-title"><i class="bi bi-headset me-2"></i>Hubungi Kami</h3>
            <p style="font-size: 0.88rem; color: var(--nb-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan permintaan perbaikan atau konsultasi instrumen melalui saluran kontak resmi kami.</p>
            <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-primary w-100 justify-content-center mb-3">
              Formulir Kontak <i class="bi bi-arrow-right ms-2"></i>
            </a>
            <a href="tel:02138741447" class="profil-social-link justify-content-center"><i class="bi bi-telephone me-2"></i> 021-3874-1447</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  @include('partials.gsap-loader')
  <script>
    document.addEventListener('DOMContentLoaded', function() {


      const sidebarLinks = document.querySelectorAll('#service-nav .layanan-sidebar-link');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const urlObj = new URL(this.href);
          const serviceKey = urlObj.searchParams.get('s');
          if (!serviceKey) return;
          sidebarLinks.forEach(l => l.classList.remove('is-active'));
          this.classList.add('is-active');
          document.querySelectorAll('.service-content-block').forEach(block => block.classList.add('d-none'));
          const targetBlock = document.getElementById('service-content-' + serviceKey);
          if (targetBlock) {
            targetBlock.classList.remove('d-none');
            targetBlock.querySelectorAll('.animate-on-scroll').forEach(el => el.classList.add('is-visible'));
          }
          history.pushState(null, '', window.location.pathname + '?s=' + serviceKey);
          
          if (typeof initGSAPAnimations === 'function') {
            initGSAPAnimations();
          }
        });
      });
      
      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const serviceKey = urlParams.get('s') || 'maintenance';
        sidebarLinks.forEach(link => {
          const urlObj = new URL(link.href);
          link.classList.toggle('is-active', urlObj.searchParams.get('s') === serviceKey);
        });
        document.querySelectorAll('.service-content-block').forEach(block => block.classList.add('d-none'));
        const targetBlock = document.getElementById('service-content-' + serviceKey);
        if (targetBlock) targetBlock.classList.remove('d-none');
      });
    });
  </script>
  @endpush
@endsection
