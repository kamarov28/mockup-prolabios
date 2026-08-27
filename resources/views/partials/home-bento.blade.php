<!-- 3. Value Pillars Grid (Bento) -->
<section class="section-spacious">
  <div class="container">
    <div class="row mb-5 typo-section-head">
      <div class="col-lg-7">
        <h2 class="typo-section-title">{{ $homeData['bento_title'] ?? 'Standar Infrastruktur & Keandalan' }}</h2>
        <p class="typo-section-sub">{{ $homeData['bento_subtitle'] ?? 'Dirancang untuk memenuhi standar regulasi dan menjaga kelancaran pengujian laboratorium.' }}</p>
      </div>
    </div>

    <div class="row g-4">
      @php
        $bentoCards = $homeData['bento_cards'] ?? [
          ['icon' => 'bi-patch-check', 'title' => 'Produk Bersertifikat ISO & AKL', 'desc' => 'Lebih dari 1.000 reagen dan instrumen resmi, mendukung legalitas distribusi untuk audit BPOM dan ISO 17025.'],
          ['icon' => 'bi-file-earmark-code', 'title' => 'Akses COA & MSDS Siap Pakai', 'desc' => 'Setiap batch reagen dan media kultur dilengkapi Certificate of Analysis (COA) dan MSDS resmi, siap diunduh untuk validasi lab.'],
          ['icon' => 'bi-snow', 'title' => 'Pengiriman Cold-Chain yang Aman', 'desc' => 'Infrastruktur cold-chain yang teruji agar reagen sensitif suhu tetap stabil sampai di laboratorium Anda.'],
          ['icon' => 'bi-tools', 'title' => 'After-Sales & Kalibrasi Terpadu', 'desc' => 'Kualifikasi alat (IQ/OQ/PQ), layanan kalibrasi rutin, dan pelatihan teknis oleh spesialis aplikasi.']
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
