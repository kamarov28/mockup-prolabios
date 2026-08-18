<div class="admin-card border-0">
  <div class="admin-card-header pb-0 border-bottom-0" style="background: var(--color-surface);">
    <ul class="nav nav-pills" id="bannerTabs" role="tablist" style="gap: 8px;">
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline active" id="prod-banner-tab" data-bs-toggle="tab" data-bs-target="#prod-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-box me-1"></i> <span>Katalog Produk</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="sec-banner-tab" data-bs-toggle="tab" data-bs-target="#sec-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-diagram-3 me-1"></i> <span>Sektor Industri</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="serv-banner-tab" data-bs-toggle="tab" data-bs-target="#serv-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-tools me-1"></i> <span>Layanan</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="info-banner-tab" data-bs-toggle="tab" data-bs-target="#info-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-newspaper me-1"></i> <span>Artikel &amp; Informasi</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="cont-banner-tab" data-bs-toggle="tab" data-bs-target="#cont-banner-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-telephone me-1"></i> <span>Kontak</span></button>
      </li>
    </ul>
  </div>

  <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="admin-card-body p-4" style="background: var(--color-surface);">
    @csrf
    <input type="hidden" name="section" value="banners">
    <input type="hidden" name="tab" value="{{ request('tab', 'prod-banner') }}">

    <div class="tab-content" id="bannerTabsContent">
      
      @php
        $bannerSections = [
          'products' => ['name' => 'Katalog Produk', 'id' => 'prod-banner-panel', 'active' => true, 'icon' => 'bi-box'],
          'sectors' => ['name' => 'Sektor Industri', 'id' => 'sec-banner-panel', 'active' => false, 'icon' => 'bi-diagram-3'],
          'services' => ['name' => 'Layanan & Purnajual', 'id' => 'serv-banner-panel', 'active' => false, 'icon' => 'bi-tools'],
          'info' => ['name' => 'Artikel & Berita', 'id' => 'info-banner-panel', 'active' => false, 'icon' => 'bi-newspaper'],
          'contact' => ['name' => 'Kontak Perusahaan', 'id' => 'cont-banner-panel', 'active' => false, 'icon' => 'bi-telephone']
        ];
      @endphp

      @foreach($bannerSections as $key => $conf)
        <div class="tab-pane fade {{ $conf['active'] ? 'show active' : '' }}" id="{{ $conf['id'] }}" role="tabpanel">
          <div class="d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-20 pb-3 mb-4">
            <h2 class="h5 fw-bold mb-0 text-white" style="font-family: var(--font-headline);">
              <i class="bi {{ $conf['icon'] }} text-info me-2"></i>Header Halaman {{ $conf['name'] }}
            </h2>
            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">
              Rekomendasi Banner: 1920×600px (WebP / JPG maks 5MB)
            </span>
          </div>

          <div class="mb-3">
            <label for="{{ $key }}_title" class="admin-card-header-label mb-2">Judul Halaman</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error($key.'_title') is-invalid @enderror" id="{{ $key }}_title" name="{{ $key }}_title" value="{{ old($key.'_title', $homeData[$key.'_title'] ?? '') }}" required>
            @error($key.'_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-4">
            <label for="{{ $key }}_subtitle" class="admin-card-header-label mb-2">Subjudul / Deskripsi Halaman</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error($key.'_subtitle') is-invalid @enderror" id="{{ $key }}_subtitle" name="{{ $key }}_subtitle" value="{{ old($key.'_subtitle', $homeData[$key.'_subtitle'] ?? '') }}" required>
            @error($key.'_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="row align-items-center rounded-3 p-3 bg-black border border-secondary border-opacity-20 g-3">
            <div class="col-md-4 col-lg-3">
              <div class="rounded border border-secondary border-opacity-20 bg-dark overflow-hidden position-relative" style="aspect-ratio: 16/9;">
                <img id="{{ $key }}_banner_preview" src="{{ $homeData[$key.'_banner_image'] ?? asset('images/placeholder.svg') }}" alt="Banner" class="w-100 h-100" style="object-fit: cover;">
              </div>
            </div>
            <div class="col-md-8 col-lg-9">
              <div class="mb-2">
                <label for="{{ $key }}_banner_file" class="form-label small text-secondary fw-bold mb-1">Upload File Gambar Banner Baru</label>
                <input type="file" id="{{ $key }}_banner_file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="{{ $key }}_banner_file" accept="image/*">
              </div>
              <div>
                <label for="{{ $key }}_banner_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar Eksternal</label>
                <input type="text" id="{{ $key }}_banner_url" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="{{ $key }}_banner_url" value="{{ $homeData[$key.'_banner_image'] ?? '' }}" placeholder="https://...">
              </div>
            </div>
          </div>
        </div>
      @endforeach

    </div>

    <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
      <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BANNER</button>
    </div>
  </form>
</div>
