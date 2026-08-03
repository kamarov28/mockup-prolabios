<div class="admin-card border-0">
  <div class="admin-card-header pb-0 border-bottom-0" style="background: var(--color-surface);">
    <ul class="nav nav-pills" id="bannerTabs" role="tablist" style="gap: 8px;">
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline active" id="prod-banner-tab" data-bs-toggle="tab" data-bs-target="#prod-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-box"></i> <span>Katalog Produk</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="sec-banner-tab" data-bs-toggle="tab" data-bs-target="#sec-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-diagram-3"></i> <span>Sektor Industri</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="serv-banner-tab" data-bs-toggle="tab" data-bs-target="#serv-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-tools"></i> <span>Layanan</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="info-banner-tab" data-bs-toggle="tab" data-bs-target="#info-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-newspaper"></i> <span>Artikel &amp; Informasi</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="cont-banner-tab" data-bs-toggle="tab" data-bs-target="#cont-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-telephone"></i> <span>Kontak</span></button>
      </li>
    </ul>
  </div>

  <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="admin-card-body p-4" style="background: var(--color-surface);">
    @csrf
    <input type="hidden" name="section" value="banners">

    <div class="tab-content" id="bannerTabsContent">
      
      <!-- Page: Products -->
      <div class="tab-pane fade show active" id="prod-banner-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Header Halaman Produk</h2>
        <div class="mb-3">
          <label for="products_title" class="admin-card-header-label mb-2">Judul Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="products_title" name="products_title" value="{{ old('products_title', $homeData['products_title'] ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label for="products_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="products_subtitle" name="products_subtitle" value="{{ old('products_subtitle', $homeData['products_subtitle'] ?? '') }}" required>
        </div>
        <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20">
          <div class="col-md-3">
            <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
              <img id="products_banner_preview" src="{{ $homeData['products_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="products_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload Gambar Banner</label>
              <input type="file" id="products_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="products_banner_file" accept="image/*">
            </div>
            <div>
              <label for="products_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
              <input type="text" id="products_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="products_banner_url" value="{{ $homeData['products_banner_image'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>

      <!-- Page: Sectors -->
      <div class="tab-pane fade" id="sec-banner-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Header Halaman Sektor Industri</h2>
        <div class="mb-3">
          <label for="sectors_title" class="admin-card-header-label mb-2">Judul Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="sectors_title" name="sectors_title" value="{{ old('sectors_title', $homeData['sectors_title'] ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label for="sectors_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="sectors_subtitle" name="sectors_subtitle" value="{{ old('sectors_subtitle', $homeData['sectors_subtitle'] ?? '') }}" required>
        </div>
        <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20">
          <div class="col-md-3">
            <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
              <img id="sectors_banner_preview" src="{{ $homeData['sectors_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="sectors_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload Gambar Banner</label>
              <input type="file" id="sectors_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sectors_banner_file" accept="image/*">
            </div>
            <div>
              <label for="sectors_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
              <input type="text" id="sectors_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sectors_banner_url" value="{{ $homeData['sectors_banner_image'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>

      <!-- Page: Services -->
      <div class="tab-pane fade" id="serv-banner-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Header Halaman Layanan</h2>
        <div class="mb-3">
          <label for="services_title" class="admin-card-header-label mb-2">Judul Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="services_title" name="services_title" value="{{ old('services_title', $homeData['services_title'] ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label for="services_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="services_subtitle" name="services_subtitle" value="{{ old('services_subtitle', $homeData['services_subtitle'] ?? '') }}" required>
        </div>
        <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20">
          <div class="col-md-3">
            <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
              <img id="services_banner_preview" src="{{ $homeData['services_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="services_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload Gambar Banner</label>
              <input type="file" id="services_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="services_banner_file" accept="image/*">
            </div>
            <div>
              <label for="services_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
              <input type="text" id="services_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="services_banner_url" value="{{ $homeData['services_banner_image'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>

      <!-- Page: Information -->
      <div class="tab-pane fade" id="info-banner-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Header Halaman Artikel &amp; Informasi</h2>
        <div class="mb-3">
          <label for="info_title" class="admin-card-header-label mb-2">Judul Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="info_title" name="info_title" value="{{ old('info_title', $homeData['info_title'] ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label for="info_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="info_subtitle" name="info_subtitle" value="{{ old('info_subtitle', $homeData['info_subtitle'] ?? '') }}" required>
        </div>
        <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20">
          <div class="col-md-3">
            <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
              <img id="info_banner_preview" src="{{ $homeData['info_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="info_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload Gambar Banner</label>
              <input type="file" id="info_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="info_banner_file" accept="image/*">
            </div>
            <div>
              <label for="info_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
              <input type="text" id="info_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="info_banner_url" value="{{ $homeData['info_banner_image'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>

      <!-- Page: Contact -->
      <div class="tab-pane fade" id="cont-banner-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Header Halaman Kontak</h2>
        <div class="mb-3">
          <label for="contact_title" class="admin-card-header-label mb-2">Judul Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_title" name="contact_title" value="{{ old('contact_title', $homeData['contact_title'] ?? '') }}" required>
        </div>
        <div class="mb-3">
          <label for="contact_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_subtitle" name="contact_subtitle" value="{{ old('contact_subtitle', $homeData['contact_subtitle'] ?? '') }}" required>
        </div>
        <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20">
          <div class="col-md-3">
            <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden" style="aspect-ratio: 16/9; max-height: 120px;">
              <img id="contact_banner_preview" src="{{ $homeData['contact_banner_image'] ?? '' }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-md-9">
            <div class="mb-2">
              <label for="contact_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload Gambar Banner</label>
              <input type="file" id="contact_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="contact_banner_file" accept="image/*">
            </div>
            <div>
              <label for="contact_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
              <input type="text" id="contact_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="contact_banner_url" value="{{ $homeData['contact_banner_image'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
      <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BANNER</button>
    </div>
  </form>
</div>
