@props([
  'icon' => 'inbox',
  'message' => 'Belum ada data.',
  'actionUrl' => null,
  'actionLabel' => null,
  'actionClass' => 'admin-btn-primary',
  'actionIcon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }} style="color: var(--color-text-muted);">
  <i data-lucide="{{ $icon }}" style="font-size: 2.5rem; opacity: 0.3; display: block; margin-bottom: 16px;"></i>
  <p style="font-size: 0.88rem;">{{ $message }}</p>
  @if($actionUrl && $actionLabel)
    <a href="{{ $actionUrl }}" class="admin-btn {{ $actionClass }}">
      @if($actionIcon)
        <i data-lucide="{{ $actionIcon }}"></i>
      @endif
      {{ $actionLabel }}
    </a>
  @endif
  {{ $slot }}
</div>
