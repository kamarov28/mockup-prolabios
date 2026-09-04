@extends('admin.layout')

@php
  $isEdit = isset($post);
  $titleText = $isEdit ? 'Edit Artikel' : 'Tulis Artikel';
  $actionUrl = $isEdit ? route('admin.posts.update', ['slug' => $post['slug']]) : route('admin.posts.store');

  // Normalize cover preview URL (support /storage/..., /uploads/..., full http)
  $rawImage = old('image_url', $post['image'] ?? '');
  if ($rawImage && !str_starts_with($rawImage, 'http') && !str_starts_with($rawImage, 'data:')) {
    $previewSrc = asset(ltrim($rawImage, '/'));
  } elseif ($rawImage) {
    $previewSrc = $rawImage;
  } else {
    $previewSrc = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80';
  }
@endphp

@section('title', $isEdit ? 'Edit Artikel: ' . $post['title'] : 'Tulis Artikel Baru')
@section('page_title', $titleText)

@section('admin_content')

<div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap">
  <div>
    <span class="admin-page-label">Konten</span>
    <h2 class="admin-page-title mb-1">{{ $titleText }}</h2>
    <p style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
      @if($isEdit)
        Mengedit: <strong style="color: var(--color-text-main);">{{ $post['title'] }}</strong>
      @else
        Tulis artikel / berita untuk halaman informasi publik.
      @endif
    </p>
  </div>
  <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-outline">
    <i class="bi bi-arrow-left"></i> Kembali
  </a>
</div>

<div class="admin-card" style="max-width: 900px;">
  <div class="admin-card-header">
    <div>
      <span class="admin-card-header-label">Formulir</span>
      <h3 class="admin-card-header-title mb-0">Data Artikel</h3>
    </div>
  </div>

  <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="admin-card-body">
    @csrf
    @if(!empty($isEdit)) @method('PUT') @endif

    @if ($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="d-flex flex-column gap-4">

      <div class="row g-3">
        <div class="col-md-8">
          <div class="admin-form-group mb-0">
            <label for="title" class="admin-form-label">Judul Artikel <span style="color: var(--color-accent);">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post['title'] ?? '') }}" required placeholder="Contoh: Seminar LabIndonesia - Pengujian Endotoxin Bakteri" autofocus>
            <p class="form-text mb-0 mt-2">
              Preview link:
              <span style="color: var(--color-accent);">{{ url('/informasi') }}?detail=<strong id="slug-preview">{{ isset($post) ? $post['slug'] : 'judul-artikel' }}</strong></span>
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="admin-form-group mb-0">
            <label for="category" class="admin-form-label">Kategori <span style="color: var(--color-accent);">*</span></label>
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
        </div>
      </div>

      <div class="pt-3" style="border-top: 1px solid var(--color-border);">
        <div class="row g-4">
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
            <label class="admin-form-label d-block">Status Artikel</label>
            <div class="d-flex flex-column gap-2 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_option" id="statusDraft" value="draft" {{ $selectedOption === 'draft' ? 'checked' : '' }}>
                <label class="form-check-label" for="statusDraft">Draft</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status_option" id="statusOnlineNow" value="online_now" {{ $selectedOption === 'online_now' ? 'checked' : '' }}>
                <label class="form-check-label" for="statusOnlineNow">Online sekarang</label>
              </div>
              <div class="form-check d-flex align-items-center gap-2 flex-wrap">
                <input class="form-check-input" type="radio" name="status_option" id="statusScheduled" value="scheduled" {{ $selectedOption === 'scheduled' ? 'checked' : '' }}>
                <label class="form-check-label me-1" for="statusScheduled">Online pada:</label>
                <input type="date" name="publish_date" id="publish_date_input" class="form-control form-control-sm" style="width: 160px;" value="{{ $savedDate }}">
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <label class="admin-form-label d-block">Highlight Beranda</label>
            <div class="d-flex align-items-center gap-4 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="highlight" id="highlightNo" value="0" {{ old('highlight', ($post['is_featured'] ?? false) ? '1' : '0') === '0' ? 'checked' : '' }}>
                <label class="form-check-label" for="highlightNo">Tidak</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="highlight" id="highlightYes" value="1" {{ old('highlight', ($post['is_featured'] ?? false) ? '1' : '0') === '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="highlightYes" style="color: #f59e0b;">
                  <i class="bi bi-star-fill me-1"></i> Tampil di beranda
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="admin-form-group mb-0 pt-3" style="border-top: 1px solid var(--color-border);">
        <label class="admin-form-label">Gambar Cover</label>
        <div class="row g-3 align-items-center">
          <div class="col-sm-4">
            <div style="width: 100%; aspect-ratio: 16/9; max-height: 140px; border: 1px solid var(--color-border); border-radius: 8px; overflow: hidden; background: rgba(255,255,255,0.03);">
              <img id="image-preview" src="{{ $previewSrc }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
          <div class="col-sm-8">
            <div class="mb-3">
              <label for="image_file" class="admin-form-label">Upload Cover</label>
              <input class="form-control" type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewLocalImage(this)">
              <p class="form-text mb-0 mt-1" style="font-size: 0.75rem; color: var(--color-text-muted);">JPG, PNG, WebP, GIF — maks 5MB. Disarankan rasio 16:9.</p>
            </div>
            <div>
              <label for="image_url" class="admin-form-label">Atau URL Cover</label>
              <input type="text" class="form-control" id="image_url" name="image_url" value="{{ old('image_url', $post['image'] ?? '') }}" placeholder="https://example.com/image.jpg atau /storage/uploads/..." oninput="previewUrlImage(this.value)">
            </div>
          </div>
        </div>
      </div>

      <div class="admin-form-group mb-0">
        <label for="content" class="admin-form-label">Isi Artikel</label>
        <textarea class="form-control" id="content" name="content" rows="10" placeholder="Tulis isi artikel lengkap...">{{ old('content', $post['content'] ?? '') }}</textarea>
      </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-5 pt-4" style="border-top: 1px solid var(--color-border);">
      <a href="{{ route('admin.posts') }}" class="admin-btn admin-btn-outline">
        <i class="bi bi-arrow-left"></i> Batal
      </a>
      <button type="submit" class="admin-btn admin-btn-primary">
        <i class="bi bi-check-lg"></i> Simpan Artikel
      </button>
    </div>
  </form>
</div>

@endsection

@section('admin_styles')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('admin_scripts')
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

  <script>
    function previewLocalImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          var preview = document.getElementById('image-preview');
          if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function previewUrlImage(url) {
      if (url && url.trim() !== '') {
        var preview = document.getElementById('image-preview');
        if (preview) preview.src = url.trim();
      }
    }

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

      const form = document.querySelector('form');
      const categorySelect = document.getElementById('category');
      const DRAFT_KEY = 'prolabios_post_draft';

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

      if (form) {
        form.addEventListener('submit', function() {
          localStorage.removeItem(DRAFT_KEY);
        });
      }
    });
  </script>
@endsection
