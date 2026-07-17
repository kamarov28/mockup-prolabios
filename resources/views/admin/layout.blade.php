<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel') | PROLABIOS</title>
  <meta name="description" content="Dashboard portal admin untuk mengelola katalog produk, artikel, dan sektor industri PT Prolabios Mitra Analitika.">

  <!-- Font: Space Grotesk — same as main website -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- Bootstrap 5 CSS (Layout utilities) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Admin Design System (Vite — HMR Enabled) -->
  @vite(['resources/css/admin.css'])

  @yield('admin_styles')
</head>
<body>
  <!-- Progress Loading Bar -->
  <div id="page-loading-bar" style="position: fixed; top: 0; left: 0; width: 0%; height: 2px; background: var(--color-accent, #FF4950); z-index: 9999; transition: width 0.4s ease, opacity 0.4s ease; opacity: 0; pointer-events: none;"></div>

  <div id="admin-wrapper">
    <!-- ── Sidebar ──────────────────────────────────────────────────────── -->
    <aside id="admin-sidebar">

      <!-- Brand -->
      <div class="sidebar-brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo">
        </a>
        <span class="admin-badge">Admin</span>
      </div>

      <!-- Navigation -->
      <div class="sidebar-menu">

        <span class="sidebar-nav-label">Panel</span>

        <div class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="bi bi-grid-1x2"></i> Dashboard
          </a>
        </div>

        <div class="sidebar-item {{ request()->routeIs('admin.home.edit') ? 'active' : '' }}">
          <a href="{{ route('admin.home.edit') }}" class="sidebar-link">
            <i class="bi bi-sliders"></i> Pengaturan Web
          </a>
        </div>

        <span class="sidebar-nav-label" style="margin-top: 8px;">Konten</span>

        <div class="sidebar-item {{ request()->is('admin/products*') ? 'active' : '' }}">
          <a href="{{ route('admin.products') }}" class="sidebar-link">
            <i class="bi bi-box-seam"></i> Produk
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
          <a href="{{ route('admin.posts') }}" class="sidebar-link">
            <i class="bi bi-file-text"></i> Artikel
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/sectors*') ? 'active' : '' }}">
          <a href="{{ route('admin.sectors') }}" class="sidebar-link">
            <i class="bi bi-layers"></i> Sektor
          </a>
        </div>

        <!-- Logout -->
        <hr class="sidebar-sep" style="margin-top: auto;">
        <div class="sidebar-item">
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link" style="color: rgba(255,73,80,0.7) !important;">
              <i class="bi bi-arrow-bar-left"></i> Keluar
            </button>
          </form>
        </div>

      </div>
    </aside>

    <!-- ── Main Content ─────────────────────────────────────────────────── -->
    <div id="admin-content">

      <!-- Sticky Header -->
      <header class="admin-header">
        <p class="admin-header-title">@yield('page_title', 'Dashboard')</p>
        <div class="admin-header-actions">
          <span class="admin-header-user">
            <i class="bi bi-person-circle"></i>
            Administrator
          </span>
          <a href="{{ url('/') }}" target="_blank" class="admin-header-web-link">
            <i class="bi bi-box-arrow-up-right"></i>
            Lihat Web
          </a>
        </div>
      </header>

      <!-- Page Body -->
      <main class="admin-body">
        @yield('admin_content')
      </main>

    </div>
  </div>

  <!-- Scroll to Top -->
  <button type="button" id="scroll-to-top" aria-label="Scroll to top" style="position: fixed; bottom: 32px; right: 32px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: 50%; color: var(--color-text-muted); cursor: pointer; opacity: 0; visibility: hidden; transition: all 0.3s ease; z-index: 1050;">
    <i class="bi bi-arrow-up" style="font-size: 1rem;"></i>
  </button>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // ── Toast Helper ──────────────────────────────────────────────────────
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    // ── Flash Messages ────────────────────────────────────────────────────
    @if(session('success'))
      Toast.fire({ icon: 'success', title: {!! json_encode(session('success')) !!} });
    @endif
    @if(session('error'))
      Toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
    @endif

    // ── Delete Confirmation ───────────────────────────────────────────────
    document.addEventListener('submit', function(e) {
      const form = e.target;
      const methodInput = form.querySelector('input[name="_method"]');
      if (methodInput && methodInput.value.toUpperCase() === 'DELETE') {
        e.preventDefault();
        Swal.fire({
          title: 'Hapus Data?',
          text: 'Data yang dihapus tidak dapat dipulihkan.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'admin-btn admin-btn-danger mx-2',
            cancelButton: 'admin-btn admin-btn-ghost mx-2',
            popup: ''
          },
          buttonsStyling: false
        }).then((result) => { if (result.isConfirmed) form.submit(); });
      }
    });

    // ── Logout Confirmation ───────────────────────────────────────────────
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
      logoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Keluar dari Admin?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Ya, Keluar',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          customClass: {
            confirmButton: 'admin-btn admin-btn-danger mx-2',
            cancelButton: 'admin-btn admin-btn-ghost mx-2'
          },
          buttonsStyling: false
        }).then((result) => { if (result.isConfirmed) logoutForm.submit(); });
      });
    }

    // ── Copy Link Helper ──────────────────────────────────────────────────
    document.addEventListener('click', function(e) {
      const copyBtn = e.target.closest('.btn-copy-link');
      if (copyBtn) {
        const url = copyBtn.getAttribute('data-url');
        if (url) {
          navigator.clipboard.writeText(url).then(() => {
            Toast.fire({ icon: 'success', title: 'Tautan disalin!' });
          }).catch(() => {
            Toast.fire({ icon: 'error', title: 'Gagal menyalin.' });
          });
        }
      }
    });

    // ── Unsaved Changes Guard ─────────────────────────────────────────────
    let isFormDirty = false;
    document.addEventListener('input', function(e) {
      if (e.target.closest('form') && !e.target.matches('input[name="s"], #local-search-input'))
        isFormDirty = true;
    });
    document.addEventListener('change', function(e) {
      if (e.target.closest('form') && !e.target.matches('input[name="s"], #local-search-input'))
        isFormDirty = true;
    });
    document.addEventListener('submit', function() { isFormDirty = false; });
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof $ !== 'undefined') {
        const sn = $('#content, .summernote');
        if (sn.length) sn.on('summernote.change', () => { isFormDirty = true; });
      }
    });
    document.addEventListener('click', function(e) {
      const link = e.target.closest('a');
      if (link && isFormDirty) {
        const href = link.getAttribute('href');
        if (href && href !== '#' && !href.startsWith('javascript:') && link.getAttribute('target') !== '_blank') {
          e.preventDefault();
          Swal.fire({
            title: 'Perubahan Belum Disimpan!',
            text: 'Informasi yang Anda ketik akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Tinggalkan',
            cancelButtonText: 'Tetap di Sini',
            reverseButtons: true,
            customClass: {
              confirmButton: 'admin-btn admin-btn-ghost mx-2',
              cancelButton: 'admin-btn admin-btn-primary mx-2'
            },
            buttonsStyling: false
          }).then((result) => {
            if (result.isConfirmed) {
              isFormDirty = false;
              window.location.href = href;
            }
          });
        }
      }
    });
    window.addEventListener('beforeunload', function(e) {
      if (isFormDirty) { e.preventDefault(); e.returnValue = ''; }
    });

    // ── Progress Loading Bar ──────────────────────────────────────────────
    const loadingBar = document.getElementById('page-loading-bar');
    if (loadingBar) {
      loadingBar.style.opacity = '1';
      loadingBar.style.width = '100%';
      setTimeout(() => {
        loadingBar.style.opacity = '0';
        setTimeout(() => { loadingBar.style.width = '0%'; }, 400);
      }, 400);
      document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && !isFormDirty) {
          const href = link.getAttribute('href');
          if (href && href !== '#' && !href.startsWith('javascript:') && link.getAttribute('target') !== '_blank') {
            loadingBar.style.opacity = '1';
            loadingBar.style.width = '70%';
          }
        }
      });
    }

    // ── Scroll to Top ─────────────────────────────────────────────────────
    const scrollBtn = document.getElementById('scroll-to-top');
    if (scrollBtn) {
      window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
          scrollBtn.style.opacity = '1';
          scrollBtn.style.visibility = 'visible';
        } else {
          scrollBtn.style.opacity = '0';
          scrollBtn.style.visibility = 'hidden';
        }
      });
      scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // ── Search Hotkey (/) ─────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
      const active = document.activeElement;
      if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable)) return;
      if (e.key === '/') {
        const input = document.querySelector('input[name="s"], #local-search-input');
        if (input) { e.preventDefault(); input.focus(); input.select(); }
      }
    });
  </script>

  @yield('admin_scripts')
</body>
</html>
