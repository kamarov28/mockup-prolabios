<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel') | PROLABIOS</title>
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  @vite(['resources/css/admin.css'])
  @yield('admin_styles')
</head>
<body>
  <div id="page-loading-bar" style="position: fixed; top: 0; left: 0; width: 0%; height: 2px; background: var(--color-accent, #FF4950); z-index: 9999; transition: width 0.4s ease, opacity 0.4s ease; opacity: 0; pointer-events: none;"></div>

  <div id="admin-wrapper">
    <aside id="admin-sidebar">
      <div class="sidebar-brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo">
        </a>
      </div>

      <div class="sidebar-menu">
        <span class="sidebar-nav-label">Panel</span>

        <div class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="bi bi-grid-1x2"></i> Dashboard
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/rfqs*') ? 'active' : '' }}">
          <a href="{{ route('admin.rfqs.index') }}" class="sidebar-link">
            <i class="bi bi-receipt"></i> Pengajuan RFQ
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

        <div class="sidebar-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
          <a href="{{ route('admin.categories.index') }}" class="sidebar-link">
            <i class="bi bi-diagram-3"></i> Kategori Produk
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/principals*') ? 'active' : '' }}">
          <a href="{{ route('admin.principals') }}" class="sidebar-link">
            <i class="bi bi-award"></i> Prinsipal / Mitra
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
          <a href="{{ route('admin.posts') }}" class="sidebar-link">
            <i class="bi bi-file-earmark-text"></i> Artikel
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/sectors*') ? 'active' : '' }}">
          <a href="{{ route('admin.sectors') }}" class="sidebar-link">
            <i class="bi bi-building"></i> Sektor
          </a>
        </div>

        <span class="sidebar-nav-label" style="margin-top: 8px;">Bantuan</span>

        <div class="sidebar-item {{ request()->is('admin/help*') ? 'active' : '' }}">
          <a href="{{ url('/admin/help') }}" class="sidebar-link">
            <i class="bi bi-book"></i> Panduan Admin
          </a>
        </div>
      </div>

      <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
          @csrf
          <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
            <i class="bi bi-box-arrow-left"></i> Keluar
          </button>
        </form>
      </div>
    </aside>

    <div id="admin-content">
      <header id="admin-topbar">
        <div class="topbar-left">
          <button type="button" id="sidebar-toggle" class="btn btn-link d-lg-none p-0 me-2" style="color:inherit;" aria-label="Toggle menu">
            <i class="bi bi-list fs-4"></i>
          </button>
          <span class="topbar-title">@yield('page_title', 'Admin')</span>
        </div>
        <div class="topbar-right d-flex align-items-center gap-3">
          <span class="topbar-user d-none d-md-inline"><i class="bi bi-person-circle"></i> {{ auth()->user()->name ?? 'admin' }}</span>
          <a href="{{ url('/') }}" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">
            <i class="bi bi-box-arrow-up-right"></i> Lihat Web
          </a>
        </div>
      </header>

      <main class="admin-page-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @yield('admin_content')
      </main>
    </div>
  </div>

  <button type="button" id="scroll-to-top" aria-label="Scroll to top" style="position:fixed;bottom:24px;right:24px;width:40px;height:40px;border-radius:50%;border:1px solid var(--color-border,#333);background:#121214;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0;visibility:hidden;z-index:50;transition:opacity .25s;">
    <i class="bi bi-arrow-up"></i>
  </button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false, timer: 2800, timerProgressBar: true,
      background: '#1a1a1e', color: '#fff'
    });
    @if(session('success')) Toast.fire({ icon: 'success', title: @json(session('success')) }); @endif
    @if(session('error')) Toast.fire({ icon: 'error', title: @json(session('error')) }); @endif

    document.getElementById('sidebar-toggle')?.addEventListener('click', function () {
      document.getElementById('admin-sidebar')?.classList.toggle('open');
      document.getElementById('admin-wrapper')?.classList.toggle('sidebar-open');
    });

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-copy-link');
      if (btn) {
        const url = btn.getAttribute('data-url');
        if (url) {
          navigator.clipboard.writeText(url).then(() => Toast.fire({ icon: 'success', title: 'Tautan disalin!' }))
            .catch(() => Toast.fire({ icon: 'error', title: 'Gagal menyalin.' }));
        }
      }
    });

    let isFormDirty = false;
    function isDataEntryForm(form) {
      if (!form) return false;
      const method = (form.getAttribute('method') || 'GET').toUpperCase();
      return method === 'POST' || method === 'PUT' || method === 'PATCH';
    }
    document.addEventListener('input', function(e) {
      const form = e.target.closest('form');
      if (isDataEntryForm(form) && !e.target.matches('input[name="s"], #local-search-input'))
        isFormDirty = true;
    });
    document.addEventListener('change', function(e) {
      const form = e.target.closest('form');
      if (isDataEntryForm(form) && !e.target.matches('input[name="s"], #local-search-input, select[name="category"], select[name="sector"], select[name="sort"]'))
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

    const loadingBar = document.getElementById('page-loading-bar');
    if (loadingBar) {
      loadingBar.style.opacity = '1';
      loadingBar.style.width = '100%';
      setTimeout(() => {
        loadingBar.style.opacity = '0';
        setTimeout(() => { loadingBar.style.width = '0%'; }, 400);
      }, 400);
    }

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
  </script>
  @yield('admin_scripts')
</body>
</html>
