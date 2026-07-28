<!-- 2. Trusted Principals Marquee -->
<section class="hitech-marquee-section">
  <div class="container mb-3 text-center">
    <span class="hitech-label-muted">Authorized Global Principals &amp; Partners</span>
  </div>
  @php
    $activePrincipals = \Illuminate\Support\Facades\Cache::remember('active_principals_v4', 600, function () {
        $items = \Illuminate\Support\Facades\DB::table('principals')
            ->where('status', 'online')
            ->get()
            ->map(fn($r) => (array) $r)
            ->toArray();

        foreach ($items as &$pr) {
            if (!empty($pr['logo']) && !file_exists(public_path(ltrim($pr['logo'], '/')))) {
                if (str_contains(strtolower($pr['name']), 'bioendo') && file_exists(public_path('images/vendor/Bioendo-labs.png'))) {
                    $pr['logo'] = '/images/vendor/Bioendo-labs.png';
                }
            }
        }
        return $items;
    });
  @endphp
  @if(!empty($activePrincipals))
    <div class="marquee-container" style="position: relative; display: flex; overflow: hidden; user-select: none; padding: 15px 0;">
      <div class="marquee-content-single">
        <!-- Loop 1 -->
        @foreach($activePrincipals as $pr)
          <div class="marquee-logo-box"><img src="{{ asset($pr['logo']) }}" alt="{{ $pr['name'] }} — Authorized Principal Manufacturer Logo" loading="lazy" decoding="async"></div>
        @endforeach
        
        <!-- Loop 2 (seamless infinite marquee) -->
        @foreach($activePrincipals as $pr)
          <div class="marquee-logo-box"><img src="{{ asset($pr['logo']) }}" alt="{{ $pr['name'] }} — Authorized Principal Manufacturer Logo" loading="lazy" decoding="async"></div>
        @endforeach
      </div>
    </div>
  @endif
</section>
