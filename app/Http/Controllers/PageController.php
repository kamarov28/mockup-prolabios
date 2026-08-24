<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home(DataService $dataService)
    {
        $homeData = $dataService->getHomepageData();
        $recentPosts = $dataService->getPosts([], 3);
        $featuredProducts = $dataService->getProducts([], 4);

        return view('welcome', compact('homeData', 'recentPosts', 'featuredProducts'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function produk(Request $request, DataService $dataService)
    {
        $categoriesStructure = $dataService->getCategoriesStructure();

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

        $filteredProducts = $dataService->getPaginatedProducts($filters, 12);

        return view('produk', [
            'products' => $filteredProducts,
            'categoriesStructure' => $categoriesStructure,
            'activeCategory' => $activeCategory,
            'activeSubCategory' => $activeSubCategory,
        ]);
    }

    public function detailProduk(Request $request, DataService $dataService)
    {
        // Prefer numeric product id (?id=123). Title lookup kept only for legacy bookmarks.
        $product = null;
        $identifier = $request->query('id');
        if ($identifier !== null && $identifier !== '') {
            if (is_numeric($identifier)) {
                $product = $dataService->getProductById((int) $identifier);
            } else {
                $product = $dataService->getProductByTitle((string) $identifier);
            }
        }

        return view('detail-produk', compact('product'));
    }

    public function beliProduk(Request $request, DataService $dataService)
    {
        $product = null;
        $identifier = $request->query('id');
        if ($identifier !== null && $identifier !== '') {
            if (is_numeric($identifier)) {
                $product = $dataService->getProductById((int) $identifier);
            } else {
                $product = $dataService->getProductByTitle((string) $identifier);
            }
        }

        return view('beli-produk', compact('product'));
    }

    public function sektor(DataService $dataService)
    {
        $sectors = $dataService->getSectors();

        $firstSectorId = count($sectors) > 0 ? $sectors[0]['id'] : 'biomolecular';
        $activeSector  = request()->get('s') ?? request()->get('kategori') ?? $firstSectorId;

        $products = $dataService->getProducts(['sector' => $activeSector]);

        return view('sektor', compact('sectors', 'products', 'activeSector'));
    }

    public function layanan()
    {
        return view('layanan');
    }

    public function informasi(Request $request, DataService $dataService, ?string $slug = null)
    {
        $recentPosts = $dataService->getPosts([], 3);

        $categoryCounts = Cache::remember('blog_category_counts', 3600, function () {
            $rows = DB::table('posts')
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
            $currentBlog = $dataService->getPostBySlug((string) $detail);
            if (! $currentBlog) {
                $currentBlog = $dataService->getPostBySlug(Str::slug((string) $detail));
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

        $paginatedPosts = $dataService->getPaginatedPosts($filters, 4);

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
