<div class="admin-card border-0">
  <div class="admin-card-header pb-0 border-bottom-0" style="background: var(--color-surface);">
    <ul class="nav nav-pills" id="homeTabs" role="tablist" style="gap: 8px;">
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-image me-1"></i> <span>Hero Banner</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="bento-tab" data-bs-toggle="tab" data-bs-target="#bento-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-grid-1x2 me-1"></i> <span>Bento Grid Cards</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="sector-tab" data-bs-toggle="tab" data-bs-target="#sector-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-diagram-3 me-1"></i> <span>Sector Finder</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta-panel" type="button" role="tab" style="font-size: 0.72rem; padding: 8px 16px;"><i class="bi bi-megaphone me-1"></i> <span>Banner CTA Bawah</span></button>
      </li>
    </ul>
  </div>

  <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="admin-card-body p-4" style="background: var(--color-surface);">
    @csrf
    <input type="hidden" name="section" value="homepage">
    <input type="hidden" name="tab" value="{{ request('tab', 'hero') }}">

    <div class="tab-content" id="homeTabsContent">
      
      <!-- Sub-panel 1: Hero Section -->
      <div class="tab-pane fade show active" id="hero-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Hero Section Main Banner</h2>
        
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label for="hero_badge" class="admin-card-header-label mb-2">Hero Label / Badge</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('hero_badge') is-invalid @enderror" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $homeData['hero_badge'] ?? 'PRECISION LABORATORY SOLUTIONS') }}" required>
            @error('hero_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-8">
            <label for="hero_title" class="admin-card-header-label mb-2">Hero Title / Slogan Utama</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $homeData['hero_title'] ?? '') }}" required>
            @error('hero_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text mt-2 small text-secondary">
              Bisa sisipkan HTML kecil di judul:
              <code class="bg-black text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded d-inline-block mt-1"><span class="text-accent">Lab</span></code>
              → teks merah solid;
              <code class="bg-black text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded d-inline-block mt-1"><span class="typo-outline">Lab</span></code>
              → outline transparan. Contoh: <em>Solusi <span class="text-accent">Lab</span> untuk Industri</em>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label for="hero_subtitle" class="admin-card-header-label mb-2">Hero Subtitle / Deskripsi Utama</label>
          <textarea class="form-control bg-dark text-white border-secondary border-opacity-20 @error('hero_subtitle') is-invalid @enderror" id="hero_subtitle" name="hero_subtitle" rows="3" required>{{ old('hero_subtitle', $homeData['hero_subtitle'] ?? '') }}</textarea>
          @error('hero_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="hero_cta_text" class="admin-card-header-label mb-2">Teks Tombol CTA Hero</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('hero_cta_text') is-invalid @enderror" id="hero_cta_text" name="hero_cta_text" value="{{ old('hero_cta_text', $homeData['hero_cta_text'] ?? 'Explore Product Catalog') }}" required>
            @error('hero_cta_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="hero_cta_link" class="admin-card-header-label mb-2">Link URL Tombol CTA Hero</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('hero_cta_link') is-invalid @enderror" id="hero_cta_link" name="hero_cta_link" value="{{ old('hero_cta_link', $homeData['hero_cta_link'] ?? '/produk') }}" required>
            @error('hero_cta_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 text-white border-bottom border-secondary border-opacity-20 pb-2">Gambar Background Carousel Hero (Maks. 4 Gambar)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $imgUrl = $homeData['hero_images'][$i] ?? ''; @endphp
            <div class="col-md-6 col-lg-3">
              <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-20 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="admin-badge admin-badge-accent">Slide #{{ $i + 1 }}</span>
                    @if($imgUrl)
                      <span class="admin-badge admin-badge-success">Terisi</span>
                    @else
                      <span class="admin-badge admin-badge-muted">Kosong</span>
                    @endif
                  </div>
                  <div class="rounded border border-secondary border-opacity-20 overflow-hidden mb-3 bg-dark" style="aspect-ratio: 16/9;">
                    <img id="hero_image_preview_{{ $i }}" src="{{ $imgUrl ?: asset('images/placeholder.svg') }}" alt="Slide {{ $i + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                  </div>
                  <div class="mb-2">
                    <label for="hero_image_file_{{ $i }}" class="form-label small text-secondary fw-bold mb-1">Upload File Gambar</label>
                    <input type="file" id="hero_image_file_{{ $i }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="hero_image_file_{{ $i }}" accept="image/*">
                  </div>
                  <div>
                    <label for="hero_image_url_{{ $i }}" class="form-label small text-secondary fw-bold mb-1">Atau URL Gambar</label>
                    <input type="text" id="hero_image_url_{{ $i }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="hero_image_url_{{ $i }}" value="{{ $imgUrl }}" placeholder="https://...">
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>

      <!-- Sub-panel 2: Bento Grid Standar -->
      <div class="tab-pane fade" id="bento-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Bento Grid Standards & Infrastructure</h2>
        
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="bento_title" class="admin-card-header-label mb-2">Judul Section Bento</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('bento_title') is-invalid @enderror" id="bento_title" name="bento_title" value="{{ old('bento_title', $homeData['bento_title'] ?? 'Infrastructure & Reliability Standards') }}" required>
            @error('bento_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="bento_subtitle" class="admin-card-header-label mb-2">Subjudul Section Bento</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('bento_subtitle') is-invalid @enderror" id="bento_subtitle" name="bento_subtitle" value="{{ old('bento_subtitle', $homeData['bento_subtitle'] ?? '') }}" required>
            @error('bento_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 text-white border-bottom border-secondary border-opacity-20 pb-2">4 Kartu Pilar Nilai (Bento Cards)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $bCard = $homeData['bento_cards'][$i] ?? []; @endphp
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-20 h-100">
                <span class="admin-badge admin-badge-accent mb-2">Kartu Bento #{{ $i + 1 }}</span>
                <div class="mb-2">
                  <label for="bento_card_icon_{{ $i }}" class="form-label small text-secondary fw-bold mb-1">Icon Bootstrap (<a href="https://icons.getbootstrap.com" target="_blank" class="text-accent text-decoration-none">Cari Icon <i class="bi bi-box-arrow-up-right"></i></a>)</label>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text bg-dark text-secondary border-secondary border-opacity-20"><i class="bi {{ $bCard['icon'] ?? 'bi-patch-check' }}"></i></span>
                    <input type="text" id="bento_card_icon_{{ $i }}" class="form-control bg-dark text-white border-secondary border-opacity-20" name="bento_card_icon_{{ $i }}" value="{{ old('bento_card_icon_'.$i, $bCard['icon'] ?? 'bi-patch-check') }}" placeholder="bi-patch-check" required>
                  </div>
                </div>
                <div class="mb-2">
                  <label for="bento_card_title_{{ $i }}" class="form-label small text-secondary fw-bold mb-1">Judul Pilar</label>
                  <input type="text" id="bento_card_title_{{ $i }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="bento_card_title_{{ $i }}" value="{{ old('bento_card_title_'.$i, $bCard['title'] ?? '') }}" required>
                </div>
                <div>
                  <label for="bento_card_desc_{{ $i }}" class="form-label small text-secondary fw-bold mb-1">Deskripsi Penjelasan</label>
                  <textarea id="bento_card_desc_{{ $i }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="bento_card_desc_{{ $i }}" rows="3" required>{{ old('bento_card_desc_'.$i, $bCard['desc'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>

      <!-- Sub-panel 3: Interactive Sector Finder -->
      <div class="tab-pane fade" id="sector-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Interactive Sector Finder</h2>
        
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="sector_title" class="admin-card-header-label mb-2">Judul Section Sector Finder</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('sector_title') is-invalid @enderror" id="sector_title" name="sector_title" value="{{ old('sector_title', $homeData['sector_title'] ?? 'Interactive Sector Finder') }}" required>
            @error('sector_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="sector_subtitle" class="admin-card-header-label mb-2">Subjudul Section Sector Finder</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('sector_subtitle') is-invalid @enderror" id="sector_subtitle" name="sector_subtitle" value="{{ old('sector_subtitle', $homeData['sector_subtitle'] ?? '') }}" required>
            @error('sector_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        @php
          $sectorConfigs = [
              'pharma' => '1. Pharma & Biotech',
              'fnb' => '2. Food & Beverage',
              'healthcare' => '3. Healthcare & Clinical',
              'brewing' => '4. Brewing & Research'
          ];
        @endphp

        <div class="row g-3">
          @foreach($sectorConfigs as $sKey => $sLabel)
            @php $sPanel = $homeData['sector_panels'][$sKey] ?? []; @endphp
            <div class="col-md-6">
              <div class="p-3 rounded-3 bg-black border border-secondary border-opacity-20 h-100">
                <span class="admin-badge admin-badge-info mb-2">{{ $sLabel }}</span>
                <div class="mb-2">
                  <label for="sector_tag_{{ $sKey }}" class="form-label small text-secondary fw-bold mb-1">Tag Sub-kategori</label>
                  <input type="text" id="sector_tag_{{ $sKey }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sector_tag_{{ $sKey }}" value="{{ old('sector_tag_'.$sKey, $sPanel['tag'] ?? '') }}" required>
                </div>
                <div class="mb-2">
                  <label for="sector_title_{{ $sKey }}" class="form-label small text-secondary fw-bold mb-1">Judul Alur Kerja</label>
                  <input type="text" id="sector_title_{{ $sKey }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sector_title_{{ $sKey }}" value="{{ old('sector_title_'.$sKey, $sPanel['title'] ?? '') }}" required>
                </div>
                <div class="mb-2">
                  <label for="sector_desc_{{ $sKey }}" class="form-label small text-secondary fw-bold mb-1">Deskripsi Ringkas</label>
                  <textarea id="sector_desc_{{ $sKey }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sector_desc_{{ $sKey }}" rows="3" required>{{ old('sector_desc_'.$sKey, $sPanel['desc'] ?? '') }}</textarea>
                </div>
                <div>
                  <label for="sector_link_{{ $sKey }}" class="form-label small text-secondary fw-bold mb-1">Link URL Tombol</label>
                  <input type="text" id="sector_link_{{ $sKey }}" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" name="sector_link_{{ $sKey }}" value="{{ old('sector_link_'.$sKey, $sPanel['link'] ?? '') }}" required>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Sub-panel 4: Bottom Conversion CTA Banner -->
      <div class="tab-pane fade" id="cta-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-white border-bottom border-secondary border-opacity-20 pb-3" style="font-family: var(--font-headline);">Banner Konversi CTA Bawah (Bottom Banner)</h2>
        
        <div class="mb-3">
          <label for="cta_banner_badge" class="admin-card-header-label mb-2">Label / Tag Banner</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('cta_banner_badge') is-invalid @enderror" id="cta_banner_badge" name="cta_banner_badge" value="{{ old('cta_banner_badge', $homeData['cta_banner_badge'] ?? 'TECHNICAL PROCUREMENT SUPPORT') }}" required>
          @error('cta_banner_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="cta_banner_title" class="admin-card-header-label mb-2">Judul Banner Konversi</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('cta_banner_title') is-invalid @enderror" id="cta_banner_title" name="cta_banner_title" value="{{ old('cta_banner_title', $homeData['cta_banner_title'] ?? 'Require Custom Procurement or Project Quote?') }}" required>
          @error('cta_banner_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="cta_banner_sub" class="admin-card-header-label mb-2">Subjudul Deskripsi Banner</label>
          <textarea class="form-control bg-dark text-white border-secondary border-opacity-20 @error('cta_banner_sub') is-invalid @enderror" id="cta_banner_sub" name="cta_banner_sub" rows="3" required>{{ old('cta_banner_sub', $homeData['cta_banner_sub'] ?? '') }}</textarea>
          @error('cta_banner_sub') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="cta_banner_btn_text" class="admin-card-header-label mb-2">Teks Tombol CTA</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('cta_banner_btn_text') is-invalid @enderror" id="cta_banner_btn_text" name="cta_banner_btn_text" value="{{ old('cta_banner_btn_text', $homeData['cta_banner_btn_text'] ?? 'Contact Sales / Request Quote') }}" required>
            @error('cta_banner_btn_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="cta_banner_btn_url" class="admin-card-header-label mb-2">Link URL Tombol CTA</label>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('cta_banner_btn_url') is-invalid @enderror" id="cta_banner_btn_url" name="cta_banner_btn_url" value="{{ old('cta_banner_btn_url', $homeData['cta_banner_btn_url'] ?? '/kontak') }}" required>
            @error('cta_banner_btn_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>

    </div>

    <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
      <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BERANDA</button>
    </div>
  </form>
</div>
