<button type="button" id="scroll-to-top" class="btn-scroll-to-top" style="opacity: 0; visibility: hidden;" aria-label="Kembali ke atas">
  <i class="bi bi-arrow-up-short"></i>
</button>

<div id="search-overlay" class="search-overlay" role="dialog" aria-modal="true" aria-label="Pencarian produk" aria-hidden="true">
  <div class="search-overlay-backdrop" id="search-close-backdrop"></div>
  
  <div class="search-modal-card">
    <button type="button" class="search-close-btn" id="search-close" aria-label="Tutup pencarian">
      <i class="bi bi-x-lg"></i>
    </button>

    <div class="search-modal-header">
      <form action="{{ url('/produk') }}" method="GET" class="search-modal-form w-100" role="search">
        <div class="search-input-box">
          <i class="bi bi-search search-modal-icon"></i>
          <input type="search" name="q" id="search-overlay-input" placeholder="Cari produk, reagen, atau kode katalog..." autocomplete="off" enterkeyhint="search">
        </div>
      </form>
    </div>

    <div class="search-modal-body">
      <div class="search-section-label">Rekomendasi Pencarian</div>
      <div class="search-suggestions mb-0">
        @foreach($searchSuggestions ?? ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'] as $tag)
          <a href="{{ url('/produk?q=' . urlencode($tag)) }}" class="suggestion-tag"><i class="bi bi-arrow-up-right me-1 opacity-50"></i>{{ $tag }}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>
