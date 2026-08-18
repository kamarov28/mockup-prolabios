@extends('admin.layout')

@php
  $section = request()->get('section');
  $tabParam = request()->get('tab');
  
  if ($section === 'homepage') {
      $pageTitle = 'Editor Halaman Beranda (Homepage)';
  } elseif ($section === 'banners') {
      $pageTitle = 'Editor Banner & Header Halaman';
  } elseif ($section === 'contacts') {
      $pageTitle = 'Pengaturan Kontak & Alamat';
  } elseif ($section === 'general') {
      $pageTitle = 'Pengaturan Umum, Logo & SEO';
  } else {
      $pageTitle = 'Pengaturan & Editor Halaman Website';
  }
@endphp

@section('title', $pageTitle)
@section('page_title', $pageTitle)

@section('admin_content')
<div class="container-fluid px-0">

  <!-- ========================================== -->
  <!-- TOP SECTION NAVIGATION BAR (If inside a section) -->
  <!-- ========================================== -->
  @if($section)
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3 rounded-3" style="background: var(--color-surface); border: 1px solid var(--color-border);">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.home.edit') }}" class="admin-btn admin-btn-outline me-2" style="padding: 6px 14px; font-size: 0.75rem;">
          <i class="bi bi-grid-fill me-1"></i> <span>Semua Menu</span>
        </a>
        <div class="vr bg-secondary opacity-25 d-none d-md-block mx-1"></div>
        <a href="{{ route('admin.home.edit', ['section' => 'homepage']) }}" class="admin-btn {{ $section === 'homepage' ? 'admin-btn-accent' : 'admin-btn-outline' }}" style="padding: 6px 12px; font-size: 0.72rem;">
          <i class="bi bi-house-door me-1"></i> <span>Beranda</span>
        </a>
        <a href="{{ route('admin.home.edit', ['section' => 'banners']) }}" class="admin-btn {{ $section === 'banners' ? 'admin-btn-accent' : 'admin-btn-outline' }}" style="padding: 6px 12px; font-size: 0.72rem;">
          <i class="bi bi-images me-1"></i> <span>Banner Halaman</span>
        </a>
        <a href="{{ route('admin.home.edit', ['section' => 'contacts']) }}" class="admin-btn {{ $section === 'contacts' ? 'admin-btn-accent' : 'admin-btn-outline' }}" style="padding: 6px 12px; font-size: 0.72rem;">
          <i class="bi bi-telephone-outbound me-1"></i> <span>Kontak & Alamat</span>
        </a>
        <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="admin-btn {{ $section === 'general' ? 'admin-btn-accent' : 'admin-btn-outline' }}" style="padding: 6px 12px; font-size: 0.72rem;">
          <i class="bi bi-gear-wide-connected me-1"></i> <span>Umum & SEO</span>
        </a>
      </div>

      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/') }}" target="_blank" class="admin-btn admin-btn-outline text-secondary" style="padding: 6px 12px; font-size: 0.72rem;" title="Lihat Website Publik">
          <i class="bi bi-box-arrow-up-right me-1"></i> <span>Live Preview</span>
        </a>
      </div>
    </div>
  @endif

  <!-- Validation Errors Alert -->
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border border-danger-subtle" style="background: rgba(239, 68, 68, 0.12); border-radius: 8px;" role="alert">
      <div class="d-flex align-items-center mb-1">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2"></i>
        <strong class="text-danger">Terdapat beberapa data yang belum sesuai:</strong>
      </div>
      <ul class="mb-0 small text-white-50 ps-4">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- ========================================== -->
  <!-- MAIN SETTINGS DASHBOARD (No Section Parameter) -->
  <!-- ========================================== -->
  @if(!$section)
    <!-- Header Summary -->
    <div class="mb-5">
      <span class="admin-page-label">PENGATURAN KONTEN WEBSITE</span>
      <h1 class="admin-page-title mb-2">Pilih Bagian yang Ingin Diatur</h1>
      <p class="text-secondary small mb-0" style="max-width: 600px; line-height: 1.6;">
        Silakan pilih salah satu modul di bawah ini untuk memperbarui teks, banner, nomor kontak, logo, atau pengaturan media sosial website Anda.
      </p>
    </div>

    <div class="row g-4">
      
      <!-- Card 1: Homepage Editor -->
      <div class="col-md-6 col-lg-3">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4 transition-all" style="background: #0e0e10; border: 1px solid var(--color-border); border-radius: 12px;">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(255, 73, 80, 0.1); color: var(--color-accent);">
                <i class="bi bi-house-gear fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-accent">Halaman Utama</span>
            </div>
            <h3 class="h5 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Halaman Beranda</h3>
            <p class="text-secondary small mb-4" style="line-height: 1.6; font-size: 0.85rem;">
              Atur banner slideshow utama, teks slogan hero, 4 kartu fokus industri, profil singkat perusahaan, dan banner ajakan (CTA) di beranda.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'homepage']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Beranda</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Card 2: Page Banners Editor -->
      <div class="col-md-6 col-lg-3">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4 transition-all" style="background: #0e0e10; border: 1px solid var(--color-border); border-radius: 12px;">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(56, 189, 248, 0.1); color: #38bdf8;">
                <i class="bi bi-images fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-info">Header &amp; Banner</span>
            </div>
            <h3 class="h5 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Banner Halaman</h3>
            <p class="text-secondary small mb-4" style="line-height: 1.6; font-size: 0.85rem;">
              Ubah gambar latar belakang header, judul, dan deskripsi pada halaman Katalog Produk, Sektor Industri, Layanan, Artikel, dan Kontak.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'banners']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Banner</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Card 3: Contact & Address Settings -->
      <div class="col-md-6 col-lg-3">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4 transition-all" style="background: #0e0e10; border: 1px solid var(--color-border); border-radius: 12px;">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                <i class="bi bi-telephone-outbound fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-success">Informasi Kontak</span>
            </div>
            <h3 class="h5 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Kontak &amp; Alamat</h3>
            <p class="text-secondary small mb-4" style="line-height: 1.6; font-size: 0.85rem;">
              Kelola nomor WhatsApp Sales/Marketing/Finance, template sapaan WA, email resmi, alamat kantor, dan link peta Google Maps.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'contacts']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Kontak</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Card 4: General Settings & Logo -->
      <div class="col-md-6 col-lg-3">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4 transition-all" style="background: #0e0e10; border: 1px solid var(--color-border); border-radius: 12px;">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-gear-wide-connected fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-warning">Setelan Situs</span>
            </div>
            <h3 class="h5 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Umum &amp; SEO</h3>
            <p class="text-secondary small mb-4" style="line-height: 1.6; font-size: 0.85rem;">
              Ubah nama PT, upload logo &amp; favicon, jam kerja, link akun media sosial, serta meta SEO &amp; Google Search Console.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Setelan</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

    </div>
  @endif

  <!-- ========================================== -->
  <!-- FORM EDITOR SECTIONS (Modular Partials) -->
  <!-- ========================================== -->
  @if($section === 'homepage')
    @include('admin.partials.editor-homepage')
  @elseif($section === 'banners')
    @include('admin.partials.editor-banners')
  @elseif($section === 'contacts' || $section === 'general')
    @include('admin.partials.editor-settings')
  @endif

