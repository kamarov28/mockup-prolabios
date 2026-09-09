{{-- resources/views/partials/subpage-hero.blade.php --}}
<section class="profil-hero-banner subpage-hero-banner">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-9">
        @if(!empty($badge))
          <span class="nb-badge">
            {!! $badge !!}
          </span>
        @endif
        <h1 class="profil-main-title">
          {{ $title ?? '' }}
        </h1>
        @if(!empty($subtitle))
          <p class="profil-main-subtitle">
            {!! $subtitle !!}
          </p>
        @endif
      </div>
    </div>
  </div>
</section>
