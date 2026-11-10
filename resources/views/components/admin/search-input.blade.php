@props([
  'name' => 's',
  'id' => null,
  'value' => '',
  'placeholder' => 'Cari data...',
  'clearUrl' => null,
  'autocomplete' => 'off',
])

@php
  $inputId = $id ?? ($name ? 'search-' . $name : 'admin-search-' . Str::random(4));
@endphp

<div {{ $attributes->merge(['class' => 'admin-search-wrapper']) }}>
  <i data-lucide="search" class="admin-search-icon"></i>
  <input
    type="search"
    @if($name) name="{{ $name }}" @endif
    id="{{ $inputId }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    autocomplete="{{ $autocomplete }}"
    class="admin-search-input"
    aria-label="{{ $placeholder }}"
  >
  <kbd class="admin-search-kbd d-none d-md-inline-flex" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); padding: 1px 6px; font-size: 0.68rem; font-family: var(--font-mono); color: #9CA3AF; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 4px; pointer-events: none; transition: opacity 0.15s ease;">/</kbd>
  @if(!empty($value) && $clearUrl)
    <a href="{{ $clearUrl }}" class="admin-search-clear" title="Hapus pencarian">
      <i data-lucide="x" style="width: 14px; height: 14px;"></i>
    </a>
  @endif
</div>
