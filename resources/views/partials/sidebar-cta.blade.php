<div class="profil-cta-box p-4">
  @if(!empty($badge))
    <span class="nb-badge mb-3">{{ $badge }}</span>
  @endif
  <h3 class="profil-sidebar-title">{{ $title ?? 'Butuh Bantuan?' }}</h3>
  <p>{{ $text ?? 'Diskusikan kebutuhan spesifikasi produk atau instrumen laboratorium Anda langsung dengan tim teknis kami.' }}</p>
  <div class="d-flex flex-column gap-2">
    <a href="{{ $primaryUrl ?? url('/kontak') }}" class="nb-btn nb-btn-primary w-100 justify-content-center">
      {{ $primaryText ?? 'Hubungi Kami' }} <i class="bi bi-arrow-right ms-1"></i>
    </a>
    @if(!empty($secondaryUrl))
      <a href="{{ $secondaryUrl }}" class="nb-btn nb-btn-ghost w-100 justify-content-center" style="font-size: 0.82rem;" @if(!empty($secondaryBlank)) target="_blank" rel="noopener noreferrer" @endif>
        @if(!empty($secondaryIcon)) <i class="{{ $secondaryIcon }} me-1"></i> @endif {{ $secondaryText ?? 'Katalog' }}
      </a>
    @endif
  </div>
</div>
