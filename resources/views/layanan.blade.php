@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Layanan Kami</span>
      <h1 class="editorial-page-title">Layanan Purna Jual</h1>
      <p class="editorial-page-subtitle">Solusi layanan teknis yang dapat diandalkan untuk kebutuhan laboratorium Anda</p>
    </div>
  </div>

  <!-- Layanan Content -->
  <section style="padding: 80px 0;" id="service-nav">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          @php $activeService = request()->get('s') ?? 'maintenance'; @endphp

          <div class="mb-5">
            <h3 class="profil-sidebar-title">Pilih Layanan</h3>
            <nav class="layanan-sidebar-nav">
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="layanan-sidebar-link {{ $activeService == 'maintenance' ? 'is-active' : '' }}">Maintenance &amp; Repair</a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="layanan-sidebar-link {{ $activeService == 'labdesign' ? 'is-active' : '' }}">Lab Design &amp; Build</a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="layanan-sidebar-link {{ $activeService == 'consultation' ? 'is-active' : '' }}">Consultation &amp; Training</a>
            </nav>
          </div>

          <div class="profil-cta-box d-none d-md-block">
            <h3 class="profil-sidebar-title">Hubungi Kami</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan perbaikan atau konsultasi instrumen melalui kontak resmi perusahaan.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn d-block mb-3">Formulir Kontak <i class="bi bi-arrow-right"></i></a>
            <a href="tel:02138741447" class="profil-social-link"><i class="bi bi-telephone"></i> 021-3874-1447</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">

          <!-- Service Block: Maintenance & Repair -->
          <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Maintenance Service" class="w-100" style="object-fit: cover; height: 320px; display: block;">
            </div>
            <span class="profil-section-label">Layanan 01</span>
            <h2 class="profil-section-title">Maintenance &amp; Repair</h2>
            <p class="profil-body-text">Sebagai komitmen untuk menjadi penyedia <em>aftersales</em> yang tepercaya, PT Prolabios Mitra Analitika tidak hanya menjual alat, namun memastikan investasi instrumen laboratorium Anda selalu dalam kondisi prima. Tim teknisi kami tersertifikasi langsung oleh prinsipal manufaktur.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Preventive Maintenance</h3><p class="profil-body-text">Jadwal perawatan berkala untuk mencegah kerusakan alat yang dapat menghentikan kegiatan operasional lab Anda.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Troubleshooting &amp; Repair</h3><p class="profil-body-text">Perbaikan cepat dengan menggunakan suku cadang (<em>spare part</em>) original yang terjamin keasliannya.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Kalibrasi Internal</h3><p class="profil-body-text">Layanan pengecekan akurasi pembacaan instrumen untuk menjaga keandalan hasil analisa pengujian.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Jadwalkan Kunjungan Teknisi</h3>
              <p class="profil-body-text mb-4">Apakah Anda mengalami masalah dengan instrumen laboratorium Anda? Segera hubungi kami untuk menjadwalkan pemeriksaan.</p>
              <a href="{{ url('/kontak') }}?subjek=service" class="profil-cta-btn">Formulir Service Request <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Lab Design & Build -->
          <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100" style="object-fit: cover; height: 320px; display: block;">
            </div>
            <span class="profil-section-label">Layanan 02</span>
            <h2 class="profil-section-title">Lab Design &amp; Build</h2>
            <p class="profil-body-text">Kami merancang dan membangun laboratorium modern yang memenuhi standar keselamatan kerja (K3), efisiensi alur kerja (workflow), serta kepatuhan terhadap regulasi nasional maupun internasional (ISO/GLP). Tim konsultan kami siap mendampingi dari perencanaan ruang hingga serah terima.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Tata Letak &amp; Ergonomi</h3><p class="profil-body-text">Desain meja lab (bench), exhaust hood, biosafety cabinet, dan sirkulasi tata udara optimal.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Kepatuhan Standar</h3><p class="profil-body-text">Membantu memastikan lab Anda memenuhi regulasi K3, ISO 17025, dan standar industri spesifik.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Instalasi Utilitas Lab</h3><p class="profil-body-text">Jaringan pipa gas khusus lab, kelistrikan lab stabil, dan instalasi pembuangan limbah ramah lingkungan.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Mulai Rencana Pembangunan Lab Anda</h3>
              <p class="profil-body-text mb-4">Konsultasikan konsep laboratorium Anda secara gratis dengan desainer spesialis kami.</p>
              <a href="{{ url('/kontak') }}?subjek=labdesign" class="profil-cta-btn">Konsultasi Desain Lab <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Consultation & Training -->
          <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Consultation & Training" class="w-100" style="object-fit: cover; height: 320px; display: block;">
            </div>
            <span class="profil-section-label">Layanan 03</span>
            <h2 class="profil-section-title">Consultation &amp; Training</h2>
            <p class="profil-body-text">Meningkatkan kapasitas SDM laboratorium Anda melalui pelatihan pengoperasian instrumen yang tepat, interpretasi data hasil uji, dan konsultasi pemecahan masalah (troubleshooting) metode analisis spesifik.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Pelatihan Alat Baru</h3><p class="profil-body-text">Sesi training eksklusif secara langsung oleh teknisi bersertifikasi prinsipal saat alat selesai diinstal.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Optimasi Metode Uji</h3><p class="profil-body-text">Konsultasi pemilihan kit reagen dan metode analisa yang paling efisien, ekonomis, dan akurat.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Workshop &amp; Mutu</h3><p class="profil-body-text">Pelatihan standarisasi dokumen mutu laboratorium dan verifikasi kalibrasi alat harian.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Butuh Sesi Pelatihan Kustom?</h3>
              <p class="profil-body-text mb-4">Ajukan kebutuhan workshop atau training instrumen lab sesuai kebutuhan spesifik tim Anda.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Hubungi Tim Pelatih Kami <i class="bi bi-arrow-right"></i></a>
            </div>
            </div>
          <!-- Mobile-only CTA Box -->
          <div class="profil-cta-box d-md-none mt-5">
            <h3 class="profil-sidebar-title">Hubungi Kami</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan perbaikan atau konsultasi instrumen melalui kontak resmi perusahaan.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn d-block mb-3">Formulir Kontak <i class="bi bi-arrow-right"></i></a>
            <a href="tel:02138741447" class="profil-social-link"><i class="bi bi-telephone"></i> 021-3874-1447</a>
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
