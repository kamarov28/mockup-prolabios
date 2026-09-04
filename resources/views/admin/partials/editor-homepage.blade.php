<div class="admin-card">
  <div class="admin-card-header pb-0" style="background: var(--color-surface); border-bottom: 2px solid #1E1E1E;">
    <ul class="nav nav-pills" id="homeTabs" role="tablist" style="gap: 8px; margin-bottom: 14px;">
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-panel" type="button" role="tab" style="font-size: 0.78rem; padding: 7px 16px;"><i class="bi bi-image me-1"></i> <span>Hero Banner</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="bento-tab" data-bs-toggle="tab" data-bs-target="#bento-panel" type="button" role="tab" style="font-size: 0.78rem; padding: 7px 16px;"><i class="bi bi-grid-1x2 me-1"></i> <span>Bento Grid Cards</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="sector-tab" data-bs-toggle="tab" data-bs-target="#sector-panel" type="button" role="tab" style="font-size: 0.78rem; padding: 7px 16px;"><i class="bi bi-diagram-3 me-1"></i> <span>Sector Finder</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="admin-btn admin-btn-outline" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta-panel" type="button" role="tab" style="font-size: 0.78rem; padding: 7px 16px;"><i class="bi bi-megaphone me-1"></i> <span>Banner Konversi RFQ</span></button>
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
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 2px solid #1E1E1E;">
          <div>
            <span class="admin-card-header-label">HERO HEADLINE & SLIDESHOW</span>
            <h2 class="h5 fw-bold mb-0" style="font-family: var(--font-headline); color: var(--color-text-main);">Hero Section Main Banner</h2>
          </div>
          <span class="admin-badge admin-badge-accent">Fullscreen Mode</span>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label for="hero_badge" class="admin-form-label mb-2">Hero Label / Badge</label>
            <input type="text" class="form-control @error('hero_badge') is-invalid @enderror" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $homeData['hero_badge'] ?? 'PRECISION LABORATORY SOLUTIONS') }}" required>
            @error('hero_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-8">
            <label for="hero_title" class="admin-form-label mb-2">Hero Title / Slogan Utama</label>
            <input type="text" class="form-control @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $homeData['hero_title'] ?? '') }}" required>
            @error('hero_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text mt-2 small text-secondary lh-base">
              Bisa sisipkan styling teks khusus di judul:
              <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
                <code style="font-size: 0.72rem;">{{ '<span class="text-accent">Kata Penting</span>' }}</code>
                <span class="text-secondary" style="font-size: 0.75rem;">→ aksen merah Ruby</span>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label for="hero_subtitle" class="admin-form-label mb-2">Hero Subtitle / Deskripsi Utama</label>
          <textarea class="form-control @error('hero_subtitle') is-invalid @enderror" id="hero_subtitle" name="hero_subtitle" rows="3" required>{{ old('hero_subtitle', $homeData['hero_subtitle'] ?? '') }}</textarea>
          @error('hero_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="hero_cta_text" class="admin-form-label mb-2">Teks Tombol CTA Hero</label>
            <input type="text" class="form-control @error('hero_cta_text') is-invalid @enderror" id="hero_cta_text" name="hero_cta_text" value="{{ old('hero_cta_text', $homeData['hero_cta_text'] ?? 'Explore Product Catalog') }}" required>
            @error('hero_cta_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="hero_cta_link" class="admin-form-label mb-2">Link URL Tombol CTA Hero</label>
            <input type="text" class="form-control @error('hero_cta_link') is-invalid @enderror" id="hero_cta_link" name="hero_cta_link" value="{{ old('hero_cta_link', $homeData['hero_cta_link'] ?? '/produk') }}" required>
            @error('hero_cta_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 pb-2" style="font-family: var(--font-headline); color: var(--color-text-main); border-bottom: 2px solid #1E1E1E;">Gambar Background Carousel Hero (Maks. 4 Gambar)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $imgUrl = $homeData['hero_images'][$i] ?? ''; @endphp
            <div class="col-md-6 col-lg-3">
              <div class="p-3 h-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 2px solid #1E1E1E; border-radius: 4px; box-shadow: 2px 2px 0 #1E1E1E;">
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="admin-badge admin-badge-accent">Slide #{{ $i + 1 }}</span>
                    @if($imgUrl)
                      <span class="admin-badge admin-badge-success">Terisi</span>
                    @else
                      <span class="admin-badge admin-badge-muted">Kosong</span>
                    @endif
                  </div>
                  <div class="overflow-hidden mb-3" style="aspect-ratio: 16/9; background: #FFFFFF; border: 2px solid #1E1E1E; border-radius: 4px;">
                    <img id="hero_image_preview_{{ $i }}" src="{{ $imgUrl ?: asset('images/placeholder.svg') }}" alt="Slide {{ $i + 1 }}" class="w-100 h-100" style="object-fit: cover;">
                  </div>
                  <div class="mb-2">
                    <label for="hero_image_file_{{ $i }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Upload File Gambar</label>
                    <input type="file" id="hero_image_file_{{ $i }}" class="form-control form-control-sm" name="hero_image_file_{{ $i }}" accept="image/*">
                  </div>
                  <div>
                    <label for="hero_image_url_{{ $i }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Atau URL Gambar</label>
                    <input type="text" id="hero_image_url_{{ $i }}" class="form-control form-control-sm" name="hero_image_url_{{ $i }}" value="{{ $imgUrl }}" placeholder="https://...">
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>

      <!-- Sub-panel 2: Bento Grid Standar -->
      <div class="tab-pane fade" id="bento-panel" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 2px solid #1E1E1E;">
          <div>
            <span class="admin-card-header-label">VALUE PILLARS & STANDARDS</span>
            <h2 class="h5 fw-bold mb-0" style="font-family: var(--font-headline); color: var(--color-text-main);">Bento Grid Standards &amp; Infrastructure</h2>
          </div>
          <span class="admin-badge admin-badge-info">4 Kartu Pilar</span>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="bento_title" class="admin-form-label mb-2">Judul Section Bento</label>
            <input type="text" class="form-control @error('bento_title') is-invalid @enderror" id="bento_title" name="bento_title" value="{{ old('bento_title', $homeData['bento_title'] ?? 'Infrastructure & Reliability Standards') }}" required>
            @error('bento_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="bento_subtitle" class="admin-form-label mb-2">Subjudul Section Bento</label>
            <input type="text" class="form-control @error('bento_subtitle') is-invalid @enderror" id="bento_subtitle" name="bento_subtitle" value="{{ old('bento_subtitle', $homeData['bento_subtitle'] ?? '') }}" required>
            @error('bento_subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 pb-2" style="font-family: var(--font-headline); color: var(--color-text-main); border-bottom: 2px solid #1E1E1E;">4 Kartu Pilar Nilai (Bento Cards)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $bCard = $homeData['bento_cards'][$i] ?? []; @endphp
            <div class="col-md-6">
              <div class="p-3 h-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 2px solid #1E1E1E; border-radius: 4px; box-shadow: 2px 2px 0 #1E1E1E;">
                <div>
                  <span class="admin-badge admin-badge-accent mb-2">Kartu Bento #{{ $i + 1 }}</span>
                  <div class="mb-2">
                    <label for="bento_card_icon_{{ $i }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Icon Bootstrap (<a href="https://icons.getbootstrap.com" target="_blank" style="color: var(--color-accent); text-decoration: none; font-weight: 700;">Cari Icon <i class="bi bi-box-arrow-up-right"></i></a>)</label>
                    <div class="input-group input-group-sm">
                      <span class="input-group-text" style="background: #FFFFFF; border: 2px solid #1E1E1E; border-right: none;"><i class="bi {{ $bCard['icon'] ?? 'bi-patch-check' }}"></i></span>
                      <input type="text" id="bento_card_icon_{{ $i }}" class="form-control" style="border: 2px solid #1E1E1E;" name="bento_card_icon_{{ $i }}" value="{{ old('bento_card_icon_'.$i, $bCard['icon'] ?? 'bi-patch-check') }}" placeholder="bi-patch-check" required>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label for="bento_card_title_{{ $i }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Judul Pilar</label>
                    <input type="text" id="bento_card_title_{{ $i }}" class="form-control form-control-sm" name="bento_card_title_{{ $i }}" value="{{ old('bento_card_title_'.$i, $bCard['title'] ?? '') }}" required>
                  </div>
                  <div>
                    <label for="bento_card_desc_{{ $i }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Deskripsi Penjelasan</label>
                    <textarea id="bento_card_desc_{{ $i }}" class="form-control form-control-sm" name="bento_card_desc_{{ $i }}" rows="3" required>{{ old('bento_card_desc_'.$i, $bCard['desc'] ?? '') }}</textarea>
                  </div>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>

      <!-- Sub-panel 3: Interactive Sector Finder -->
      <div class="tab-pane fade" id="sector-panel" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 2px solid #1E1E1E;">
          <div>
            <span class="admin-card-header-label">SECTOR WORKFLOWS</span>
            <h2 class="h5 fw-bold mb-0" style="font-family: var(--font-headline); color: var(--color-text-main);">Interactive Sector Finder</h2>
          </div>
          <span class="admin-badge admin-badge-warning">4 Sektor Utama</span>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="sector_title" class="admin-form-label mb-2">Judul Section Sector Finder</label>
            <input type="text" class="form-control @error('sector_title') is-invalid @enderror" id="sector_title" name="sector_title" value="{{ old('sector_title', $homeData['sector_title'] ?? 'Interactive Sector Finder') }}" required>
            @error('sector_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="sector_subtitle" class="admin-form-label mb-2">Subjudul Section Sector Finder</label>
            <input type="text" class="form-control @error('sector_subtitle') is-invalid @enderror" id="sector_subtitle" name="sector_subtitle" value="{{ old('sector_subtitle', $homeData['sector_subtitle'] ?? '') }}" required>
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
              <div class="p-3 h-100 d-flex flex-column justify-content-between" style="background: var(--color-surface-2); border: 2px solid #1E1E1E; border-radius: 4px; box-shadow: 2px 2px 0 #1E1E1E;">
                <div>
                  <span class="admin-badge admin-badge-info mb-2">{{ $sLabel }}</span>
                  <div class="mb-2">
                    <label for="sector_tag_{{ $sKey }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Tag Sub-kategori</label>
                    <input type="text" id="sector_tag_{{ $sKey }}" class="form-control form-control-sm" name="sector_tag_{{ $sKey }}" value="{{ old('sector_tag_'.$sKey, $sPanel['tag'] ?? '') }}" required>
                  </div>
                  <div class="mb-2">
                    <label for="sector_title_{{ $sKey }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Judul Alur Kerja</label>
                    <input type="text" id="sector_title_{{ $sKey }}" class="form-control form-control-sm" name="sector_title_{{ $sKey }}" value="{{ old('sector_title_'.$sKey, $sPanel['title'] ?? '') }}" required>
                  </div>
                  <div class="mb-2">
                    <label for="sector_desc_{{ $sKey }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Deskripsi Ringkas</label>
                    <textarea id="sector_desc_{{ $sKey }}" class="form-control form-control-sm" name="sector_desc_{{ $sKey }}" rows="3" required>{{ old('sector_desc_'.$sKey, $sPanel['desc'] ?? '') }}</textarea>
                  </div>
                  <div>
                    <label for="sector_link_{{ $sKey }}" class="form-label small fw-bold mb-1" style="color: var(--color-text-main);">Link URL Tombol</label>
                    <input type="text" id="sector_link_{{ $sKey }}" class="form-control form-control-sm" name="sector_link_{{ $sKey }}" value="{{ old('sector_link_'.$sKey, $sPanel['link'] ?? '') }}" required>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Sub-panel 4: Bottom Conversion CTA Banner -->
      <div class="tab-pane fade" id="cta-panel" role="tabpanel">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 2px solid #1E1E1E;">
          <div>
            <span class="admin-card-header-label">BOTTOM CONVERSION ACTION</span>
            <h2 class="h5 fw-bold mb-0" style="font-family: var(--font-headline); color: var(--color-text-main);">Banner Konversi CTA Bawah (Bottom RFQ Callout)</h2>
          </div>
          <span class="admin-badge admin-badge-success">Call to Action</span>
        </div>

        <div class="mb-3">
          <label for="cta_banner_badge" class="admin-form-label mb-2">Label / Tag Banner</label>
          <input type="text" class="form-control @error('cta_banner_badge') is-invalid @enderror" id="cta_banner_badge" name="cta_banner_badge" value="{{ old('cta_banner_badge', $homeData['cta_banner_badge'] ?? 'TECHNICAL PROCUREMENT SUPPORT') }}" required>
          @error('cta_banner_badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="cta_banner_title" class="admin-form-label mb-2">Judul Banner Konversi</label>
          <input type="text" class="form-control @error('cta_banner_title') is-invalid @enderror" id="cta_banner_title" name="cta_banner_title" value="{{ old('cta_banner_title', $homeData['cta_banner_title'] ?? 'Require Custom Procurement or Project Quote?') }}" required>
          @error('cta_banner_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="cta_banner_sub" class="admin-form-label mb-2">Subjudul Deskripsi Banner</label>
          <textarea class="form-control @error('cta_banner_sub') is-invalid @enderror" id="cta_banner_sub" name="cta_banner_sub" rows="3" required>{{ old('cta_banner_sub', $homeData['cta_banner_sub'] ?? '') }}</textarea>
          @error('cta_banner_sub') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="cta_banner_btn_text" class="admin-form-label mb-2">Teks Tombol CTA</label>
            <input type="text" class="form-control @error('cta_banner_btn_text') is-invalid @enderror" id="cta_banner_btn_text" name="cta_banner_btn_text" value="{{ old('cta_banner_btn_text', $homeData['cta_banner_btn_text'] ?? 'Contact Sales / Request Quote') }}" required>
            @error('cta_banner_btn_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6">
            <label for="cta_banner_btn_url" class="admin-form-label mb-2">Link URL Tombol CTA</label>
            <input type="text" class="form-control @error('cta_banner_btn_url') is-invalid @enderror" id="cta_banner_btn_url" name="cta_banner_btn_url" value="{{ old('cta_banner_btn_url', $homeData['cta_banner_btn_url'] ?? '/kontak') }}" required>
            @error('cta_banner_btn_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>

    </div>

    <div class="mt-4 pt-4 text-end" style="border-top: 2px solid #1E1E1E;">
      <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.82rem;"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BERANDA</button>
    </div>
  </form>
</div>
