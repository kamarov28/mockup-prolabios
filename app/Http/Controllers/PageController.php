<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display the welcome homepage.
     */
    public function home(DataService $dataService)
    {
        $homeData = $dataService->getHomepageData();
        $recentPosts = $dataService->getPosts([], 3);
        $featuredProducts = $dataService->getProducts([], 4);

        return view('welcome', compact('homeData', 'recentPosts', 'featuredProducts'));
    }

    /**
     * Display the company profile page.
     */
    public function profil()
    {
        return view('profil');
    }

    /**
     * Display the catalog products page.
     */
    public function produk(Request $request, DataService $dataService)
    {
        $categoriesStructure = $dataService->getCategoriesStructure();

        // Sanitize and normalize activeCategory
        $rawCategory = Str::slug((string) $request->query('category', 'all'));
        $activeCategory = isset($categoriesStructure[$rawCategory]) ? $rawCategory : 'all';

        // Sanitize and normalize activeSubCategory
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

        // Build database query filters instead of in-memory array filtering
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

    /**
     * Display a single product's details.
     */
    public function detailProduk(Request $request, DataService $dataService)
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

        return view('detail-produk', compact('product'));
    }

    /**
     * Display the dedicated shopping/checkout page for a single product
     * (price, live stock, indent/pre-order status, and add-to-cart form).
     * Kept separate from detailProduk() so the description page can focus
     * purely on specs/application info without overwhelming the visitor.
     */
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

    /**
     * Display the Sektor Fokus page.
     */
    public function sektor(DataService $dataService)
    {
        $sectors = $dataService->getSectors();

        // Determine active sector from query string (same logic as blade had before)
        $firstSectorId = count($sectors) > 0 ? $sectors[0]['id'] : 'biomolecular';
        $activeSector  = request()->get('s') ?? request()->get('kategori') ?? $firstSectorId;

        // Fetch all products for this specific sector (no arbitrary row limit)
        $products = $dataService->getProducts(['sector' => $activeSector]);

        return view('sektor', compact('sectors', 'products', 'activeSector'));
    }

    /**
     * Display the Layanan Purna Jual page.
     */
    public function layanan()
    {
        return view('layanan');
    }

    /**
     * Display news and blog posts.
     */
    public function informasi(Request $request, DataService $dataService)
    {
        $recentPosts = $dataService->getPosts([], 3);

        // Use GROUP BY query instead of loading all posts to memory
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

        $detail = $request->query('detail');
        $currentBlog = null;
        // Allow alphanumerics, hyphens, underscores, and dots in slugs
        if ($detail && preg_match('/^[a-zA-Z0-9\-_.]+$/', (string) $detail)) {
            $currentBlog = $dataService->getPostBySlug((string) $detail);
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

    /**
     * Display the contact page.
     */
    public function kontak()
    {
        return view('kontak');
    }

    /**
     * Display the privacy policy page (Kebijakan Privasi - UU PDP).
     */
    public function privacy()
    {
        return view('kebijakan-privasi');
    }

    /**
     * Display the terms of service page (Syarat & Ketentuan).
     */
    public function terms()
    {
        return view('syarat-ketentuan');
    }
}
