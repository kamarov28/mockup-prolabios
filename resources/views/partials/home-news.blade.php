<!-- 6. Technical Insights & Articles — Editorial Bento Grid (Kristi-inspired) -->
<section class="section-spacious typo-news-section">
  <div class="container">
    <!-- Section Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">Wawasan &amp; Edukasi Laboratorium</h2>
        <p class="typo-section-sub">Panduan aplikasi pengujian, update regulasi ISO/BPOM, dan wawasan teknis analis lab.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="{{ url('/informasi') }}" class="typo-btn-link" style="font-size: 0.85rem;" aria-label="Lihat semua artikel dan informasi">
          Lihat Semua Artikel <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>

    @if(count($recentPosts) > 0)
      @php
        $leadPost = $recentPosts[0] ?? null;
        $secondaryPosts = array_slice($recentPosts, 1, 3); // up to 3 items on the right
      @endphp

      <div class="row g-4 align-items-stretch editorial-bento">
        {{-- ===== LEFT: Featured Article (≈60%) ===== --}}
        @if($leadPost)
          @php
            $leadDateRaw = is_object($leadPost['date'] ?? null) ? $leadPost['date']->format('Y-m-d') : ($leadPost['date'] ?? '');
            $leadImage = $leadPost['image'] ?? null;
            if (empty($leadImage)) {
              $leadImage = 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80';
            } elseif (!str_starts_with($leadImage, 'http')) {
              $leadImage = asset($leadImage);
            }
          @endphp

          <div class="col-lg-7">
            <article class="editorial-featured-card h-100 d-flex flex-column">
              <!-- Thumbnail -->
              <div class="editorial-featured-thumb">
                <img
                  src="{{ $leadImage }}"
                  alt="{{ $leadPost['title'] }}"
                  loading="lazy"
                  decoding="async"
                >
              </div>

              <!-- Body -->
              <div class="editorial-featured-body d-flex flex-column flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                  <span class="editorial-badge editorial-badge-sunny">
                    {{ $leadPost['category'] ?? 'INFO TERKAIT' }}
                  </span>
                  <span class="editorial-meta font-monospace">
                    • {{ $leadDateRaw }}
                  </span>
                </div>

                <h3 class="editorial-featured-title">
                  <a href="{{ url('/informasi') }}?detail={{ $leadPost['slug'] }}" class="stretched-link">
                    {{ $leadPost['title'] }}
                  </a>
                </h3>

                <p class="editorial-featured-excerpt">
                  {{ Str::limit(strip_tags(html_entity_decode($leadPost['content'] ?? '')), 160) }}
                </p>

                <div class="mt-auto pt-3 d-flex align-items-center justify-content-between">
                  <a href="{{ url('/informasi') }}?detail={{ $leadPost['slug'] }}" class="editorial-read-link">
                    Baca Pembahasan Lengkap <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <span class="editorial-meta font-monospace d-none d-sm-inline">QC &amp; Regulatory Guide</span>
                </div>
              </div>
            </article>
          </div>
        @endif

        {{-- ===== RIGHT: Compact Article List (≈40%) ===== --}}
        <div class="col-lg-5">
          <div class="editorial-list-card h-100 d-flex flex-column">
            @forelse($secondaryPosts as $index => $post)
              @php
                $pDateRaw = is_object($post['date'] ?? null) ? $post['date']->format('Y-m-d') : ($post['date'] ?? '');
              @endphp

              <article class="editorial-list-item {{ $index > 0 ? 'has-border' : '' }}">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                  <span class="editorial-badge editorial-badge-dark">
                    {{ $post['category'] ?? 'BERITA' }}
                  </span>
                  <span class="editorial-meta font-monospace">
                    {{ $pDateRaw }}
                  </span>
                </div>

                <h4 class="editorial-list-title">
                  <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}">
                    {{ $post['title'] }}
                  </a>
                </h4>
              </article>
            @empty
              <div class="p-4 text-center text-muted small">
                Belum ada artikel pendukung.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    @else
      <div class="text-center py-5">
        <p class="text-muted mb-0">Belum ada artikel terbaru.</p>
      </div>
    @endif
  </div>
</section>
