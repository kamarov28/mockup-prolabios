<!-- 6. Technical Insights & Articles -->
<section class="section-spacious typo-news-section">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 typo-section-head">
      <div>
        <h2 class="typo-section-title">Insights &amp; Laboratory Education</h2>
        <p class="typo-section-sub">Testing application guides, ISO/BPOM regulatory updates, and Prolabios activity news.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="{{ url('/informasi') }}" class="typo-btn-link" style="font-size: 0.85rem;" aria-label="View All Articles and Information">
          View All Articles <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>

    <div class="row g-4">
      @if(count($recentPosts) > 0)
        @foreach($recentPosts as $post)
          @php
            $dateParts = explode(' ', $post['date']);
            $day = isset($dateParts[0]) ? $dateParts[0] : '';
            $month = isset($dateParts[1]) ? $dateParts[1] : '';
          @endphp
          <div class="col-lg-4 col-md-12">
            <div class="card typo-blog-card h-100 position-relative">
              <div class="card-body p-0">
                <span class="typo-blog-card-meta">
                  {{ $post['category'] }} &bull; {{ $day }} {{ $month }}
                </span>
                <h3 class="typo-blog-card-title">
                  <a href="{{ url('/informasi') }}?detail={{ $post['slug'] }}" class="stretched-link">{{ $post['title'] }}</a>
                </h3>
                <p class="typo-blog-card-desc">{{ Str::limit(strip_tags(html_entity_decode($post['content'])), 140) }}</p>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-12 text-center py-4">
          <p class="text-muted">No recent articles available.</p>
        </div>
      @endif
    </div>
  </div>
</section>
