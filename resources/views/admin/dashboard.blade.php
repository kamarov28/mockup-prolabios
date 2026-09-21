@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('admin_content')

<div class="dash-cockpit-wrapper">

  {{-- ── Header Strip & Status ────────────────────────────────────────────────── --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
      <span class="admin-page-label">Ikhtisar Sistem</span>
      <h2 class="admin-page-title mb-0" style="font-size: 1.25rem;">Command Center Prolabios</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
      <div class="dash-status-pill">
        <span class="dash-status-dot"></span>
        <span>Sistem Aktif &bull; {{ now()->translatedFormat('d M Y') }}</span>
      </div>
    </div>
  </div>

  {{-- ── 1. KPI Micro-Stat Cards ──────────────────────────────────────────────── --}}
  <div class="row g-3 mb-3">

    {{-- RFQ Card --}}
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

    {{-- Products Card --}}
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

    {{-- Posts Card --}}
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

    {{-- Sectors Card --}}
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

  </div>

  {{-- ── 2. Main Cockpit Grid (3 Columns on Desktop) ─────────────────────────── --}}
  <div class="row g-3 mb-3">

    {{-- Column 1: RFQ Inquiry Masuk (col-xl-5) --}}
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

    {{-- Column 2: Segmented Switcher (Produk & Artikel) (col-xl-4) --}}
    <div class="col-xl-4 col-lg-6">
      <div class="admin-card h-100 d-flex flex-column" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          {{-- Interactive Segmented Switcher --}}
          <div class="dash-segmented-control" role="tablist">
            <button type="button" class="dash-segment-btn active" data-tab="products" id="tab-btn-products">
              <i data-lucide="package" style="width: 13px; height: 13px;"></i>
              <span>Produk ({{ count($recentProducts) }})</span>
            </button>
            <button type="button" class="dash-segment-btn" data-tab="posts" id="tab-btn-posts">
              <i data-lucide="file-text" style="width: 13px; height: 13px;"></i>
              <span>Artikel ({{ count($recentPosts) }})</span>
            </button>
          </div>

          {{-- Quick Add Dynamic Link --}}
          <a href="{{ route('admin.products.create') }}" id="tab-add-btn" class="dash-card-link">
            <i data-lucide="plus" style="width: 13px; height: 13px;"></i>
            <span id="tab-add-text">Tambah</span>
          </a>
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
                            <a href="{{ route('admin.products.edit', $p['id']) }}" class="dash-item-title-link">
                              {{ $p['title'] }}
                            </a>
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
                          <a href="{{ route('admin.products.edit', $p['id']) }}" class="admin-action-link edit" title="Edit Produk" style="width: 28px; height: 28px;">
                            <i data-lucide="file-edit" style="width: 13px; height: 13px;"></i>
                          </a>
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
          <div id="dash-panel-posts" class="dash-tab-panel" style="display: none;">
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
                          <a href="{{ route('admin.posts.edit', $post['slug']) }}" class="admin-action-link edit" title="Edit Artikel" style="width: 28px; height: 28px;">
                            <i data-lucide="file-edit" style="width: 13px; height: 13px;"></i>
                          </a>
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

    {{-- Column 3: Sebaran Kategori + Aksi Cepat (col-xl-3) --}}
    <div class="col-xl-3 col-lg-12 d-flex flex-column gap-3">

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

      {{-- Quick Action Launchpad --}}
      <div class="admin-card" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <span class="admin-card-header-label" style="font-size: 0.68rem;">Akses Instan</span>
          <span class="dash-card-link" style="color: var(--color-text-muted); font-size: 0.72rem;">Pintasan</span>
        </div>
        <div class="admin-card-body p-2">
          <div class="dash-quick-actions-grid">
            <a href="{{ route('admin.products.create') }}" class="dash-quick-btn" title="Tambah Produk Baru">
              <i data-lucide="plus-circle" style="color: #0284C7;"></i>
              <span>+ Produk</span>
            </a>
            <a href="{{ route('admin.products.create.bulk') }}" class="dash-quick-btn" title="Impor Produk Excel">
              <i data-lucide="file-spreadsheet" style="color: #16A34A;"></i>
              <span>Impor Excel</span>
            </a>
            <a href="{{ route('admin.rfqs.export') }}" class="dash-quick-btn" title="Ekspor RFQ ke Excel">
              <i data-lucide="download" style="color: var(--color-accent);"></i>
              <span>Ekspor RFQ</span>
            </a>
            <a href="{{ route('admin.home.edit') }}" class="dash-quick-btn" title="Edit Halaman Beranda">
              <i data-lucide="sliders" style="color: #9333EA;"></i>
              <span>Edit Web</span>
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>

  {{-- ── 3. Bottom Row: Pipeline Status & Ecosystem Overview ─────────────────── --}}
  <div class="row g-3">

    {{-- Pipeline Funnel (col-lg-8) --}}
    <div class="col-lg-8">
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

          {{-- Multi-stage Visual Progress Bar with Interactive Chart.js-like Tooltip --}}
          <div class="dash-pipeline-container position-relative mb-3 pt-4">
            {{-- Floating Chart.js-like Tooltip --}}
            <div id="dash-pipeline-tooltip" class="dash-chart-tooltip" role="tooltip" style="display: none; opacity: 0;">
              <div class="dash-tooltip-body">
                <span class="dash-tooltip-box" id="dash-tt-box"></span>
                <span class="dash-tooltip-label" id="dash-tt-label"></span>
                <span class="dash-tooltip-val" id="dash-tt-val"></span>
              </div>
              <div class="dash-tooltip-caret"></div>
            </div>

            <div class="dash-pipeline-bar" id="rfq-pipeline-bar">
              <div class="dash-bar-segment"
                   style="width: {{ $pNew }}%; background: #F59E0B;"
                   data-status="new"
                   data-title="Baru Masuk"
                   data-count="{{ $rfqPipeline['new'] }}"
                   data-pct="{{ $pNew }}"
                   data-color="#F59E0B"></div>
              <div class="dash-bar-segment"
                   style="width: {{ $pContacted }}%; background: #0284C7;"
                   data-status="contacted"
                   data-title="Dihubungi"
                   data-count="{{ $rfqPipeline['contacted'] }}"
                   data-pct="{{ $pContacted }}"
                   data-color="#0284C7"></div>
              <div class="dash-bar-segment"
                   style="width: {{ $pQuoted }}%; background: var(--color-accent, #A6171C);"
                   data-status="quoted"
                   data-title="Penawaran"
                   data-count="{{ $rfqPipeline['quoted'] }}"
                   data-pct="{{ $pQuoted }}"
                   data-color="#A6171C"></div>
              <div class="dash-bar-segment"
                   style="width: {{ $pClosed }}%; background: #10B981;"
                   data-status="closed"
                   data-title="Selesai"
                   data-count="{{ $rfqPipeline['closed'] }}"
                   data-pct="{{ $pClosed }}"
                   data-color="#10B981"></div>
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

    {{-- Ecosystem & Partnership Hub (col-lg-4) --}}
    <div class="col-lg-4">
      <div class="admin-card h-100" style="margin-bottom: 0;">
        <div class="admin-card-header py-2 px-3">
          <div class="d-flex align-items-center gap-2">
            <i data-lucide="network" style="width: 16px; height: 16px; color: var(--color-accent);"></i>
            <h2 class="admin-card-header-title" style="font-size: 0.92rem;">Ekosistem &amp; Kemitraan</h2>
          </div>
          <span class="text-muted" style="font-size: 0.74rem;">Katalog Lab</span>
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

  </div>

</div>

<style>
  /* ── Dashboard Compact Viewport Styling ──────────────────────────────────── */
  .dash-cockpit-wrapper {
    max-width: 100%;
  }

  /* Status Pill with Pulsing Live Dot */
  .dash-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px;
    background: #FFFFFF;
    border: 1px solid var(--color-border);
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    box-shadow: var(--shadow-xs);
  }
  .dash-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10B981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    animation: dashPulse 2s infinite ease-in-out;
  }
  @keyframes dashPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.85); }
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

  /* Chart.js Identical Floating Tooltip */
  .dash-chart-tooltip {
    position: absolute;
    z-index: 1050;
    pointer-events: none;
    background: rgba(26, 26, 26, 0.94);
    color: #FFFFFF;
    border-radius: 6px;
    padding: 6px 10px;
    font-family: var(--font-body, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
    font-size: 0.74rem;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28);
    backdrop-filter: blur(4px);
    transform: translate(-50%, -100%);
    white-space: nowrap;
    transition: opacity 0.12s ease, left 0.12s cubic-bezier(0.4, 0, 0.2, 1), top 0.12s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .dash-chart-tooltip.show {
    display: block !important;
    opacity: 1 !important;
  }
  .dash-tooltip-body {
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .dash-tooltip-box {
    width: 9px;
    height: 9px;
    border-radius: 2px;
    display: inline-block;
    flex-shrink: 0;
  }
  .dash-tooltip-label {
    font-weight: 600;
    color: #E2E8F0;
  }
  .dash-tooltip-val {
    font-weight: 700;
    color: #FFFFFF;
  }
  .dash-tooltip-caret {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid rgba(26, 26, 26, 0.94);
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
      addBtn.href = isProd ? "{{ route('admin.products.create') }}" : "{{ route('admin.posts.create') }}";
      if (addText) {
        addText.textContent = isProd ? 'Tambah Produk' : 'Tambah Artikel';
      }
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

    // RFQ Pipeline Interactive Chart.js-like Tooltip & Cross-Hover
    const pipelineBar = document.getElementById('rfq-pipeline-bar');
    const pipelineContainer = document.querySelector('.dash-pipeline-container');
    const tooltip = document.getElementById('dash-pipeline-tooltip');
    const ttBox = document.getElementById('dash-tt-box');
    const ttLabel = document.getElementById('dash-tt-label');
    const ttVal = document.getElementById('dash-tt-val');
    const barSegments = document.querySelectorAll('.dash-bar-segment');
    const funnelCards = document.querySelectorAll('.dash-funnel-card');

    function showPipelineTooltip(title, count, pct, color, targetEl) {
      if (!tooltip || !targetEl || !pipelineContainer) return;
      if (ttBox) ttBox.style.backgroundColor = color;
      if (ttLabel) ttLabel.textContent = title + ':';
      if (ttVal) ttVal.textContent = count + ' (' + pct + '%)';

      tooltip.style.display = 'block';
      tooltip.style.visibility = 'hidden';
      const ttWidth = tooltip.offsetWidth || 130;
      const halfWidth = ttWidth / 2;

      const contRect = pipelineContainer.getBoundingClientRect();
      const targetRect = targetEl.getBoundingClientRect();

      const centerX = (targetRect.left + targetRect.width / 2) - contRect.left;
      const topY = targetRect.top - contRect.top - 7;

      // Clamp horizontal center inside container so tooltip is never clipped on left/right edges
      const padding = 10;
      const minCenter = halfWidth + padding;
      const maxCenter = Math.max(minCenter, contRect.width - halfWidth - padding);
      const clampedX = Math.max(minCenter, Math.min(centerX, maxCenter));

      // Dynamically align caret to point at target segment center even when tooltip is clamped
      const caretOffset = centerX - clampedX;
      const caret = tooltip.querySelector('.dash-tooltip-caret');
      if (caret) {
        const maxShift = Math.max(0, halfWidth - 14);
        const clampedShift = Math.max(-maxShift, Math.min(caretOffset, maxShift));
        caret.style.left = 'calc(50% + ' + clampedShift + 'px)';
      }

      tooltip.style.left = clampedX + 'px';
      tooltip.style.top = topY + 'px';
      tooltip.style.visibility = 'visible';
      void tooltip.offsetWidth;
      tooltip.classList.add('show');
    }

    function hidePipelineTooltip() {
      if (!tooltip) return;
      tooltip.classList.remove('show');
      setTimeout(function() {
        if (!tooltip.classList.contains('show')) {
          tooltip.style.display = 'none';
        }
      }, 120);
      const caret = tooltip.querySelector('.dash-tooltip-caret');
      if (caret) caret.style.left = '50%';
      if (pipelineBar) pipelineBar.classList.remove('has-hover');
      barSegments.forEach(function(s) { s.classList.remove('is-hovered'); });
      funnelCards.forEach(function(c) { c.classList.remove('is-active-card'); });
    }

    barSegments.forEach(function(segment) {
      segment.addEventListener('mouseenter', function() {
        const status = this.getAttribute('data-status');
        const title = this.getAttribute('data-title');
        const count = this.getAttribute('data-count');
        const pct = this.getAttribute('data-pct');
        const color = this.getAttribute('data-color');

        if (pipelineBar) pipelineBar.classList.add('has-hover');
        this.classList.add('is-hovered');

        const matchingCard = document.querySelector('.dash-funnel-card[data-status="' + status + '"]');
        if (matchingCard) matchingCard.classList.add('is-active-card');

        showPipelineTooltip(title, count, pct, color, this);
      });

      segment.addEventListener('mouseleave', hidePipelineTooltip);

      segment.addEventListener('click', function() {
        const status = this.getAttribute('data-status');
        window.location.href = "{{ route('admin.rfqs.index') }}?status=" + encodeURIComponent(status);
      });
    });

    funnelCards.forEach(function(card) {
      card.addEventListener('mouseenter', function() {
        const status = this.getAttribute('data-status');
        const title = this.getAttribute('data-title');
        const count = this.getAttribute('data-count');
        const pct = this.getAttribute('data-pct');
        const color = this.getAttribute('data-color');

        this.classList.add('is-active-card');
        if (pipelineBar) pipelineBar.classList.add('has-hover');

        const matchingSegment = document.querySelector('.dash-bar-segment[data-status="' + status + '"]');
        if (matchingSegment && matchingSegment.offsetWidth > 0) {
          matchingSegment.classList.add('is-hovered');
          showPipelineTooltip(title, count, pct, color, matchingSegment);
        } else {
          showPipelineTooltip(title, count, pct, color, this);
        }
      });

      card.addEventListener('mouseleave', hidePipelineTooltip);
    });
  });
</script>
@endsection
