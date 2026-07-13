@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['services_banner_image'] ?? 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['services_banner_image'] ?? 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['services_title'] ?? 'Layanan Kami' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['services_subtitle'] ?? 'Dukungan purnajual dan layanan konsultasi terpadu' }}</p>
    </div>
  </div>

  <!-- Layanan Content -->
  <section class="py-5 bg-light" id="service-nav">
    <div class="container">
      <div class="row">
        <!-- Sidebar (Left) -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Pilih Layanan</h2>
            <div class="list-group list-group-flush">
              @php $activeService = request()->get('s') ?? 'maintenance'; @endphp
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'maintenance' ? 'active' : '' }}">Maintenance & Repair</a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'labdesign' ? 'active' : '' }}">Lab Design & Build</a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'consultation' ? 'active' : '' }}">Consultation & Training</a>
            </div>
          </div>

          <div class="bg-white p-4 rounded shadow-sm border-0 text-center animate-on-scroll animate-slide-right delay-100">
            <h2 class="h5 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Hubungi Teknisi</h2>
            <p class="small text-muted mb-3">Respon cepat via WhatsApp untuk pengajuan perbaikan alat.</p>
            <a href="https://wa.me/{{ $waNumberTech }}?text=Halo%20Prolabios%2C%20saya%20ingin%20mengajukan%20service%20request%20untuk%20instrumen%20laboratorium%20kami." target="_blank" rel="noopener noreferrer" class="btn btn-success w-100 fw-bold d-flex align-items-center justify-content-center shadow-sm">
              <i class="bi bi-whatsapp me-2 fs-5"></i> WhatsApp Teknisi
            </a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 animate-on-scroll animate-slide-up">
            
            <!-- Service Block: Maintenance & Repair -->
            <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Maintenance Service" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Maintenance & Repair (Perawatan & Perbaikan)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Sebagai komitmen untuk menjadi penyedia <em>aftersales</em> yang tepercaya, PT Prolabios Mitra Analitika tidak hanya menjual alat, namun memastikan investasi instrumen laboratorium Anda selalu dalam kondisi prima. Tim teknisi kami tersertifikasi langsung oleh prinsipal manufaktur.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Preventive Maintenance</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Jadwal perawatan berkala untuk mencegah kerusakan alat yang dapat menghentikan kegiatan operasional lab Anda.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Troubleshooting & Repair</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Perbaikan cepat dengan menggunakan suku cadang (<em>spare part</em>) original yang terjamin keasliannya.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Kalibrasi Internal</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Layanan pengecekan akurasi pembacaan instrumen untuk menjaga keandalan hasil analisa pengujian.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Jadwalkan Kunjungan Teknisi</h3>
                <p class="text-muted mb-4">Apakah Anda mengalami masalah dengan instrumen laboratorium Anda? Segera hubungi kami untuk menjadwalkan pemeriksaan.</p>
                <a href="{{ url('/kontak') }}?subjek=service" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Formulir Service Request</a>
              </div>
            </div>

            <!-- Service Block: Lab Design & Build -->
            <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Lab Design & Build (Perancangan & Pembangunan Lab)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Kami merancang dan membangun laboratorium modern yang memenuhi standar keselamatan kerja (K3), efisiensi alur kerja (workflow), serta kepatuhan terhadap regulasi nasional maupun internasional (ISO/GLP). Tim konsultan kami siap mendampingi dari perencanaan ruang hingga serah terima.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Tata Letak & Ergonomi</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Desain meja lab (bench), exhaust hood, biosafety cabinet, dan sirkulasi tata udara optimal.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Kepatuhan Standar</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Membantu memastikan lab Anda memenuhi regulasi K3, ISO 17025, dan standar industri spesifik.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Instalasi Utilitas Lab</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Jaringan pipa gas khusus lab, kelistrikan lab stabil, dan instalasi pembuangan limbah ramah lingkungan.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Mulai Rencana Pembangunan Lab Anda</h3>
                <p class="text-muted mb-4">Konsultasikan konsep laboratorium Anda secara gratis dengan desainer spesialis kami.</p>
                <a href="{{ url('/kontak') }}?subjek=labdesign" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Konsultasi Desain Lab</a>
              </div>
            </div>

            <!-- Service Block: Consultation & Training -->
            <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Consultation & Training" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Consultation & Training (Konsultasi & Pelatihan)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Meningkatkan kapasitas SDM laboratorium Anda melalui pelatihan pengoperasian instrumen yang tepat, interpretasi data hasil uji, dan konsultasi pemecahan masalah (troubleshooting) metode analisis spesifik.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Pelatihan Alat Baru</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Sesi training eksklusif secara langsung oleh teknisi bersertifikasi prinsipal saat alat selesai diinstal.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Optimasi Metode Uji</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Konsultasi pemilihan kit reagen dan metode analisa yang paling efisien, ekonomis, dan akurat.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Workshop & Mutu</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Pelatihan standarisasi dokumen mutu laboratorium dan verifikasi kalibrasi alat harian.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Butuh Sesi Pelatihan Kustom?</h3>
                <p class="text-muted mb-4">Ajukan kebutuhan workshop atau training instrumen lab sesuai kebutuhan spesifik tim Anda.</p>
                <a href="{{ url('/kontak') }}?subjek=consultation" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Hubungi Tim Pelatih Kami</a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarLinks = document.querySelectorAll('#service-nav .list-group-item');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Get service key from href param s
          const urlObj = new URL(this.href);
          const serviceKey = urlObj.searchParams.get('s');
          
          if (!serviceKey) return;
          
          // Update active link class
          sidebarLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          
          // Hide all blocks, show the target block
          document.querySelectorAll('.service-content-block').forEach(block => {
            block.classList.add('d-none');
          });
          
          const targetBlock = document.getElementById('service-content-' + serviceKey);
          if (targetBlock) {
            targetBlock.classList.remove('d-none');
            
            // Re-trigger animate on scroll reveal for components in target block
            targetBlock.querySelectorAll('.animate-on-scroll').forEach(el => {
              el.classList.add('is-visible');
            });
          }
          
          // Update URL without page jump or reload
          history.pushState(null, '', window.location.pathname + '?s=' + serviceKey);
        });
      });
      
      // Handle browser back/forward buttons
      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const serviceKey = urlParams.get('s') || 'maintenance';
        
        sidebarLinks.forEach(link => {
          const urlObj = new URL(link.href);
          if (urlObj.searchParams.get('s') === serviceKey) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
        
        document.querySelectorAll('.service-content-block').forEach(block => {
          block.classList.add('d-none');
        });
        
        const targetBlock = document.getElementById('service-content-' + serviceKey);
        if (targetBlock) {
          targetBlock.classList.remove('d-none');
        }
      });
    });
  </script>
  @endpush
