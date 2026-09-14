@props([
  'id' => 'dropzone',
  'name' => null,
  'title' => '+ Klik di sini untuk menambah file',
  'subtitle' => 'Dapat memilih beberapa file sekaligus (Ctrl/Shift) atau menambah file satu per satu.',
  'icon' => 'cloud-upload',
  'accept' => 'image/*',
  'multiple' => true
])

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'p-4 mb-3 d-flex flex-column align-items-center justify-content-center text-center']) }} style="border: 1.5px dashed var(--color-border); border-radius: 10px; background: var(--color-surface-subtle); cursor: pointer; transition: all 0.15s ease;">
  <i data-lucide="{{ $icon }}" class="fs-2 mb-2 d-inline-block mx-auto" style="color: var(--color-accent, #A6171C); width: 36px; height: 36px;"></i>
  <span class="fw-bold d-block" style="color: var(--color-text-main); font-size: 0.92rem;">
    {{ $title }}
  </span>
  @if($subtitle)
    <span class="small text-muted d-block mt-1" style="max-width: 520px;">
      {{ $subtitle }}
    </span>
  @endif
</div>

@if($name)
  <input type="file" id="{{ $id }}_input" name="{{ $name }}" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }} style="display: none;">
@endif
