<!-- 1. Hero Section (Clean, Quiet & Focused) -->
<section class="section-spacious typo-hero">
  @php
    $heroImages = array_values(array_filter($homeData['hero_images'] ?? []));
    if (count($heroImages) === 0) {
        $heroImages = [
            'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
        ];
    }
  @endphp

  <div class="typo-hero-bg">
    @foreach($heroImages as $index => $imgUrl)
      <img
        class="hero-bg-slide @if($index === 0) active @endif"
        src="{{ $imgUrl }}"
        alt="Prolabios Laboratory Equipment"
        decoding="async"
        @if($index === 0)
          fetchpriority="high"
        @else
          loading="lazy"
        @endif
      >
    @endforeach
    <canvas id="hero-kinetic-canvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; pointer-events: none; opacity: 0.6;"></canvas>
    <div class="typo-hero-overlay"></div>
  </div>

  <noscript>
    <style>.typo-hero-entrance { opacity: 1 !important; }</style>
  </noscript>

  <div class="container" style="position: relative; z-index: 2;">
    <div class="typo-hero-entrance col-lg-8 ps-0">
      <div class="mb-3">
        <span class="typo-pill-outline">{{ $homeData['hero_badge'] ?? 'PRECISION LABORATORY SOLUTIONS' }}</span>
      </div>
      <h1 class="typo-hero-title mb-3">
        {!! $homeData['hero_title'] ?? 'Uncompromised <span class="text-accent">Testing Accuracy</span> for Research &amp; Industry.' !!}
      </h1>
      <p class="typo-lead mb-4">
        {{ $homeData['hero_subtitle'] ?? 'Official provider of analytical instruments, culture media, and laboratory reagents meeting strict international quality standards.' }}
      </p>
      
      <!-- Focused Primary CTA -->
      <div class="d-flex flex-wrap gap-3 typo-hero-ctas align-items-center mt-3">
        <a href="{{ url($homeData['hero_cta_link'] ?? '/produk') }}" class="typo-btn-link">
          {{ $homeData['hero_cta_text'] ?? 'Explore Product Catalog' }} <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
    
    <!-- Sleek Hero Slider Counter & Progress Bar Controls -->
    @if(count($heroImages) > 1)
      <div class="typo-hero-controls">
        <div class="hero-counter-box me-1">
          <span id="hero-slide-current" class="hero-counter-num">01</span>
          <span class="hero-counter-sep">/</span>
          <span id="hero-slide-total" class="hero-counter-total">{{ count($heroImages) < 10 ? '0' . count($heroImages) : count($heroImages) }}</span>
        </div>
        
        <div class="hero-progress-bar-wrap mx-2">
          <div id="hero-progress-fill" class="hero-progress-fill"></div>
        </div>

        <div class="hero-arrow-btns d-flex align-items-center gap-2">
          <button id="hero-prev" class="typo-hero-ctrl-btn" aria-label="Previous Slide">
            <i class="bi bi-arrow-left"></i>
          </button>
          <button id="hero-next" class="typo-hero-ctrl-btn" aria-label="Next Slide">
            <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>
    @endif
  </div>
</section>
