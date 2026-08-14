@extends('admin.layout')

@php 
  $isEdit = isset($post);
  $titleText = $isEdit ? 'Edit Artikel: ' . $post['title'] : 'Tulis Artikel Baru';
  $actionUrl = $isEdit ? route('admin.posts.update', ['slug' => $post['slug']]) : route('admin.posts.store');
@endphp

@section('title', $titleText)
@section('page_title', $titleText)

@section('admin_content')
<div class="card bg-white shadow-sm max-w-4xl mx-auto">
  <div class="card-header">
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-newspaper text-success me-2"></i>Formulir Data Artikel</h5>
  </div>
  
  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
    @csrf

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-3">
      <!-- Title -->
      <div class="col-md-8">
        <label for="title" class="form-label fw-bold">Judul Artikel <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post['title'] ?? '') }}" required placeholder="Contoh: Seminar LabIndonesia - Pengujian Endotoxin Bakteri">
        <div class="form-text small mt-1 text-muted">
          Preview link detail: <span class="text-success">{{ url('/informasi') }}?detail=<strong id="slug-preview">{{ isset($post) ? $post['slug'] : 'judul-artikel' }}</strong></span>
        </div>
      </div>

      <!-- Category -->
      <div class="col-md-6">
        <label for="category" class="form-label fw-bold">Kategori Artikel <span class="text-danger">*</span></label>
        <select class="form-select" id="category" name="category" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="Berita" {{ old('category', $post['category'] ?? '') === 'Berita' ? 'selected' : '' }}>Berita</option>
          <option value="Event" {{ old('category', $post['category'] ?? '') === 'Event' ? 'selected' : '' }}>Event</option>
          <option value="Info Terkait" {{ old('category', $post['category'] ?? '') === 'Info Terkait' || old('category', $post['category'] ?? '') === 'Info' ? 'selected' : '' }}>Info Terkait</option>
          <option value="IPTEK" {{ old('category', $post['category'] ?? '') === 'IPTEK' ? 'selected' : '' }}>IPTEK</option>
          <option value="Kegiatan" {{ old('category', $post['category'] ?? '') === 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
          <option value="Berita & Kegiatan" {{ old('category', $post['category'] ?? '') === 'Berita & Kegiatan' ? 'selected' : '' }}>Berita &amp; Kegiatan</option>
        </select>
      </div>

      <!-- Status & Highlight Radio Group -->
      <div class="col-12 mt-4 pt-3 border-top border-secondary border-opacity-10">
        <div class="row g-4">
          <!-- Status -->
          @php
            $currentStatus = $post['status'] ?? 'online';
            $currentDate = !empty($post['date']) ? date('Y-m-d', strtotime($post['date'])) : '';
            $today = date('Y-m-d');
            $isScheduled = ($currentStatus === 'online' && !empty($currentDate) && $currentDate > $today);
            $defaultOption = $currentStatus === 'draft' ? 'draft' : ($isScheduled ? 'scheduled' : 'online_now');
            $selectedOption = old('status_option', $defaultOption);
            $savedDate = old('publish_date', $currentDate ?: $today);
          @endphp
          <div class="col-md-6">
            <label class="form-label fw-bold d-block">Status Artikel</label>
            <div class="d-flex flex-column gap-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_option" id="statusDraft" value="draft" {{ $selectedOption === 'draft' ? 'checked' : '' }}>
                <label class="form-check-label" for="statusDraft">Draft (Simpan sebagai draf)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_option" id="statusOnlineNow" value="online_now" {{ $selectedOption === 'online_now' ? 'checked' : '' }}>
                <label class="form-check-label" for="statusOnlineNow">Online Now (Terbitkan Sekarang)</label>
              </div>
              <div class="form-check d-flex align-items-center gap-2">
                <input class="form-check-input" type="radio" name="status_option" id="statusScheduled" value="scheduled" {{ $selectedOption === 'scheduled' ? 'checked' : '' }}>
                <label class="form-check-label me-1" for="statusScheduled">Online pada tanggal:</label>
                <input type="date" name="publish_date" id="publish_date_input" class="form-control form-control-sm" style="width: 160px;" value="{{ $savedDate }}">
              </div>
            </div>
          </div>

          <!-- Highlight / Show on Frontpage -->
          <div class="col-md-6">
            <label class="form-label fw-bold d-block">Highlight (Beranda)</label>
            <div class="d-flex align-items-center gap-4 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="highlight" id="highlightNo" value="0" {{ old('highlight', ($post['is_featured'] ?? false) ? '1' : '0') === '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="highlightNo">No (Biasa)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="highlight" id="highlightYes" value="1" {{ old('highlight', ($post['is_featured'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
                <label class="form-check-label text-warning fw-semibold" for="highlightYes">
                  <i class="bi bi-star-fill me-1"></i> Show on Frontpage
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Image Area -->
      <div class="col-12 mt-4">
        <label class="form-label fw-bold">Gambar Cover Artikel</label>
        <div class="row g-3">
          <div class="col-sm-4 text-center">
            <div class="border rounded bg-light overflow-hidden mx-auto d-flex align-items-center justify-content-center" style="width: 100%; aspect-ratio: 16/9; max-height: 120px;">
              <img id="image-preview" src="{{ $post['image'] ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80' }}" alt="Preview" class="w-100 h-100" style="object-fit: cover;">
            </div>
          </div>
          <div class="col-sm-8">
            <div class="mb-3">
              <label for="image_file" class="form-label small fw-bold">Upload Cover Baru</label>
              <input class="form-control" type="file" id="image_file" name="image_file" accept="image/*" onchange="previewLocalImage(this)">
            </div>
            <div>
              <label for="image_url" class="form-label small fw-bold">Atau URL Gambar Cover</label>
              <input type="text" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $post['image'] ?? '') }}" placeholder="https://example.com/image.jpg" oninput="previewUrlImage(this.value)">
            </div>
          </div>
        </div>
      </div>

      <!-- Konten Artikel -->
      <div class="col-12 mt-4">
        <label for="content" class="form-label fw-bold">Isi Artikel / Berita</label>
        <textarea class="form-control" id="content" name="content" rows="10" placeholder="Tulis isi artikel lengkap...">{{ old('content', $post['content'] ?? '') }}</textarea>
      </div>
    </div>

    <!-- Buttons -->
    <div class="mt-4 border-top pt-3 d-flex justify-content-between">
      <a href="{{ route('admin.posts') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Batal</a>
      <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-lg me-1"></i> Simpan Artikel</button>
    </div>
  </form>
