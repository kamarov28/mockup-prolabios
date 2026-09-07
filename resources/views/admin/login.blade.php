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

  <style>
    .login-nb-input-group.has-error {
      border-color: #A6171C !important;
      box-shadow: 3px 3px 0 #A6171C !important;
    }
    .login-nb-input-group.has-error .login-nb-input-icon {
      background: #FEE2E2 !important;
      color: #A6171C !important;
      border-right-color: #A6171C !important;
    }
    .login-nb-alert-error {
      border: 2px solid #1E1E1E !important;
      background: #FEE2E2 !important;
      color: #7F1D1D !important;
      box-shadow: 3px 3px 0 #1E1E1E !important;
      border-radius: 6px !important;
      padding: 12px 14px !important;
      font-size: 0.85rem !important;
      font-weight: 600 !important;
    }
    .login-nb-field-error {
      font-size: 0.8rem;
      font-weight: 600;
      color: #A6171C;
    }
  </style>
</head>
<body class="login-nb-page">

  <div class="login-nb-container">

    <div class="login-nb-card">

      <!-- Brand Header -->
      <div class="login-nb-header text-center mb-4">
        <a href="{{ url('/') }}" class="d-inline-block mb-3" title="Kembali ke Beranda">
          <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo" class="login-nb-logo">
        </a>
        <div class="d-flex align-items-center justify-content-center mb-2">
          <span class="login-nb-badge">ADMIN CONSOLE</span>
        </div>
        <h1 class="login-nb-title">Masuk ke Panel</h1>
        <p class="login-nb-subtitle">Kelola katalog, permintaan RFQ, artikel, dan portal.</p>
      </div>

      <!-- Flash Messages / Warning Alert -->
      @if(session('success'))
        <div class="login-nb-alert login-nb-alert-success mb-3" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
      @endif

      @if(session('error') || $errors->any())
        <div class="login-nb-alert login-nb-alert-error mb-3 d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill fs-5 text-danger me-2 flex-shrink-0"></i>
          <div>
            <strong>Peringatan:</strong> {{ session('error') ?? $errors->first() }}
          </div>
        </div>
      @endif

      <!-- Form -->
      <form action="{{ url('/admin/login') }}" method="POST" class="login-nb-form">
        @csrf

        <div class="mb-3">
          <label for="username" class="login-nb-label">Username</label>
          <div class="login-nb-input-group @if(session('error') || $errors->any()) has-error @endif">
            <span class="login-nb-input-icon"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="login-nb-input" id="username" name="username" required value="{{ old('username') }}" placeholder="Masukkan username" @if(!old('username')) autofocus @endif autocomplete="username">
          </div>
        </div>

        <div class="mb-4">
          <label for="password" class="login-nb-label">Kata Sandi</label>
          <div class="login-nb-input-group @if(session('error') || $errors->any()) has-error @endif">
            <span class="login-nb-input-icon"><i class="bi bi-shield-lock-fill"></i></span>
            <input type="password" class="login-nb-input" id="password" name="password" required placeholder="••••••••" autocomplete="current-password" @if(old('username')) autofocus @endif>
            <button type="button" class="login-nb-toggle-btn" id="toggle-password" title="Lihat password" aria-label="Lihat password">
              <i id="toggle-password-icon" class="bi bi-eye-slash"></i>
            </button>
          </div>
          @if(session('error') || $errors->any())
            <div class="login-nb-field-error mt-2 d-flex align-items-center gap-1">
              <i class="bi bi-x-circle-fill"></i>
              <span>Kata sandi salah. Silakan periksa kembali.</span>
            </div>
          @endif
        </div>

        <button type="submit" class="login-nb-btn-submit w-100 mb-3">
          <span>Masuk Workspace</span>
          <i class="bi bi-arrow-right"></i>
        </button>

        <div class="login-nb-footer d-flex justify-content-between align-items-center pt-3 border-top">
          <a href="{{ url('/') }}" class="login-nb-back-link">
            <i class="bi bi-arrow-left me-1"></i> Ke Beranda Publik
          </a>
          <span class="login-nb-secure-pill">
            <i class="bi bi-lock-fill text-success me-1"></i> SSL Protected
          </span>
        </div>
      </form>

    </div>

    <!-- Attribution Footer Note -->
    <div class="text-center mt-3 login-nb-copyright">
      &copy; {{ date('Y') }} PT Prolabios Mitra Analitika &bull; Internal System
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
