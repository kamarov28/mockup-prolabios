<!-- 2. Authorized Principals — boxed ticker -->
<section class="nb-principals">
  <div class="container">
    <div class="nb-principals-shell">
      <div class="nb-principals-label">
        <span class="nb-mono">[ AUTHORIZED PRINCIPAL PARTNERS ]</span>
      </div>

      @php
        $activePrincipals = \Illuminate\Support\Facades\Cache::remember('active_principals_v5', 600, function () {
            $items = [];
            try {
                $items = \App\Models\Principal::online()
                    ->orderBy('name')
                    ->get(['id', 'name', 'logo', 'status'])
                    ->map(fn ($p) => $p->toArray())
                    ->all();
            } catch (\Throwable $e) {
                $items = [];
            }

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
                if (! empty($pr['logo']) && ! file_exists(public_path(ltrim($pr['logo'], '/')))) {
                    if (str_contains(strtolower($pr['name']), 'bioendo') && file_exists(public_path('images/vendor/Bioendo-labs.png'))) {
                        $pr['logo'] = '/images/vendor/Bioendo-labs.png';
                    }
                }
            }

            return $items;
        });
      @endphp

      @if(!empty($activePrincipals))
        <div class="marquee-container nb-principals-track">
          <div class="marquee-content">
            @foreach($activePrincipals as $pr)
              <div class="marquee-logo-box nb-logo-cell">
                <img src="{{ asset($pr['logo']) }}" alt="{{ $pr['name'] }} — Logo prinsipal resmi" loading="lazy" decoding="async">
              </div>
            @endforeach
            @foreach($activePrincipals as $pr)
              <div class="marquee-logo-box nb-logo-cell">
                <img src="{{ asset($pr['logo']) }}" alt="{{ $pr['name'] }} — Logo prinsipal resmi" loading="lazy" decoding="async">
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
