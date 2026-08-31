@if($section === 'contacts')
  <div class="admin-card border-0 max-w-4xl mx-auto">
    <div class="admin-card-header py-3 d-flex align-items-center justify-content-between" style="background: var(--color-surface);">
      <h2 class="h5 mb-0 fw-bold text-white"><i class="bi bi-telephone-outbound text-success me-2"></i>Informasi Kontak Global &amp; Lokasi</h2>
      <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Sinkron dengan Header, Footer, Kontak &amp; RFQ</span>
    </div>

    <form action="{{ route('admin.home.update') }}" method="POST" class="admin-card-body p-4" style="background: var(--color-surface);">
      @csrf
      <input type="hidden" name="section" value="contacts">

      <div class="row g-4">
        <!-- Nomor Telepon & WhatsApp -->
        <div class="col-md-6">
          <label for="contact_phone" class="admin-card-header-label mb-2">Nomor WhatsApp Utama (CS / Sales)</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-success border-secondary border-opacity-20"><i class="bi bi-whatsapp"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $homeData['contact_phone'] ?? '0821-8792-9433') }}" required>
          </div>
          @error('contact_phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Format: 08xx / 62xx. Terhubung ke tombol floating WhatsApp dan header.</div>
        </div>

        <div class="col-md-6">
          <label for="contact_phone_technician" class="admin-card-header-label mb-2">Nomor WhatsApp Layanan Teknik (Teknisi)</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-info border-secondary border-opacity-20"><i class="bi bi-wrench-adjustable"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_phone_technician') is-invalid @enderror" id="contact_phone_technician" name="contact_phone_technician" value="{{ old('contact_phone_technician', $homeData['contact_phone_technician'] ?? '0812-837-4867') }}" required>
          </div>
          @error('contact_phone_technician') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Kontak WhatsApp untuk konsultasi teknis pada halaman Layanan.</div>
        </div>

        <div class="col-md-6">
          <label for="contact_phone_marketing" class="admin-card-header-label mb-2">Telepon Kantor - Head Office (Marketing)</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-secondary border-secondary border-opacity-20"><i class="bi bi-telephone"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_phone_marketing') is-invalid @enderror" id="contact_phone_marketing" name="contact_phone_marketing" value="{{ old('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '021-3874-1447') }}" required>
          </div>
          @error('contact_phone_marketing') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Nomor telepon kantor pusat (PMA HOPMA) divisi marketing.</div>
        </div>

        <div class="col-md-6">
          <label for="contact_phone_finance" class="admin-card-header-label mb-2">Telepon Kantor - Finance &amp; Warehouse</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-secondary border-secondary border-opacity-20"><i class="bi bi-building"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_phone_finance') is-invalid @enderror" id="contact_phone_finance" name="contact_phone_finance" value="{{ old('contact_phone_finance', $homeData['contact_phone_finance'] ?? '021-8792-9433') }}" required>
          </div>
          @error('contact_phone_finance') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Nomor telepon kantor operasional finance &amp; gudang.</div>
        </div>

        <!-- Template Sapaan WhatsApp Otomatis -->
        <div class="col-12">
          <label for="whatsapp_default_message" class="admin-card-header-label mb-2">Pesan Otomatis Default WhatsApp (Greeting Template)</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('whatsapp_default_message') is-invalid @enderror" id="whatsapp_default_message" name="whatsapp_default_message" value="{{ old('whatsapp_default_message', $homeData['whatsapp_default_message'] ?? 'Halo Prolabios, saya ingin berkonsultasi mengenai produk dan penawaran alat laboratorium.') }}">
          @error('whatsapp_default_message') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Teks pembuka yang otomatis terisi ketika pengunjung mengklik tombol WhatsApp di website.</div>
        </div>

        <!-- Email Resmi & Katalog PDF -->
        <div class="col-md-6">
          <label for="contact_email" class="admin-card-header-label mb-2">Alamat Email Resmi</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-secondary border-secondary border-opacity-20"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $homeData['contact_email'] ?? 'marketing@prolabios.com') }}" required>
          </div>
          @error('contact_email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="catalog_pdf_url" class="admin-card-header-label mb-2">Link Google Drive / Download Katalog PDF</label>
          <div class="input-group">
            <span class="input-group-text bg-black text-danger border-secondary border-opacity-20"><i class="bi bi-file-earmark-pdf"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('catalog_pdf_url') is-invalid @enderror" id="catalog_pdf_url" name="catalog_pdf_url" value="{{ old('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '') }}" placeholder="https://drive.google.com/...">
          </div>
          @error('catalog_pdf_url') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <!-- Alamat Kantor -->
        <div class="col-12">
          <label for="contact_address" class="admin-card-header-label mb-2">Alamat Lengkap Kantor &amp; Gudang</label>
          <textarea class="form-control bg-dark text-white border-secondary border-opacity-20 @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" rows="3" required>{{ old('contact_address', $homeData['contact_address'] ?? '') }}</textarea>
          @error('contact_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Google Maps Embed URL -->
        <div class="col-12">
          <label for="google_maps_embed_url" class="admin-card-header-label mb-2">URL Google Maps Embed (iframe src)</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('google_maps_embed_url') is-invalid @enderror" id="google_maps_embed_url" name="google_maps_embed_url" value="{{ old('google_maps_embed_url', $homeData['google_maps_embed_url'] ?? '') }}" placeholder="https://www.google.com/maps/embed?...">
          @error('google_maps_embed_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Masukkan URL dari fitur Google Maps &gt; Bagikan &gt; Sematkan Peta &gt; Ambil nilai di dalam <code>src="..."</code>.</div>
        </div>
      </div>

      <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
        <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN KONTAK</button>
      </div>
    </form>
  </div>
@endif

@if($section === 'general')
  <div class="admin-card border-0">
    <div class="admin-card-header py-3 d-flex align-items-center justify-content-between" style="background: var(--color-surface);">
      <h5 class="mb-0 fw-bold text-white"><i class="bi bi-gear-wide-connected text-warning me-2"></i>Pengaturan Umum, Identitas, Logo &amp; SEO</h5>
      <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1" style="font-size: 0.7rem;">Konfigurasi Global Situs</span>
    </div>

    <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="admin-card-body p-4" style="background: var(--color-surface);">
      @csrf
      <input type="hidden" name="section" value="general">

      <!-- Nama Perusahaan & Jam Operasional -->
      <div class="row g-3">
        <div class="col-md-6">
          <label for="company_name" class="admin-card-header-label mb-2">Nama Perusahaan / PT</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $homeData['company_name'] ?? 'PT. Prolabios Mitra Analitika') }}" required>
          @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Nama utama PT / Perusahaan yang tampil di title bar dan footer website.</div>
        </div>
        
        <div class="col-md-6">
          <label for="operational_hours" class="admin-card-header-label mb-2">Jam Operasional</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('operational_hours') is-invalid @enderror" id="operational_hours" name="operational_hours" value="{{ old('operational_hours', $homeData['operational_hours'] ?? 'Senin - Jumat: 08.00 - 17.00') }}" required>
          @error('operational_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Jadwal operasional kantor resmi (tampil di footer &amp; halaman kontak).</div>
        </div>
      </div>

      <!-- Upload Logo & Favicon -->
      <div class="row g-4 mt-2">
        <div class="col-md-6">
          <label class="admin-card-header-label mb-2">Logo Utama Website (PNG Transparan)</label>
          <div class="row g-3 align-items-center p-3 rounded-3 bg-black border border-secondary border-opacity-20">
            <div class="col-sm-4 text-center">
              <div class="border border-secondary border-opacity-20 rounded bg-dark p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 130px; height: 65px;">
                <img id="site_logo_preview" src="{{ !empty($homeData['site_logo']) ? $homeData['site_logo'] : asset('images/logo-prolabios.png') }}" alt="Preview Logo" class="w-100 h-100" style="object-fit: contain;">
              </div>
            </div>
            <div class="col-sm-8">
              <div class="mb-2">
                <label for="site_logo_file" class="form-label small text-secondary fw-bold mb-1">Upload File Logo Baru</label>
                <input class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" type="file" id="site_logo_file" name="site_logo_file" accept="image/*">
              </div>
              <div>
                <label for="site_logo_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" id="site_logo_url" name="site_logo_url" value="{{ old('site_logo_url', $homeData['site_logo'] ?? '') }}" placeholder="https://...">
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <label class="admin-card-header-label mb-2">Favicon Browser (.ico / .png)</label>
          <div class="row g-3 align-items-center p-3 rounded-3 bg-black border border-secondary border-opacity-20">
            <div class="col-sm-4 text-center">
              <div class="border border-secondary border-opacity-20 rounded bg-dark p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                <img id="site_favicon_preview" src="{{ !empty($homeData['site_favicon']) ? $homeData['site_favicon'] : asset('images/favicon.png') }}" alt="Preview Favicon" style="width: 36px; height: 36px; object-fit: contain;">
              </div>
            </div>
            <div class="col-sm-8">
              <div class="mb-2">
                <label for="site_favicon_file" class="form-label small text-secondary fw-bold mb-1">Upload Favicon Baru</label>
                <input class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" type="file" id="site_favicon_file" name="site_favicon_file" accept="image/*">
              </div>
              <div>
                <label for="site_favicon_url" class="form-label small text-secondary fw-bold mb-1">Atau URL Favicon</label>
                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" id="site_favicon_url" name="site_favicon_url" value="{{ old('site_favicon_url', $homeData['site_favicon'] ?? '') }}" placeholder="https://...">
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <label class="admin-card-header-label mb-2">Background Banner Login Admin (Kolom Kanan)</label>
          <div class="row g-3 align-items-center p-3 rounded-3 bg-black border border-secondary border-opacity-20">
            <div class="col-sm-3 text-center">
              <div class="border border-secondary border-opacity-20 rounded bg-dark p-2 mx-auto d-flex align-items-center justify-content-center overflow-hidden" style="width: 160px; height: 90px;">
                <img id="admin_login_bg_preview" src="{{ !empty($homeData['admin_login_bg']) ? $homeData['admin_login_bg'] : 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" alt="Preview Login Background" class="w-100 h-100" style="object-fit: cover;">
              </div>
            </div>
            <div class="col-sm-9">
              <div class="mb-2">
                <label for="admin_login_bg_file" class="form-label small text-secondary fw-bold mb-1">Upload Background Baru</label>
                <input class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" type="file" id="admin_login_bg_file" name="admin_login_bg_file" accept="image/*">
              </div>
              <div>
                <label for="admin_login_bg_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar</label>
                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" id="admin_login_bg_url" name="admin_login_bg_url" value="{{ old('admin_login_bg_url', $homeData['admin_login_bg'] ?? '') }}" placeholder="https://images.unsplash.com/...">
              </div>
              <p class="form-text text-secondary mb-0 mt-1 small">Foto laboratorium beresolusi tinggi untuk kolom visual pada halaman /admin/login.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Pengaturan SEO & Meta Global -->
      <h6 class="mt-5 mb-3 fw-bold text-white border-bottom border-secondary border-opacity-20 pb-2"><i class="bi bi-search text-info me-2"></i>Pengaturan SEO &amp; Mesin Pencari</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="meta_default_description" class="admin-card-header-label mb-2">Default Meta Description</label>
          <textarea class="form-control bg-dark text-white border-secondary border-opacity-20 @error('meta_default_description') is-invalid @enderror" id="meta_default_description" name="meta_default_description" rows="3">{{ old('meta_default_description', $homeData['meta_default_description'] ?? '') }}</textarea>
          @error('meta_default_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Deskripsi ringkas yang muncul pada hasil pencarian Google &amp; preview share media sosial.</div>
        </div>

        <div class="col-md-6">
          <label for="meta_default_keywords" class="admin-card-header-label mb-2">Default Meta Keywords</label>
          <textarea class="form-control bg-dark text-white border-secondary border-opacity-20 @error('meta_default_keywords') is-invalid @enderror" id="meta_default_keywords" name="meta_default_keywords" rows="3">{{ old('meta_default_keywords', $homeData['meta_default_keywords'] ?? '') }}</textarea>
          @error('meta_default_keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text text-secondary mt-1 small">Kata kunci dipisahkan koma (contoh: alat laboratorium, mikrobiologi, media kultur).</div>
        </div>

        <div class="col-12">
          <label for="google_search_console_id" class="admin-card-header-label mb-2">Google Search Console Verification Tag / Code (Opsional)</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('google_search_console_id') is-invalid @enderror" id="google_search_console_id" name="google_search_console_id" value="{{ old('google_search_console_id', $homeData['google_search_console_id'] ?? '') }}" placeholder="google-site-verification=...">
          @error('google_search_console_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>

      <!-- Media Sosial Links -->
      <h6 class="mt-5 mb-3 fw-bold text-white border-bottom border-secondary border-opacity-20 pb-2"><i class="bi bi-share text-accent me-2"></i>Link Akun Media Sosial Resmi</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-danger border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-instagram"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('social_instagram') is-invalid @enderror" id="social_instagram" name="social_instagram" placeholder="https://instagram.com/akun" value="{{ old('social_instagram', $homeData['social_instagram'] ?? '') }}">
          </div>
          @error('social_instagram') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-primary border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-facebook"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('social_facebook') is-invalid @enderror" id="social_facebook" name="social_facebook" placeholder="https://facebook.com/akun" value="{{ old('social_facebook', $homeData['social_facebook'] ?? '') }}">
          </div>
          @error('social_facebook') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-info border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-linkedin"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('social_linkedin') is-invalid @enderror" id="social_linkedin" name="social_linkedin" placeholder="https://linkedin.com/company/akun" value="{{ old('social_linkedin', $homeData['social_linkedin'] ?? '') }}">
          </div>
          @error('social_linkedin') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-white border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-twitter-x"></i></span>
            <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20 @error('social_twitter') is-invalid @enderror" id="social_twitter" name="social_twitter" placeholder="https://twitter.com/akun" value="{{ old('social_twitter', $homeData['social_twitter'] ?? '') }}">
          </div>
          @error('social_twitter') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
        <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN UMUM</button>
      </div>
    </form>
  </div>
@endif
