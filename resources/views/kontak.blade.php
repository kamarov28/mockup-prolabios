@extends('layouts.app')

@section('title', 'Hubungi Kami | PROLABIOS')

@section('preload')
  <link rel="preload" href="{{ $siteSettings['contact_banner_image'] ?? 'https://images.unsplash.com/photo-1596524430615-b46475ddff6e?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['contact_banner_image'] ?? 'https://images.unsplash.com/photo-1596524430615-b46475ddff6e?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['contact_title'] ?? 'Hubungi Kami' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['contact_subtitle'] ?? 'Kami siap melayani kebutuhan laboratorium Anda' }}</p>
    </div>
  </div>

  <!-- Contact Content -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row g-5">
        
        <!-- Contact Info Cards -->
        <div class="col-lg-4 col-md-5">
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-geo-alt-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Alamat Kantor</h2>
              <p class="text-muted small mb-0">{!! nl2br(e($siteSettings['contact_address'] ?? "Komplek Cibinong Griya Asri Blok: A9/10, RT 01 RW 08\nCibinong – Bogor, West Java, Indonesia 16913")) !!}</p>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-100" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-telephone-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Telepon</h2>
              <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><strong>Head Office (Marketing):</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }}</a></li>
                <li class="mb-2"><strong>Finance &amp; Warehouse:</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }}</a></li>
              </ul>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-200" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-envelope-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Email</h2>
              <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><a href="mailto:{{ $siteSettings['contact_email'] ?? 'lisa.aryadi@prolabios.com' }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_email'] ?? 'lisa.aryadi@prolabios.com' }}</a></li>
                <li><a href="mailto:sandi@prolabios.com" class="text-decoration-none text-muted">sandi@prolabios.com</a></li>
              </ul>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-300" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-clock-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Jam Operasional</h2>
              <p class="text-muted small mb-1">Senin – Jumat: 09.00 – 18.00 WIB</p>
              <p class="text-muted small mb-0">Sabtu – Minggu: Tutup</p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8 col-md-7">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 h-100 animate-on-scroll animate-slide-up">
            <h3 class="mb-4 fw-bold" style="color: var(--color-secondary, #2b2d42);">Kirim Pesan</h3>
            <form id="contactForm" class="contact-form" onsubmit="return handleContactForm(event)">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap Anda">
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" required placeholder="contoh@email.com">
                </div>
                <div class="col-md-6">
                  <label for="telepon" class="form-label fw-semibold">No. Telepon</label>
                  <input type="tel" class="form-control" id="telepon" name="telepon" placeholder="+62 xxx xxxx xxxx">
                </div>
                <div class="col-md-6">
                  <label for="perusahaan" class="form-label fw-semibold">Perusahaan / Instansi</label>
                  <input type="text" class="form-control" id="perusahaan" name="perusahaan" placeholder="Nama perusahaan atau instansi Anda">
                </div>
                <div class="col-12">
                  <label for="subjek" class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                  <select class="form-select" id="subjek" name="subjek" required>
                    <option value="">-- Pilih Subjek --</option>
                    <option value="inquiry">Pertanyaan Produk</option>
                    <option value="quotation">Permintaan Penawaran Harga</option>
                    <option value="service">Service Request / Perbaikan</option>
                    <option value="consultation">Konsultasi Teknis</option>
                    <option value="labdesign">Desain Laboratorium</option>
                    <option value="other">Lainnya</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="pesan" class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="pesan" name="pesan" rows="5" required placeholder="Tulis pesan Anda di sini..."></textarea>
                </div>
                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-primary px-4 py-2 fw-bold w-100 w-md-auto shadow-sm">Kirim Pesan</button>
                </div>
              </div>
            </form>
            
            <div id="formSuccess" class="text-center py-5" style="display: none;">
              <div class="text-success mb-3">
                <i class="bi bi-check-circle-fill display-1"></i>
              </div>
              <h3 class="fw-bold">Pesan Terkirim!</h3>
              <p class="text-muted">Terima kasih telah menghubungi kami. Tim kami akan merespon dalam 1x24 jam kerja.</p>
              <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-3">Kembali ke Beranda</a>
            </div>
          </div>
        </div>

      </div>

      <!-- WhatsApp CTA -->
      <div class="bg-success text-white p-4 p-md-5 rounded shadow-sm mt-5 animate-on-scroll animate-scale-in delay-100" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%) !important;">
        <div class="row align-items-center text-center text-md-start">
          <div class="col-md-8 mb-4 mb-md-0">
            <h3 class="fw-bold">Butuh Respon Cepat?</h3>
            <p class="mb-0 text-white">Hubungi kami langsung via WhatsApp untuk konsultasi produk dan layanan.</p>
          </div>
          <div class="col-md-4 text-md-end">
            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prolabios%2C%20saya%20ingin%20bertanya%20mengenai%20produk%20dan%20layanan%20Anda." target="_blank" rel="noopener noreferrer" class="btn btn-light fw-bold px-4 py-3 rounded-pill shadow-sm d-inline-flex align-items-center" style="color: #146c43;">
              <i class="bi bi-whatsapp fs-4 me-2"></i>
              Chat via WhatsApp
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection

