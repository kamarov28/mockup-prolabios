@props([
  'label' => 'Upload Gambar',
  'nameFile' => 'image_file',
  'nameUrl' => 'image_url',
  'valueUrl' => '',
  'previewId' => 'image-preview',
  'placeholderImage' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80',
  'helpText' => null,
  'aspectRatio' => '1/1',
  'boxWidth' => '120px',
  'boxHeight' => '120px'
])

@php
  $currentSrc = $valueUrl ?: $placeholderImage;
@endphp

<div {{ $attributes->merge(['class' => 'admin-form-group mb-0']) }}>
  @if($label)
    <label class="admin-form-label">{{ $label }}</label>
  @endif
  @if($helpText)
    <p class="form-text mb-3">{{ $helpText }}</p>
  @endif

  <div class="row g-3 align-items-center">
    <div class="col-sm-3 text-center">
      <div style="width: {{ $boxWidth }}; height: {{ $boxHeight }}; margin: 0 auto; border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-surface-subtle); display: flex; align-items: center; justify-content: center; overflow: hidden; aspect-ratio: {{ $aspectRatio }};">
        <img id="{{ $previewId }}" src="{{ $currentSrc }}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
      </div>
    </div>
    <div class="col-sm-9">
      <div class="mb-3">
        <label for="{{ $nameFile }}" class="admin-form-label">Upload File Baru</label>
        <input class="form-control" type="file" id="{{ $nameFile }}" name="{{ $nameFile }}" accept="image/*">
      </div>
      <div>
        <label for="{{ $nameUrl }}" class="admin-form-label">Atau Masukkan URL Gambar</label>
        <input type="text" class="form-control" id="{{ $nameUrl }}" name="{{ $nameUrl }}" value="{{ $valueUrl }}" placeholder="https://example.com/image.jpg">
      </div>
    </div>
  </div>
</div>

<script @nonce>
  (function() {
    function initImagePreview_{{ str_replace('-', '_', $previewId) }}() {
      var fileInput = document.getElementById('{{ $nameFile }}');
      var urlInput = document.getElementById('{{ $nameUrl }}');
      var previewEl = document.getElementById('{{ $previewId }}');
      var defaultSrc = '{{ $currentSrc }}';
      var fallbackSrc = '{{ $placeholderImage }}';

      if (fileInput && previewEl) {
        fileInput.addEventListener('change', function() {
          if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              previewEl.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
          } else if (urlInput && urlInput.value.trim() !== '') {
            previewEl.src = urlInput.value.trim();
          } else {
            previewEl.src = defaultSrc || fallbackSrc;
          }
        });
      }

      if (urlInput && previewEl) {
        urlInput.addEventListener('input', function() {
          var val = this.value.trim();
          if (val) {
            previewEl.src = val;
          } else if (fileInput && fileInput.files && fileInput.files[0]) {
            // Keep current local file preview if a file is already selected
          } else {
            previewEl.src = defaultSrc || fallbackSrc;
          }
        });
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initImagePreview_{{ str_replace('-', '_', $previewId) }});
    } else {
      initImagePreview_{{ str_replace('-', '_', $previewId) }}();
    }
  })();
</script>
