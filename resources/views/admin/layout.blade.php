<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel') | PROLABIOS</title>
  <meta name="description" content="Dashboard portal admin untuk mengelola katalog produk, artikel, dan sektor industri PT Prolabios Mitra Analitika.">
  
  <!-- Font & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    :root {
      --color-primary: #D32F2F;
      --color-secondary: #8B1A1A;
      --admin-sidebar-bg: #0f172a; /* Premium Dark Navy */
      --admin-sidebar-hover: #1e293b;
      --admin-sidebar-active: #D32F2F;
      --admin-bg: #f8fafc; /* Premium Light Gray */
      --admin-border: #e2e8f0;
    }
    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--admin-bg);
      color: #334155;
      overflow-x: hidden;
    }
    /* Layout Structure */
    #admin-wrapper {
      display: flex;
      min-height: 100vh;
    }
    #admin-sidebar {
      width: 260px;
      background-color: var(--admin-sidebar-bg);
      color: #94a3b8;
      transition: all 0.3s ease;
      flex-shrink: 0;
      z-index: 100;
      border-right: 1px solid rgba(255, 255, 255, 0.05);
    }
    #admin-content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    /* Sidebar Navigation */
    .sidebar-brand {
      padding: 1.75rem 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
    }
    .sidebar-brand img {
      max-width: 100%;
      height: auto;
      max-height: 38px;
      filter: brightness(0) invert(1);
    }
    .sidebar-menu {
      padding: 1.5rem 0;
    }
    .sidebar-item {
      padding: 0.25rem 1rem;
    }
    .sidebar-link {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      color: #94a3b8;
      text-decoration: none;
      border-radius: 0.5rem;
      font-weight: 500;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }
    .sidebar-link i {
      font-size: 1.2rem;
      margin-right: 0.75rem;
    }
    .sidebar-link:hover {
      background-color: var(--admin-sidebar-hover);
      color: #ffffff;
    }
    .sidebar-item.active .sidebar-link {
      background-color: var(--admin-sidebar-hover);
      color: #ffffff;
      border-left-color: var(--admin-sidebar-active);
      font-weight: 600;
    }
    /* Top Header */
    .admin-header {
      background-color: #ffffff;
      border-bottom: 1px solid var(--admin-border);
      padding: 1.25rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .admin-body {
      padding: 2rem;
      flex-grow: 1;
    }
    /* Card Premium Styling */
    .card {
      border: 1px solid var(--admin-border);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01) !important;
      border-radius: 0.75rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
    }
    .card-header {
      background-color: #ffffff;
      border-bottom: 1px solid var(--admin-border);
      font-weight: 700;
      padding: 1.25rem 1.5rem;
      color: #1e293b;
    }
    /* Table Premium Styling */
    .table thead th {
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      color: #475569;
      background-color: #f8fafc !important;
      border-bottom: 1px solid var(--admin-border);
      padding: 1rem 1rem;
    }
    .table tbody td {
      border-bottom: 1px solid var(--admin-border);
      padding: 1rem 1rem;
      vertical-align: middle;
      color: #334155;
    }
    .table tbody tr:hover td {
      background-color: #f8fafc !important;
    }
    /* Accessibility Contrast Fixes */
    .sidebar-link.text-danger {
      color: #f87171 !important;
    }
    .sidebar-link.text-danger:hover {
      background-color: rgba(248, 113, 113, 0.1);
      color: #fca5a5 !important;
    }
    .text-info {
      color: #0d9488 !important; /* Premium teal */
    }
    .text-success, .badge.text-success {
      color: #059669 !important; /* Premium emerald */
    }
    .text-secondary, .badge.text-secondary {
      color: #475569 !important;
    }
    /* Buttons Customization */
    .btn-primary {
      background-color: var(--color-primary);
      border-color: var(--color-primary);
      box-shadow: 0 2px 4px rgba(211, 47, 47, 0.2);
    }
    .btn-primary:hover {
      background-color: var(--color-secondary);
      border-color: var(--color-secondary);
    }
    /* Mobile Responsive Sidebar Adjustments */
    @media (max-width: 991px) {
      #admin-wrapper {
        flex-direction: column;
      }
      #admin-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      }
      .sidebar-menu {
        display: flex;
        flex-wrap: wrap;
        padding: 0.5rem 1rem;
        gap: 0.25rem;
      }
      .sidebar-item {
        padding: 0;
      }
      .sidebar-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
      }
      .sidebar-brand {
        padding: 1rem;
        flex-direction: row !important;
        justify-content: space-between;
        align-items: center;
      }
      .sidebar-brand img {
        max-height: 30px;
      }
      .admin-header {
        padding: 1rem;
      }
      .admin-header h1 {
        font-size: 1.2rem;
      }
      .admin-header .d-flex {
        display: none !important;
      }
      .admin-body {
        padding: 1rem;
      }
      .sidebar-menu .mt-5 {
        margin-top: 0 !important;
        border-top: none !important;
        padding-top: 0 !important;
      }
    }
    /* Scroll to Top Button Styling */
    .btn-scroll-to-top {
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-scroll-to-top:hover {
      background-color: var(--color-secondary) !important;
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23) !important;
    }
    .btn-scroll-to-top:active {
      transform: translateY(-1px) scale(1);
    }
    
    /* Custom Scrollbar for Sidebar */
    #admin-sidebar::-webkit-scrollbar {
      width: 5px;
    }
    #admin-sidebar::-webkit-scrollbar-track {
      background: var(--admin-sidebar-bg);
    }
    #admin-sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
    }
  </style>
  @yield('admin_styles')
