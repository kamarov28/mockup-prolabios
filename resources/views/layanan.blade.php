@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Layanan Kami</span>
      <h1 class="editorial-page-title">Layanan After-Sales</h1>
      <p class="editorial-page-subtitle">Solusi layanan teknis yang andal untuk kebutuhan laboratorium Anda</p>
    </div>
  </div>

  <!-- Layanan Content -->
  <section class="section-main" id="service-nav">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          @php $activeService = request()->get('s') ?? 'maintenance'; @endphp

          <div class="mb-5">
            <h3 class="profil-sidebar-title">Pilih Layanan</h3>
            <nav class="layanan-sidebar-nav">
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="layanan-sidebar-link {{ $activeService == 'maintenance' ? 'is-active' : '' }}">Perawatan & Perbaikan</a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="layanan-sidebar-link {{ $activeService == 'labdesign' ? 'is-active' : '' }}">Desain & Pembangunan Lab</a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="layanan-sidebar-link {{ $activeService == 'consultation' ? 'is-active' : '' }}">Konsultasi & Pelatihan</a>
            </nav>
          </div>

          <div class="profil-cta-box d-none d-md-block">
            <h3 class="profil-sidebar-title">Hubungi Kami</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan permintaan perbaikan atau konsultasi instrumen melalui saluran kontak resmi perusahaan.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn d-block mb-3">Formulir Kontak <i class="bi bi-arrow-right"></i></a>
            <a href="tel:02138741447" class="profil-social-link"><i class="bi bi-telephone"></i> 021-3874-1447</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">

          <!-- Service Block: Maintenance & Repair -->
          <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Layanan Perawatan" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Layanan 01</span>
            <h2 class="profil-section-title">Perawatan & Perbaikan</h2>
            <p class="profil-body-text">Sebagai komitmen kami sebagai penyedia <em>layanan purna jual</em> yang terpercaya, PT Prolabios Mitra Analitika tidak hanya menjual peralatan—kami memastikan investasi Anda pada instrumen laboratorium tetap dalam kondisi terbaik. Tim teknisi kami tersertifikasi langsung oleh pabrikan.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Pemeliharaan Preventif</h3><p class="profil-body-text">Jadwal pemeliharaan rutin untuk mencegah kerusakan peralatan yang dapat menghentikan operasional lab Anda.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Troubleshooting & Perbaikan</h3><p class="profil-body-text">Perbaikan cepat menggunakan suku cadang asli <em>spare parts</em>.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Kalibrasi Internal</h3><p class="profil-body-text">Layanan untuk memverifikasi akurasi pembacaan instrumen agar hasil analisis pengujian tetap andal.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Jadwalkan Kunjungan Teknisi</h3>
              <p class="profil-body-text mb-4">Mengalami masalah dengan instrumen laboratorium Anda? Hubungi kami segera untuk menjadwalkan inspeksi.</p>
              <a href="{{ url('/kontak') }}?subjek=service" class="profil-cta-btn">Formulir Permintaan Servis <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Lab Design & Build -->
          <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Layanan 02</span>
            <h2 class="profil-section-title">Desain & Pembangunan Lab</h2>
            <p class="profil-body-text">Kami merancang dan membangun laboratorium modern yang memenuhi standar K3, alur kerja efisien, serta regulasi nasional dan internasional (ISO/GLP). Tim konsultan kami siap mendampingi dari tahap perencanaan hingga serah terima.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Layout & Ergonomi</h3><p class="profil-body-text">Desain meja laboratorium, hood exhaust, biosafety cabinet, dan sistem sirkulasi udara yang optimal.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Kepatuhan terhadap Standar</h3><p class="profil-body-text">Membantu memastikan lab Anda memenuhi regulasi K3, ISO 17025, dan standar industri terkait.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Instalasi Utilitas Lab</h3><p class="profil-body-text">Sistem pipa gas laboratorium khusus, sistem kelistrikan lab yang stabil, dan sistem pembuangan limbah ramah lingkungan.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Mulai Rencana Pengembangan Lab Anda</h3>
              <p class="profil-body-text mb-4">Konsultasikan konsep lab Anda dengan desainer spesialis kami secara gratis.</p>
              <a href="{{ url('/kontak') }}?subjek=labdesign" class="profil-cta-btn">Konsultasi Desain Lab <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Konsultasi & Pelatihan -->
          <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Konsultasi & Pelatihan" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Layanan 03</span>
            <h2 class="profil-section-title">Konsultasi & Pelatihan</h2>
            <p class="profil-body-text">Tingkatkan kemampuan staf laboratorium Anda melalui pelatihan pengoperasian instrumen, interpretasi hasil uji, serta konsultasi troubleshooting untuk metode analisis spesifik.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Pelatihan Peralatan Baru</h3><p class="profil-body-text">Sesi pelatihan tatap muka eksklusif dipimpin teknisi tersertifikasi prinsipal setelah peralatan terpasang.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Optimasi Metode Uji</h3><p class="profil-body-text">Konsultasi pemilihan kit reagen dan metode analisis yang efisien, hemat biaya, dan akurat.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Workshop & Mutu</h3><p class="profil-body-text">Pelatihan standardisasi dokumen mutu laboratorium dan verifikasi kalibrasi instrumen harian.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Butuh Sesi Pelatihan Khusus?</h3>
              <p class="profil-body-text mb-4">Ajukan permintaan workshop atau pelatihan instrumen laboratorium sesuai kebutuhan tim Anda.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Hubungi Tim Pelatihan Kami <i class="bi bi-arrow-right"></i></a>
            </div>
            </div>
          <!-- Mobile-only CTA Box -->
          <div class="profil-cta-box d-md-none mt-5">
            <h3 class="profil-sidebar-title">Hubungi Kami</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Ajukan permintaan perbaikan atau konsultasi instrumen melalui saluran kontak resmi perusahaan.</p>
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
