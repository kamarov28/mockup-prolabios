<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\HomepageService;
use App\Services\PostService;
use App\Services\ProductService;
use App\Services\SectorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home(HomepageService $homepage, PostService $posts, ProductService $products)
    {
        $homeData = $homepage->getHomepageData();
        $recentPosts = $posts->getPosts([], 3);
        $featuredProducts = $products->getFeaturedProducts(4);

        return view('welcome', compact('homeData', 'recentPosts', 'featuredProducts'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function produk(Request $request, ProductService $products)
    {
        $categoriesStructure = $products->getCategoriesStructure();

        $rawCategory = Str::slug((string) $request->query('category', 'all'));
        $activeCategory = isset($categoriesStructure[$rawCategory]) ? $rawCategory : 'all';

        $activeSubCategory = null;
        $rawSubCategory = $request->query('subcategory');
        if ($rawSubCategory) {
            $normalizedSub = Str::slug((string) $rawSubCategory);
            $allowedSubs = [];
            if ($activeCategory !== 'all' && isset($categoriesStructure[$activeCategory]) && is_array($categoriesStructure[$activeCategory]['subs'] ?? null)) {
                $allowedSubs = array_keys($categoriesStructure[$activeCategory]['subs']);
            }
            if ($normalizedSub === 'all' || in_array($normalizedSub, $allowedSubs)) {
                $activeSubCategory = $normalizedSub;
            }
        }

        $searchQuery = $request->query('search') ?? $request->query('s') ?? $request->query('q');

        $filters = [];
        if ($activeCategory !== 'all') {
            $filters['category'] = $categoriesStructure[$activeCategory]['name'] ?? $activeCategory;
            if ($activeSubCategory && $activeSubCategory !== 'all') {
                $filters['sub_category'] = $categoriesStructure[$activeCategory]['subs'][$activeSubCategory] ?? $activeSubCategory;
            }
        }
        if ($searchQuery) {
            $filters['search'] = $searchQuery;
        }

        $filteredProducts = $products->getPaginatedProducts($filters, 12);

        return view('produk', [
            'products' => $filteredProducts,
            'categoriesStructure' => $categoriesStructure,
            'activeCategory' => $activeCategory,
            'activeSubCategory' => $activeSubCategory,
        ]);
    }

    /**
     * Canonical product detail: /produk/{slug}
     */
    public function detailProduk(string $slug, ProductService $products)
    {
        $product = $products->getProductBySlug($slug);

        if (! $product) {
            abort(404);
        }

        return view('detail-produk', compact('product'));
    }

    /**
     * Canonical buy / RFQ add redirect: /produk/{slug}/beli → /produk/{slug}
     */
    public function beliProduk(string $slug, ProductService $products)
    {
        $product = $products->getProductBySlug($slug);

        if (! $product) {
            abort(404);
        }

        $canonicalSlug = $product->slug ?: $slug;

        return redirect()->route('produk.detail', ['slug' => $canonicalSlug], 301);
    }

    /**
     * Legacy /produk/detail?id=123 → permanent redirect to /produk/{slug}
     */
    public function detailProdukLegacy(Request $request, ProductService $products)
    {
        $product = $this->resolveLegacyProduct($request, $products);

        if ($product && ! empty($product->slug)) {
            return redirect()->route('produk.detail', ['slug' => $product->slug], 301);
        }

        return view('detail-produk', compact('product'));
    }

    /**
     * Legacy /produk/beli?id=123 → permanent redirect to /produk/{slug}
     */
    public function beliProdukLegacy(Request $request, ProductService $products)
    {
        $product = $this->resolveLegacyProduct($request, $products);

        if ($product && ! empty($product->slug)) {
            return redirect()->route('produk.detail', ['slug' => $product->slug], 301);
        }

        return redirect()->route('produk.index', [], 301);
    }

    /**
     * Resolve legacy product identifier from query param (id / slug / title).
     */
    private function resolveLegacyProduct(Request $request, ProductService $products)
    {
        $identifier = $request->query('id');
        if ($identifier === null || $identifier === '') {
            return null;
        }

        if (is_numeric($identifier)) {
            return $products->getProductById((int) $identifier);
        }

        return $products->getProductBySlug(Str::slug((string) $identifier))
            ?? $products->getProductByTitle((string) $identifier);
    }

    public function sektor(Request $request, SectorService $sectors, ProductService $products)
    {
        $sectorList = $sectors->getSectors();

        $validIds = array_column($sectorList, 'id');
        $requested = $request->get('s') ?? $request->get('kategori');

        if ($requested && in_array($requested, $validIds, true)) {
            $activeSector = $requested;
        } else {
            $activeSector = count($sectorList) > 0 ? $sectorList[0]['id'] : 'biomolecular';
        }

        $productsPaginated = $products->getPaginatedProducts(['sector' => $activeSector], 24);
        $relatedProducts = $products->getProducts(['sector' => $activeSector], 3);

        return view('sektor', [
            'sectors' => $sectorList,
            'products' => $productsPaginated,
            'activeSector' => $activeSector,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function layanan()
    {
        return view('layanan');
    }

    public function informasi(Request $request, PostService $posts, ?string $slug = null)
    {
        $recentPosts = $posts->getPosts([], 3);

        $categoryCounts = Cache::remember('blog_category_counts', 3600, function () {
            $rows = Post::query()
                ->select('category', DB::raw('COUNT(*) as total'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->get()
                ->keyBy('category');

            $getCt = fn (string $key) => (int) ($rows->get($key)->total ?? 0);

            return [
                'Berita' => $getCt('Berita'),
                'Event' => $getCt('Event'),
                'Info Terkait' => $getCt('Info Terkait') + $getCt('Info'),
                'IPTEK' => $getCt('IPTEK'),
                'Kegiatan' => $getCt('Kegiatan'),
            ];
        });

        $detail = $slug ?? $request->query('detail');
        $currentBlog = null;
        if ($detail) {
            $currentBlog = $posts->getPostBySlug((string) $detail);
            if (! $currentBlog) {
                $currentBlog = $posts->getPostBySlug(Str::slug((string) $detail));
            }
            if (! $currentBlog) {
                abort(404);
            }

            $isPublished = ($currentBlog['status'] ?? '') === 'online'
                && (empty($currentBlog['date']) || $currentBlog['date'] <= date('Y-m-d'));

            if (! $isPublished) {
                $isAdmin = auth()->check() && (bool) (auth()->user()->is_admin ?? false);
                if (! $isAdmin) {
                    abort(404);
                }
            }
        }

        $rawKategori = $request->query('kategori');
        $allowedKategori = ['berita', 'event', 'info', 'iptek', 'kegiatan'];
        $selectedCategory = null;
        $filters = [];
        if ($rawKategori && in_array(strtolower($rawKategori), $allowedKategori)) {
            $selectedCategory = strtolower($rawKategori);
            $filters['category'] = $selectedCategory;
        }

        $paginatedPosts = $posts->getPaginatedPosts($filters, 4);

        return view('informasi', [
            'posts' => $paginatedPosts,
            'categoryCounts' => $categoryCounts,
            'currentBlog' => $currentBlog,
            'selectedCategory' => $selectedCategory,
            'recentPosts' => $recentPosts,
        ]);
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function privacy()
    {
        return view('kebijakan-privasi');
    }

    public function terms()
    {
        return view('syarat-ketentuan');
    }
}