</head>
<body>
  <!-- Top Progress Loading Bar -->
  <div id="page-loading-bar" style="position: fixed; top: 0; left: 0; width: 0%; height: 3px; background-color: var(--color-primary, #D32F2F); z-index: 9999; transition: width 0.4s ease, opacity 0.4s ease; box-shadow: 0 0 10px rgba(211, 47, 47, 0.7); opacity: 0; pointer-events: none;"></div>

  <div id="admin-wrapper">
    <!-- Sidebar -->
    <aside id="admin-sidebar">
      <div class="sidebar-brand">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/logo-prolabios.png') }}" alt="Prolabios Logo">
        </a>
        <span class="badge bg-danger ms-2 px-2 py-1 text-uppercase small" style="font-size: 0.65rem;">Admin</span>
      </div>
      
      <div class="sidebar-menu">
        <div class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </div>

        <div class="sidebar-item {{ request()->routeIs('admin.home.edit') ? 'active' : '' }}">
          <a href="{{ route('admin.home.edit') }}" class="sidebar-link">
            <i class="bi bi-gear-fill"></i> Pengaturan Web
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/products*') ? 'active' : '' }}">
          <a href="{{ route('admin.products') }}" class="sidebar-link">
            <i class="bi bi-box-seam"></i> Produk
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
          <a href="{{ route('admin.posts') }}" class="sidebar-link">
            <i class="bi bi-newspaper"></i> Artikel (Info)
          </a>
        </div>

        <div class="sidebar-item {{ request()->is('admin/sectors*') ? 'active' : '' }}">
          <a href="{{ route('admin.sectors') }}" class="sidebar-link">
            <i class="bi bi-collection"></i> Sektor Industri
          </a>
        </div>
        
        <div class="mt-5 border-top border-secondary pt-3 sidebar-item">
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link bg-transparent border-0 w-100 text-start text-danger">
              <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
          </form>
        </div>
      </div>
    </aside>

    <!-- Main Content wrapper -->
    <div id="admin-content" class="d-flex flex-column">
      
      <!-- Header -->
      <header class="admin-header shadow-sm">
        <h1 class="h4 mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h1>
        <div class="d-flex align-items-center">
          <span class="me-2 small text-muted">Masuk sebagai</span>
          <span class="badge bg-light text-dark border fw-semibold px-3 py-2"><i class="bi bi-person-fill text-danger me-1"></i> Administrator</span>
          <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-3"><i class="bi bi-box-arrow-up-right me-1"></i> Lihat Web</a>
        </div>
      </header>

       <!-- Page Body Content -->
      <main class="admin-body">
        @yield('admin_content')
      </main>

    </div>
  </div>

  <!-- Bootstrap 5 Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Global QOL Hotkey for Search Focus -->
  <script>
    document.addEventListener('keydown', function(e) {
      const active = document.activeElement;
      if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable)) {
        return;
      }
      if (e.key === '/') {
        const searchInput = document.querySelector('input[name="s"], #local-search-input');
        if (searchInput) {
          e.preventDefault();
          searchInput.focus();
          searchInput.select();
        }
      }
    });

    // Global SweetAlert2 Flash Alerts and Toast
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    @if(session('success'))
      Toast.fire({
        icon: 'success',
        title: {!! json_encode(session('success')) !!}
      });
    @endif

    @if(session('error'))
      Toast.fire({
        icon: 'error',
        title: {!! json_encode(session('error')) !!}
      });
    @endif

    // Global Interceptor for Delete Confirmation
    document.addEventListener('submit', function(e) {
      const form = e.target;
      const methodInput = form.querySelector('input[name="_method"]');
      if (methodInput && methodInput.value.toUpperCase() === 'DELETE') {
        e.preventDefault();
        
        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: 'Data yang dihapus tidak dapat dipulihkan!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          customClass: {
            confirmButton: 'btn btn-danger px-4 mx-2',
            cancelButton: 'btn btn-secondary px-4 mx-2'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      }
    });

    // Intercept logout form
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
      logoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Apakah Anda yakin ingin keluar?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Keluar!',
          cancelButtonText: 'Batal',
          customClass: {
            confirmButton: 'btn btn-danger px-4 mx-2',
            cancelButton: 'btn btn-secondary px-4 mx-2'
          },
          buttonsStyling: false
        }).then((result) => {
          if (result.isConfirmed) {
            logoutForm.submit();
          }
        });
      });
    }

    // Global "Copy Link to Clipboard" QOL Helper
    document.addEventListener('click', function(e) {
      const copyBtn = e.target.closest('.btn-copy-link');
      if (copyBtn) {
        const urlToCopy = copyBtn.getAttribute('data-url');
        if (urlToCopy) {
          navigator.clipboard.writeText(urlToCopy).then(() => {
            Toast.fire({
              icon: 'success',
              title: 'Tautan berhasil disalin ke clipboard!'
            });
          }).catch(err => {
            Toast.fire({
              icon: 'error',
              title: 'Gagal menyalin tautan.'
            });
          });
        }
      }
    });

    // Global Client-Side Table Filter (Instant Search)
    document.addEventListener('input', function(e) {
      if (e.target.matches('input[name="s"], #local-search-input')) {
        const query = e.target.value.toLowerCase().trim();
        const table = document.querySelector('table tbody');
        if (table) {
          const rows = table.querySelectorAll('tr');
          let hasVisibleRow = false;
          
          rows.forEach(row => {
            // Exclude headers or helper rows if any
            if (row.classList.contains('no-filter')) return;

            const cells = row.querySelectorAll('td');
            let match = false;
            cells.forEach(cell => {
              // Search in text content of cells (ignoring links/buttons)
              if (cell.textContent.toLowerCase().includes(query)) {
                match = true;
              }
            });

            if (match || query === '') {
              row.style.setProperty('display', '', 'important');
              hasVisibleRow = true;
            } else {
              row.style.setProperty('display', 'none', 'important');
            }
          });

          // Show empty row message if no rows match the query
          let emptyRow = table.querySelector('.empty-search-row');
          if (!hasVisibleRow && query !== '') {
            if (!emptyRow) {
              const colCount = table.closest('table').querySelectorAll('thead th').length || 5;
              emptyRow = document.createElement('tr');
              emptyRow.className = 'empty-search-row no-filter';
              emptyRow.innerHTML = `<td colspan="${colCount}" class="text-center text-muted py-4"><i class="bi bi-search me-2"></i>Tidak ada data yang cocok dengan "${e.target.value}"</td>`;
              table.appendChild(emptyRow);
            }
          } else {
            if (emptyRow) {
              emptyRow.remove();
            }
          }
        }
      }
    });

    // Prevent search forms from reloading page on Enter
    document.addEventListener('submit', function(e) {
      const form = e.target;
      const searchInput = form.querySelector('input[name="s"]');
      if (searchInput) {
        e.preventDefault();
      }
    });

    // Unsaved Changes Protection (Confirm Navigation)
    let isFormDirty = false;

    // Monitor standard form changes
    document.addEventListener('input', function(e) {
      if (e.target.closest('form') && !e.target.matches('input[name="s"], #local-search-input')) {
        isFormDirty = true;
      }
    });
    document.addEventListener('change', function(e) {
      if (e.target.closest('form') && !e.target.matches('input[name="s"], #local-search-input')) {
        isFormDirty = true;
      }
    });

    // Clear warning state on submission
    document.addEventListener('submit', function(e) {
      isFormDirty = false;
    });

    // Bind change listener for Summernote safely
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof $ !== 'undefined') {
        const summernoteEl = $('#content, .summernote');
        if (summernoteEl.length) {
          summernoteEl.on('summernote.change', function() {
            isFormDirty = true;
          });
        }
      }
    });

    // Intercept navigation links
    document.addEventListener('click', function(e) {
      const link = e.target.closest('a');
      if (link && isFormDirty) {
        const href = link.getAttribute('href');
        // Ignore hash, javascript, empty, and target target="_blank" links
        if (href && href !== '#' && !href.startsWith('javascript:') && link.getAttribute('target') !== '_blank') {
          e.preventDefault();
          
          Swal.fire({
            title: 'Perubahan Belum Disimpan!',
            text: 'Informasi yang Anda ketik akan hilang jika meninggalkan halaman ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tinggalkan',
            cancelButtonText: 'Tetap di Sini',
            customClass: {
              confirmButton: 'btn btn-primary px-4 mx-2',
              cancelButton: 'btn btn-danger px-4 mx-2'
            },
            buttonsStyling: false
          }).then((result) => {
            if (result.isConfirmed) {
              isFormDirty = false;
              if (loadingBar) {
                loadingBar.style.opacity = '1';
                loadingBar.style.width = '70%';
              }
              window.location.href = href;
            }
          });
        }
      }
    });

    // Browser close or reload alert
    window.addEventListener('beforeunload', function(e) {
      if (isFormDirty) {
        e.preventDefault();
        e.returnValue = ''; // Standard trigger for modern browsers
      }
    });

    // Scroll to Top logic
    const scrollToTopBtn = document.getElementById('scroll-to-top');
    if (scrollToTopBtn) {
      window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
          scrollToTopBtn.style.opacity = '1';
          scrollToTopBtn.style.visibility = 'visible';
        } else {
          scrollToTopBtn.style.opacity = '0';
          scrollToTopBtn.style.visibility = 'hidden';
        }
      });

      scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }

    // Custom Slim Progress Loading Bar Ala YouTube/GitHub
    const loadingBar = document.getElementById('page-loading-bar');
    if (loadingBar) {
      // 1. Trigger transition finish on load
      loadingBar.style.opacity = '1';
      loadingBar.style.width = '100%';
      setTimeout(() => {
        loadingBar.style.opacity = '0';
        setTimeout(() => {
          loadingBar.style.width = '0%';
        }, 400);
      }, 500);

      // 2. Intercept click events on links to show instant transition progress
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
  </script>

  <!-- Scroll to Top Float Button -->
  <button type="button" id="scroll-to-top" class="btn btn-danger btn-scroll-to-top shadow-lg rounded-circle" style="position: fixed; bottom: 30px; right: 30px; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; z-index: 1050; opacity: 0; visibility: hidden; transition: all 0.3s ease; border: none; background-color: var(--color-primary, #D32F2F); color: #ffffff;">
    <i class="bi bi-arrow-up-short" style="font-size: 1.75rem; line-height: 1;"></i>
  </button>

  @yield('admin_scripts')
</body>
</html>
