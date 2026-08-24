<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel') | Prolabios</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/css/admin.css'])
  @yield('admin_styles')
  <style>
    #page-loading-bar {
      position: fixed; top: 0; left: 0; height: 2px; width: 0%;
      background: var(--color-accent, #ff4950); z-index: 9999;
      transition: width 0.3s ease, opacity 0.4s ease; opacity: 0; pointer-events: none;
    }
    #scroll-to-top {
      position: fixed; bottom: 24px; right: 24px; width: 40px; height: 40px;
      border-radius: 50%; border: 1px solid var(--color-border, #333);
      background: #121214; color: #fff; display: flex; align-items: center;
      justify-content: center; cursor: pointer; opacity: 0; visibility: hidden;
      transition: opacity 0.25s ease, visibility 0.25s ease; z-index: 50;
    }
  </style>
</head>
<body class="admin-body">
<div id="page-loading-bar"></div>

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand px-3 py-3">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
      <span class="brand-mark">PRO</span>
      <span class="brand-text d-none d-lg-inline">PT.Prolabios Mitra Analitika</span>
    </a>
  </div>
  <nav class="sidebar-nav flex-grow-1">
    <div class="sidebar-section-label">Panel</div>
    <div class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/rfq*') ? 'active' : '' }}">
      <a href="{{ route('admin.rfqs') }}" class="sidebar-link"><i class="bi bi-clipboard-check"></i><span>Pengajuan RFQ</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/settings*') || request()->is('admin/homepage*') || request()->is('admin/seo*') || request()->is('admin/contact*') || request()->is('admin/banner*') ? 'active' : '' }}">
      <a href="{{ url('/admin/settings') }}" class="sidebar-link"><i class="bi bi-sliders"></i><span>Pengaturan Web</span></a>
    </div>

    <div class="sidebar-section-label">Konten</div>
    <div class="sidebar-item {{ request()->is('admin/products*') ? 'active' : '' }}">
      <a href="{{ route('admin.products') }}" class="sidebar-link"><i class="bi bi-box-seam"></i><span>Produk</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
      <a href="{{ route('admin.categories.index') }}" class="sidebar-link"><i class="bi bi-diagram-3"></i><span>Kategori Produk</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/principals*') ? 'active' : '' }}">
      <a href="{{ route('admin.principals') }}" class="sidebar-link"><i class="bi bi-award"></i><span>Prinsipal / Mitra</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
      <a href="{{ route('admin.posts') }}" class="sidebar-link"><i class="bi bi-file-text"></i><span>Artikel</span></a>
    </div>
    <div class="sidebar-item {{ request()->is('admin/sectors*') ? 'active' : '' }}">
      <a href="{{ route('admin.sectors') }}" class="sidebar-link"><i class="bi bi-building"></i><span>Sektor</span></a>
    </div>

    <div class="sidebar-section-label">Bantuan</div>
    <div class="sidebar-item {{ request()->is('admin/help*') ? 'active' : '' }}">
      <a href="{{ url('/admin/help') }}" class="sidebar-link"><i class="bi bi-book"></i><span>Panduan Admin</span></a>
    </div>
  </nav>

  <div class="sidebar-footer p-3">
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start p-0">
        <i class="bi bi-box-arrow-left"></i><span>Keluar</span>
      </button>
    </form>
  </div>
</aside>

<div class="admin-main">
  <header class="admin-topbar d-flex align-items-center justify-content-between px-3 px-lg-4">
    <div class="d-flex align-items-center gap-3">
      <button type="button" class="btn btn-link text-white d-lg-none p-0" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="bi bi-list fs-4"></i>
      </button>
      <span class="topbar-title text-uppercase small fw-semibold letter-spacing">@yield('page_title', 'Admin')</span>
    </div>
    <div class="d-flex align-items-center gap-2 gap-md-3">
      <span class="text-muted small d-none d-md-inline"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'Administrator' }}</span>
      <a href="{{ url('/') }}" target="_blank" class="admin-btn admin-btn-ghost admin-btn-sm">
        <i class="bi bi-box-arrow-up-right"></i> Lihat Web
      </a>
    </div>
  </header>

  <main class="admin-content p-3 p-lg-4">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('admin_content')
  </main>
</div>

<button type="button" id="scroll-to-top" aria-label="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2800,
    timerProgressBar: true,
    background: '#1a1a1e',
    color: '#fff',
  });

  @if(session('success'))
    Toast.fire({ icon: 'success', title: @json(session('success')) });
  @endif
  @if(session('error'))
    Toast.fire({ icon: 'error', title: @json(session('error')) });
  @endif

  document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('adminSidebar')?.classList.toggle('open');
  });

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-copy-link');
    if (btn) {
      const url = btn.getAttribute('data-url');
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
  // Only track POST/PUT forms (create/edit). GET filter forms must not trigger beforeunload.
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
  }
</script>
@yield('admin_scripts')
</body>
</html>
