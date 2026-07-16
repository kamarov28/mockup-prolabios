@extends('layouts.app')

@section('title', 'Profil Perusahaan | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Tentang Kami</span>
      <h1 class="editorial-page-title">Profil Perusahaan</h1>
      <p class="editorial-page-subtitle">Mengenal Lebih Dekat PT. Prolabios Mitra Analitika</p>
    </div>
  </div>

  <!-- Profil Content -->
  <section style="padding: 80px 0;">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 order-md-1">

          <!-- Social Links -->
          <div class="mb-5">
            <h3 class="profil-sidebar-title">Tetap Terhubung</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Ikuti media sosial kami untuk mendapatkan informasi dan pembaruan terkini.</p>
            <div class="d-flex flex-column gap-2">
              <a href="https://web.facebook.com/PT-Prolabios-Mitra-Analitika-1787666991553394/" target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-facebook"></i> Facebook
              </a>
              <a href="https://www.instagram.com/prolabios.id" target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-instagram"></i> Instagram
              </a>
              <a href="https://www.linkedin.com/company/pt-prolabios-mitra-analitika/posts/?feedView=all" target="_blank" rel="noopener noreferrer" class="profil-social-link">
                <i class="bi bi-linkedin"></i> LinkedIn
              </a>
            </div>
          </div>

          <!-- CTA Box -->
          <div class="profil-cta-box">
            <h3 class="profil-sidebar-title">Butuh Konsultasi?</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Tim ahli kami siap membantu Anda memilih instrumen dan reagen yang tepat.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn">Hubungi Kami <i class="bi bi-arrow-right"></i></a>
          </div>

        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8 order-md-2">

          <!-- Story Image -->
          <div class="profil-hero-img mb-5">
            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Prolabios Laboratory" class="w-100" style="object-fit: cover; height: 360px; display: block;" loading="lazy" decoding="async">
          </div>

          <!-- Our Story -->
          <div class="mb-5">
            <span class="profil-section-label">01 — Cerita Kami</span>
            <h2 class="profil-section-title">Our Story</h2>
            <p class="profil-body-text">
              <strong>Prolabios Mitra Analitika (PMA)</strong> dibangun untuk menjadi salah satu distributor terkemuka di Indonesia dengan semangat memenuhi kebutuhan produk atau layanan serta peningkatan keterampilan bagi pengguna laboratorium. Kesuksesan kami adalah pertumbuhan perusahaan, kesejahteraan karyawan, dan kepuasan pelanggan.
            </p>
          </div>

          <hr style="border-color: var(--color-border); margin: 48px 0;">

          <!-- Vision & Mission -->
          <div id="visi-misi" class="mb-5">
            <span class="profil-section-label">02 — Tujuan</span>
            <h2 class="profil-section-title">Visi & Misi</h2>
            <div class="row g-4 mt-2">
              <div class="col-md-6">
                <div class="profil-vm-card">
                  <div class="profil-vm-icon"><i class="bi bi-eye"></i></div>
                  <h3 class="profil-vm-title">Our Vision</h3>
                  <p class="profil-body-text">Menjadi perusahaan terdepan dalam pemenuhan kebutuhan, eskalasi keterampilan, dan mitra terbaik dalam mencari solusi untuk kebutuhan Anda.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="profil-vm-card">
                  <div class="profil-vm-icon"><i class="bi bi-bullseye"></i></div>
                  <h3 class="profil-vm-title">Our Mission</h3>
                  <ul class="profil-mission-list">
                    <li><strong>Product:</strong> Menyediakan produk dan layanan berkualitas terbaik, harga terjangkau, dan manfaat maksimal.</li>
                    <li><strong>Users:</strong> Menjadi mitra dalam pemahaman produk dan pemecahan masalah.</li>
                    <li><strong>Purchasing:</strong> Menjadi mitra pengadaan yang dapat diandalkan.</li>
                    <li><strong>Aftersales:</strong> Penyedia layanan purnajual yang tepercaya dan dapat diandalkan.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <hr style="border-color: var(--color-border); margin: 48px 0;">

          <!-- Core Values -->
          <div>
            <span class="profil-section-label">03 — Nilai Utama</span>
            <h2 class="profil-section-title">Core Values</h2>
            <div class="row g-4 mt-2">
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">P</div>
                  <h4 class="profil-value-title">Professional</h4>
                  <p class="profil-body-text">Menunjukkan keahlian tinggi dan standar kerja profesional dalam melayani mitra.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">R</div>
                  <h4 class="profil-value-title">Robust</h4>
                  <p class="profil-body-text">Ketangguhan dalam menghadapi tantangan dan menghadirkan produk berkualitas tinggi.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">O</div>
                  <h4 class="profil-value-title">Offering the Best</h4>
                  <p class="profil-body-text">Komitmen memberikan produk dan solusi terbaik bagi kebutuhan laboratorium Anda.</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    (function () {
      if (document.documentElement.classList.contains('no-motion')) return;
      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

      function loadScript(src) {
        return new Promise(function (resolve, reject) {
          var s = document.createElement('script');
          s.src = src;
          s.async = true;
          s.onload = resolve;
          s.onerror = reject;
          document.head.appendChild(s);
        });
      }

      var boot = function () {
        loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js')
          .then(function () {
            return loadScript('https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js');
          })
          .then(function () {
            if (typeof initGSAPAnimations === 'function') {
              initGSAPAnimations();
            }
          })
          .catch(function () {
            // GSAP failed load fallback is handled gracefully by styles
          });
      };

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
      } else {
        boot();
      }
    })();
  </script>
@endpush
