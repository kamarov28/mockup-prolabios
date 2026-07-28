<div class="card bg-white shadow-sm border-0">
  <div class="card-header border-bottom py-3 bg-white">
    <ul class="nav nav-pills card-header-pills" id="homeTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold small" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-panel" type="button" role="tab"><i class="bi bi-image me-1"></i> Hero Section</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold small" id="bento-tab" data-bs-toggle="tab" data-bs-target="#bento-panel" type="button" role="tab"><i class="bi bi-grid-1x2 me-1"></i> Bento Grid Standar</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold small" id="sector-tab" data-bs-toggle="tab" data-bs-target="#sector-panel" type="button" role="tab"><i class="bi bi-diagram-3 me-1"></i> Sector Finder</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold small" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta-panel" type="button" role="tab"><i class="bi bi-megaphone me-1"></i> Banner CTA Bawah</button>
      </li>
    </ul>
  </div>

  <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
    @csrf
    <input type="hidden" name="section" value="homepage">

    <div class="tab-content" id="homeTabsContent">
      
      <!-- Sub-panel 1: Hero Section -->
      <div class="tab-pane fade show active" id="hero-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Hero Section Main Banner</h2>
        
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label for="hero_badge" class="form-label fw-bold">Hero Label / Badge</label>
            <input type="text" class="form-control" id="hero_badge" name="hero_badge" value="{{ old('hero_badge', $homeData['hero_badge'] ?? 'PRECISION LABORATORY SOLUTIONS') }}" required>
          </div>
          <div class="col-md-8">
            <label for="hero_title" class="form-label fw-bold">Hero Title / Slogan</label>
            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $homeData['hero_title'] ?? '') }}" required>
            <div class="form-text mt-1 small" style="color: var(--color-text-muted, #a0a0a0);">
              Format Efek Teks: Gunakan <code style="background: rgba(255,73,80,0.15); color: #ff4950; padding: 2px 6px; border-radius: 4px;">&lt;span class="text-accent"&gt;Kata&lt;/span&gt;</code> untuk merah solid, atau <code style="background: rgba(255,73,80,0.15); color: #ff4950; padding: 2px 6px; border-radius: 4px;">&lt;span class="typo-outline"&gt;Kata&lt;/span&gt;</code> untuk efek outline garis merah (transparan).
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label for="hero_subtitle" class="form-label fw-bold">Hero Subtitle / Deskripsi Utama</label>
          <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3" required>{{ old('hero_subtitle', $homeData['hero_subtitle'] ?? '') }}</textarea>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="hero_cta_text" class="form-label fw-bold">Teks Tombol CTA Hero</label>
            <input type="text" class="form-control" id="hero_cta_text" name="hero_cta_text" value="{{ old('hero_cta_text', $homeData['hero_cta_text'] ?? 'Explore Product Catalog') }}" required>
          </div>
          <div class="col-md-6">
            <label for="hero_cta_link" class="form-label fw-bold">Link URL Tombol CTA Hero</label>
            <input type="text" class="form-control" id="hero_cta_link" name="hero_cta_link" value="{{ old('hero_cta_link', $homeData['hero_cta_link'] ?? '/produk') }}" required>
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 text-secondary border-bottom pb-1">Gambar Background Carousel Hero (Maks. 4 Gambar)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $imgUrl = $homeData['hero_images'][$i] ?? ''; @endphp
            <div class="col-md-6 col-lg-3">
              <div class="border rounded p-3 bg-light h-100">
                <span class="badge bg-secondary mb-2">Slide #{{ $i + 1 }}</span>
                <div class="rounded border overflow-hidden mb-2" style="aspect-ratio: 16/9;">
                  <img id="hero_image_preview_{{ $i }}" src="{{ $imgUrl ?: 'https://via.placeholder.com/400x225?text=No+Image' }}" alt="Slide {{ $i + 1 }}" class="w-100 h-100" style="object-fit: cover;">
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

      <!-- Sub-panel 2: Bento Grid Standar -->
      <div class="tab-pane fade" id="bento-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Bento Grid Standards &amp; Infrastructure</h2>
        
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="bento_title" class="form-label fw-bold">Judul Section Bento</label>
            <input type="text" class="form-control" id="bento_title" name="bento_title" value="{{ old('bento_title', $homeData['bento_title'] ?? 'Infrastructure & Reliability Standards') }}" required>
          </div>
          <div class="col-md-6">
            <label for="bento_subtitle" class="form-label fw-bold">Subjudul Section Bento</label>
            <input type="text" class="form-control" id="bento_subtitle" name="bento_subtitle" value="{{ old('bento_subtitle', $homeData['bento_subtitle'] ?? '') }}" required>
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3 text-secondary border-bottom pb-1">4 Kartu Pilar Nilai (Bento Cards)</h3>
        <div class="row g-3">
          @for($i = 0; $i < 4; $i++)
            @php $bCard = $homeData['bento_cards'][$i] ?? []; @endphp
            <div class="col-md-6">
              <div class="border rounded p-3 bg-light h-100">
                <span class="badge bg-danger mb-2">Kartu Bento #{{ $i + 1 }}</span>
                <div class="mb-2">
                  <label for="bento_card_icon_{{ $i }}" class="form-label small fw-bold">Icon Bootstrap</label>
                  <input type="text" id="bento_card_icon_{{ $i }}" class="form-control form-control-sm" name="bento_card_icon_{{ $i }}" value="{{ old('bento_card_icon_'.$i, $bCard['icon'] ?? 'bi-patch-check') }}" placeholder="bi-patch-check" required>
                </div>
                <div class="mb-2">
                  <label for="bento_card_title_{{ $i }}" class="form-label small fw-bold">Judul Pilar</label>
                  <input type="text" id="bento_card_title_{{ $i }}" class="form-control form-control-sm" name="bento_card_title_{{ $i }}" value="{{ old('bento_card_title_'.$i, $bCard['title'] ?? '') }}" required>
                </div>
                <div>
                  <label for="bento_card_desc_{{ $i }}" class="form-label small fw-bold">Deskripsi Penjelasan</label>
                  <textarea id="bento_card_desc_{{ $i }}" class="form-control form-control-sm" name="bento_card_desc_{{ $i }}" rows="3" required>{{ old('bento_card_desc_'.$i, $bCard['desc'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          @endfor
        </div>
      </div>

      <!-- Sub-panel 3: Interactive Sector Finder -->
      <div class="tab-pane fade" id="sector-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Interactive Sector Finder</h2>
        
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="sector_title" class="form-label fw-bold">Judul Section Sector Finder</label>
            <input type="text" class="form-control" id="sector_title" name="sector_title" value="{{ old('sector_title', $homeData['sector_title'] ?? 'Interactive Sector Finder') }}" required>
          </div>
          <div class="col-md-6">
            <label for="sector_subtitle" class="form-label fw-bold">Subjudul Section Sector Finder</label>
            <input type="text" class="form-control" id="sector_subtitle" name="sector_subtitle" value="{{ old('sector_subtitle', $homeData['sector_subtitle'] ?? '') }}" required>
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
              <div class="border rounded p-3 bg-light h-100">
                <span class="badge bg-primary mb-2">{{ $sLabel }}</span>
                <div class="mb-2">
                  <label for="sector_tag_{{ $sKey }}" class="form-label small fw-bold">Tag Sub-kategori</label>
                  <input type="text" id="sector_tag_{{ $sKey }}" class="form-control form-control-sm" name="sector_tag_{{ $sKey }}" value="{{ old('sector_tag_'.$sKey, $sPanel['tag'] ?? '') }}" required>
                </div>
                <div class="mb-2">
                  <label for="sector_title_{{ $sKey }}" class="form-label small fw-bold">Judul Alur Kerja</label>
                  <input type="text" id="sector_title_{{ $sKey }}" class="form-control form-control-sm" name="sector_title_{{ $sKey }}" value="{{ old('sector_title_'.$sKey, $sPanel['title'] ?? '') }}" required>
                </div>
                <div class="mb-2">
                  <label for="sector_desc_{{ $sKey }}" class="form-label small fw-bold">Deskripsi Ringkas</label>
                  <textarea id="sector_desc_{{ $sKey }}" class="form-control form-control-sm" name="sector_desc_{{ $sKey }}" rows="3" required>{{ old('sector_desc_'.$sKey, $sPanel['desc'] ?? '') }}</textarea>
                </div>
                <div>
                  <label for="sector_link_{{ $sKey }}" class="form-label small fw-bold">Link URL Tombol</label>
                  <input type="text" id="sector_link_{{ $sKey }}" class="form-control form-control-sm" name="sector_link_{{ $sKey }}" value="{{ old('sector_link_'.$sKey, $sPanel['link'] ?? '') }}" required>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Sub-panel 4: Bottom Conversion CTA Banner -->
      <div class="tab-pane fade" id="cta-panel" role="tabpanel">
        <h2 class="h5 fw-bold mb-4 text-dark border-bottom pb-2">Banner Konversi CTA Bawah (Bottom Banner)</h2>
        
        <div class="mb-3">
          <label for="cta_banner_badge" class="form-label fw-bold">Label / Tag Banner</label>
          <input type="text" class="form-control" id="cta_banner_badge" name="cta_banner_badge" value="{{ old('cta_banner_badge', $homeData['cta_banner_badge'] ?? 'TECHNICAL PROCUREMENT SUPPORT') }}" required>
        </div>

        <div class="mb-3">
          <label for="cta_banner_title" class="form-label fw-bold">Judul Banner Konversi</label>
          <input type="text" class="form-control" id="cta_banner_title" name="cta_banner_title" value="{{ old('cta_banner_title', $homeData['cta_banner_title'] ?? 'Require Custom Procurement or Project Quote?') }}" required>
        </div>

        <div class="mb-3">
          <label for="cta_banner_sub" class="form-label fw-bold">Subjudul Deskripsi Banner</label>
          <textarea class="form-control" id="cta_banner_sub" name="cta_banner_sub" rows="3" required>{{ old('cta_banner_sub', $homeData['cta_banner_sub'] ?? '') }}</textarea>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="cta_banner_btn_text" class="form-label fw-bold">Teks Tombol CTA</label>
            <input type="text" class="form-control" id="cta_banner_btn_text" name="cta_banner_btn_text" value="{{ old('cta_banner_btn_text', $homeData['cta_banner_btn_text'] ?? 'Contact Sales / Request Quote') }}" required>
          </div>
          <div class="col-md-6">
            <label for="cta_banner_btn_url" class="form-label fw-bold">Link URL Tombol CTA</label>
            <input type="text" class="form-control" id="cta_banner_btn_url" name="cta_banner_btn_url" value="{{ old('cta_banner_btn_url', $homeData['cta_banner_btn_url'] ?? '/kontak') }}" required>
          </div>
        </div>
      </div>

    </div>

    <div class="mt-4 border-top pt-3 text-end">
      <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN BERANDA</button>
    </div>
  </form>
</div>
