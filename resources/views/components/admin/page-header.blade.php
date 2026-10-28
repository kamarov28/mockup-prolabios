@props([
  'label' => '',
  'badge' => '',
  'title' => '',
  'description' => '',
  'subtitle' => '',
  'editing' => null,
  'backUrl' => null,
  'backText' => 'Kembali',
  'actionUrl' => null,
  'actionText' => null,
  'actionIcon' => 'plus'
])

@php
  $headerLabel = $label ?: $badge;
  $headerDesc = $description ?: $subtitle;
@endphp

<div {{ $attributes->merge(['class' => 'd-flex justify-content-between align-items-start mb-4 gap-3 flex-wrap admin-page-header-wrap']) }}>
  <div style="flex: 1 1 320px; min-width: 0; max-width: 720px;">
    @if($headerLabel)
      <span class="admin-page-label">{{ $headerLabel }}</span>
    @endif
    <h1 class="admin-page-title mb-1">{!! $title !!}</h1>
    @if($editing)
      <div class="admin-page-desc">
        Mengedit: <strong style="color: var(--color-text-main);">{{ $editing }}</strong>
      </div>
    @elseif($headerDesc)
      <p class="admin-page-desc">
        {!! $headerDesc !!}
      </p>
    @elseif($slot->isNotEmpty())
      <div class="admin-page-desc">
        {{ $slot }}
      </div>
    @endif
  </div>

  <div class="d-flex align-items-center gap-2 flex-wrap ms-sm-auto flex-shrink-0">
    @if(isset($actions))
      {{ $actions }}
    @else
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
    @endif
  </div>
</div>
