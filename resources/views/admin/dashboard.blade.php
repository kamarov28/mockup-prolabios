@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')

@php
  $user = auth()->user();
  $canRfq = $user?->canManageRfqs();
  $canViewCatalog = $user?->canViewCatalog();
  $canManageCatalog = $user?->canManageCatalog();
  $canPosts = $user?->canManagePosts();
  $canSystem = $user?->isSuperAdmin();
  $defaultTab = (!$canViewCatalog && $canPosts) ? 'posts' : 'products';
@endphp

<div class="dash-wrapper">

  {{-- ── Header Strip ────────────────────────────────────────────────────────── --}}
  <div class="mb-4">
    <span class="admin-page-label">Ikhtisar Bisnis</span>
    <h2 class="admin-page-title mb-0" style="font-size: 1.5rem;">Dashboard Operasional</h2>
  </div>

  {{-- ── 1. KPI Micro-Stat Cards ──────────────────────────────────────────────── --}}
  <div class="row g-3 mb-3">

    {{-- RFQ Card --}}
    @can('manage-rfq')
    <div class="col-sm-6 col-xl-3">
      <a href="{{ route('admin.rfqs.index') }}" class="dash-stat-card text-decoration-none">
        <div class="dash-stat-icon-wrap" style="background: #FEE2E2; color: var(--color-accent, #A6171C);">
          <i data-lucide="receipt"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Pengajuan RFQ</span>
          <div class="d-flex align-items-baseline gap-2">
            <span class="dash-stat-val" style="color: var(--color-accent, #A6171C);">{{ $rfqsCount }}</span>
            @if(isset($newRfqsCount) && $newRfqsCount > 0)
              <span class="admin-badge admin-badge-warning" style="font-size: 0.68rem; padding: 2px 6px;">
                {{ $newRfqsCount }} Baru
              </span>
            @endif
          </div>
        </div>
        <div class="dash-stat-arrow">
          <i data-lucide="arrow-up-right"></i>
        </div>
      </a>
    </div>
    @endcan

    {{-- Products Card --}}
    @can('view-catalog')
    <div class="col-sm-6 col-xl-3">
      <a href="{{ route('admin.products') }}" class="dash-stat-card text-decoration-none">
        <div class="dash-stat-icon-wrap" style="background: #E0F2FE; color: #0284C7;">
          <i data-lucide="package"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Total Produk</span>
          <div class="d-flex align-items-baseline gap-2">
            <span class="dash-stat-val">{{ $productsCount }}</span>
            <span class="text-muted" style="font-size: 0.72rem;">Katalog</span>
          </div>
        </div>
        <div class="dash-stat-arrow">
          <i data-lucide="arrow-up-right"></i>
        </div>
      </a>
    </div>
    @endcan

    {{-- Posts Card --}}
    @can('manage-posts')
    <div class="col-sm-6 col-xl-3">
      <a href="{{ route('admin.posts') }}" class="dash-stat-card text-decoration-none">
        <div class="dash-stat-icon-wrap" style="background: #DCFCE7; color: #16A34A;">
          <i data-lucide="file-text"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Artikel Berita</span>
          <div class="d-flex align-items-baseline gap-2">
            <span class="dash-stat-val">{{ $postsCount }}</span>
            <span class="text-muted" style="font-size: 0.72rem;">Rilis</span>
          </div>
        </div>
        <div class="dash-stat-arrow">
          <i data-lucide="arrow-up-right"></i>
        </div>
      </a>
    </div>
    @endcan

    {{-- Sectors Card --}}
    @can('manage-catalog')
    <div class="col-sm-6 col-xl-3">
      <a href="{{ route('admin.sectors') }}" class="dash-stat-card text-decoration-none">
        <div class="dash-stat-icon-wrap" style="background: #F3E8FF; color: #9333EA;">
          <i data-lucide="layers"></i>
        </div>
        <div class="dash-stat-content">
          <span class="dash-stat-label">Sektor Industri</span>
          <div class="d-flex align-items-baseline gap-2">
            <span class="dash-stat-val">{{ $sectorsCount }}</span>
            <span class="text-muted" style="font-size: 0.72rem;">Pasar</span>
          </div>
        </div>
        <div class="dash-stat-arrow">
          <i data-lucide="arrow-up-right"></i>
        </div>
      </a>
    </div>
    @endcan

  </div>

  {{-- ── 2. Main Cockpit Grid (3 Columns on Desktop) ─────────────────────────── --}}
  <div class="row g-3 mb-3">

    {{-- Column 1: RFQ Inquiry Masuk (col-xl-5) --}}
    @can('manage-rfq')
    <div class="col-xl-5 col-lg-6">
      <div class="admin-card h-100 d-flex flex-column" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="receipt" style="width: 17px; height: 17px; color: var(--color-accent);"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Inquiry RFQ Terbaru</h2>
          </div>
          <a href="{{ route('admin.rfqs.index') }}" class="dash-card-link">
            Semua ({{ $rfqsCount }}) <i data-lucide="arrow-right" style="width: 13px; height: 13px;"></i>
          </a>
        </div>
        <div class="admin-card-body-flush flex-grow-1">
          @if(count($recentRfqs) > 0)
            <div class="table-responsive">
              <table class="admin-table dash-compact-table">
                <thead>
                  <tr>
                    <th>No. RFQ</th>
                    <th>Status</th>
                    <th>Pemohon / Instansi</th>
                    <th style="text-align: center;">Item</th>
                    <th style="text-align: right; width: 40px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentRfqs as $rfq)
                    <tr>
                      <td style="white-space: nowrap;">
                        <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="dash-item-link" title="Buka RFQ">
                          {{ $rfq->rfq_number }}
                        </a>
                      </td>
                      <td style="white-space: nowrap;">
                        <span class="admin-badge {{ $rfq->status_badge_class }}" style="font-size: 0.7rem; padding: 2px 6px;">
                          {{ $rfq->status_label }}
                        </span>
                      </td>
                      <td>
                        <div class="dash-text-truncate" style="max-width: 140px;" title="{{ $rfq->name }} &bull; {{ $rfq->company_name }}">
                          <strong style="color: var(--color-text-main); font-size: 0.82rem; display: block;">{{ $rfq->name }}</strong>
                          <span class="text-muted" style="font-size: 0.74rem; line-height: 1.2;">{{ $rfq->company_name ?: '—' }}</span>
                        </div>
                      </td>
                      <td style="text-align: center; white-space: nowrap;">
                        <span class="admin-badge admin-badge-muted" style="font-size: 0.7rem;">
                          {{ $rfq->items->count() }} item
                        </span>
                      </td>
                      <td style="text-align: right;">
                        <a href="{{ route('admin.rfqs.show', $rfq->id) }}" class="admin-action-link" title="Detail RFQ" style="width: 28px; height: 28px;">
                          <i data-lucide="eye" style="width: 13px; height: 13px;"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i data-lucide="inbox" style="width: 28px; height: 28px; color: var(--color-text-muted); opacity: 0.4;"></i>
              <p class="mt-2 mb-0 text-muted" style="font-size: 0.82rem;">Belum ada pengajuan RFQ.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
    @endcan

    {{-- Column 2: Segmented Switcher (Produk & Artikel) --}}
    <div class="{{ $canRfq ? 'col-xl-4 col-lg-6' : 'col-xl-7 col-lg-7' }}">
      <div class="admin-card h-100 d-flex flex-column" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          {{-- Interactive Segmented Switcher --}}
          @if($canViewCatalog && $canPosts)
          <div class="dash-segmented-control" role="tablist">
            <button type="button" class="dash-segment-btn {{ $defaultTab === 'products' ? 'active' : '' }}" data-tab="products" id="tab-btn-products">
              <i data-lucide="package" style="width: 13px; height: 13px;"></i>
              <span>Produk ({{ count($recentProducts) }})</span>
            </button>
            <button type="button" class="dash-segment-btn {{ $defaultTab === 'posts' ? 'active' : '' }}" data-tab="posts" id="tab-btn-posts">
              <i data-lucide="file-text" style="width: 13px; height: 13px;"></i>
              <span>Artikel ({{ count($recentPosts) }})</span>
            </button>
          </div>
          @elseif($canViewCatalog)
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="package" style="width: 17px; height: 17px; color: #0284C7;"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Katalog Produk Terbaru</h2>
          </div>
          @elseif($canPosts)
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="file-text" style="width: 17px; height: 17px; color: #16A34A;"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Artikel &amp; Publikasi Terbaru</h2>
          </div>
          @endif

          {{-- Quick Add Dynamic Link --}}
          @if(($canManageCatalog && $defaultTab === 'products') || ($canPosts && $defaultTab === 'posts'))
          <a href="{{ $defaultTab === 'products' ? route('admin.products.create') : route('admin.posts.create') }}" id="tab-add-btn" class="dash-card-link">
            <i data-lucide="plus" style="width: 13px; height: 13px;"></i>
            <span id="tab-add-text">{{ $defaultTab === 'products' ? 'Tambah Produk' : 'Tambah Artikel' }}</span>
          </a>
          @endif
        </div>

        <div class="admin-card-body-flush flex-grow-1">
          {{-- Tab Content: Produk Terbaru --}}
          <div id="dash-panel-products" class="dash-tab-panel">
            @if(count($recentProducts) > 0)
              <div class="table-responsive">
                <table class="admin-table dash-compact-table">
                  <thead>
                    <tr>
                      <th style="width: 44px; text-align: center;">Item</th>
                      <th>Nama &amp; Katalog</th>
                      <th style="width: 40px; text-align: right;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentProducts as $p)
                      @php
                        $pImg = $p['image'] ?? null;
                        if ($pImg && !str_starts_with($pImg, 'http') && !str_starts_with($pImg, 'data:')) {
                          $pImgSrc = asset(ltrim($pImg, '/'));
                        } else {
                          $pImgSrc = $pImg;
                        }
                      @endphp
                      <tr>
                        {{-- Mini Thumbnail --}}
                        <td style="text-align: center; vertical-align: middle;">
                          <div class="dash-mini-thumb">
                            @if($pImgSrc)
                              <img src="{{ $pImgSrc }}" alt="{{ $p['title'] }}" loading="lazy"
                                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                              <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; color: var(--color-text-muted);">
                                <i data-lucide="package" style="width: 13px; height: 13px; opacity: 0.4;"></i>
                              </div>
                            @else
                              <div class="d-flex align-items-center justify-content-center w-100 h-100" style="color: var(--color-text-muted);">
                                <i data-lucide="package" style="width: 13px; height: 13px; opacity: 0.45;"></i>
                              </div>
                            @endif
                          </div>
                        </td>
                        {{-- Title & Info --}}
                        <td>
                          <div class="dash-text-truncate" style="max-width: 195px;" title="{{ $p['title'] }}">
                            @can('manage-catalog')
                            <a href="{{ route('admin.products.edit', $p['id']) }}" class="dash-item-title-link">
                              {{ $p['title'] }}
                            </a>
                            @else
                            <a href="{{ url('/produk/detail') }}?id={{ $p['id'] }}" target="_blank" class="dash-item-title-link">
                              {{ $p['title'] }}
                            </a>
                            @endcan
                            <div class="d-flex align-items-center gap-1 mt-0">
                              <span class="cat-key-badge" style="font-size: 0.68rem; padding: 1px 5px;">
                                {{ $p['catalog'] ?: 'SKU —' }}
                              </span>
                              <span class="text-muted" style="font-size: 0.72rem;">
                                &bull; {{ str_replace('-', ' ', $p['category'] ?? 'Umum') }}
                              </span>
                            </div>
                          </div>
                        </td>
                        {{-- Action --}}
                        <td style="text-align: right;">
                          @can('manage-catalog')
                          <a href="{{ route('admin.products.edit', $p['id']) }}" class="admin-action-link edit" title="Edit Produk" style="width: 28px; height: 28px;">
                            <i data-lucide="file-edit" style="width: 13px; height: 13px;"></i>
                          </a>
                          @else
                          <a href="{{ url('/produk/detail') }}?id={{ $p['id'] }}" target="_blank" class="admin-action-link view" title="Lihat di Web" style="width: 28px; height: 28px;">
                            <i data-lucide="eye" style="width: 13px; height: 13px;"></i>
                          </a>
                          @endcan
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4">
                <p class="mb-0 text-muted" style="font-size: 0.82rem;">Belum ada produk.</p>
              </div>
            @endif
          </div>

          {{-- Tab Content: Artikel Terbaru --}}
          <div id="dash-panel-posts" class="dash-tab-panel" style="{{ $defaultTab === 'posts' ? 'display: block;' : 'display: none;' }}">
            @if(count($recentPosts) > 0)
              <div class="table-responsive">
                <table class="admin-table dash-compact-table">
                  <thead>
                    <tr>
                      <th style="width: 44px; text-align: center;">Cover</th>
                      <th>Judul &amp; Kategori</th>
                      <th style="width: 40px; text-align: right;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentPosts as $post)
                      @php
                        $postImg = $post['image'] ?? null;
                        if ($postImg && !str_starts_with($postImg, 'http') && !str_starts_with($postImg, 'data:')) {
                          $postImgSrc = asset(ltrim($postImg, '/'));
                        } else {
                          $postImgSrc = $postImg;
                        }
                      @endphp
                      <tr>
                        {{-- Mini Thumbnail --}}
                        <td style="text-align: center; vertical-align: middle;">
                          <div class="dash-mini-thumb">
                            @if($postImgSrc)
                              <img src="{{ $postImgSrc }}" alt="{{ $post['title'] }}" loading="lazy"
                                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                              <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; color: var(--color-text-muted);">
                                <i data-lucide="file-text" style="width: 13px; height: 13px; opacity: 0.4;"></i>
                              </div>
                            @else
                              <div class="d-flex align-items-center justify-content-center w-100 h-100" style="color: var(--color-text-muted);">
                                <i data-lucide="file-text" style="width: 13px; height: 13px; opacity: 0.45;"></i>
                              </div>
                            @endif
                          </div>
                        </td>
                        {{-- Title & Category --}}
                        <td>
                          <div class="dash-text-truncate" style="max-width: 195px;" title="{{ $post['title'] }}">
                            <a href="{{ route('admin.posts.edit', $post['slug']) }}" class="dash-item-title-link">
                              {{ $post['title'] }}
                            </a>
                            <div class="d-flex align-items-center gap-1 mt-0">
                              <span class="admin-badge admin-badge-success" style="font-size: 0.68rem; padding: 1px 5px;">
                                {{ $post['category'] ?? 'Berita' }}
                              </span>
                              @if(isset($post['status']))
                                <span class="admin-badge {{ $post['status'] === 'online' ? 'admin-badge-info' : 'admin-badge-warning' }}" style="font-size: 0.68rem; padding: 1px 5px;">
                                  {{ ucfirst($post['status']) }}
                                </span>
                              @endif
                            </div>
                          </div>
                        </td>
                        {{-- Action --}}
                        <td style="text-align: right;">
                          @can('manage-posts')
                          <a href="{{ route('admin.posts.edit', $post['slug']) }}" class="admin-action-link edit" title="Edit Artikel" style="width: 28px; height: 28px;">
                            <i data-lucide="file-edit" style="width: 13px; height: 13px;"></i>
                          </a>
                          @else
                          <a href="{{ url('/informasi/' . $post['slug']) }}" target="_blank" class="admin-action-link view" title="Lihat di Web" style="width: 28px; height: 28px;">
                            <i data-lucide="eye" style="width: 13px; height: 13px;"></i>
                          </a>
                          @endcan
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4">
                <p class="mb-0 text-muted" style="font-size: 0.82rem;">Belum ada artikel.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Column 3: Sebaran Kategori + Aksi Cepat --}}
    <div class="{{ $canRfq ? 'col-xl-3 col-lg-12' : 'col-xl-5 col-lg-5' }} d-flex flex-column gap-3">

      @can('view-catalog')
      {{-- Sebaran Kategori Card --}}
      <div class="admin-card flex-grow-1" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="pie-chart" style="width: 15px; height: 15px; color: var(--color-accent);"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Sebaran Katalog</h2>
          </div>
          <span class="text-muted" style="font-size: 0.72rem;">Top Kategori</span>
        </div>
        <div class="admin-card-body p-3 d-flex flex-column justify-content-between">
          {{-- Donut Chart Mini --}}
          <div style="width: 100%; height: 125px; position: relative; margin-bottom: 10px;">
            <canvas id="categoryChart"></canvas>
          </div>

          {{-- Category Legend List --}}
          <div class="w-100" style="font-size: 0.76rem;">
            @php
              $colors = ['#A6171C', '#1E1E1E', '#F1C045', '#7A1015', '#8C8275', '#B8AF9F', '#D6D0C5', '#3D3835'];
              $ci = 0; $cc = count($colors);
              $topCats = array_slice($categoryDist, 0, 5, true);
            @endphp
            @foreach($topCats as $catName => $count)
              @php $col = $colors[$ci % $cc]; $ci++; @endphp
              <div class="d-flex justify-content-between align-items-center py-1" style="border-bottom: 1px dashed var(--color-border);">
                <span class="text-truncate d-flex align-items-center gap-2" style="max-width: 145px; color: var(--color-text-muted);">
                  <span style="width: 8px; height: 8px; border-radius: 2px; background: {{ $col }}; flex-shrink: 0;"></span>
                  {{ $catName }}
                </span>
                <span style="font-weight: 700; color: var(--color-text-main); font-size: 0.78rem;">{{ $count }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      @endcan

      {{-- Quick Action Launchpad --}}
      <div class="admin-card" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <span class="admin-card-header-label" style="font-size: 0.68rem;">Akses Instan</span>
          <span class="dash-card-link" style="color: var(--color-text-muted); font-size: 0.72rem;">Pintasan</span>
        </div>
        <div class="admin-card-body p-2">
          <div class="dash-quick-actions-grid">
            @can('manage-catalog')
            <a href="{{ route('admin.products.create') }}" class="dash-quick-btn" title="Tambah Produk Baru">
              <i data-lucide="plus-circle" style="color: #0284C7;"></i>
              <span>+ Produk</span>
            </a>
            <a href="{{ route('admin.products.create.bulk') }}" class="dash-quick-btn" title="Impor Produk Excel">
              <i data-lucide="file-spreadsheet" style="color: #16A34A;"></i>
              <span>Impor Excel</span>
            </a>
            @endcan

            @can('manage-rfq')
            <a href="{{ route('admin.rfqs.export') }}" class="dash-quick-btn" title="Ekspor RFQ ke Excel">
              <i data-lucide="download" style="color: var(--color-accent);"></i>
              <span>Ekspor RFQ</span>
            </a>
            @endcan

            @can('manage-posts')
            <a href="{{ route('admin.posts.create') }}" class="dash-quick-btn" title="Tulis Artikel Baru">
              <i data-lucide="file-plus" style="color: #16A34A;"></i>
              <span>+ Artikel</span>
            </a>
            @endcan

            @can('manage-system')
            <a href="{{ route('admin.home.edit') }}" class="dash-quick-btn" title="Edit Halaman Beranda">
              <i data-lucide="sliders" style="color: #9333EA;"></i>
              <span>Edit Web</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="dash-quick-btn" title="Kelola Akun Staf Admin">
              <i data-lucide="users" style="color: #0369A1;"></i>
              <span>Kelola Admin</span>
            </a>
            @endcan
          </div>
        </div>
      </div>

    </div>

  </div>

  @if($canSystem)
  {{-- ── 3. Google Analytics 4: Wilayah & Halaman Terpopuler ────────────────── --}}
  <div class="admin-card mb-3" id="ga4-analytics-card">
    <div class="admin-card-header py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2">
        <i data-lucide="bar-chart-2" style="width: 16px; height: 16px; color: var(--color-accent, #A6171C);"></i>
        <div class="d-flex align-items-center gap-2">
          <h2 class="admin-card-header-title mb-0" style="font-size: 0.92rem;">Google Analytics 4 • Sebaran Wilayah &amp; Produk Populer</h2>
          <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem; font-weight: 500;" id="ga4-status-badge">
            <span class="spinner-border spinner-border-sm me-1 text-primary" role="status" style="width: 10px; height: 10px;"></span> Menghubungkan...
          </span>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="dash-card-link" style="font-size: 0.76rem;" title="Pengaturan GA4">
          <i data-lucide="settings" style="width: 13px; height: 13px;"></i> Setelan GA4
        </a>
        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 d-inline-flex align-items-center gap-1" id="ga4-refresh-btn" style="font-size: 0.74rem;">
          <i data-lucide="refresh-cw" style="width: 12px; height: 12px;"></i>
          <span>Segarkan</span>
        </button>
      </div>
    </div>

    <div class="admin-card-body p-3" id="ga4-content-area">
      <div class="text-center py-4 text-muted" id="ga4-loading-indicator">
        <div class="spinner-border text-primary spinner-border-sm mb-2" role="status"></div>
        <p class="small mb-0">Menghubungkan ke Google Analytics Data API...</p>
      </div>
    </div>
  </div>
  @endif

  {{-- ── 4. Bottom Row: Pipeline Status & Ecosystem Overview ─────────────────── --}}
  <div class="row g-3">

    {{-- Pipeline Funnel --}}
    @can('manage-rfq')
    <div class="{{ $canManageCatalog ? 'col-lg-8' : 'col-12' }}">
      <div class="admin-card h-100 dash-pipeline-card" style="margin-bottom: 0; overflow: visible;">
        <div class="admin-card-header py-2 px-3">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="git-commit" style="width: 16px; height: 16px; color: var(--color-accent);"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Pipeline Status Permintaan Penawaran (RFQ)</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;">Alur Penjualan</span>
        </div>
        <div class="admin-card-body p-3" style="overflow: visible;">

          @php
            $totRfq = max(1, $rfqsCount);
            $pNew = round(($rfqPipeline['new'] / $totRfq) * 100);
            $pContacted = round(($rfqPipeline['contacted'] / $totRfq) * 100);
            $pQuoted = round(($rfqPipeline['quoted'] / $totRfq) * 100);
            $pClosed = round(($rfqPipeline['closed'] / $totRfq) * 100);
          @endphp

          {{-- Multi-stage Visual Progress Bar --}}
          <div class="dash-pipeline-container position-relative mb-3">
            <div class="dash-pipeline-bar" id="rfq-pipeline-bar">
              <a href="{{ route('admin.rfqs.index', ['status' => 'new']) }}"
                 class="dash-bar-segment text-decoration-none"
                 style="width: {{ $pNew }}%; background: #F59E0B;"
                 data-status="new"
                 title="Baru Masuk: {{ $rfqPipeline['new'] }} ({{ $pNew }}%)"></a>
              <a href="{{ route('admin.rfqs.index', ['status' => 'contacted']) }}"
                 class="dash-bar-segment text-decoration-none"
                 style="width: {{ $pContacted }}%; background: #0284C7;"
                 data-status="contacted"
                 title="Dihubungi: {{ $rfqPipeline['contacted'] }} ({{ $pContacted }}%)"></a>
              <a href="{{ route('admin.rfqs.index', ['status' => 'quoted']) }}"
                 class="dash-bar-segment text-decoration-none"
                 style="width: {{ $pQuoted }}%; background: var(--color-accent, #A6171C);"
                 data-status="quoted"
                 title="Penawaran: {{ $rfqPipeline['quoted'] }} ({{ $pQuoted }}%)"></a>
              <a href="{{ route('admin.rfqs.index', ['status' => 'closed']) }}"
                 class="dash-bar-segment text-decoration-none"
                 style="width: {{ $pClosed }}%; background: #10B981;"
                 data-status="closed"
                 title="Selesai: {{ $rfqPipeline['closed'] }} ({{ $pClosed }}%)"></a>
            </div>
          </div>

          {{-- 4 Stage Pipeline Cards --}}
          <div class="row g-2">
            <div class="col-6 col-md-3">
              <a href="{{ route('admin.rfqs.index', ['status' => 'new']) }}"
                 class="dash-funnel-card text-decoration-none"
                 data-status="new"
                 data-title="Baru Masuk"
                 data-count="{{ $rfqPipeline['new'] }}"
                 data-pct="{{ $pNew }}"
                 data-color="#F59E0B">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="dash-stage-dot" style="background: #F59E0B;"></span>
                  <span class="dash-stage-title">Baru Masuk</span>
                </div>
                <div class="dash-stage-count" style="color: #B45309;">{{ $rfqPipeline['new'] }}</div>
                <span class="dash-stage-sub">Perlu respon tim</span>
              </a>
            </div>

            <div class="col-6 col-md-3">
              <a href="{{ route('admin.rfqs.index', ['status' => 'contacted']) }}"
                 class="dash-funnel-card text-decoration-none"
                 data-status="contacted"
                 data-title="Dihubungi"
                 data-count="{{ $rfqPipeline['contacted'] }}"
                 data-pct="{{ $pContacted }}"
                 data-color="#0284C7">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="dash-stage-dot" style="background: #0284C7;"></span>
                  <span class="dash-stage-title">Dihubungi</span>
                </div>
                <div class="dash-stage-count" style="color: #0369A1;">{{ $rfqPipeline['contacted'] }}</div>
                <span class="dash-stage-sub">Sedang negosiasi</span>
              </a>
            </div>

            <div class="col-6 col-md-3">
              <a href="{{ route('admin.rfqs.index', ['status' => 'quoted']) }}"
                 class="dash-funnel-card text-decoration-none"
                 data-status="quoted"
                 data-title="Penawaran"
                 data-count="{{ $rfqPipeline['quoted'] }}"
                 data-pct="{{ $pQuoted }}"
                 data-color="#A6171C">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="dash-stage-dot" style="background: var(--color-accent, #A6171C);"></span>
                  <span class="dash-stage-title">Penawaran</span>
                </div>
                <div class="dash-stage-count" style="color: var(--color-accent, #A6171C);">{{ $rfqPipeline['quoted'] }}</div>
                <span class="dash-stage-sub">Quotation terkirim</span>
              </a>
            </div>

            <div class="col-6 col-md-3">
              <a href="{{ route('admin.rfqs.index', ['status' => 'closed']) }}"
                 class="dash-funnel-card text-decoration-none"
                 data-status="closed"
                 data-title="Selesai"
                 data-count="{{ $rfqPipeline['closed'] }}"
                 data-pct="{{ $pClosed }}"
                 data-color="#10B981">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <span class="dash-stage-dot" style="background: #10B981;"></span>
                  <span class="dash-stage-title">Selesai</span>
                </div>
                <div class="dash-stage-count" style="color: #047857;">{{ $rfqPipeline['closed'] }}</div>
                <span class="dash-stage-sub">Arsip &amp; ditutup</span>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
    @endcan

    {{-- Ecosystem & Partnership Hub --}}
    @can('manage-catalog')
    <div class="{{ $canRfq ? 'col-lg-4' : 'col-12' }}">
      <div class="admin-card h-100" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="award" style="width: 16px; height: 16px; color: var(--color-accent);"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Mitra &amp; Kategori</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;">Katalog</span>
        </div>
        <div class="admin-card-body p-3 d-flex flex-column justify-content-between">
          <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
              <div class="d-flex align-items-center gap-2">
                <i data-lucide="award" style="width: 16px; height: 16px; color: var(--color-accent);"></i>
                <span style="font-size: 0.82rem; font-weight: 600; color: var(--color-text-main);">Prinsipal / Mitra</span>
              </div>
              <a href="{{ route('admin.principals') }}" class="dash-item-link" style="font-size: 0.82rem;">
                {{ $principalsCount }} Pabrikan &rarr;
              </a>
            </div>

            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: var(--color-surface-2); border: 1px solid var(--color-border);">
              <div class="d-flex align-items-center gap-2">
                <i data-lucide="folder-tree" style="width: 16px; height: 16px; color: #0284C7;"></i>
                <span style="font-size: 0.82rem; font-weight: 600; color: var(--color-text-main);">Kategori Taksonomi</span>
              </div>
              <a href="{{ route('admin.categories.index') }}" class="dash-item-link" style="font-size: 0.82rem; color: #0284C7;">
                {{ $categoriesCount }} Kategori &rarr;
              </a>
            </div>
          </div>

          <div class="mt-2 pt-2 d-flex align-items-center justify-content-between" style="border-top: 1px dashed var(--color-border);">
            <a href="{{ route('admin.guide') }}" class="dash-card-link" style="color: var(--color-text-secondary); font-size: 0.76rem;">
              <i data-lucide="book-open" style="width: 13px; height: 13px;"></i> Panduan Admin
            </a>
            <a href="{{ url('/') }}" target="_blank" class="dash-card-link" style="font-size: 0.76rem;">
              Buka Web <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    @endcan

  </div>

</div>

<style>
  /* ── Dashboard Compact Viewport Styling ──────────────────────────────────── */
  .dash-wrapper {
    max-width: 100%;
  }

  /* Micro Stat Cards */
  .dash-stat-card {
    background: #FFFFFF;
    border: 1px solid var(--color-border);
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: var(--shadow-xs);
    position: relative;
    transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    cursor: pointer;
  }
  .dash-stat-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
  }
  .dash-stat-icon-wrap {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .dash-stat-icon-wrap i {
    width: 19px;
    height: 19px;
  }
  .dash-stat-content {
    flex-grow: 1;
    min-width: 0;
  }
  .dash-stat-label {
    display: block;
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: var(--color-text-muted);
    margin-bottom: 2px;
  }
  .dash-stat-val {
    font-family: var(--font-headline);
    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.1;
    color: var(--color-text-main);
  }
  .dash-stat-arrow {
    color: #9CA3AF;
    padding: 4px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s ease, transform 0.15s ease;
  }
  .dash-stat-card:hover .dash-stat-arrow {
    color: var(--color-accent);
    transform: translate(2px, -2px);
  }
  .dash-stat-arrow i {
    width: 16px;
    height: 16px;
  }

  /* Compact Table Rules */
  .dash-compact-table th {
    padding: 7px 10px !important;
    font-size: 0.74rem !important;
    letter-spacing: 0.3px;
    background: var(--color-surface-2) !important;
    border-bottom: 1px solid var(--color-border) !important;
  }
  .dash-compact-table td {
    padding: 7px 10px !important;
    vertical-align: middle;
    font-size: 0.82rem;
  }
  .dash-item-link {
    font-weight: 700;
    color: var(--color-accent);
    text-decoration: none;
    font-size: 0.83rem;
  }
  .dash-item-link:hover {
    text-decoration: underline;
  }
  .dash-item-title-link {
    color: var(--color-text-main);
    font-weight: 600;
    font-size: 0.82rem;
    text-decoration: none;
    display: block;
    line-height: 1.3;
  }
  .dash-item-title-link:hover {
    color: var(--color-accent);
  }
  .dash-text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .dash-card-link {
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--color-accent);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s ease;
  }
  .dash-card-link:hover {
    color: var(--color-accent-hover);
  }

  /* Mini Thumbnail */
  .dash-mini-thumb {
    width: 38px;
    height: 28px;
    border-radius: 6px;
    border: 1px solid var(--color-border);
    background: var(--color-surface-2);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin: 0 auto;
  }
  .dash-mini-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* Segmented Switcher Controls */
  .dash-segmented-control {
    display: flex;
    align-items: center;
    gap: 2px;
    background: var(--color-surface-2);
    padding: 2px;
    border-radius: 8px;
    border: 1px solid var(--color-border);
  }
  .dash-segment-btn {
    border: 1px solid transparent;
    background: transparent;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
  }
  .dash-segment-btn.active {
    background: #FFFFFF;
    color: var(--color-text-main);
    border-color: var(--color-border);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  }
  .dash-segment-btn:hover:not(.active) {
    color: var(--color-text-main);
    background: rgba(0, 0, 0, 0.03);
  }

  /* Quick Actions Grid */
  .dash-quick-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
  }
  .dash-quick-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 10px;
    background: var(--color-surface-2);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    text-decoration: none;
    color: var(--color-text-main);
    font-size: 0.77rem;
    font-weight: 600;
    transition: all 0.15s ease;
  }
  .dash-quick-btn:hover {
    background: #FFFFFF;
    border-color: #CBD5E1;
    color: var(--color-accent);
    box-shadow: var(--shadow-xs);
    transform: translateY(-1px);
  }
  .dash-quick-btn i {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
  }

  /* Pipeline Bar & Floating Chart.js-like Tooltip */
  .dash-pipeline-card,
  .dash-pipeline-card .admin-card-body {
    overflow: visible !important;
  }
  .dash-pipeline-container {
    position: relative;
  }
  .dash-pipeline-bar {
    display: flex;
    height: 12px;
    border-radius: 999px;
    background: var(--color-surface-2);
    border: 1px solid var(--color-border);
    position: relative;
    cursor: pointer;
    overflow: hidden;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.06);
  }
  .dash-bar-segment {
    height: 100%;
    transition: transform 0.18s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.18s ease,
                filter 0.18s ease;
    transform-origin: center bottom;
    position: relative;
    cursor: pointer;
  }
  .dash-bar-segment:hover,
  .dash-bar-segment.is-hovered {
    filter: brightness(1.18);
    transform: scaleY(1.35);
    z-index: 2;
  }
  .dash-pipeline-bar.has-hover .dash-bar-segment:not(.is-hovered):not(:hover) {
    opacity: 0.45;
  }

  .dash-funnel-card {
    display: block;
    background: var(--color-surface-2);
    border: 1px solid var(--color-border);
    border-radius: 8px;
    padding: 10px 12px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
  }
  .dash-funnel-card:hover,
  .dash-funnel-card.is-active-card {
    background: #FFFFFF;
    border-color: #94A3B8;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }
  .dash-stage-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .dash-stage-title {
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--color-text-main);
  }
  .dash-stage-count {
    font-family: var(--font-headline);
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.1;
  }
  .dash-stage-sub {
    display: block;
    font-size: 0.68rem;
    color: var(--color-text-muted);
    margin-top: 2px;
  }

  @media (min-width: 1200px) {
    .admin-body {
      padding: 18px 24px !important;
    }
  }
