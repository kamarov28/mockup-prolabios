@extends('layouts.app')

@section('title', 'Profil Perusahaan | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">About Us</span>
      <h1 class="editorial-page-title">Company Profile</h1>
      <p class="editorial-page-subtitle">Getting to Know PT. Prolabios Mitra Analitika Better</p>
    </div>
  </div>

  <!-- Profil Content -->
  <section class="section-main">
    <div class="container">
      <div class="row g-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 order-last order-md-1">

          <!-- Social Links -->
          <div class="mb-5">
            <h3 class="profil-sidebar-title">Stay Connected</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Follow us on social media to get the latest information and updates.</p>
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
            <h3 class="profil-sidebar-title">Need a Consultation?</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Our team of experts is ready to help you choose the right instruments and reagents.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn">Contact Us <i class="bi bi-arrow-right"></i></a>
          </div>

        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8 order-first order-md-2">

          <!-- Story Image -->
          <div class="profil-hero-img mb-5">
            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Prolabios Laboratory" class="w-100" style="object-fit: cover; height: 360px; display: block;" loading="lazy" decoding="async">
          </div>

          <!-- Our Story -->
          <div class="mb-5">
            <span class="profil-section-label">Our Story</span>
            <h2 class="profil-section-title">Our Story</h2>
            <p class="profil-body-text">
                <strong>Prolabios Mitra Analitika (PMA)</strong> was established to become one of Indonesia’s leading distributors, driven by a commitment to meeting the product and service needs of laboratory users and enhancing their skills. Our success is measured by the company’s growth, employee well-being, and customer satisfaction.
            </p>
          </div>

          <hr style="border-color: var(--color-border); margin: 48px 0;">

          <!-- Vision & Mission -->
          <div id="visi-misi" class="mb-5">
            <span class="profil-section-label">Our Principles</span>
            <h2 class="profil-section-title">Vision & Mission</h2>
            <div class="row g-4 mt-2">
              <div class="col-md-6">
                <div class="profil-vm-card">
                  <div class="profil-vm-icon"><i class="bi bi-eye"></i></div>
                  <h3 class="profil-vm-title">Our Vision</h3>
                  <p class="profil-body-text">To be a leading company in meeting your needs, enhancing your skills, and serving as your best partner in finding solutions to your needs.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="profil-vm-card">
                  <div class="profil-vm-icon"><i class="bi bi-bullseye"></i></div>
                  <h3 class="profil-vm-title">Our Mission</h3>
                  <ul class="profil-mission-list">
                    <li><strong>Product:</strong> Providing the highest-quality products and services at affordable prices and with maximum benefits.</li>
                    <li><strong>Users:</strong> To be a partner in product understanding and problem-solving.</li>
                    <li><strong>Purchasing:</strong> To be a reliable procurement partner.</li>
                    <li><strong>Aftersales:</strong> A trusted and reliable after-sales service provider.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <hr style="border-color: var(--color-border); margin: 48px 0;">

          <!-- Core Values -->
          <div>
            <span class="profil-section-label">Our Core Values</span>
            <h2 class="profil-section-title">The P-R-O Way</h2>
            <div class="row g-4 mt-2">
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">P</div>
                  <h4 class="profil-value-title">Professional</h4>
                  <p class="profil-body-text">Demonstrates a high level of expertise and professional work standards in serving partners.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">R</div>
                  <h4 class="profil-value-title">Robust</h4>
                  <p class="profil-body-text">Facing challenges head-on to ensure robust and high-quality product delivery.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profil-value-card">
                  <div class="profil-value-letter">O</div>
                  <h4 class="profil-value-title">Offering the Best</h4>
                  <p class="profil-body-text">Committed to offering the best products and solutions for your laboratory needs.</p>
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
  @include('partials.gsap-loader')
@endpush
