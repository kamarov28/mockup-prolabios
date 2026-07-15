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
  <!-- FORM EDITOR: SECTION HOMEPAGE -->
  <!-- ========================================== -->
  @if($section === 'homepage')
    <div class="card bg-white shadow-sm border-0">
      <div class="card-header border-bottom py-3 bg-white">
        <ul class="nav nav-pills card-header-pills" id="homeTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold small" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-panel" type="button" role="tab"><i class="bi bi-image me-1"></i> Hero Slideshow</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="focus-tab" data-bs-toggle="tab" data-bs-target="#focus-panel" type="button" role="tab"><i class="bi bi-award me-1"></i> Fokus Industri</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-panel" type="button" role="tab"><i class="bi bi-info-circle me-1"></i> Tentang PMA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="hotline-tab" data-bs-toggle="tab" data-bs-target="#hotline-panel" type="button" role="tab"><i class="bi bi-telephone me-1"></i> CS Hotline</button>
          </li>
        </ul>
      </div>

      <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
        @csrf
        <input type="hidden" name="section" value="homepage">

        <div class="tab-content" id="homeTabsContent">
          
          <!-- Sub-panel: Hero Slideshow -->
          <div class="tab-pane fade show active" id="hero-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Hero Slideshow Banner</h2>
            
            <div class="mb-3">
              <label for="hero_title" class="form-label fw-bold">Hero Title / Slogan</label>
              <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $homeData['hero_title'] ?? '') }}" required>
              <div class="form-text">Gunakan tag HTML seperti <code>&lt;span class="text-primary"&gt;kata&lt;/span&gt;</code> untuk memberikan aksen warna merah.</div>
            </div>

            <div class="mb-4">
              <label for="hero_subtitle" class="form-label fw-bold">Hero Subtitle</label>
              <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3" required>{{ old('hero_subtitle', $homeData['hero_subtitle'] ?? '') }}</textarea>
            </div>

            <h3 class="h6 fw-bold mb-3 text-secondary border-bottom pb-1">Gambar Slideshow Carousel (Maks. 4 Gambar)</h3>
            <div class="row g-3">
              @for($i = 0; $i < 4; $i++)
                @php $imgUrl = $homeData['hero_images'][$i] ?? ''; @endphp
                <div class="col-md-6 col-lg-3">
                  <div class="border rounded p-3 bg-light h-100">
                    <span class="badge bg-secondary mb-2">Slide #{{ $i + 1 }}</span>
                    <div class="rounded border overflow-hidden mb-2" style="aspect-ratio: 16/9;">
                      <img id="hero_image_preview_{{ $i }}" src="{{ $imgUrl }}" alt="Slide {{ $i + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="mb-2">
                      <label for="hero_image_file_{{ $i }}" class="form-label small fw-bold">Upload File</label>
                      <input type="file" id="hero_image_file_{{ $i }}" class="form-control form-control-sm" name="hero_image_file_{{ $i }}" accept="image/*">
                    </div>
                    <div>
                      <label for="hero_image_url_{{ $i }}" class="form-label small fw-bold">Atau Gunakan URL</label>
                      <input type="text" id="hero_image_url_{{ $i }}" class="form-control form-control-sm" name="hero_image_url_{{ $i }}" value="{{ $imgUrl }}">
                    </div>
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <!-- Sub-panel: Fokus Industri -->
          <div class="tab-pane fade" id="focus-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Bagian Fokus Industri</h2>
            
            <div class="mb-4">
              <label for="focus_title" class="form-label fw-bold">Judul Section Fokus</label>
              <input type="text" class="form-control" id="focus_title" name="focus_title" value="{{ old('focus_title', $homeData['focus_title'] ?? '') }}" required>
            </div>

            <h3 class="h6 fw-bold mb-3 text-secondary border-bottom pb-1">Kartu Fokus Industri (Maks. 3 Kartu)</h3>
            <div class="row g-3">
              @for($i = 0; $i < 3; $i++)
                @php 
                  $card = $homeData['focus_cards'][$i] ?? []; 
                  $cardImg = $card['image'] ?? '';
                @endphp
                <div class="col-md-4">
                  <div class="border rounded p-3 bg-light h-100">
                    <span class="badge bg-danger mb-2">Kartu #{{ $i + 1 }}</span>
                    <div class="mb-2">
                      <label for="focus_card_title_{{ $i }}" class="form-label small fw-bold">Judul Kartu</label>
                      <input type="text" id="focus_card_title_{{ $i }}" class="form-control form-control-sm" name="focus_card_title_{{ $i }}" value="{{ old('focus_card_title_'.$i, $card['title'] ?? '') }}" required>
                    </div>
                    <div class="mb-2">
                      <label for="focus_card_desc_{{ $i }}" class="form-label small fw-bold">Deskripsi Singkat</label>
                      <textarea id="focus_card_desc_{{ $i }}" class="form-control form-control-sm" name="focus_card_desc_{{ $i }}" rows="3" required>{{ old('focus_card_desc_'.$i, $card['description'] ?? '') }}</textarea>
                    </div>
                    <div class="rounded border overflow-hidden mb-2" style="aspect-ratio: 16/9;">
                      <img id="focus_card_preview_{{ $i }}" src="{{ $cardImg }}" alt="Preview" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="mb-2">
                      <label for="focus_card_file_{{ $i }}" class="form-label small fw-bold">Upload Gambar</label>
                      <input type="file" id="focus_card_file_{{ $i }}" class="form-control form-control-sm" name="focus_card_file_{{ $i }}" accept="image/*">
                    </div>
                    <div>
                      <label for="focus_card_url_{{ $i }}" class="form-label small fw-bold">Atau Gunakan URL</label>
                      <input type="text" id="focus_card_url_{{ $i }}" class="form-control form-control-sm" name="focus_card_url_{{ $i }}" value="{{ $cardImg }}">
                    </div>
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <!-- Sub-panel: Tentang PMA -->
          <div class="tab-pane fade" id="about-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Bagian Tentang PMA (Beranda)</h2>
            
            <div class="mb-3">
              <label for="about_title" class="form-label fw-bold">Judul Section Tentang Kami</label>
              <input type="text" class="form-control" id="about_title" name="about_title" value="{{ old('about_title', $homeData['about_title'] ?? '') }}" required>
            </div>

            <div class="mb-3">
              <label for="about_description" class="form-label fw-bold">Deskripsi Profil PMA</label>
              <textarea class="form-control" id="about_description" name="about_description" rows="5" required>{{ old('about_description', $homeData['about_description'] ?? '') }}</textarea>
            </div>
          </div>

          <!-- Sub-panel: CS Hotline -->
          <div class="tab-pane fade" id="hotline-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Layanan Pelanggan &amp; CS Hotline</h2>
            
            <div class="mb-3">
              <label for="hotline_label" class="form-label fw-bold">Label Hotline</label>
              <input type="text" class="form-control" id="hotline_label" name="hotline_label" value="{{ old('hotline_label', $homeData['hotline_label'] ?? '') }}" required>
            </div>

            <div class="mb-3">
              <label for="hotline_number" class="form-label fw-bold">Nomor Kontak WhatsApp / Telp</label>
              <input type="text" class="form-control" id="hotline_number" name="hotline_number" value="{{ old('hotline_number', $homeData['hotline_number'] ?? '') }}" required>
            </div>

            <div class="mb-3">
              <label for="hotline_description" class="form-label fw-bold">Deskripsi Layanan CS</label>
              <textarea class="form-control" id="hotline_description" name="hotline_description" rows="3" required>{{ old('hotline_description', $homeData['hotline_description'] ?? '') }}</textarea>
            </div>
          </div>

        </div>

        <div class="mt-4 border-top pt-3 text-end">
          <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BERANDA</button>
        </div>
      </form>
    </div>
  @endif

  <!-- ========================================== -->
  <!-- FORM EDITOR: SECTION PAGE BANNERS -->
  <!-- ========================================== -->
  @if($section === 'banners')
    <div class="card bg-white shadow-sm border-0">
      <div class="card-header border-bottom py-3 bg-white">
        <ul class="nav nav-pills card-header-pills" id="bannerTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold small" id="prod-banner-tab" data-bs-toggle="tab" data-bs-target="#prod-banner-panel" type="button" role="tab">Halaman Produk</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="sec-banner-tab" data-bs-toggle="tab" data-bs-target="#sec-banner-panel" type="button" role="tab">Halaman Sektor</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="serv-banner-tab" data-bs-toggle="tab" data-bs-target="#serv-banner-panel" type="button" role="tab">Halaman Layanan</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="info-banner-tab" data-bs-toggle="tab" data-bs-target="#info-banner-panel" type="button" role="tab">Halaman Informasi</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold small" id="cont-banner-tab" data-bs-toggle="tab" data-bs-target="#cont-banner-panel" type="button" role="tab">Halaman Kontak</button>
          </li>
        </ul>
      </div>

      <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
        @csrf
        <input type="hidden" name="section" value="banners">

        <div class="tab-content" id="bannerTabsContent">
          
          <!-- Page: Products -->
          <div class="tab-pane fade show active" id="prod-banner-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Header Halaman Produk</h2>
            <div class="mb-3">
              <label for="products_title" class="form-label fw-bold">Judul Halaman</label>
              <input type="text" class="form-control" id="products_title" name="products_title" value="{{ old('products_title', $homeData['products_title'] ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="products_subtitle" class="form-label fw-bold">Subjudul / Deskripsi Halaman</label>
              <input type="text" class="form-control" id="products_subtitle" name="products_subtitle" value="{{ old('products_subtitle', $homeData['products_subtitle'] ?? '') }}" required>
            </div>
            <div class="row align-items-center border rounded p-3 bg-light">
              <div class="col-md-3">
                <div class="rounded border bg-light overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
                  <img id="products_banner_preview" src="{{ $homeData['products_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
                </div>
              </div>
              <div class="col-md-9">
                <div class="mb-2">
                  <label for="products_banner_file" class="form-label small fw-bold">Upload Gambar Banner</label>
                  <input type="file" id="products_banner_file" class="form-control form-control-sm" name="products_banner_file" accept="image/*">
                </div>
                <div>
                  <label for="products_banner_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
                  <input type="text" id="products_banner_url" class="form-control form-control-sm" name="products_banner_url" value="{{ $homeData['products_banner_image'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>

          <!-- Page: Sectors -->
          <div class="tab-pane fade" id="sec-banner-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Header Halaman Sektor Industri</h2>
            <div class="mb-3">
              <label for="sectors_title" class="form-label fw-bold">Judul Halaman</label>
              <input type="text" class="form-control" id="sectors_title" name="sectors_title" value="{{ old('sectors_title', $homeData['sectors_title'] ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="sectors_subtitle" class="form-label fw-bold">Subjudul / Deskripsi Halaman</label>
              <input type="text" class="form-control" id="sectors_subtitle" name="sectors_subtitle" value="{{ old('sectors_subtitle', $homeData['sectors_subtitle'] ?? '') }}" required>
            </div>
            <div class="row align-items-center border rounded p-3 bg-light">
              <div class="col-md-3">
                <div class="rounded border bg-light overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
                  <img id="sectors_banner_preview" src="{{ $homeData['sectors_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
                </div>
              </div>
              <div class="col-md-9">
                <div class="mb-2">
                  <label for="sectors_banner_file" class="form-label small fw-bold">Upload Gambar Banner</label>
                  <input type="file" id="sectors_banner_file" class="form-control form-control-sm" name="sectors_banner_file" accept="image/*">
                </div>
                <div>
                  <label for="sectors_banner_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
                  <input type="text" id="sectors_banner_url" class="form-control form-control-sm" name="sectors_banner_url" value="{{ $homeData['sectors_banner_image'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>

          <!-- Page: Services -->
          <div class="tab-pane fade" id="serv-banner-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Header Halaman Layanan</h2>
            <div class="mb-3">
              <label for="services_title" class="form-label fw-bold">Judul Halaman</label>
              <input type="text" class="form-control" id="services_title" name="services_title" value="{{ old('services_title', $homeData['services_title'] ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="services_subtitle" class="form-label fw-bold">Subjudul / Deskripsi Halaman</label>
              <input type="text" class="form-control" id="services_subtitle" name="services_subtitle" value="{{ old('services_subtitle', $homeData['services_subtitle'] ?? '') }}" required>
            </div>
            <div class="row align-items-center border rounded p-3 bg-light">
              <div class="col-md-3">
                <div class="rounded border bg-light overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
                  <img id="services_banner_preview" src="{{ $homeData['services_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
                </div>
              </div>
              <div class="col-md-9">
                <div class="mb-2">
                  <label for="services_banner_file" class="form-label small fw-bold">Upload Gambar Banner</label>
                  <input type="file" id="services_banner_file" class="form-control form-control-sm" name="services_banner_file" accept="image/*">
                </div>
                <div>
                  <label for="services_banner_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
                  <input type="text" id="services_banner_url" class="form-control form-control-sm" name="services_banner_url" value="{{ $homeData['services_banner_image'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>

          <!-- Page: Information -->
          <div class="tab-pane fade" id="info-banner-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Header Halaman Artikel &amp; Informasi</h2>
            <div class="mb-3">
              <label for="info_title" class="form-label fw-bold">Judul Halaman</label>
              <input type="text" class="form-control" id="info_title" name="info_title" value="{{ old('info_title', $homeData['info_title'] ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="info_subtitle" class="form-label fw-bold">Subjudul / Deskripsi Halaman</label>
              <input type="text" class="form-control" id="info_subtitle" name="info_subtitle" value="{{ old('info_subtitle', $homeData['info_subtitle'] ?? '') }}" required>
            </div>
            <div class="row align-items-center border rounded p-3 bg-light">
              <div class="col-md-3">
                <div class="rounded border bg-light overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
                  <img id="info_banner_preview" src="{{ $homeData['info_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
                </div>
              </div>
              <div class="col-md-9">
                <div class="mb-2">
                  <label for="info_banner_file" class="form-label small fw-bold">Upload Gambar Banner</label>
                  <input type="file" id="info_banner_file" class="form-control form-control-sm" name="info_banner_file" accept="image/*">
                </div>
                <div>
                  <label for="info_banner_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
                  <input type="text" id="info_banner_url" class="form-control form-control-sm" name="info_banner_url" value="{{ $homeData['info_banner_image'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>

          <!-- Page: Contact -->
          <div class="tab-pane fade" id="cont-banner-panel" role="tabpanel">
            <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Header Halaman Kontak</h2>
            <div class="mb-3">
              <label for="contact_title" class="form-label fw-bold">Judul Halaman</label>
              <input type="text" class="form-control" id="contact_title" name="contact_title" value="{{ old('contact_title', $homeData['contact_title'] ?? '') }}" required>
            </div>
            <div class="mb-3">
              <label for="contact_subtitle" class="form-label fw-bold">Subjudul / Deskripsi Halaman</label>
              <input type="text" class="form-control" id="contact_subtitle" name="contact_subtitle" value="{{ old('contact_subtitle', $homeData['contact_subtitle'] ?? '') }}" required>
            </div>
            <div class="row align-items-center border rounded p-3 bg-light">
              <div class="col-md-3">
                <div class="rounded border bg-light overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
                  <img id="contact_banner_preview" src="{{ $homeData['contact_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
                </div>
              </div>
              <div class="col-md-9">
                <div class="mb-2">
                  <label for="contact_banner_file" class="form-label small fw-bold">Upload Gambar Banner</label>
                  <input type="file" id="contact_banner_file" class="form-control form-control-sm" name="contact_banner_file" accept="image/*">
                </div>
                <div>
                  <label for="contact_banner_url" class="form-label small fw-bold">Atau Gunakan URL Gambar</label>
                  <input type="text" id="contact_banner_url" class="form-control form-control-sm" name="contact_banner_url" value="{{ $homeData['contact_banner_image'] ?? '' }}">
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="mt-4 border-top pt-3 text-end">
          <button type="submit" class="btn btn-info text-white px-4 fw-bold rounded-pill"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BANNER</button>
        </div>
      </form>
    </div>
  @endif

  <!-- ========================================== -->
  <!-- FORM EDITOR: SECTION CONTACTS -->
  <!-- ========================================== -->
  @if($section === 'contacts')
    <div class="card bg-white shadow-sm border-0 max-w-4xl mx-auto">
      <div class="card-header border-bottom py-3 bg-white">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="bi bi-telephone-outbound text-success me-2"></i>Informasi Kontak Global</h2>
      </div>

      <form action="{{ route('admin.home.update') }}" method="POST" class="card-body p-4">
        @csrf
        <input type="hidden" name="section" value="contacts">

        <div class="mb-3">
          <label for="contact_phone" class="form-label fw-bold">Nomor WhatsApp Utama (CS / Sales)</label>
          <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $homeData['contact_phone'] ?? '0821-8792-9433') }}" required>
          <div class="form-text">Perubahan akan langsung ter-update di link obrolan WhatsApp utama di seluruh situs.</div>
        </div>

        <div class="mb-3">
          <label for="contact_phone_marketing" class="form-label fw-bold">Telepon Kantor - Head Office (Marketing)</label>
          <input type="text" class="form-control" id="contact_phone_marketing" name="contact_phone_marketing" value="{{ old('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '021-3874-1447') }}" required>
          <div class="form-text">Nomor telepon kantor pusat (PMA HOPMA) yang terhubung ke divisi marketing.</div>
        </div>

        <div class="mb-3">
          <label for="contact_phone_finance" class="form-label fw-bold">Telepon Kantor - Finance &amp; Warehouse</label>
          <input type="text" class="form-control" id="contact_phone_finance" name="contact_phone_finance" value="{{ old('contact_phone_finance', $homeData['contact_phone_finance'] ?? '021-8792-9433') }}" required>
          <div class="form-text">Nomor telepon kantor (PMA VILLAPMA) yang terhubung ke finance &amp; warehouse.</div>
        </div>

        <div class="mb-3">
          <label for="contact_phone_technician" class="form-label fw-bold">Nomor WhatsApp Layanan Teknik (Teknisi)</label>
          <input type="text" class="form-control" id="contact_phone_technician" name="contact_phone_technician" value="{{ old('contact_phone_technician', $homeData['contact_phone_technician'] ?? '0812-837-4867') }}" required>
          <div class="form-text">Perubahan akan memperbarui kontak WhatsApp teknisi pada halaman Layanan kami.</div>
        </div>

        <div class="mb-3">
          <label for="contact_email" class="form-label fw-bold">Alamat Email Utama</label>
          <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ old('contact_email', $homeData['contact_email'] ?? 'marketing@prolabios.com') }}" required>
          <div class="form-text">Email resmi perusahaan yang tampil di header, footer, dan kontak.</div>
        </div>

        <div class="mb-3">
          <label for="contact_address" class="form-label fw-bold">Alamat Lengkap Kantor</label>
          <textarea class="form-control" id="contact_address" name="contact_address" rows="4" required>{{ old('contact_address', $homeData['contact_address'] ?? '') }}</textarea>
        </div>

        <div class="mb-3">
          <label for="catalog_pdf_url" class="form-label fw-bold">Link Google Drive Katalog PDF</label>
          <input type="url" class="form-control" id="catalog_pdf_url" name="catalog_pdf_url" value="{{ old('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '') }}">
          <div class="form-text">Tautan langsung Google Drive untuk dokumen katalog PDF ("Unduh Katalog").</div>
        </div>

        <div class="mt-4 border-top pt-3 text-end">
          <button type="submit" class="btn btn-success px-4 fw-bold rounded-pill"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN KONTAK</button>
        </div>
      </form>
    </div>
  @endif

  @if($section === 'general')
    <div class="card bg-white shadow-sm border-0">
      <div class="card-header border-bottom py-3 bg-white">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-gear text-warning me-2"></i>Pengaturan Umum, Logo &amp; Media Sosial</h5>
      </div>

      <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
        @csrf
        <input type="hidden" name="section" value="general">

        <!-- Nama Perusahaan & Jam Operasional -->
        <div class="row g-3">
          <div class="col-md-6">
            <label for="company_name" class="form-label fw-bold">Nama Perusahaan</label>
            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $homeData['company_name'] ?? 'PT. Prolabios Mitra Analitika') }}" required>
            <div class="form-text">Nama utama PT / Perusahaan yang tampil di title bar dan logo website.</div>
          </div>
          
          <div class="col-md-6">
            <label for="operational_hours" class="form-label fw-bold">Jam Operasional</label>
            <input type="text" class="form-control" id="operational_hours" name="operational_hours" value="{{ old('operational_hours', $homeData['operational_hours'] ?? 'Senin - Jumat: 08.00 - 17.00') }}" required>
            <div class="form-text">Jadwal buka-tutup kantor resmi (tampil di footer).</div>
          </div>
        </div>

        <!-- Logo Website Upload -->
        <div class="col-12 mt-4">
          <label class="form-label fw-bold">Logo Utama Website</label>
          <div class="row g-3 align-items-center">
            <div class="col-sm-3 text-center">
              <div class="border rounded bg-light p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 140px; height: 70px;">
                <img id="site_logo_preview" src="{{ !empty($homeData['site_logo']) ? $homeData['site_logo'] : asset('images/logo-prolabios.png') }}" alt="Preview Logo" class="w-100 h-100" style="object-fit: contain;">
              </div>
            </div>
            <div class="col-sm-9">
              <div class="mb-2">
                <label for="site_logo_file" class="form-label small fw-bold">Upload File Logo Baru (Saran: PNG transparan)</label>
                <input class="form-control" type="file" id="site_logo_file" name="site_logo_file" accept="image/*">
              </div>
              <div>
                <label for="site_logo_url" class="form-label small fw-bold">Atau Gunakan URL Gambar Logo Eksternal</label>
                <input type="text" class="form-control" id="site_logo_url" name="site_logo_url" value="{{ old('site_logo_url', $homeData['site_logo'] ?? '') }}" placeholder="https://example.com/logo.png">
              </div>
            </div>
          </div>
        </div>

        <!-- Media Sosial Links -->
        <h6 class="mt-5 mb-3 fw-bold text-dark border-bottom pb-2"><i class="bi bi-share text-primary me-2"></i>Link Akun Media Sosial</h6>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-light text-danger" style="width: 45px; justify-content: center;"><i class="bi bi-instagram"></i></span>
              <input type="url" class="form-control" id="social_instagram" name="social_instagram" placeholder="https://instagram.com/akun" value="{{ old('social_instagram', $homeData['social_instagram'] ?? '') }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-light text-primary" style="width: 45px; justify-content: center;"><i class="bi bi-facebook"></i></span>
              <input type="url" class="form-control" id="social_facebook" name="social_facebook" placeholder="https://facebook.com/akun" value="{{ old('social_facebook', $homeData['social_facebook'] ?? '') }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-light text-info" style="width: 45px; justify-content: center;"><i class="bi bi-linkedin"></i></span>
              <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" placeholder="https://linkedin.com/company/akun" value="{{ old('social_linkedin', $homeData['social_linkedin'] ?? '') }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text bg-light text-dark" style="width: 45px; justify-content: center;"><i class="bi bi-twitter-x"></i></span>
              <input type="url" class="form-control" id="social_twitter" name="social_twitter" placeholder="https://twitter.com/akun" value="{{ old('social_twitter', $homeData['social_twitter'] ?? '') }}">
            </div>
          </div>
        </div>

        <div class="mt-4 border-top pt-3 text-end">
          <button type="submit" class="btn btn-warning px-4 fw-bold text-white rounded-pill"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN UMUM</button>
        </div>
      </form>
    </div>
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
