@if ($paginator->hasPages())
  <nav class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 w-100 py-3 mt-4 border-top border-secondary border-opacity-10" aria-label="Navigasi Halaman Produk">
    {{-- Pagination Status Summary --}}
    <div class="text-secondary small fw-medium">
      Menampilkan <span class="text-white fw-semibold">{{ $paginator->firstItem() ?? 0 }}</span> &ndash; <span class="text-white fw-semibold">{{ $paginator->lastItem() ?? 0 }}</span> dari total <span class="text-white fw-semibold">{{ $paginator->total() }}</span> produk
    </div>

    {{-- Pagination Buttons --}}
    <ul class="pagination pagination-sm mb-0 gap-1">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="Halaman Sebelumnya">
          <span class="page-link bg-dark bg-opacity-50 text-white-50 border-secondary border-opacity-25 px-3 py-2 rounded-0">
            <i class="bi bi-chevron-left me-1"></i> Prev
          </span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link bg-dark bg-opacity-75 text-white border-secondary border-opacity-25 px-3 py-2 rounded-0" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman Sebelumnya">
            <i class="bi bi-chevron-left me-1"></i> Prev
          </a>
        </li>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <li class="page-item disabled" aria-disabled="true"><span class="page-link bg-transparent text-secondary border-0 px-2 py-2">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active" aria-current="page">
                <span class="page-link bg-danger text-white border-danger px-3 py-2 fw-bold rounded-0">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link bg-dark bg-opacity-75 text-white border-secondary border-opacity-25 px-3 py-2 rounded-0" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link bg-dark bg-opacity-75 text-white border-secondary border-opacity-25 px-3 py-2 rounded-0" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman Berikutnya">
            Next <i class="bi bi-chevron-right ms-1"></i>
          </a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true" aria-label="Halaman Berikutnya">
          <span class="page-link bg-dark bg-opacity-50 text-white-50 border-secondary border-opacity-25 px-3 py-2 rounded-0">
            Next <i class="bi bi-chevron-right ms-1"></i>
          </span>
        </li>
      @endif
    </ul>
  </nav>
@elseif ($paginator->total() > 0)
  <div class="d-flex justify-content-between align-items-center w-100 py-3 mt-4 border-top border-secondary border-opacity-10 text-secondary small fw-medium">
    <span>Menampilkan seluruh <span class="text-white fw-semibold">{{ $paginator->total() }}</span> produk dalam kategori ini</span>
  </div>
@endif
