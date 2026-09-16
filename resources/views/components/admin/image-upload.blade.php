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
        <input class="form-control" type="file" id="{{ $nameFile }}" name="{{ $nameFile }}" accept="image/*" onchange="previewLocalImageComponent(this, '{{ $previewId }}')">
      </div>
      <div>
        <label for="{{ $nameUrl }}" class="admin-form-label">Atau Masukkan URL Gambar</label>
        <input type="text" class="form-control" id="{{ $nameUrl }}" name="{{ $nameUrl }}" value="{{ $valueUrl }}" placeholder="https://example.com/image.jpg" oninput="previewUrlImageComponent(this.value, '{{ $previewId }}', '{{ $placeholderImage }}')">
      </div>
    </div>
  </div>
</div>

@once
<script @nonce>
  function previewLocalImageComponent(input, previewId) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        var el = document.getElementById(previewId);
        if (el) el.src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewUrlImageComponent(val, previewId, fallback) {
    var el = document.getElementById(previewId);
    if (!el) return;
    var trimmed = val.trim();
    if (!trimmed) {
      el.src = fallback;
    } else {
      el.src = trimmed;
    }
  }
</script>
@endonce