</style>

@endsection

@section('admin_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js" integrity="sha384-T/4KgSWuZEPozpPz7rnnp/5lDSnpY1VPJCojf1S81uTHS1E38qgLfMgVsAeRCWc4" crossorigin="anonymous" @nonce></script>
<script @nonce>
  // Strictly CSP-Compliant Segmented Tab Switcher
  function switchDashTab(tab) {
    const isProd = tab === 'products';
    const prodPanel = document.getElementById('dash-panel-products');
    const postPanel = document.getElementById('dash-panel-posts');
    const prodBtn = document.getElementById('tab-btn-products');
    const postBtn = document.getElementById('tab-btn-posts');
    const addBtn = document.getElementById('tab-add-btn');
    const addText = document.getElementById('tab-add-text');

    if (prodPanel) prodPanel.style.display = isProd ? 'block' : 'none';
    if (postPanel) postPanel.style.display = isProd ? 'none' : 'block';

    if (prodBtn) prodBtn.classList.toggle('active', isProd);
    if (postBtn) postBtn.classList.toggle('active', !isProd);

    if (addBtn) {
      @can('manage-catalog')
      if (isProd) {
        addBtn.style.display = 'inline-flex';
        addBtn.href = "{{ route('admin.products.create') }}";
        if (addText) addText.textContent = 'Tambah Produk';
      }
      @else
      if (isProd) {
        addBtn.style.display = 'none';
      }
      @endcan
      @can('manage-posts')
      if (!isProd) {
        addBtn.style.display = 'inline-flex';
        addBtn.href = "{{ route('admin.posts.create') }}";
        if (addText) addText.textContent = 'Tambah Artikel';
      }
      @else
      if (!isProd) {
        addBtn.style.display = 'none';
      }
      @endcan
    }

    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners via JS for CSP compliance (NO inline onclick)
    const segmentBtns = document.querySelectorAll('.dash-segment-btn');
    segmentBtns.forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const tabKey = this.getAttribute('data-tab');
        switchDashTab(tabKey);
      });
    });

    // Initialize Donut Chart
    const el = document.getElementById('categoryChart');
    if (el) {
      const ctx = el.getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: {!! json_encode(array_keys($categoryDist)) !!},
          datasets: [{
            data: {!! json_encode(array_values($categoryDist)) !!},
            backgroundColor: ['#A6171C', '#1E1E1E', '#F1C045', '#7A1015', '#8C8275', '#B8AF9F', '#D6D0C5', '#3D3835'],
            hoverOffset: 4,
            borderWidth: 2,
            borderColor: '#FFFFFF'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              padding: 8,
              cornerRadius: 6,
              bodyFont: { size: 11 }
            }
          },
          cutout: '70%'
        }
      });
    }

    // RFQ Pipeline Segment & Card Cross-Hover Sync
    const pipelineBar = document.getElementById('rfq-pipeline-bar');
    const barSegments = document.querySelectorAll('.dash-bar-segment');
    const funnelCards = document.querySelectorAll('.dash-funnel-card');

    barSegments.forEach(function(segment) {
      segment.addEventListener('mouseenter', function() {
        const status = this.getAttribute('data-status');
        if (pipelineBar) pipelineBar.classList.add('has-hover');
        this.classList.add('is-hovered');

        const matchingCard = document.querySelector('.dash-funnel-card[data-status="' + status + '"]');
        if (matchingCard) matchingCard.classList.add('is-active-card');
      });

      segment.addEventListener('mouseleave', function() {
        if (pipelineBar) pipelineBar.classList.remove('has-hover');
        this.classList.remove('is-hovered');
        funnelCards.forEach(function(c) { c.classList.remove('is-active-card'); });
      });
    });

    funnelCards.forEach(function(card) {
      card.addEventListener('mouseenter', function() {
        const status = this.getAttribute('data-status');
        this.classList.add('is-active-card');
        if (pipelineBar) pipelineBar.classList.add('has-hover');

        const matchingSegment = document.querySelector('.dash-bar-segment[data-status="' + status + '"]');
        if (matchingSegment) matchingSegment.classList.add('is-hovered');
      });

      card.addEventListener('mouseleave', function() {
        this.classList.remove('is-active-card');
        if (pipelineBar) pipelineBar.classList.remove('has-hover');
        barSegments.forEach(function(s) { s.classList.remove('is-hovered'); });
      });
    });

    // ── Google Analytics 4 Dashboard Integration ──────────────────────────
    @if($canSystem)
    const ga4Card = document.getElementById('ga4-analytics-card');
    const ga4Content = document.getElementById('ga4-content-area');
    const ga4Badge = document.getElementById('ga4-status-badge');
    const ga4RefreshBtn = document.getElementById('ga4-refresh-btn');

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function renderGa4Prompt(msg) {
      if (!ga4Content) return;
      ga4Content.innerHTML = `
        <div class="p-3 rounded text-center" style="background: var(--color-surface-2, #F8FAFC); border: 1px dashed var(--color-border, #E2E8F0);">
          <div class="d-inline-flex p-2 rounded-circle mb-2" style="background: #FEF3C7; color: #D97706;">
            <i data-lucide="key" style="width: 20px; height: 20px;"></i>
          </div>
          <h3 class="h6 fw-bold mb-1" style="color: var(--color-text-main);">Robot Service Account Siap • Membutuhkan GA4 Property ID</h3>
          <p class="small text-muted mb-3 mx-auto" style="max-width: 580px;">
            Kredensial robot Google Service Account (<code>analytics-reader</code>) sudah terpasang. Untuk menampilkan live grafik sebaran provinsi & produk populer, masukkan 9 digit Property ID dari Google Analytics Anda.
          </p>
          <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.home.edit', ['section' => 'general']) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
              <i data-lucide="settings" style="width: 14px; height: 14px;"></i>
              <span>Isi GA4 Property ID</span>
            </a>
          </div>
        </div>
      `;
      if (window.lucide) { lucide.createIcons(); }
    }

    function renderGa4Error(msg) {
      if (!ga4Content) return;
      ga4Content.innerHTML = `
        <div class="p-3 rounded text-center text-danger" style="background: #FEF2F2; border: 1px solid #FEE2E2;">
          <div class="d-inline-flex p-2 rounded-circle mb-2" style="background: #FEE2E2; color: #DC2626;">
            <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
          </div>
          <h3 class="h6 fw-bold mb-1">Gagal Mengambil Data Google Analytics</h3>
          <p class="small mb-0 text-secondary">${escapeHtml(msg)}</p>
        </div>
      `;
      if (window.lucide) { lucide.createIcons(); }
    }

    function renderGa4Data(res) {
      if (!ga4Content) return;
      const regions = res.regions || [];
      const topPages = res.top_pages || [];
      const maxUsers = Math.max(1, res.max_users || 1);

      let regionsHtml = '';
      if (regions.length === 0) {
        regionsHtml = '<div class="text-muted small py-3 text-center">Belum ada data wilayah pengunjung dalam 30 hari terakhir.</div>';
      } else {
        regionsHtml = '<div class="d-flex flex-column gap-2">';
        regions.forEach(function(r) {
          const pct = Math.min(100, Math.round((r.users / maxUsers) * 100));
          regionsHtml += `
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1 small">
                <span class="fw-semibold text-truncate" style="max-width: 180px;">${escapeHtml(r.name)}</span>
                <span class="text-muted" style="font-size: 0.76rem;"><strong>${r.users}</strong> pengguna (${r.views} tayangan)</span>
              </div>
              <div class="progress" style="height: 6px; background-color: var(--color-surface-2, #E2E8F0); border-radius: 3px;">
                <div class="progress-bar" style="width: ${pct}%; background-color: var(--color-accent, #A6171C); border-radius: 3px;"></div>
              </div>
            </div>
          `;
        });
        regionsHtml += '</div>';
      }

      let pagesHtml = '';
      if (topPages.length === 0) {
        pagesHtml = '<div class="text-muted small py-3 text-center">Belum ada kunjungan halaman dalam 30 hari terakhir.</div>';
      } else {
        pagesHtml = '<div class="list-group list-group-flush">';
        topPages.forEach(function(p, idx) {
          pagesHtml += `
            <div class="list-group-item px-0 py-2 border-bottom d-flex align-items-center justify-content-between gap-2" style="background: transparent;">
              <div class="d-flex align-items-center gap-2 overflow-hidden">
                <span class="badge rounded-circle bg-light text-secondary border d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.7rem; flex-shrink: 0;">${idx + 1}</span>
                <div class="overflow-hidden">
                  <div class="fw-medium text-truncate small" style="color: var(--color-text-main);">${escapeHtml(p.label)}</div>
                  <div class="text-muted text-truncate font-monospace" style="font-size: 0.7rem;">${escapeHtml(p.path)}</div>
                </div>
              </div>
              <div class="text-end flex-shrink-0" style="font-size: 0.78rem;">
                <span class="badge bg-light text-dark border px-2 py-1"><strong>${p.views}</strong> views</span>
              </div>
            </div>
          `;
        });
        pagesHtml += '</div>';
      }

      ga4Content.innerHTML = `
        <div class="row g-3">
          <div class="col-lg-6">
            <div class="p-3 rounded h-100" style="background: var(--color-surface-2, #FAFAFA); border: 1px solid var(--color-border, #E5E7EB);">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                  <i data-lucide="map-pin" style="width: 15px; height: 15px; color: var(--color-accent, #A6171C);"></i>
                  <h3 class="h6 mb-0 fw-bold" style="font-size: 0.86rem;">Sebaran Pengunjung per Wilayah (Provinsi)</h3>
                </div>
                <span class="text-muted" style="font-size: 0.72rem;">Total: ${res.total_users || 0} Pengguna</span>
              </div>
              ${regionsHtml}
            </div>
          </div>
          <div class="col-lg-6">
            <div class="p-3 rounded h-100" style="background: var(--color-surface-2, #FAFAFA); border: 1px solid var(--color-border, #E5E7EB);">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                  <i data-lucide="compass" style="width: 15px; height: 15px; color: #0284C7;"></i>
                  <h3 class="h6 mb-0 fw-bold" style="font-size: 0.86rem;">Halaman &amp; Produk Paling Populer</h3>
                </div>
                <span class="text-muted" style="font-size: 0.72rem;">Total: ${res.total_views || 0} Tayangan</span>
              </div>
              ${pagesHtml}
            </div>
          </div>
        </div>
      `;
      if (window.lucide) { lucide.createIcons(); }
    }

    function loadGa4Analytics(forceRefresh = false) {
      if (!ga4Content) return;
      const url = '{{ route('admin.analytics.data') }}' + (forceRefresh ? '?refresh=1' : '');
      if (ga4Badge) {
        ga4Badge.innerHTML = '<span class="spinner-border spinner-border-sm me-1 text-primary" role="status" style="width: 10px; height: 10px;"></span> Memuat...';
        ga4Badge.className = 'badge bg-light text-secondary border px-2 py-1';
      }

      fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(function(res) { return res.json(); })
        .then(function(res) {
          if (res.status === 'success') {
            if (ga4Badge) {
              ga4Badge.textContent = 'Terhubung • ' + (res.updated_at || 'Baru saja');
              ga4Badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 py-1';
            }
            renderGa4Data(res);
          } else if (res.status === 'needs_property_id') {
            if (ga4Badge) {
              ga4Badge.textContent = 'Perlu Property ID';
              ga4Badge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1';
            }
            renderGa4Prompt(res.message);
          } else {
            if (ga4Badge) {
              ga4Badge.textContent = 'Koneksi Terhambat';
              ga4Badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1';
            }
            renderGa4Error(res.message);
          }
        })
        .catch(function(err) {
          if (ga4Badge) {
            ga4Badge.textContent = 'Offline';
            ga4Badge.className = 'badge bg-secondary-subtle text-secondary border px-2 py-1';
          }
          renderGa4Error('Gagal memuat analitik: ' + err.message);
        });
    }

    if (ga4RefreshBtn) {
      ga4RefreshBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loadGa4Analytics(true);
      });
    }

    // Auto-trigger GA4 loader
    loadGa4Analytics();
    @endif
  });
</script>
@endsection
