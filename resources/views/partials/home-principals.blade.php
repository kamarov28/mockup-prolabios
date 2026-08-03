<!-- 2. Trusted Principals Marquee -->
<section class="hitech-marquee-section">
  <div class="container mb-3 text-center">
    <span class="hitech-label-muted">Authorized Global Principals &amp; Partners</span>
  </div>
  @php
    $activePrincipals = \Illuminate\Support\Facades\Cache::remember('active_principals_v5', 600, function () {
        $items = [];
        try {
            $items = \Illuminate\Support\Facades\DB::table('principals')
                ->where('status', 'online')
                ->get()
                ->map(fn($r) => (array) $r)
                ->toArray();
        } catch (\Exception $e) {}

        if (empty($items)) {
            return [
                ['name' => 'Liofilchem', 'logo' => '/images/vendor/liofilchem.png'],
                ['name' => 'Bioendo', 'logo' => '/images/vendor/Bioendo-labs.png'],
                ['name' => 'Terragene', 'logo' => '/images/vendor/terragene.png'],
                ['name' => 'Diamidex', 'logo' => '/images/vendor/diamidex.png'],
                ['name' => 'Biotool', 'logo' => '/images/vendor/biotool.png'],
                ['name' => 'BNF Korea', 'logo' => '/images/vendor/bnf_korea.png'],
                ['name' => 'Solus Scientific', 'logo' => '/images/vendor/solus_scientific.png'],
                ['name' => 'Meizheng', 'logo' => '/images/vendor/meizheng.png'],
                ['name' => 'Leadfluid', 'logo' => '/images/vendor/leadfluid.png'],
                ['name' => 'IFM', 'logo' => '/images/vendor/ifm.png'],
                ['name' => 'KSL Pulse', 'logo' => '/images/vendor/ksl_pulse.png'],
                ['name' => 'Ratel', 'logo' => '/images/vendor/ratel.png'],
                ['name' => 'Vecverse', 'logo' => '/images/vendor/vecverse.png'],
                ['name' => 'Vision Med', 'logo' => '/images/vendor/vision_med.png'],
                ['name' => 'Lumeley', 'logo' => '/images/vendor/lumeley.png'],
            ];
        }

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
      <div class="marquee-content">
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
