@extends('admin.layout')

@php
  $section = request()->get('section');
  
  if ($section === 'homepage') {
      $pageTitle = 'Editor Halaman Beranda (Homepage)';
  } elseif ($section === 'banners') {
      $pageTitle = 'Editor Banner & Header Halaman';
  } elseif ($section === 'contacts') {
      $pageTitle = 'Pengaturan Kontak & Alamat';
  } elseif ($section === 'general') {
      $pageTitle = 'Pengaturan Umum, Logo & Media Sosial';
  } else {
      $pageTitle = 'Pengaturan & Editor Halaman Website';
  }
@endphp

@section('title', $pageTitle)
@section('page_title', $pageTitle)

@section('admin_content')
<div class="container-fluid px-0">

  <!-- BACK BUTTON IF ON SUB-SECTION -->
  @if($section)
    <div class="mb-4">
      <a href="{{ route('admin.home.edit') }}" class="admin-btn admin-btn-outline" style="padding: 6px 16px; font-size: 0.75rem;">
        <i class="bi bi-arrow-left"></i> <span>Kembali ke Menu Pengaturan</span>
      </a>
    </div>
  @endif

  <!-- ========================================== -->
  <!-- MAIN SETTINGS DASHBOARD (No Section Parameter) -->
  <!-- ========================================== -->
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
              Kelola nomor WhatsApp Sales/Marketing/Finance, email resmi, alamat lengkap kantor &amp; gudang, serta link unduhan katalog PDF.
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
            <h3 class="h5 fw-bold text-white mb-2" style="font-family: var(--font-headline);">Umum &amp; Sosmed</h3>
            <p class="text-secondary small mb-4" style="line-height: 1.6; font-size: 0.85rem;">
              Ubah nama PT / Perusahaan, upload logo website resmi, atur jam operasional layanan, dan kelola link akun Instagram, LinkedIn, Facebook.
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
      @if($section === 'homepage')
        for (let i = 0; i < 4; i++) {
          bindPreviewListener(`hero_image_file_${i}`, `hero_image_preview_${i}`);
          bindUrlListener(`hero_image_url_${i}`, `hero_image_preview_${i}`);
        }
        for (let i = 0; i < 3; i++) {
          bindPreviewListener(`focus_card_file_${i}`, `focus_card_preview_${i}`);
          bindUrlListener(`focus_card_url_${i}`, `focus_card_preview_${i}`);
        }
      @endif

      @if($section === 'banners')
        bindPreviewListener('products_banner_file', 'products_banner_preview');
        bindUrlListener('products_banner_url', 'products_banner_preview');
        
        bindPreviewListener('sectors_banner_file', 'sectors_banner_preview');
        bindUrlListener('sectors_banner_url', 'sectors_banner_preview');
        
        bindPreviewListener('services_banner_file', 'services_banner_preview');
        bindUrlListener('services_banner_url', 'services_banner_preview');
        
        bindPreviewListener('info_banner_file', 'info_banner_preview');
        bindUrlListener('info_banner_url', 'info_banner_preview');
        
        bindPreviewListener('contact_banner_file', 'contact_banner_preview');
        bindUrlListener('contact_banner_url', 'contact_banner_preview');
      @endif

      @if($section === 'general')
        bindPreviewListener('site_logo_file', 'site_logo_preview');
        bindUrlListener('site_logo_url', 'site_logo_preview');
      @endif
    });
  </script>
  @endif
@endsection