@endsection

@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')
@section('meta_description', 'Layanan Prolabios - Dukungan purnajual, konsultasi teknis, training, dan maintenance untuk alat laboratorium dan instrumen analitika.')
@section('meta_keywords', 'layanan, purnajual, konsultasi, training, maintenance, service laboratorium, prolabios, dukungan teknis')
@section('canonical_url', url('/layanan'))

@section('preload')
  <link rel="preload" href="{{ $siteSettings['services_banner_image'] ?? 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['services_banner_image'] ?? 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['services_title'] ?? 'Layanan Kami' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['services_subtitle'] ?? 'Dukungan purnajual dan layanan konsultasi terpadu' }}</p>
    </div>
  </div>

  <!-- Layanan Content -->
  <section class="py-5 bg-light" id="service-nav">
    <div class="container">
      <div class="row">
        <!-- Sidebar (Left) -->
        <div class="col-lg-3 col-md-4 mb-4">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-right">
            <h2 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2" style="color: var(--color-secondary, #2b2d42);">Pilih Layanan</h2>
            <div class="list-group list-group-flush">
              @php $activeService = request()->get('s') ?? 'maintenance'; @endphp
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'maintenance' ? 'active' : '' }}">Maintenance & Repair</a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'labdesign' ? 'active' : '' }}">Lab Design & Build</a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="list-group-item list-group-item-action sector-sidebar-link {{ $activeService == 'consultation' ? 'active' : '' }}">Consultation & Training</a>
            </div>
          </div>

          <div class="bg-white p-4 rounded shadow-sm border-0 text-center animate-on-scroll animate-slide-right delay-100">
            <h2 class="h5 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Hubungi Teknisi</h2>
            <p class="small text-muted mb-3">Respon cepat via WhatsApp untuk pengajuan perbaikan alat.</p>
            <a href="https://wa.me/{{ $waNumberTech }}?text=Halo%20Prolabios%2C%20saya%20ingin%20mengajukan%20service%20request%20untuk%20instrumen%20laboratorium%20kami." target="_blank" rel="noopener noreferrer" class="btn btn-success w-100 fw-bold d-flex align-items-center justify-content-center shadow-sm">
              <i class="bi bi-whatsapp me-2 fs-5"></i> WhatsApp Teknisi
            </a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 animate-on-scroll animate-slide-up">
            
            <!-- Service Block: Maintenance & Repair -->
            <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Maintenance Service" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Maintenance & Repair (Perawatan & Perbaikan)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Sebagai komitmen untuk menjadi penyedia <em>aftersales</em> yang tepercaya, PT Prolabios Mitra Analitika tidak hanya menjual alat, namun memastikan investasi instrumen laboratorium Anda selalu dalam kondisi prima. Tim teknisi kami tersertifikasi langsung oleh prinsipal manufaktur.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Preventive Maintenance</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Jadwal perawatan berkala untuk mencegah kerusakan alat yang dapat menghentikan kegiatan operasional lab Anda.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Troubleshooting & Repair</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Perbaikan cepat dengan menggunakan suku cadang (<em>spare part</em>) original yang terjamin keasliannya.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Kalibrasi Internal</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Layanan pengecekan akurasi pembacaan instrumen untuk menjaga keandalan hasil analisa pengujian.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Jadwalkan Kunjungan Teknisi</h3>
                <p class="text-muted mb-4">Apakah Anda mengalami masalah dengan instrumen laboratorium Anda? Segera hubungi kami untuk menjadwalkan pemeriksaan.</p>
                <a href="{{ url('/kontak') }}?subjek=service" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Formulir Service Request</a>
              </div>
            </div>

            <!-- Service Block: Lab Design & Build -->
            <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Lab Design & Build (Perancangan & Pembangunan Lab)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Kami merancang dan membangun laboratorium modern yang memenuhi standar keselamatan kerja (K3), efisiensi alur kerja (workflow), serta kepatuhan terhadap regulasi nasional maupun internasional (ISO/GLP). Tim konsultan kami siap mendampingi dari perencanaan ruang hingga serah terima.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Tata Letak & Ergonomi</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Desain meja lab (bench), exhaust hood, biosafety cabinet, dan sirkulasi tata udara optimal.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Kepatuhan Standar</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Membantu memastikan lab Anda memenuhi regulasi K3, ISO 17025, dan standar industri spesifik.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Instalasi Utilitas Lab</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Jaringan pipa gas khusus lab, kelistrikan lab stabil, dan instalasi pembuangan limbah ramah lingkungan.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Mulai Rencana Pembangunan Lab Anda</h3>
                <p class="text-muted mb-4">Konsultasikan konsep laboratorium Anda secara gratis dengan desainer spesialis kami.</p>
                <a href="{{ url('/kontak') }}?subjek=labdesign" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Konsultasi Desain Lab</a>
              </div>
            </div>

            <!-- Service Block: Consultation & Training -->
            <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
              <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Consultation & Training" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
              </div>
              
              <h2 class="mb-3 fw-bold" style="color: var(--color-secondary, #2b2d42);">Consultation & Training (Konsultasi & Pelatihan)</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                Meningkatkan kapasitas SDM laboratorium Anda melalui pelatihan pengoperasian instrumen yang tepat, interpretasi data hasil uji, dan konsultasi pemecahan masalah (troubleshooting) metode analisis spesifik.
              </p>

              <div class="row g-4 mt-3">
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-100">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Pelatihan Alat Baru</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Sesi training eksklusif secara langsung oleh teknisi bersertifikasi prinsipal saat alat selesai diinstal.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-200">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Optimasi Metode Uji</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Konsultasi pemilihan kit reagen dan metode analisa yang paling efisien, ekonomis, dan akurat.</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 animate-on-scroll animate-slide-up delay-300">
                  <div class="card h-100 border-0 shadow-sm bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="card-body p-4">
                      <h3 class="h5 fw-bold text-primary mb-3" style="font-size: 1rem;">Workshop & Mutu</h3>
                      <p class="card-text text-muted small mb-0" style="font-size: 0.78rem; line-height: 1.6;">Pelatihan standarisasi dokumen mutu laboratorium dan verifikasi kalibrasi alat harian.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-4 rounded bg-light border animate-on-scroll animate-scale-in delay-200">
                <h3 class="h4 fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Butuh Sesi Pelatihan Kustom?</h3>
                <p class="text-muted mb-4">Ajukan kebutuhan workshop atau training instrumen lab sesuai kebutuhan spesifik tim Anda.</p>
                <a href="{{ url('/kontak') }}?subjek=consultation" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">Hubungi Tim Pelatih Kami</a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarLinks = document.querySelectorAll('#service-nav .list-group-item');
      
      sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Get service key from href param s
          const urlObj = new URL(this.href);
          const serviceKey = urlObj.searchParams.get('s');
          
          if (!serviceKey) return;
          
          // Update active link class
          sidebarLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          
          // Hide all blocks, show the target block
          document.querySelectorAll('.service-content-block').forEach(block => {
            block.classList.add('d-none');
          });
          
          const targetBlock = document.getElementById('service-content-' + serviceKey);
          if (targetBlock) {
            targetBlock.classList.remove('d-none');
            
            // Re-trigger animate on scroll reveal for components in target block
            targetBlock.querySelectorAll('.animate-on-scroll').forEach(el => {
              el.classList.add('is-visible');
            });
          }
          
          // Update URL without page jump or reload
          history.pushState(null, '', window.location.pathname + '?s=' + serviceKey);
        });
      });
      
      // Handle browser back/forward buttons
      window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const serviceKey = urlParams.get('s') || 'maintenance';
        
        sidebarLinks.forEach(link => {
          const urlObj = new URL(link.href);
          if (urlObj.searchParams.get('s') === serviceKey) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
        
        document.querySelectorAll('.service-content-block').forEach(block => {
          block.classList.add('d-none');
        });
        
        const targetBlock = document.getElementById('service-content-' + serviceKey);
        if (targetBlock) {
          targetBlock.classList.remove('d-none');
        }
      });
    });
  </script>
  @endpush
@endsection
