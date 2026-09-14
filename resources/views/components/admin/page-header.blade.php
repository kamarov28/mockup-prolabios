@props([
  'label' => '',
  'title' => '',
  'description' => '',
  'backUrl' => null,
  'backText' => 'Kembali',
  'actionUrl' => null,
  'actionText' => null,
  'actionIcon' => 'plus'
])

<div {{ $attributes->merge(['class' => 'd-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap']) }}>
  <div>
    @if($label)
      <span class="admin-page-label">{{ $label }}</span>
    @endif
    <h2 class="admin-page-title mb-1">{{ $title }}</h2>
    @if($description || $slot->isNotEmpty())
      <div style="color: var(--color-text-muted); font-size: 0.88rem; margin: 0;">
        @if($description)
          {!! $description !!}
        @else
          {{ $slot }}
        @endif
      </div>
    @endif
  </div>

  <div class="d-flex align-items-center gap-2">
    @if($backUrl)
      <a href="{{ $backUrl }}" class="admin-btn admin-btn-outline">
        <i data-lucide="arrow-left"></i> {{ $backText }}
      </a>
    @endif

    @if($actionUrl && $actionText)
      <a href="{{ $actionUrl }}" class="admin-btn admin-btn-primary">
        <i data-lucide="{{ $actionIcon }}"></i> {{ $actionText }}
      </a>
    @endif
  </div>
</div>
