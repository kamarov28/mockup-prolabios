@props([
  'title' => null,
  'icon' => null,
  'required' => false,
  'badge' => null,
  'description' => null
])

<div {{ $attributes->merge(['class' => 'p-3']) }} style="border: 1px solid var(--color-border); border-radius: 8px; background: var(--color-surface-subtle);">
  @if($title)
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
      <label class="admin-form-label mb-0">
        @if($icon)
          <i data-lucide="{{ $icon }}" class="me-1" style="color: var(--color-accent);"></i>
        @endif
        {{ $title }}
        @if($required)
          <span style="color: var(--color-accent);">*</span>
        @endif
      </label>
      @if($badge)
        <span class="admin-badge admin-badge-accent">{{ $badge }}</span>
      @endif
    </div>
  @endif

  {{ $slot }}

  @if($description)
    <p class="form-text mb-0 mt-2">{{ $description }}</p>
  @endif
</div>