@extends('layouts.app')

@section('title', 'Hubungi Kami | PROLABIOS')
@section('meta_description', 'Hubungi Prolabios Mitra Analitika - Kontak marketing, finance, dan customer service untuk konsultasi produk dan layanan laboratorium.')
@section('meta_keywords', 'kontak, hubungi kami, customer service, marketing, finance, prolabios, alamat, telepon, email')
@section('canonical_url', url('/kontak'))

@section('preload')
  <link rel="preload" href="{{ $siteSettings['contact_banner_image'] ?? 'https://images.unsplash.com/photo-1596524430615-b46475ddff6e?auto=format&fit=crop&w=1920&q=80' }}" as="image">
@endsection

@section('content')
  <!-- Page Header -->
  <div class="page-header position-relative py-5" style="background: url('{{ $siteSettings['contact_banner_image'] ?? 'https://images.unsplash.com/photo-1596524430615-b46475ddff6e?auto=format&fit=crop&w=1920&q=80' }}') center/cover;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
    <div class="container position-relative text-white py-4 text-center">
      <h1 class="display-5 fw-bold mb-3">{{ $siteSettings['contact_title'] ?? 'Hubungi Kami' }}</h1>
      <p class="lead mb-0 text-light opacity-75">{{ $siteSettings['contact_subtitle'] ?? 'Kami siap melayani kebutuhan laboratorium Anda' }}</p>
    </div>
  </div>

  <!-- Contact Content -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row g-5">
        
        <!-- Contact Info Cards -->
        <div class="col-lg-4 col-md-5">
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-geo-alt-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Alamat Kantor</h2>
              <p class="text-muted small mb-0">{!! nl2br(e($siteSettings['contact_address'] ?? "Komplek Cibinong Griya Asri Blok: A9/10, RT 01 RW 08\nCibinong – Bogor, West Java, Indonesia 16913")) !!}</p>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-100" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-telephone-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Telepon</h2>
              <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><strong>Head Office (Marketing):</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_marketing'] ?? '021-3874-1447') }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_phone_marketing'] ?? '021-3874-1447' }}</a></li>
                <li class="mb-2"><strong>Finance &amp; Warehouse:</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSettings['contact_phone_finance'] ?? '021-8792-9433') }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_phone_finance'] ?? '021-8792-9433' }}</a></li>
              </ul>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-200" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-envelope-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Email</h2>
              <ul class="list-unstyled mb-0 small">
                <li class="mb-2"><a href="mailto:{{ $siteSettings['contact_email'] ?? 'lisa.aryadi@prolabios.com' }}" class="text-decoration-none text-muted">{{ $siteSettings['contact_email'] ?? 'lisa.aryadi@prolabios.com' }}</a></li>
                <li><a href="mailto:sandi@prolabios.com" class="text-decoration-none text-muted">sandi@prolabios.com</a></li>
              </ul>
            </div>
          </div>
          
          <div class="card border-0 shadow-sm mb-4 animate-on-scroll animate-slide-right delay-300" style="border-left: 4px solid var(--color-primary) !important;">
            <div class="card-body p-4">
              <div class="text-primary mb-3">
                <i class="bi bi-clock-fill fs-2"></i>
              </div>
              <h2 class="h5 fw-bold" style="color: var(--color-secondary, #2b2d42);">Jam Operasional</h2>
              <p class="text-muted small mb-1">Senin – Jumat: 09.00 – 18.00 WIB</p>
              <p class="text-muted small mb-0">Sabtu – Minggu: Tutup</p>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8 col-md-7">
          <div class="bg-white p-4 p-md-5 rounded shadow-sm border-0 h-100 animate-on-scroll animate-slide-up">
            <h3 class="mb-4 fw-bold" style="color: var(--color-secondary, #2b2d42);">Kirim Pesan</h3>
            <form id="contactForm" class="contact-form" novalidate>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan nama lengkap Anda" minlength="3">
                  <div class="invalid-feedback">Nama lengkap harus diisi (minimal 3 karakter).</div>
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" required placeholder="contoh@email.com">
                  <div class="invalid-feedback">Email harus valid dan diisi dengan benar.</div>
                </div>
                <div class="col-md-6">
                  <label for="telepon" class="form-label fw-semibold">No. Telepon</label>
                  <input type="tel" class="form-control" id="telepon" name="telepon" placeholder="+62 xxx xxxx xxxx" pattern="^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$">
                  <div class="invalid-feedback">Nomor telepon harus valid (contoh: +6281234567890).</div>
                </div>
                <div class="col-md-6">
                  <label for="perusahaan" class="form-label fw-semibold">Perusahaan / Instansi</label>
                  <input type="text" class="form-control" id="perusahaan" name="perusahaan" placeholder="Nama perusahaan atau instansi Anda">
                </div>
                <div class="col-12">
                  <label for="subjek" class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                  <select class="form-select" id="subjek" name="subjek" required>
                    <option value="">-- Pilih Subjek --</option>
                    <option value="inquiry">Pertanyaan Produk</option>
                    <option value="quotation">Permintaan Penawaran Harga</option>
                    <option value="service">Service Request / Perbaikan</option>
                    <option value="consultation">Konsultasi Teknis</option>
                    <option value="labdesign">Desain Laboratorium</option>
                    <option value="other">Lainnya</option>
                  </select>
                  <div class="invalid-feedback">Silakan pilih subjek pesan.</div>
                </div>
                <div class="col-12">
                  <label for="pesan" class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="pesan" name="pesan" rows="5" required placeholder="Tulis pesan Anda di sini..." minlength="10"></textarea>
                  <div class="invalid-feedback">Pesan harus diisi (minimal 10 karakter).</div>
                </div>
                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-primary px-4 py-2 fw-bold w-100 w-md-auto shadow-sm">Kirim Pesan</button>
                </div>
              </div>
            </form>
            
            <div id="formSuccess" class="text-center py-5" style="display: none;">
              <div class="text-success mb-3">
                <i class="bi bi-check-circle-fill display-1"></i>
              </div>
              <h3 class="fw-bold">Pesan Terkirim!</h3>
              <p class="text-muted">Terima kasih telah menghubungi kami. Tim kami akan merespon dalam 1x24 jam kerja.</p>
              <a href="{{ url('/') }}" class="btn btn-outline-secondary mt-3">Kembali ke Beranda</a>
            </div>
          </div>
        </div>

      </div>

      <!-- WhatsApp CTA -->
      <div class="bg-success text-white p-4 p-md-5 rounded shadow-sm mt-5 animate-on-scroll animate-scale-in delay-100" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%) !important;">
        <div class="row align-items-center text-center text-md-start">
          <div class="col-md-8 mb-4 mb-md-0">
            <h3 class="fw-bold">Butuh Respon Cepat?</h3>
            <p class="mb-0 text-white">Hubungi kami langsung via WhatsApp untuk konsultasi produk dan layanan.</p>
          </div>
          <div class="col-md-4 text-md-end">
            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Prolabios%2C%20saya%20ingin%20bertanya%20mengenai%20produk%20dan%20layanan%20Anda." target="_blank" rel="noopener noreferrer" class="btn btn-light fw-bold px-4 py-3 rounded-pill shadow-sm d-inline-flex align-items-center" style="color: #146c43;">
              <i class="bi bi-whatsapp fs-4 me-2"></i>
              Chat via WhatsApp
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const contactForm = document.getElementById('contactForm');
      
      if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
          if (!contactForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Add invalid class to all invalid fields
            const invalidInputs = contactForm.querySelectorAll(':invalid');
            invalidInputs.forEach(input => {
              input.classList.add('is-invalid');
            });
            
            // Scroll to first invalid field
            if (invalidInputs.length > 0) {
              invalidInputs[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
              invalidInputs[0].focus();
            }
          } else {
            event.preventDefault();
            
            // Simulate form submission
            const formData = new FormData(contactForm);
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
            
            // Simulate API call
            setTimeout(function() {
              contactForm.style.display = 'none';
              document.getElementById('formSuccess').style.display = 'block';
              submitBtn.disabled = false;
              submitBtn.textContent = originalText;
            }, 1500);
          }
          
          contactForm.classList.add('was-validated');
        });
        
        // Remove invalid class on input
        contactForm.querySelectorAll('input, select, textarea').forEach(input => {
          input.addEventListener('input', function() {
            if (this.checkValidity()) {
              this.classList.remove('is-invalid');
            }
          });
        });
      }
    });
  </script>
@endpush
