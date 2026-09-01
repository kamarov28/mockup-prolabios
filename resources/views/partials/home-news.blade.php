<!-- 6. Technical Insights & Articles (Editorial Magazine Layout) -->
<section class="section-spacious typo-news-section">
  <div class="container">
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
        $secondaryPosts = array_slice($recentPosts, 1, 2);
      @endphp

      <div class="row g-4 align-items-stretch">
        <!-- 1. Large Lead Editorial Story (Left) -->
        @if($leadPost)
          @php
            $leadDate = explode(' ', $leadPost['date']);
            $lDay = $leadDate[0] ?? '';
            $lMonth = $leadDate[1] ?? '';
          @endphp
          <div class="col-lg-7">
            <div class="card typo-blog-card typo-blog-card--lead h-100 p-4 p-md-5 d-flex flex-column justify-content-between position-relative">
              <div>
                <div class="d-flex align-items-center gap-2 mb-3">
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 font-monospace" style="font-size: 0.7rem;">
                    {{ $leadPost['category'] }}
                  </span>
                  <span class="text-muted small font-monospace">&bull; {{ $lDay }} {{ $lMonth }}</span>
                </div>

                <h3 class="fs-4 fw-bold mb-3" style="line-height: 1.35;">
                  <a href="{{ url('/informasi') }}?detail={{ $leadPost['slug'] }}" class="stretched-link text-dark text-decoration-none">
                    {{ $leadPost['title'] }}
                  </a>
                </h3>

                <p class="text-muted mb-4" style="font-size: 0.92rem; line-height: 1.6;">
                  {{ Str::limit(strip_tags(html_entity_decode($leadPost['content'])), 220) }}
                </p>
              </div>

              <div class="pt-3 border-top d-flex align-items-center justify-content-between text-muted" style="font-size: 0.82rem;">
                <span class="text-accent fw-medium">Baca Pembahasan Lengkap <i class="bi bi-arrow-right ms-1"></i></span>
                <span class="font-monospace">QC &amp; Regulatory Guide</span>
              </div>
            </div>
          </div>
        @endif

        <!-- 2. Supporting Articles List (Right) -->
        <div class="col-lg-5 d-flex flex-column gap-4">
          @if(count($secondaryPosts) > 0)
            @foreach($secondaryPosts as $post)
              @php
                $pDate = explode(' ', $post['date']);
                $day = $pDate[0] ?? '';
                $month = $pDate[1] ?? ''
              @endphp
              <div class="card typo-blog-card flex-grow-1 p-4 position-relative">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 font-monospace" style="font-size: 0.65rem;">
                    {{ $post['category'] }}
                  </span>
                  <span class="text-muted small font-monospace">&bull; {{ $day }} {{ $month }}</span>
                </div>

                <h4 class="fs-6 fw-semibold mb-2" style="line-height: 1.4;">
                  <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}" class="stretched-link text-dark text-decoration-none">
                    {{ $post['title'] }}
                  </a>
                </h4>

                <p class="text-muted mb-0" style="font-size: 0.82rem; line-height: 1.5;">
                  {{ Str::limit(strip_tags(html_entity_decode($post['content'])), 95) }}
                </p>
              </div>
            @endforeach
          @else
            <div class="card typo-blog-card flex-grow-1 p-4 d-flex align-items-center justify-content-center text-muted">
              <span class="small">Belum ada artikel pendukung lainnya.</span>
            </div>
          @endif
        </div>
      </div>
    @else
      <div class="col-12 text-center py-4">
        <p class="text-muted">Belum ada artikel terbaru.</p>
      </div>
    @endif
  </div>
</section>
