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
