{{-- resources/views/partials/product-card.blade.php --}}
@php
  $cardImage = !empty($prod['image']) ? $prod['image'] : asset('images/placeholder.svg');
  $cardUrl = product_url($prod);
  $catAttr = trim(($prod['category'] ?? '') . ' ' . ($prod['sector'] ?? ''));
  $desc = !empty($prod['sub_category'])
    ? str_replace('-', ' ', $prod['sub_category'])
    : (!empty($prod['category']) ? str_replace('-', ' ', $prod['category']) : (!empty($prod['description']) ? strip_tags(html_entity_decode($prod['description'])) : 'Produk laboratorium'));
  $wrapInCol = $wrapInCol ?? true;
  $vtTarget = !empty($vt) ? 'data-vt-target="' . e($vt) . '"' : '';
@endphp

@if($wrapInCol)
  <div class="col" @if(!empty($catAttr)) data-category="{{ $catAttr }}" @endif>
@endif
  <div class="card h-100 product-card border-0">
    <div class="img-wrap">
      <img src="{{ $cardImage }}" alt="{{ $prod['title'] }} — Produk Laboratorium" loading="lazy" decoding="async" width="400" height="250">
    </div>
    <div class="card-body p-4 d-flex flex-column">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        @if(!empty($prod['catalog']))
          <div class="product-cat-code">CAT. {{ $prod['catalog'] }}</div>
        @else
          <div></div>
        @endif

        @if(!empty($prod->principal))
          @if(!empty($prod->principal->logo))
            <div class="product-principal-logo" title="Prinsipal: {{ $prod->principal->name }}">
              <img src="{{ str_starts_with($prod->principal->logo, 'http') || str_starts_with($prod->principal->logo, '/') ? $prod->principal->logo : asset('storage/' . $prod->principal->logo) }}"
                   alt="Logo {{ $prod->principal->name }}"
                   loading="lazy">
            </div>
          @else
            <span class="text-muted small fw-semibold">
              {{ $prod->principal->name }}
            </span>
          @endif
        @endif
      </div>

      <h3 class="card-title fs-6 fw-semibold mb-2">
        <a href="{{ $cardUrl }}" class="product-card-link" {!! $vtTarget !!}>{{ $prod['title'] }}</a>
      </h3>

      <p class="product-card-desc mb-3 flex-grow-1 text-muted">
        {{ Str::limit($desc, 75) }}
      </p>

      <div class="mt-auto pt-3 d-flex align-items-center gap-2 nb-card-foot">
        <a href="{{ $cardUrl }}" class="nb-btn nb-btn-ghost flex-grow-1 justify-content-center" {!! $vtTarget !!} aria-label="Detail dan spesifikasi {{ $prod['title'] }}">
          Detail <i data-lucide="arrow-right" class="ms-1"></i>
        </a>
        <form action="{{ route('cart.add') }}" method="POST" class="m-0">
          @csrf
          <input type="hidden" name="id" value="{{ $prod['id'] ?? '' }}">
          <input type="hidden" name="title" value="{{ $prod['title'] }}">
          <input type="hidden" name="quantity" value="1">
          <button type="submit" class="nb-btn nb-btn-primary px-3 text-nowrap" title="Tambahkan ke Pengajuan Penawaran" aria-label="Tambah {{ $prod['title'] }} ke keranjang">
            <i data-lucide="plus"></i> RFQ
          </button>
        </form>
      </div>
    </div>
  </div>
@if($wrapInCol)
  </div>
@endif
