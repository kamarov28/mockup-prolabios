@props([
  'icon' => 'inbox',
  'message' => 'Belum ada data.',
  'actionUrl' => null,
  'actionLabel' => null,
  'actionClass' => 'admin-btn-primary',
  'actionIcon' => null,
])

<div {{ $attributes->merge(['class' => 'd-flex flex-column align-items-center justify-content-center text-center py-5']) }} style="color: var(--color-text-muted);">
  <div class="mb-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-surface-2, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0); margin: 0 auto;">
    <i data-lucide="{{ $icon }}" style="width: 28px; height: 28px; opacity: 0.45; stroke-width: 1.5; color: var(--color-text-muted);"></i>
  </div>
  <p class="mb-3" style="font-size: 0.88rem; max-width: 440px; margin-left: auto; margin-right: auto; line-height: 1.5;">{{ $message }}</p>
  @if($actionUrl && $actionLabel)
    <div>
      <a href="{{ $actionUrl }}" class="admin-btn {{ $actionClass }}">
        @if($actionIcon)
          <i data-lucide="{{ $actionIcon }}"></i>
        @endif
        {{ $actionLabel }}
      </a>
    </div>
  @endif
  {{ $slot }}
</div>
