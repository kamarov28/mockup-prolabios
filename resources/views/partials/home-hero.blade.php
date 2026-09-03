<!-- 1. Hero — Full-Screen Immersive Neo-Brutalism Mode -->
<section class="nb-hero nb-hero--immersive">
  @php
    $heroImages = array_values(array_filter($homeData['hero_images'] ?? []));
    if (count($heroImages) === 0) {
        $heroImages = [
            'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=85',
            'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=85',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=85',
            'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=85'
        ];
    }
  @endphp

  <!-- Fullscreen Immersive Background Slideshow -->
  <div class="nb-hero-backdrop">
    @foreach($heroImages as $index => $imgUrl)
      <img
        class="nb-hero-slide @if($index === 0) is-active @endif"
        src="{{ $imgUrl }}"
        alt="Laboratorium Prolabios"
        decoding="async"
        @if($index === 0) fetchpriority="high" @else loading="lazy" @endif
      >
    @endforeach
    <!-- Subtle Contrast Gradient Overlay for Perfect Readability -->
    <div class="nb-hero-backdrop-overlay"></div>
  </div>

  <div class="container position-relative h-100 d-flex flex-column justify-content-center">
    <div class="nb-hero-immersive-content">
      <!-- Floating Copy Card with Soft Neo-Brutalist Frame -->
      <div class="nb-hero-copy">
        <span class="nb-badge">{{ $homeData['hero_badge'] ?? 'PRECISION LABORATORY SOLUTIONS' }}</span>
        <h1 class="nb-hero-title">
          {!! $homeData['hero_title'] ?? 'Trusted Analytical & <span class="nb-accent">Microbiology</span> Solutions' !!}
        </h1>
        <p class="nb-hero-lead">
          {{ $homeData['hero_subtitle'] ?? 'We provide the highest-quality culture media, laboratory instruments, and testing equipment to support a wide range of industrial needs in Indonesia.' }}
        </p>
        <div class="nb-hero-actions">
          <a href="{{ url($homeData['hero_cta_link'] ?? '/produk') }}" class="nb-btn nb-btn-primary">
            {{ $homeData['hero_cta_text'] ?? 'Explore Product Catalog' }}
            <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-ghost">
            Hubungi Sales
          </a>
        </div>
      </div>
    </div>

    <!-- Immersive Slide Controls at Bottom Right -->
    @if(count($heroImages) > 1)
      <div class="nb-hero-controls nb-hero-controls--immersive">
        <span class="nb-hero-counter">
          <span id="hero-slide-current" class="nb-mono">01</span>
          <span class="nb-muted">/</span>
          <span id="hero-slide-total" class="nb-mono">{{ count($heroImages) < 10 ? '0'.count($heroImages) : count($heroImages) }}</span>
        </span>
        <div class="nb-hero-progress"><div id="hero-progress-fill" class="nb-hero-progress-fill"></div></div>
        <div class="nb-hero-arrows">
          <button type="button" id="hero-prev" class="nb-icon-btn" aria-label="Slide sebelumnya"><i class="bi bi-arrow-left"></i></button>
          <button type="button" id="hero-next" class="nb-icon-btn" aria-label="Slide berikutnya"><i class="bi bi-arrow-right"></i></button>
        </div>
      </div>
    @endif
  </div>
</section>
