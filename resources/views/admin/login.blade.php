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
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Vite Asset Loading (HMR Support) -->
  @vite(['resources/css/admin.css'])
</head>
<body class="login-body-wrapper">

  <main class="login-card">
    <div class="login-header">
      <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo" class="mb-3">
      <h1 class="text-white small text-uppercase tracking-wider fw-semibold mb-0" style="font-size: 0.72rem; letter-spacing: 2px;">Admin Panel Portal</h1>
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
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" id="username" name="username" required value="{{ old('username') }}" placeholder="admin">
          </div>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            <button type="button" class="input-group-text" id="toggle-password" style="cursor: pointer;">
              <i id="toggle-password-icon" class="bi bi-eye-slash-fill"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-4">Masuk Panel Admin</button>
        <a href="{{ url('/') }}" class="btn-back-home text-decoration-none d-block text-center"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
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
