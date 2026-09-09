@extends('layouts.app')

@section('title', 'PT Prolabios Mitra Analitika | Solusi Laboratorium Terpercaya')

@section('preload')
  @php
    $firstHero = $homeData['hero_images'][0] ?? 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80';
  @endphp
  <link rel="preload" as="image" href="{{ $firstHero }}" fetchpriority="high">
@endsection

@section('content')
  @include('partials.home-hero')
  @include('partials.home-principals')
  @include('partials.home-bento')
  @include('partials.home-focus')

  <!-- Products -->
  <section class="section-spacious typo-products-section nb-section">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
        <div>
          <h2 class="typo-section-title">Produk & Reagen Unggulan</h2>
          <p class="typo-section-sub">Instrumen teruji dan media kultur standar farmakope siap pakai untuk kebutuhan pengujian lab.</p>
        </div>
        <div class="mt-3 mt-md-0">
          <a href="{{ url('/produk') }}" class="nb-btn nb-btn-ghost d-inline-flex align-items-center gap-2">
            Lihat Semua Produk <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 align-items-stretch">
        @if(isset($featuredProducts) && count($featuredProducts) > 0)
          @foreach($featuredProducts as $idx => $prod)
            @include('partials.product-card', ['prod' => $prod, 'vt' => 'prod-card-' . Str::slug($prod['title'])])
          @endforeach
        @else
          <div class="col-12 text-center py-4">
            <p class="text-muted">Produk unggulan sedang diperbarui.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  @include('partials.home-news')

  <!-- RFQ giant callout -->
  <section class="nb-rfq-section">
    <div class="container">
      <div class="nb-rfq-box">
        <span class="nb-badge">{{ $homeData['cta_banner_badge'] ?? 'PENGADAAN LABORATORIUM' }}</span>
        <h2 class="nb-rfq-title">{{ $homeData['cta_banner_title'] ?? 'Butuh penawaran resmi untuk laboratorium Anda?' }}</h2>
        <p class="nb-rfq-sub">{{ $homeData['cta_banner_sub'] ?? 'Kirimkan daftar kebutuhan alat & reagen Anda. Tim kami akan segera menindaklanjuti dengan penawaran harga resmi, ketersediaan stok, dan dokumen sertifikasi.' }}</p>
        <div class="nb-rfq-actions">
          <a href="{{ url($homeData['cta_banner_btn_url'] ?? '/kontak') }}" class="nb-btn nb-btn-primary">
            {{ $homeData['cta_banner_btn_text'] ?? 'Hubungi Sales / Minta Penawaran' }}
            <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ url('/cart') }}" class="nb-btn nb-btn-ghost">
            <i class="bi bi-cart3"></i> Keranjang Penawaran (RFQ)
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection
