@if($section === 'contacts')
  <div class="admin-card border-0 max-w-4xl mx-auto">
    <div class="admin-card-header py-3" style="background: var(--color-surface);">
      <h2 class="h5 mb-0 fw-bold text-white"><i class="bi bi-telephone-outbound text-success me-2"></i>Informasi Kontak Global</h2>
    </div>

    <form action="{{ route('admin.home.update') }}" method="POST" class="admin-card-body p-4" style="background: var(--color-surface);">
      @csrf
      <input type="hidden" name="section" value="contacts">

      <div class="mb-4">
        <label for="contact_phone" class="admin-card-header-label mb-2">Nomor WhatsApp Utama (CS / Sales)</label>
        <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $homeData['contact_phone'] ?? '0821-8792-9433') }}" required>
        <div class="form-text text-secondary mt-1 small">Perubahan akan langsung ter-update di link obrolan WhatsApp utama di seluruh situs.</div>
      </div>

      <div class="mb-4">
        <label for="contact_phone_marketing" class="admin-card-header-label mb-2">Telepon Kantor - Head Office (Marketing)</label>
        <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_phone_marketing" name="contact_phone_marketing" value="{{ old('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '021-3874-1447') }}" required>
        <div class="form-text text-secondary mt-1 small">Nomor telepon kantor pusat (PMA HOPMA) yang terhubung ke divisi marketing.</div>
      </div>

      <div class="mb-4">
        <label for="contact_phone_finance" class="admin-card-header-label mb-2">Telepon Kantor - Finance &amp; Warehouse</label>
        <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_phone_finance" name="contact_phone_finance" value="{{ old('contact_phone_finance', $homeData['contact_phone_finance'] ?? '021-8792-9433') }}" required>
        <div class="form-text text-secondary mt-1 small">Nomor telepon kantor (PMA VILLAPMA) yang terhubung ke finance &amp; warehouse.</div>
      </div>

      <div class="mb-4">
        <label for="contact_phone_technician" class="admin-card-header-label mb-2">Nomor WhatsApp Layanan Teknik (Teknisi)</label>
        <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_phone_technician" name="contact_phone_technician" value="{{ old('contact_phone_technician', $homeData['contact_phone_technician'] ?? '0812-837-4867') }}" required>
        <div class="form-text text-secondary mt-1 small">Perubahan akan memperbarui kontak WhatsApp teknisi pada halaman Layanan kami.</div>
      </div>

      <div class="mb-4">
        <label for="contact_email" class="admin-card-header-label mb-2">Alamat Email Utama</label>
        <input type="email" class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_email" name="contact_email" value="{{ old('contact_email', $homeData['contact_email'] ?? 'marketing@prolabios.com') }}" required>
        <div class="form-text text-secondary mt-1 small">Email resmi perusahaan yang tampil di header, footer, dan kontak.</div>
      </div>

      <div class="mb-4">
        <label for="contact_address" class="admin-card-header-label mb-2">Alamat Lengkap Kantor</label>
        <textarea class="form-control bg-dark text-white border-secondary border-opacity-20" id="contact_address" name="contact_address" rows="4" required>{{ old('contact_address', $homeData['contact_address'] ?? '') }}</textarea>
      </div>

      <div class="mb-4">
        <label for="catalog_pdf_url" class="admin-card-header-label mb-2">Link Google Drive Katalog PDF</label>
        <input type="url" class="form-control bg-dark text-white border-secondary border-opacity-20" id="catalog_pdf_url" name="catalog_pdf_url" value="{{ old('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '') }}">
        <div class="form-text text-secondary mt-1 small">Tautan langsung Google Drive untuk dokumen katalog PDF ("Unduh Katalog").</div>
      </div>

      <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
        <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN KONTAK</button>
      </div>
    </form>
  </div>
@endif

@if($section === 'general')
  <div class="admin-card border-0">
    <div class="admin-card-header py-3" style="background: var(--color-surface);">
      <h5 class="mb-0 fw-bold text-white"><i class="bi bi-gear text-warning me-2"></i>Pengaturan Umum, Logo &amp; Media Sosial</h5>
    </div>

    <form action="{{ route('admin.home.update') }}" method="POST" enctype="multipart/form-data" class="admin-card-body p-4" style="background: var(--color-surface);">
      @csrf
      <input type="hidden" name="section" value="general">

      <!-- Nama Perusahaan & Jam Operasional -->
      <div class="row g-3">
        <div class="col-md-6">
          <label for="company_name" class="admin-card-header-label mb-2">Nama Perusahaan</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="company_name" name="company_name" value="{{ old('company_name', $homeData['company_name'] ?? 'PT. Prolabios Mitra Analitika') }}" required>
          <div class="form-text text-secondary mt-1 small">Nama utama PT / Perusahaan yang tampil di title bar dan logo website.</div>
        </div>
        
        <div class="col-md-6">
          <label for="operational_hours" class="admin-card-header-label mb-2">Jam Operasional</label>
          <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-20" id="operational_hours" name="operational_hours" value="{{ old('operational_hours', $homeData['operational_hours'] ?? 'Senin - Jumat: 08.00 - 17.00') }}" required>
          <div class="form-text text-secondary mt-1 small">Jadwal buka-tutup kantor resmi (tampil di footer).</div>
        </div>
      </div>

      <!-- Logo Website Upload -->
      <div class="col-12 mt-4">
        <label class="admin-card-header-label mb-2">Logo Utama Website</label>
        <div class="row g-3 align-items-center p-3 rounded-3 bg-black border border-secondary border-opacity-20">
          <div class="col-sm-3 text-center">
            <div class="border border-secondary border-opacity-20 rounded bg-dark p-2 mx-auto d-flex align-items-center justify-content-center" style="width: 140px; height: 70px;">
              <img id="site_logo_preview" src="{{ !empty($homeData['site_logo']) ? $homeData['site_logo'] : asset('images/logo-prolabios.png') }}" alt="Preview Logo" class="w-100 h-100" style="object-fit: contain;">
            </div>
          </div>
          <div class="col-sm-9">
            <div class="mb-2">
              <label for="site_logo_file" class="form-label small text-secondary fw-bold mb-1">Upload File Logo Baru (PNG Transparan)</label>
              <input class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" type="file" id="site_logo_file" name="site_logo_file" accept="image/*">
            </div>
            <div>
              <label for="site_logo_url" class="form-label small text-secondary fw-bold mb-1">Atau Gunakan URL Gambar Logo Eksternal</label>
              <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-20" id="site_logo_url" name="site_logo_url" value="{{ old('site_logo_url', $homeData['site_logo'] ?? '') }}" placeholder="https://example.com/logo.png">
            </div>
          </div>
        </div>
      </div>

      <!-- Media Sosial Links -->
      <h6 class="mt-5 mb-3 fw-bold text-white border-bottom border-secondary border-opacity-20 pb-2"><i class="bi bi-share text-accent me-2"></i>Link Akun Media Sosial</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-danger border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-instagram"></i></span>
            <input type="url" class="form-control bg-dark text-white border-secondary border-opacity-20" id="social_instagram" name="social_instagram" placeholder="https://instagram.com/akun" value="{{ old('social_instagram', $homeData['social_instagram'] ?? '') }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-primary border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-facebook"></i></span>
            <input type="url" class="form-control bg-dark text-white border-secondary border-opacity-20" id="social_facebook" name="social_facebook" placeholder="https://facebook.com/akun" value="{{ old('social_facebook', $homeData['social_facebook'] ?? '') }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-info border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-linkedin"></i></span>
            <input type="url" class="form-control bg-dark text-white border-secondary border-opacity-20" id="social_linkedin" name="social_linkedin" placeholder="https://linkedin.com/company/akun" value="{{ old('social_linkedin', $homeData['social_linkedin'] ?? '') }}">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-black text-white border-secondary border-opacity-20" style="width: 45px; justify-content: center;"><i class="bi bi-twitter-x"></i></span>
            <input type="url" class="form-control bg-dark text-white border-secondary border-opacity-20" id="social_twitter" name="social_twitter" placeholder="https://twitter.com/akun" value="{{ old('social_twitter', $homeData['social_twitter'] ?? '') }}">
          </div>
        </div>
      </div>

      <div class="mt-4 border-top border-secondary border-opacity-20 pt-4 text-end">
        <button type="submit" class="admin-btn admin-btn-accent px-4 py-2" style="font-size: 0.75rem;"><i class="bi bi-save me-1"></i> SIMPAN PENGATURAN UMUM</button>
      </div>
    </form>
  </div>
@endif
