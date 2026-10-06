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
@endphp

@if($wrapInCol)
  <div class="col" @if(!empty($catAttr)) data-category="{{ $catAttr }}" @endif>
@endif
  <div class="card h-100 product-card border-0">
    <div class="img-wrap">
      <img src="{{ $cardImage }}" alt="{{ $p['title'] }} — Produk Laboratorium" loading="lazy" decoding="async" width="400" height="250" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
    </div>
    <div class="card-body p-4 d-flex flex-column">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        @if(!empty($p['catalog']))
          <div class="product-cat-code">CAT. {{ $p['catalog'] }}</div>
        @else
          <div></div>
        @endif

        @if(!empty($p->principal))
          @if(!empty($p->principal->logo))
            <div class="product-principal-logo" title="Prinsipal: {{ $p->principal->name }}">
              <img src="{{ str_starts_with($p->principal->logo, 'http') || str_starts_with($p->principal->logo, '/') ? $p->principal->logo : asset('storage/' . $p->principal->logo) }}"
                   alt="Logo {{ $p->principal->name }}"
                   loading="lazy"
                   onerror="this.onerror=null;this.parentElement.style.display='none';">
            </div>
          @else
            <span class="text-muted small fw-semibold">
              {{ $p->principal->name }}
            </span>
          @endif
        @endif
      </div>

      <h3 class="card-title fs-6 fw-semibold mb-2">
        <a href="{{ $cardUrl }}" class="product-card-link" {!! $vtTarget !!}>{{ $p['title'] }}</a>
      </h3>

      <p class="product-card-desc mb-3 flex-grow-1 text-muted">
        {{ Str::limit($desc, 75) }}
      </p>

      <div class="mt-auto pt-3 d-flex align-items-center gap-2 nb-card-foot">
        <a href="{{ $cardUrl }}" class="nb-btn nb-btn-ghost flex-grow-1 justify-content-center" {!! $vtTarget !!} aria-label="Detail dan spesifikasi {{ $p['title'] }}">
          Detail <i data-lucide="arrow-right" class="ms-1"></i>
        </a>
        <form action="{{ route('cart.add') }}" method="POST" class="m-0">
          @csrf
          <input type="hidden" name="id" value="{{ $p['id'] ?? '' }}">
          <input type="hidden" name="title" value="{{ $p['title'] }}">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="nb-btn nb-btn-primary px-3 text-nowrap" title="Tambahkan ke Pengajuan Penawaran" aria-label="Tambah {{ $p['title'] }} ke keranjang">
            <i data-lucide="plus"></i> RFQ
          </button>
        </form>
      </div>
    </div>
  </div>
@if($wrapInCol)
  </div>
@endif
