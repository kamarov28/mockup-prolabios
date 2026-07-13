<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | PROLABIOS</title>
  <meta name="description" content="Halaman login administrator untuk mengelola data katalog produk, artikel, dan fokus industri PT Prolabios Mitra Analitika.">
  
  <!-- Font & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #1e1e2d;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .login-card {
      background-color: #ffffff;
      border: none;
      border-radius: 0.75rem;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 420px;
      overflow: hidden;
    }
    .login-header {
      background-color: #1a1a27;
      padding: 2.5rem 2rem;
      text-align: center;
      border-bottom: 3px solid #D32F2F;
    }
    .login-header img {
      height: 40px;
      filter: brightness(0) invert(1);
    }
    .login-body {
      padding: 2.5rem 2rem;
    }
    .form-control:focus {
      border-color: #D32F2F;
      box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.15);
    }
    .btn-primary {
      background-color: #D32F2F;
      border-color: #D32F2F;
      padding: 0.75rem;
      font-weight: 600;
    }
    .btn-primary:hover, .btn-primary:focus {
      background-color: #8B1A1A;
      border-color: #8B1A1A;
    }
  </style>
</head>
<body>

  <main class="login-card">
    <div class="login-header">
      <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo" class="mb-2">
      <h1 class="text-white small text-uppercase tracking-wider fw-bold mb-0" style="font-size: 0.875rem;">Admin Panel Portal</h1>
    </div>
    
    <div class="login-body">
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

      <form action="{{ url('/admin/login') }}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold text-secondary">Email</label>
          <div class="input-group">
            <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control bg-light border-start-0" id="email" name="email" required value="{{ old('email') }}" placeholder="admin@prolabios.com">
          </div>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label fw-semibold text-secondary">Password</label>
          <div class="input-group">
            <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control bg-light border-start-0 border-end-0" id="password" name="password" required placeholder="••••••••">
            <button type="button" class="input-group-text bg-light text-secondary border-start-0" id="toggle-password" style="cursor: pointer;">
              <i id="toggle-password-icon" class="bi bi-eye-slash-fill"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 shadow-sm mb-3">MASUK PANEL ADMIN</button>
        <a href="{{ url('/') }}" class="text-decoration-none small d-block text-center" style="color: #495057; font-weight: 500;"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
      </form>
    </div>
  </main>

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
          togglePasswordIcon.className = 'bi bi-eye-slash-fill';
        } else {
          togglePasswordIcon.className = 'bi bi-eye-fill';
        }
      });
    }
  </script>
</body>
</html>
