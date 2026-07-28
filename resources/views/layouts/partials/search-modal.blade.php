<button type="button" id="scroll-to-top" class="btn-scroll-to-top" style="opacity: 0; visibility: hidden;" aria-label="Back to top">
  <i class="bi bi-arrow-up-short"></i>
</button>

<div id="search-overlay" class="search-overlay" role="dialog" aria-modal="true" aria-label="Product search" aria-hidden="true">
  <div class="search-overlay-backdrop" id="search-close-backdrop"></div>
  
  <div class="search-modal-card">
    <button type="button" class="search-close-btn" id="search-close" aria-label="Close search">
      <i class="bi bi-x-lg"></i>
    </button>

    <div class="search-modal-header">
      <form action="{{ url('/produk') }}" method="GET" class="search-modal-form w-100" role="search">
        <div class="search-input-box">
          <i class="bi bi-search search-modal-icon"></i>
          <input type="search" name="q" id="search-overlay-input" placeholder="Search reagents, instruments, catalog codes..." autocomplete="off" enterkeyhint="search">
        </div>
      </form>
    </div>

    <div class="search-modal-body">
      <div class="search-section-label">Popular Scientific Searches</div>
      <div class="search-suggestions">
        @foreach($searchSuggestions ?? ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'] as $tag)
          <a href="{{ url('/produk?q=' . urlencode($tag)) }}" class="suggestion-tag"><i class="bi bi-arrow-up-right me-1 opacity-50"></i>{{ $tag }}</a>
        @endforeach
      </div>

      <div class="search-section-label mt-4">Quick Navigation</div>
      <div class="search-quick-links">
        <a href="{{ url('/produk') }}" class="search-quick-item">
          <i class="bi bi-box-seam me-2 text-accent"></i>
          <span>Browse All Products &amp; Reagents</span>
          <i class="bi bi-chevron-right ms-auto opacity-40"></i>
        </a>
        <a href="{{ url('/layanan') }}" class="search-quick-item">
          <i class="bi bi-tools me-2 text-accent"></i>
          <span>Calibration &amp; Technical Services</span>
          <i class="bi bi-chevron-right ms-auto opacity-40"></i>
        </a>
        <a href="{{ url('/kontak') }}" class="search-quick-item">
          <i class="bi bi-envelope me-2 text-accent"></i>
          <span>Request Official Quotation</span>
          <i class="bi bi-chevron-right ms-auto opacity-40"></i>
        </a>
      </div>
    </div>
  </div>
</div>