</div>
@endsection

@section('admin_scripts')
  @if($section)
  <script>
    function bindPreviewListener(fileInputId, imgPreviewId) {
      const input = document.getElementById(fileInputId);
      const img = document.getElementById(imgPreviewId);
      if (input && img) {
        input.addEventListener('change', function() {
          if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              img.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
          }
        });
      }
    }

    function bindUrlListener(urlInputId, imgPreviewId) {
      const input = document.getElementById(urlInputId);
      const img = document.getElementById(imgPreviewId);
      if (input && img) {
        input.addEventListener('input', function() {
          const val = this.value.trim();
          if (val !== '') {
            img.src = val;
          }
        });
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Sync sub-tabs with URL query param & hidden input
      const activeTabParam = @json($tabParam);
      if (activeTabParam) {
        const targetBtn = document.querySelector(`button[data-bs-target="#${activeTabParam}-panel"]`);
        if (targetBtn) {
          const tab = new bootstrap.Tab(targetBtn);
          tab.show();
        }
      }

      // Track active tab and populate hidden tab input
      const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
      tabButtons.forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
          const targetId = e.target.getAttribute('data-bs-target');
          if (targetId) {
            const cleanTab = targetId.replace('#', '').replace('-panel', '');
            const tabInputs = document.querySelectorAll('input[name="tab"]');
            tabInputs.forEach(input => input.value = cleanTab);
            
            // Optionally update browser URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', cleanTab);
            window.history.replaceState({}, '', url);
          }
        });
      });

      @if($section === 'homepage')
        for (let i = 0; i < 4; i++) {
          bindPreviewListener(`hero_image_file_${i}`, `hero_image_preview_${i}`);
          bindUrlListener(`hero_image_url_${i}`, `hero_image_preview_${i}`);
        }
      @endif

      @if($section === 'banners')
        const bannerPages = ['products', 'sectors', 'services', 'info', 'contact'];
        bannerPages.forEach(p => {
          bindPreviewListener(`${p}_banner_file`, `${p}_banner_preview`);
          bindUrlListener(`${p}_banner_url`, `${p}_banner_preview`);
        });
      @endif

      @if($section === 'general')
        bindPreviewListener('site_logo_file', 'site_logo_preview');
        bindUrlListener('site_logo_url', 'site_logo_preview');

        bindPreviewListener('site_favicon_file', 'site_favicon_preview');
        bindUrlListener('site_favicon_url', 'site_favicon_preview');
      @endif
    });
  </script>
  @endif
@endsection
