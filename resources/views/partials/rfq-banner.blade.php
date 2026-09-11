<section class="nb-rfq-section">
  <div class="container">
    <div class="nb-rfq-box">
      <span class="nb-badge">{{ $badge ?? 'PENGADAAN LABORATORIUM' }}</span>
      <h2 class="nb-rfq-title">{{ $title ?? 'Butuh penawaran resmi untuk laboratorium Anda?' }}</h2>
      <p class="nb-rfq-sub">{{ $subtitle ?? 'Kirimkan daftar kebutuhan alat & reagen Anda. Tim kami akan segera menindaklanjuti dengan penawaran harga resmi, ketersediaan stok, dan dokumen sertifikasi.' }}</p>
      <div class="nb-rfq-actions">
        <a href="{{ url($btnUrl ?? '/kontak') }}" class="nb-btn nb-btn-primary">
          {{ $btnText ?? 'Hubungi Sales / Minta Penawaran' }}
          <i data-lucide="arrow-right" class="ms-1"></i>
        </a>
        <a href="{{ url('/cart') }}" class="nb-btn nb-btn-ghost">
          <i data-lucide="shopping-cart" class="me-1"></i> {{ $cartText ?? 'Buka Keranjang RFQ' }}
        </a>
      </div>
    </div>
  </div>
</section>
