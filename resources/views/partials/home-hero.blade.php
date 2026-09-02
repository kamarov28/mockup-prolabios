<!-- 1. Hero — Soft Neo-Brutalism split 50/50 boxes -->
<section class="nb-hero section-spacious">
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

  <div class="container">
    <div class="nb-hero-grid">
      <!-- Left: copy box -->
      <div class="nb-hero-copy">
        <span class="nb-badge">{{ $homeData['hero_badge'] ?? 'B2B PROCUREMENT' }}</span>
        <h1 class="nb-hero-title">
          {!! $homeData['hero_title'] ?? 'Trusted Analytical & <span class="nb-accent">Microbiology</span> Solutions' !!}
        </h1>
        <p class="nb-hero-lead">
          {{ $homeData['hero_subtitle'] ?? 'Penyedia instrumen analisis, media kultur, dan reagen laboratorium dengan standar kualitas internasional untuk industri Indonesia.' }}
        </p>
        <div class="nb-hero-actions">
          <a href="{{ url($homeData['hero_cta_link'] ?? '/produk') }}" class="nb-btn nb-btn-primary">
            {{ $homeData['hero_cta_text'] ?? 'Explore Product Catalog' }}
            <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/kontak') }}" class="nb-btn nb-btn-ghost">
            Contact Sales
          </a>
        </div>
      </div>

      <!-- Right: framed image card -->
      <div class="nb-hero-visual">
        <div class="nb-hero-frame">
          @foreach($heroImages as $index => $imgUrl)
            <img
              class="nb-hero-slide @if($index === 0) is-active @endif"
              src="{{ $imgUrl }}"
              alt="Peralatan laboratorium Prolabios"
              decoding="async"
              @if($index === 0) fetchpriority="high" @else loading="lazy" @endif
            >
          @endforeach

          @if(count($heroImages) > 1)
            <div class="nb-hero-controls">
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
      </div>
    </div>
  </div>
</section>