</div>
@endsection

@section('admin_styles')
  <!-- Summernote Lite Original CDN CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
  @vite(['resources/css/summernote-dark.css'])
  <style>
    /* ── File Input (Browse button) Dark Mode ───────────────────────────────── */
    input[type="file"].form-control {
      color: rgba(255,255,255,0.75) !important;
      background-color: var(--color-surface) !important;
      border: 1px solid var(--color-border) !important;
      padding: 0 !important;
      overflow: hidden;
    }
    input[type="file"].form-control::file-selector-button {
      background-color: #2a2a2e !important;
      color: rgba(255,255,255,0.85) !important;
      border: none !important;
      border-right: 1px solid var(--color-border) !important;
      padding: 0.375rem 0.85rem !important;
      margin-right: 0.75rem !important;
      cursor: pointer;
      transition: background 0.2s ease;
      font-family: var(--font-body);
      font-size: 0.875rem;
    }
    input[type="file"].form-control::file-selector-button:hover {
      background-color: rgba(255,73,80,0.15) !important;
      color: var(--color-accent) !important;
    }
  </style>
@endsection

@section('admin_scripts')
  <!-- jQuery & Summernote Lite JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <script>
    // 1. Image Preview Functions
    function previewLocalImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          var preview = document.getElementById('image-preview');
          if (preview) {
            preview.src = e.target.result;
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function previewUrlImage(url) {
      if (url && url.trim() !== '') {
        var preview = document.getElementById('image-preview');
        if (preview) {
          preview.src = url.trim();
        }
      }
    }

    // Initialize Summernote & UI events
    $(document).ready(function() {
      $('#content').summernote({
        placeholder: 'Tulis konten artikel lengkap di sini (mendukung gambar, tabel, link, formatting, dll)...',
        tabsize: 2,
        height: 350,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

      // Scheduled input auto select radio
      const scheduledDateInput = document.getElementById('publish_date_input');
      const statusScheduledRadio = document.getElementById('statusScheduled');
      if (scheduledDateInput && statusScheduledRadio) {
        scheduledDateInput.addEventListener('focus', function() {
          statusScheduledRadio.checked = true;
        });
        scheduledDateInput.addEventListener('change', function() {
          statusScheduledRadio.checked = true;
        });
      }

      // Real-time slug generator
      const titleInput = document.getElementById('title');
      const slugPreview = document.getElementById('slug-preview');
      if (titleInput && slugPreview) {
        titleInput.addEventListener('input', function() {
          const title = this.value;
          const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
          slugPreview.textContent = slug || 'judul-artikel';
        });
      }

      // Real-time localStorage auto-save
      const form = document.querySelector('form');
      const categorySelect = document.getElementById('category');
      const DRAFT_KEY = 'prolabios_post_draft';

      // Restore Draft logic (only on create mode)
      const isEdit = {{ isset($post) ? 'true' : 'false' }};
      if (!isEdit) {
        const savedDraft = localStorage.getItem(DRAFT_KEY);
        if (savedDraft) {
          try {
            const draft = JSON.parse(savedDraft);
            if (draft && (draft.title || draft.category || draft.content)) {
              const restoreConfirm = confirm('Draf tulisan sebelumnya ditemukan. Apakah Anda ingin memulihkan draf tersebut?');
              if (restoreConfirm) {
                if (draft.title && titleInput) {
                  titleInput.value = draft.title;
                  titleInput.dispatchEvent(new Event('input'));
                }
                if (draft.category && categorySelect) categorySelect.value = draft.category;
                if (draft.content) {
                  $('#content').summernote('code', draft.content);
                }
              } else {
                localStorage.removeItem(DRAFT_KEY);
              }
            }
          } catch (e) {
            console.error('Error parsing draft:', e);
          }
        }

        setInterval(function() {
          const currentTitle = titleInput ? titleInput.value : '';
          const currentCategory = categorySelect ? categorySelect.value : '';
          const currentContent = $('#content').summernote('code');

          if (currentTitle || currentCategory || (currentContent && currentContent !== '<p><br></p>')) {
            const draft = {
              title: currentTitle,
              category: currentCategory,
              content: currentContent
            };
            localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
          }
        }, 5000);
      }

      // Clear draft on submit
      if (form) {
        form.addEventListener('submit', function() {
          localStorage.removeItem(DRAFT_KEY);
        });
      }
    });
  </script>
@endsection
