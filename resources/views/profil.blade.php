@extends('layouts.app')

@section('title', 'Profil Perusahaan | PROLABIOS')

@section('preload')
  <link rel="preload" href="https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=1920&q=80" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=1920&q=80') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">Profil Perusahaan</h1>
      <p class="lead mb-0 text-light opacity-75">Mengenal Lebih Dekat PT. Prolabios Mitra Analitika</p>
    </div>
  </div>

  <!-- Profil Content -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 col-md-7 mb-4 order-md-2">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 h-100 animate-on-scroll animate-slide-up">
            <div class="overflow-hidden rounded shadow-sm mb-4" style="height: 320px;">
              <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Prolabios Building" class="w-100 h-100" style="object-fit: cover; transition: transform 0.5s ease;">
            </div>
            
            <h2 class="fw-bold mb-3" style="color: var(--color-secondary, #2b2d42);">Our Story</h2>
            <p class="text-muted" style="text-align: justify; line-height: 1.8;">
              <strong>Prolabios Mitra Analitika (PMA)</strong> dibangun untuk menjadi salah satu distributor terkemuka di Indonesia dengan semangat memenuhi kebutuhan produk atau layanan serta peningkatan keterampilan bagi pengguna laboratorium. Kesuksesan kami adalah pertumbuhan perusahaan, kesejahteraan karyawan, dan kepuasan pelanggan.
            </p>
 
            <div id="visi-misi" class="row g-4 mt-4">
              <div class="col-md-6 animate-on-scroll animate-slide-up delay-100">
                <div class="card border-0 shadow-sm h-100 bg-light" style="border-left: 4px solid var(--color-primary) !important;">
                  <div class="card-body p-4 text-center">
                    <div class="display-4 mb-3 text-primary"><i class="bi bi-eye-fill"></i></div>
                    <h3 class="h4 fw-bold" style="color: var(--color-secondary, #2b2d42);">Our Vision</h3>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">Menjadi perusahaan terdepan dalam pemenuhan kebutuhan, eskalasi keterampilan, dan mitra terbaik dalam mencari solusi untuk kebutuhan Anda.</p>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6 animate-on-scroll animate-slide-up delay-200">
                <div class="card border-0 shadow-sm h-100 bg-light" style="border-left: 4px solid var(--color-primary) !important;">
                  <div class="card-body p-4">
                    <div class="display-4 text-center mb-3 text-primary"><i class="bi bi-bullseye"></i></div>
                    <h3 class="h4 fw-bold text-center mb-3" style="color: var(--color-secondary, #2b2d42);">Our Mission</h3>
                    <ul class="list-unstyled text-muted small mt-2">
                      <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i>
                        <span><strong>Product:</strong> Menyediakan produk dan layanan berkualitas terbaik, harga terjangkau, dan manfaat maksimal.</span>
                      </li>
                      <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i>
                        <span><strong>Users:</strong> Menjadi mitra dalam pemahaman produk dan pemecahan masalah.</span>
                      </li>
                      <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i>
                        <span><strong>Purchasing:</strong> Menjadi mitra pengadaan yang dapat diandalkan.</span>
                      </li>
                      <li class="mb-0 d-flex align-items-start">
                        <i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i>
                        <span><strong>Aftersales:</strong> Penyedia layanan purnajual yang tepercaya dan dapat diandalkan.</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
 
            <div class="mt-5 animate-on-scroll animate-slide-up delay-300">
              <h3 class="h4 fw-bold mb-4 text-center" style="color: var(--color-secondary, #2b2d42);">Core Values Perusahaan</h3>
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="card h-100 border-0 shadow-sm text-center p-3 bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="display-5 fw-bold text-primary mb-2">P</div>
                    <h4 class="h6 fw-bold">Professional</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Menunjukkan keahlian tinggi dan standar kerja profesional dalam melayani mitra.</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card h-100 border-0 shadow-sm text-center p-3 bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="display-5 fw-bold text-primary mb-2">R</div>
                    <h4 class="h6 fw-bold">Robust</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Ketangguhan dalam menghadapi tantangan dan menghadirkan produk berkualitas tinggi.</p>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card h-100 border-0 shadow-sm text-center p-3 bg-light" style="border-top: 4px solid var(--color-primary) !important;">
                    <div class="display-5 fw-bold text-primary mb-2">O</div>
                    <h4 class="h6 fw-bold">Offering the best</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">Komitmen memberikan produk dan solusi terbaik bagi kebutuhan laboratorium Anda.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
 
        <!-- Sidebar -->
        <div class="col-lg-4 col-md-5 mb-4 order-md-1">
          <div class="bg-white p-4 rounded shadow-sm border-0 mb-4 animate-on-scroll animate-slide-left delay-100">
            <h3 class="h5 fw-bold mb-3 pb-2 border-bottom border-primary border-2">Tetap Terhubung</h3>
            <p class="text-muted small mb-3">Ikuti media sosial kami untuk mendapatkan informasi dan pembaruan terkini tentang teknologi laboratorium.</p>
            <div class="d-grid gap-2">
              <a href="https://web.facebook.com/PT-Prolabios-Mitra-Analitika-1787666991553394/" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary fw-semibold"><i class="bi bi-facebook me-2"></i> Facebook</a>
              <a href="https://www.instagram.com/prolabios.id?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger fw-semibold"><i class="bi bi-instagram me-2"></i> Instagram</a>
              <a href="https://www.linkedin.com/company/pt-prolabios-mitra-analitika/posts/?feedView=all" target="_blank" rel="noopener noreferrer" class="btn btn-outline-linkedin fw-semibold"><i class="bi bi-linkedin me-2"></i> LinkedIn</a>
            </div>
          </div>
 
          <div class="p-4 rounded shadow-sm text-center border-0 animate-on-scroll animate-slide-left delay-200 sidebar-cta-box">
            <h3 class="h5 fw-bold mb-3">Butuh Konsultasi?</h3>
            <p class="small mb-3">Tim ahli kami siap membantu Anda memilih instrumen dan reagen yang tepat untuk kebutuhan spesifik laboratorium Anda.</p>
            <a href="{{ url('/kontak') }}" class="btn w-100 fw-bold shadow-sm">Hubungi Kami</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
