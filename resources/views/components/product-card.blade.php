@props([
    'product' => null,
    'prod' => null,
    'wrapInCol' => true,
    'vt' => null,
])

@php
    $p = $product ?? $prod;
    $cardImage = !empty($p['image']) ? $p['image'] : asset('images/placeholder.svg');
    $cardUrl = product_url($p);
    $catAttr = trim(($p['category'] ?? '') . ' ' . ($p['sector'] ?? ''));
    $desc = !empty($p['sub_category'])
        ? str_replace('-', ' ', $p['sub_category'])
        : (!empty($p['category']) ? str_replace('-', ' ', $p['category']) : (!empty($p['description']) ? strip_tags(html_entity_decode($p['description'])) : 'Produk laboratorium'));
    $vtTarget = !empty($vt) ? 'data-vt-target="' . e($vt) . '"' : '';

    // Format title into continuous clean string (replace line breaks with spaces)
    $rawTitle = (string)($p['title'] ?? '');
    $cleanTitle = trim(preg_replace('/\s+/', ' ', $rawTitle));
    $firstLineTitle = trim(preg_split('/\r\n|\r|\n/', $rawTitle)[0] ?? $cleanTitle);
@endphp

@if($wrapInCol)
  <div class="col" @if(!empty($catAttr)) data-category="{{ $catAttr }}" @endif>
@endif
  <div class="card h-100 product-card border-0">
    <div class="img-wrap position-relative">
      <img src="{{ $cardImage }}" alt="{{ $firstLineTitle }} — Produk Laboratorium" loading="lazy" decoding="async" width="400" height="250" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
      @if(!empty($p['is_featured']))
        <span class="product-badge product-badge--featured" title="Produk Pilihan Editor">
          <i data-lucide="star"></i>
          <span>Pilihan</span>
        </span>
      @elseif(!empty($p['search_hits']) && $p['search_hits'] >= 1)
        <span class="product-badge product-badge--popular" title="Produk Paling Banyak Dicari">
          <i data-lucide="trending-up"></i>
          <span>Populer</span>
        </span>
      @endif
    </div>
    <div class="card-body p-3 p-sm-4 d-flex flex-column">
      <div class="product-card-meta d-flex align-items-center justify-content-between gap-2 mb-2">
        @if(!empty($p['catalog']))
          <div class="product-cat-code text-nowrap" title="Nomor Katalog">CAT. {{ $p['catalog'] }}</div>
        @else
          <div></div>
        @endif

        @if(!empty($p->principal))
          @if(!empty($p->principal->logo))
            <div class="product-principal-logo flex-shrink-0 ms-auto" title="Prinsipal: {{ $p->principal->name }}">
              <img src="{{ str_starts_with($p->principal->logo, 'http') || str_starts_with($p->principal->logo, '/') ? $p->principal->logo : asset('storage/' . $p->principal->logo) }}"
                   alt="Logo {{ $p->principal->name }}"
                   loading="lazy"
                   onerror="this.onerror=null;this.parentElement.style.display='none';">
            </div>
          @else
            <span class="product-principal-name text-muted ms-auto">
              {{ $p->principal->name }}
            </span>
          @endif
        @endif
      </div>

      <h3 class="card-title mb-2" title="{{ $cleanTitle }}">
        {{ $cleanTitle }}
      </h3>

      <p class="product-card-desc mb-3 text-muted">
        {{ Str::limit($desc, 70) }}
      </p>

      <div class="mt-auto pt-3 nb-card-foot">
        <a href="{{ $cardUrl }}" class="nb-card-btn nb-card-btn--detail nb-btn-ghost" {!! $vtTarget !!} aria-label="Detail dan spesifikasi {{ $firstLineTitle }}">
          <span>Detail</span>
          <i data-lucide="arrow-right"></i>
        </a>
        <form action="{{ route('cart.add') }}" method="POST" class="nb-card-form">
          @csrf
          <input type="hidden" name="id" value="{{ $p['id'] ?? '' }}">
          <input type="hidden" name="title" value="{{ $firstLineTitle }}">
          <button type="submit" class="nb-card-btn nb-card-btn--rfq nb-btn-primary" title="Tambahkan ke Pengajuan Penawaran" aria-label="Tambah {{ $firstLineTitle }} ke keranjang">
            <i data-lucide="plus"></i>
            <span>RFQ</span>
          </button>
        </form>
      </div>
    </div>
  </div>
@if($wrapInCol)
  </div>
@endif
