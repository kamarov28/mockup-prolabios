@extends('layouts.app')

@section('title', 'Layanan Kami | PROLABIOS')

@section('content')
  <!-- Editorial Page Header -->
  <div class="editorial-page-header">
    <div class="container">
      <span class="editorial-page-label">Our Services</span>
      <h1 class="editorial-page-title">After-Sales Service</h1>
      <p class="editorial-page-subtitle">Reliable technical service solutions for your laboratory needs</p>
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
            <h3 class="profil-sidebar-title">Select a Service</h3>
            <nav class="layanan-sidebar-nav">
              <a href="{{ url('/layanan') }}?s=maintenance#service-nav" class="layanan-sidebar-link {{ $activeService == 'maintenance' ? 'is-active' : '' }}">Maintenance &amp; Repair</a>
              <a href="{{ url('/layanan') }}?s=labdesign#service-nav" class="layanan-sidebar-link {{ $activeService == 'labdesign' ? 'is-active' : '' }}">Lab Design &amp; Build</a>
              <a href="{{ url('/layanan') }}?s=consultation#service-nav" class="layanan-sidebar-link {{ $activeService == 'consultation' ? 'is-active' : '' }}">Consultation &amp; Training</a>
            </nav>
          </div>

          <div class="profil-cta-box d-none d-md-block">
            <h3 class="profil-sidebar-title">Contact Us</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Submit a repair request or consult about an instrument through the company&apos;s official contact channels.</p>
            <a href="{{ url('/kontak') }}" class="profil-cta-btn d-block mb-3">Contact Form <i class="bi bi-arrow-right"></i></a>
            <a href="tel:02138741447" class="profil-social-link"><i class="bi bi-telephone"></i> 021-3874-1447</a>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">

          <!-- Service Block: Maintenance & Repair -->
          <div id="service-content-maintenance" class="service-content-block {{ $activeService == 'maintenance' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Maintenance Service" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Service 01</span>
            <h2 class="profil-section-title">Maintenance &amp; Repair</h2>
            <p class="profil-body-text">As part of our commitment to being a trusted <em>after-sales</em> provider, PT Prolabios Mitra Analitika doesn’t just sell equipment—we ensure that your investment in laboratory instruments is always in top condition. Our team of technicians is certified directly by the manufacturers.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Preventive Maintenance</h3><p class="profil-body-text">A schedule of routine maintenance to prevent equipment failure that could halt your lab’s operations.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Troubleshooting &amp; Repair</h3><p class="profil-body-text">Quick repairs utilizing guaranteed genuine <em>spare parts</em>.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Internal Calibration</h3><p class="profil-body-text">A service to verify the accuracy of instrument readings to ensure the reliability of test analysis results.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Schedule a Technician Visit</h3>
              <p class="profil-body-text mb-4">Are you having problems with your laboratory instruments? Contact us right away to schedule an inspection.</p>
              <a href="{{ url('/kontak') }}?subjek=service" class="profil-cta-btn">Service Request Form <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Lab Design & Build -->
          <div id="service-content-labdesign" class="service-content-block {{ $activeService == 'labdesign' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Lab Design & Build" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Service 02</span>
            <h2 class="profil-section-title">Lab Design &amp; Build</h2>
            <p class="profil-body-text">We design and build modern laboratories that meet occupational safety (K3) standards, ensure efficient workflows, and comply with national and international regulations (ISO/GLP). Our team of consultants is ready to assist you from the planning stage through to handover.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Layout &amp; Ergonomics</h3><p class="profil-body-text">Design of laboratory benches, exhaust hoods, biosafety cabinets, and optimal air circulation systems.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Compliance with Standards</h3><p class="profil-body-text">Helps ensure your lab complies with occupational safety and health regulations, ISO 17025, and specific industry standards.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Lab Utility Installation</h3><p class="profil-body-text">A dedicated laboratory gas pipeline system, a stable laboratory electrical system, and an environmentally friendly wastewater disposal system.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Get Started on Your Lab Development Plan</h3>
              <p class="profil-body-text mb-4">Consult with our specialist designers about your lab concept for free.</p>
              <a href="{{ url('/kontak') }}?subjek=labdesign" class="profil-cta-btn">Lab Design Consultation <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <!-- Service Block: Consultation & Training -->
          <div id="service-content-consultation" class="service-content-block {{ $activeService == 'consultation' ? '' : 'd-none' }}">
            <div class="profil-hero-img mb-5">
              <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Consultation & Training" class="w-100" style="object-fit: cover; height: 320px; display: block;" loading="lazy" decoding="async">
            </div>
            <span class="profil-section-label">Service 03</span>
            <h2 class="profil-section-title">Consultation &amp; Training</h2>
            <p class="profil-body-text">Enhance the capabilities of your laboratory staff through training in proper instrument operation, interpretation of test results, and troubleshooting consultations for specific analytical methods.</p>

            <div class="row g-4 mt-3">
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">New Equipment Training</h3><p class="profil-body-text">An exclusive in-person training session led by a principal-certified technician once the equipment has been installed.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Test Method Optimization</h3><p class="profil-body-text">Consultation on selecting the most efficient, cost-effective, and accurate reagent kits and analytical methods.</p></div></div>
              <div class="col-md-4"><div class="layanan-feature-card"><h3 class="layanan-feature-title">Workshops &amp; Quality</h3><p class="profil-body-text">Training on standardizing laboratory quality documents and verifying daily instrument calibration.</p></div></div>
            </div>

            <div class="layanan-cta-strip mt-5">
              <h3 class="layanan-cta-title">Need a Custom Training Session?</h3>
              <p class="profil-body-text mb-4">Submit a request for a workshop or training on laboratory instruments tailored to your team’s specific needs.</p>
              <a href="{{ url('/kontak') }}?subjek=consultation" class="profil-cta-btn">Contact Our Coaching Staff <i class="bi bi-arrow-right"></i></a>
            </div>
            </div>
          <!-- Mobile-only CTA Box -->
          <div class="profil-cta-box d-md-none mt-5">
            <h3 class="profil-sidebar-title">Contact Us</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.6;">Submit a repair request or consult about an instrument through the company&apos;s official contact channels.</p>
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
