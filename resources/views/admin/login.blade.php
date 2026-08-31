<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | PT Prolabios Mitra Analitika</title>
  <meta name="description" content="Portal administrasi sistem katalog, RFQ, dan manajemen konten PT Prolabios Mitra Analitika.">

  <!-- Font & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Vite Asset Loading -->
  @vite(['resources/css/admin.css'])
</head>
<body class="login-split-page">

  <div class="login-split-container">

    <!-- Kolom Kiri: Form Login -->
    <div class="login-split-form-col">
      <div class="login-form-wrapper">

        <!-- Brand Header -->
        <div class="login-brand-head mb-4">
          <a href="{{ url('/') }}" class="d-inline-block mb-3">
            <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo" class="login-brand-logo">
          </a>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="login-badge-pill">ADMIN CONSOLE</span>
            <span class="text-white-50" style="font-size: 0.7rem;">&bull; v2.5</span>
          </div>
          <h1 class="login-heading">Masuk ke Panel</h1>
          <p class="login-subheading">Kelola katalog, permintaan RFQ, artikel, dan pengaturan portal.</p>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
          <div class="alert alert-success border-0 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger border-0 shadow-sm mb-4 small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
          </div>
        @endif

        <!-- Form -->
        <form action="{{ url('/admin/login') }}" method="POST" class="login-form-fields">
          @csrf

          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="username" name="username" required value="{{ old('username') }}" placeholder="Masukkan username" autofocus autocomplete="username">
            </div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label for="password" class="form-label mb-0">Kata Sandi</label>
            </div>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
              <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
              <button type="button" class="input-group-text" id="toggle-password" title="Lihat password" aria-label="Lihat password">
                <i id="toggle-password-icon" class="bi bi-eye-slash"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 mb-4 d-flex align-items-center justify-content-center gap-2">
            <span>Masuk Workspace</span>
            <i class="bi bi-arrow-right"></i>
          </button>

          <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-10">
            <a href="{{ url('/') }}" class="btn-back-home text-decoration-none">
              <i class="bi bi-arrow-left me-1"></i> Ke Beranda Publik
            </a>
            <span class="text-white-50" style="font-size: 0.7rem;">
              <i class="bi bi-lock-fill text-success me-1"></i> SSL Protected
            </span>
          </div>
        </form>

      </div>
    </div>

    <!-- Kolom Kanan: Cinematic Visual Lab -->
    <div class="login-split-visual-col">
      @php
        $loginBg = !empty($homeData['admin_login_bg'])
          ? $homeData['admin_login_bg']
          : 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=85';
      @endphp
      <div class="login-visual-bg" style="background-image: url('{{ $loginBg }}');"></div>
      <div class="login-visual-overlay"></div>

      <div class="login-visual-content">
        <div class="mb-4">
          <span class="typo-pill-outline" style="border-color: rgba(255,255,255,0.25); color: #fff; background: rgba(0,0,0,0.4);">{{ $homeData['company_name'] ?? 'PT PROLABIOS MITRA ANALITIKA' }}</span>
        </div>
        <h2 class="login-visual-title">Precision Instruments &amp; Lab Solutions</h2>
        <p class="login-visual-desc">
          Platform terpadu untuk penyediaan instrumen analisis, reagen mikrobiologi, serta pengelolaan permintaan penawaran (RFQ) B2B berstandar internasional.
        </p>

        <div class="login-visual-metrics mt-4 pt-3">
          <div class="row g-3">
            <div class="col-6">
              <div class="metric-card">
                <span class="metric-val">15+</span>
                <span class="metric-lbl">Global Principals</span>
              </div>
            </div>
            <div class="col-6">
              <div class="metric-card">
                <span class="metric-val">ISO &amp; AKL</span>
                <span class="metric-lbl">Compliance Standard</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap 5 Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- Password Show/Hide Toggle Handler -->
  <script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('toggle-password-icon');

    if (togglePassword && passwordInput && togglePasswordIcon) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'password') {
          togglePasswordIcon.className = 'bi bi-eye-slash';
        } else {
          togglePasswordIcon.className = 'bi bi-eye';
        }
      });
    }
  </script>
</body>
</html>
