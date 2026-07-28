<!-- 3. Value Pillars Grid (Bento) -->
<section class="section-spacious">
  <div class="container">
    <div class="row mb-5 typo-section-head">
      <div class="col-lg-7">
        <h2 class="typo-section-title">{{ $homeData['bento_title'] ?? 'Infrastructure & Reliability Standards' }}</h2>
        <p class="typo-section-sub">{{ $homeData['bento_subtitle'] ?? 'Engineered to fulfill strict regulatory compliance and ensure seamless laboratory testing continuity.' }}</p>
      </div>
    </div>

    <div class="row g-4">
      @php
        $bentoCards = $homeData['bento_cards'] ?? [
          ['icon' => 'bi-patch-check', 'title' => 'ISO & AKL Certified Products', 'desc' => 'Over 1,000+ officially accredited reagents and instruments, guaranteeing distribution legality for BPOM and ISO 17025 audit compliance.'],
          ['icon' => 'bi-file-earmark-code', 'title' => 'Instant COA & MSDS Access', 'desc' => 'Every batch of reagents and culture media comes with official Certificate of Analysis (COA) and MSDS ready for lab validation download.'],
          ['icon' => 'bi-snow', 'title' => 'Safe Cold-Chain Logistics', 'desc' => 'Tested cold-chain infrastructure ensuring temperature-sensitive reagents remain stable and active upon arrival at your laboratory.'],
          ['icon' => 'bi-tools', 'title' => 'Integrated After-Sales & Calibration', 'desc' => 'Comprehensive equipment qualification (IQ/OQ/PQ), routine calibration services, and technical training by application specialists.']
        ];
      @endphp

      @foreach($bentoCards as $idx => $card)
        <div class="col-lg-6">
          <div class="hitech-bento-card">
            @if($idx === 1)
              <div class="hitech-bento-number">02</div>
            @endif
            <div class="hitech-bento-icon"><i class="bi {{ $card['icon'] ?? 'bi-patch-check' }}"></i></div>
            <h3 class="hitech-bento-title">{{ $card['title'] ?? '' }}</h3>
            <p class="hitech-bento-desc">{{ $card['desc'] ?? '' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
