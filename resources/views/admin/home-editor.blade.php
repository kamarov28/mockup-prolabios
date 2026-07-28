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

  <!-- BACK BUTTON IF NOT ON DASHBOARD -->
  @if($section)
    <div class="mb-4">
      <a href="{{ route('admin.home.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu Pengaturan
      </a>
    </div>
  @endif

  <!-- ========================================== -->
  <!-- MAIN SETTINGS DASHBOARD (No Section Parameter) -->
  <!-- ========================================== -->
  @if(!$section)
    <div class="row g-4 justify-content-center">
      
      <!-- Card 1: Homepage Editor -->
      <div class="col-md-3">
        <div class="card h-100 bg-white border-0 shadow-sm transition-all hover-translate-y">
          <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
            <div class="rounded-circle bg-danger-subtle text-danger p-4 mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
              <i class="bi bi-house-gear fs-1"></i>
            </div>
            <div>
              <h3 class="h5 fw-bold text-dark mb-2">Halaman Beranda</h3>
              <p class="text-muted small mb-4">Kelola slideshow banner utama, kartu fokus industri, profil Tentang PMA, dan info hotline CS.</p>
            </div>
            <a href="{{ route('admin.home.edit', ['section' => 'homepage']) }}" class="btn btn-danger w-100 py-2 fw-semibold rounded-pill">
              Buka Editor Beranda <i class="bi bi-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Card 2: Page Banners Editor -->
      <div class="col-md-3">
        <div class="card h-100 bg-white border-0 shadow-sm transition-all hover-translate-y">
          <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
            <div class="rounded-circle bg-info-subtle text-info p-4 mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
              <i class="bi bi-file-earmark-richtext fs-1"></i>
            </div>
            <div>
              <h3 class="h5 fw-bold text-dark mb-2">Banner &amp; Header Halaman</h3>
              <p class="text-muted small mb-4">Atur gambar banner latar belakang, judul halaman, dan subjudul untuk halaman Produk, Sektor, Layanan, Artikel, dan Kontak.</p>
            </div>
            <a href="{{ route('admin.home.edit', ['section' => 'banners']) }}" class="btn btn-info text-white w-100 py-2 fw-semibold rounded-pill">
              Buka Editor Banner <i class="bi bi-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Card 3: Contact & Address Settings -->
      <div class="col-md-3">
        <div class="card h-100 bg-white border-0 shadow-sm transition-all hover-translate-y">
          <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
            <div class="rounded-circle bg-success-subtle text-success p-4 mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
              <i class="bi bi-telephone-outbound fs-1"></i>
            </div>
            <div>
              <h3 class="h5 fw-bold text-dark mb-2">Kontak &amp; Alamat</h3>
              <p class="text-muted small mb-4">Ubah nomor telepon utama, email perusahaan, alamat kantor lengkap, dan link katalog PDF.</p>
            </div>
            <a href="{{ route('admin.home.edit', ['section' => 'contacts']) }}" class="btn btn-success w-100 py-2 fw-semibold rounded-pill">
              Buka Pengaturan Kontak <i class="bi bi-chevron-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Card 4: General Settings & Logo -->
      <div class="col-md-3">
        <div class="card h-100 bg-white border-0 shadow-sm transition-all hover-translate-y">
          <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-between">
            <div class="rounded-circle bg-warning-subtle text-warning p-4 mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
              <i class="bi bi-gear fs-1"></i>
            </div>
            <div>
              <h3 class="h5 fw-bold text-dark mb-2">Umum &amp; Logo</h3>
              <p class="text-muted small mb-4">Ubah nama perusahaan, upload file logo situs utama, edit jam operasional, dan link akun media sosial.</p>
            </div>
            <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="btn btn-warning text-white w-100 py-2 fw-semibold rounded-pill">
              Buka Setelan Umum <i class="bi bi-chevron-right ms-1"></i>
            </a>
          </div>
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
