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
  @if(!empty($value) && $clearUrl)
    <a href="{{ $clearUrl }}" class="admin-search-clear" title="Hapus pencarian">
      <i data-lucide="x" style="width: 14px; height: 14px;"></i>
    </a>
  @endif
</div>
