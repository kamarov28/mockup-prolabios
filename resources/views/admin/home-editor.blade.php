@extends('admin.layout')

@php
  $section = request()->get('section');
  $tabParam = request()->get('tab');

  // Legacy page-banner section: editorial headers no longer use banner images
  if ($section === 'banners') {
      $section = null;
  }

  if ($section === 'homepage') {
      $pageTitle = 'Editor Halaman Beranda (Homepage)';
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

  @if($section)
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 p-3" style="background: var(--color-surface); border: 2px solid #1E1E1E; border-radius: 4px; box-shadow: 3px 3px 0 #1E1E1E;">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.home.edit') }}" class="admin-btn admin-btn-outline me-1" style="padding: 6px 14px; font-size: 0.78rem;">
          <i class="bi bi-grid-fill me-1"></i> <span>Semua Modul</span>
        </a>
        <div class="vr bg-dark opacity-50 d-none d-md-block mx-1" style="width: 2px; height: 24px;"></div>
        <div class="admin-view-switcher">
          <a href="{{ route('admin.home.edit', ['section' => 'homepage']) }}" class="admin-view-switcher-btn {{ $section === 'homepage' ? 'active' : '' }}" style="padding: 6px 14px; font-size: 0.78rem;">
            <i class="bi bi-house-door me-1"></i> <span>Beranda</span>
          </a>
          <a href="{{ route('admin.home.edit', ['section' => 'contacts']) }}" class="admin-view-switcher-btn {{ $section === 'contacts' ? 'active' : '' }}" style="padding: 6px 14px; font-size: 0.78rem;">
            <i class="bi bi-telephone-outbound me-1"></i> <span>Kontak & Alamat</span>
          </a>
          <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="admin-view-switcher-btn {{ $section === 'general' ? 'active' : '' }}" style="padding: 6px 14px; font-size: 0.78rem;">
            <i class="bi bi-gear-wide-connected me-1"></i> <span>Umum & SEO</span>
          </a>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/') }}" target="_blank" class="admin-btn admin-btn-ghost" style="padding: 6px 14px; font-size: 0.78rem;" title="Lihat Website Publik">
          <i class="bi bi-box-arrow-up-right me-1"></i> <span>Live Preview</span>
        </a>
      </div>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-dismissible fade show mb-4" style="background: #FEE2E2; border: 2px solid #1E1E1E; border-radius: 4px; box-shadow: 3px 3px 0 #1E1E1E; color: #1E1E1E;" role="alert">
      <div class="d-flex align-items-center mb-1">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2"></i>
        <strong style="color: #991B1B;">Terdapat beberapa data yang belum sesuai:</strong>
      </div>
      <ul class="mb-0 small ps-4" style="color: #991B1B; font-weight: 500;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(!$section)
    <div class="mb-5">
      <span class="admin-page-label">PENGATURAN KONTEN WEBSITE</span>
      <h1 class="admin-page-title mb-2">Pilih Bagian yang Ingin Diatur</h1>
      <p style="color: var(--color-text-muted); font-size: 0.88rem; max-width: 600px; line-height: 1.6; margin-bottom: 0;">
        Silakan pilih modul di bawah untuk memperbarui teks beranda, nomor kontak, logo, atau pengaturan media sosial.
      </p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 2px solid #1E1E1E; border-radius: 4px; background: #FFD4D6; color: var(--color-accent); box-shadow: 2px 2px 0 #1E1E1E;">
                <i class="bi bi-house-gear fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-accent">Halaman Utama</span>
            </div>
            <h3 class="h5 fw-bold mb-2" style="font-family: var(--font-headline); color: var(--color-text-main);">Halaman Beranda</h3>
            <p style="color: var(--color-text-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 24px;">
              Atur slideshow hero, slogan, kartu bento standar, alur sector finder, dan banner konversi RFQ.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'homepage']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Beranda</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 2px solid #1E1E1E; border-radius: 4px; background: #B9F5D0; color: #166534; box-shadow: 2px 2px 0 #1E1E1E;">
                <i class="bi bi-telephone-outbound fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-success">Informasi Kontak</span>
            </div>
            <h3 class="h5 fw-bold mb-2" style="font-family: var(--font-headline); color: var(--color-text-main);">Kontak &amp; Alamat</h3>
            <p style="color: var(--color-text-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 24px;">
              Kelola nomor WhatsApp utama &amp; teknisi, telepon kantor marketing/finance, email resmi, dan peta.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'contacts']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Kontak</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="admin-card h-100 d-flex flex-column justify-content-between p-4">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border: 2px solid #1E1E1E; border-radius: 4px; background: #FDE68A; color: #92400E; box-shadow: 2px 2px 0 #1E1E1E;">
                <i class="bi bi-gear-wide-connected fs-4"></i>
              </div>
              <span class="admin-badge admin-badge-warning">Setelan Situs</span>
            </div>
            <h3 class="h5 fw-bold mb-2" style="font-family: var(--font-headline); color: var(--color-text-main);">Umum &amp; SEO</h3>
            <p style="color: var(--color-text-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 24px;">
              Nama PT resmi, jam operasional, logo &amp; favicon, banner login, meta deskripsi SEO, dan media sosial.
            </p>
          </div>
          <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="admin-btn admin-btn-outline text-center w-100 justify-content-center">
            <span>Edit Setelan</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  @endif
            <span>Edit Setelan</span> <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  @endif

  @if($section === 'homepage')
    @include('admin.partials.editor-homepage')
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
      const activeTabParam = @json($tabParam);
      if (activeTabParam) {
        const targetBtn = document.querySelector(`button[data-bs-target="#${activeTabParam}-panel"]`);
        if (targetBtn) {
          const tab = new bootstrap.Tab(targetBtn);
          tab.show();
        }
      }

      const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
      tabButtons.forEach(btn => {
        btn.addEventListener('shown.bs.tab', function(e) {
          const targetId = e.target.getAttribute('data-bs-target');
          if (targetId) {
            const cleanTab = targetId.replace('#', '').replace('-panel', '');
            const tabInputs = document.querySelectorAll('input[name="tab"]');
            tabInputs.forEach(input => input.value = cleanTab);
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
