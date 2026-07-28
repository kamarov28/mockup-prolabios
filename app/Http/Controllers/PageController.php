<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataService;
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

        $searchQuery = $request->query('q') ?? $request->query('s');

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
            'activeSubCategory' => $activeSubCategory
        ]);
    }

    /**
     * Display a single product's details.
     */
    public function detailProduk(Request $request, DataService $dataService)
    {
        $product = null;
        $title = $request->query('id');
        if ($title) {
            $product = $dataService->getProductByTitle((string)$title);
        }
        return view('detail-produk', compact('product'));
    }

    /**
     * Display the Sektor Fokus page.
     */
    public function sektor(DataService $dataService)
    {
        $sectors = $dataService->getSectors();
        $products = $dataService->getProducts([], 16);
        return view('sektor', compact('sectors', 'products'));
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
        
        $categoryCounts = \Illuminate\Support\Facades\Cache::remember('blog_category_counts', 3600, function () use ($dataService) {
            $allPosts = $dataService->getPosts();
            $counts = [
                'Berita' => 0,
                'Event' => 0,
                'Info Terkait' => 0,
                'IPTEK' => 0,
                'Kegiatan' => 0
            ];
            foreach ($allPosts as $post) {
                $cat = $post['category'] ?? null;
                if ($cat === 'Berita') $counts['Berita']++;
                elseif ($cat === 'Event') $counts['Event']++;
                elseif ($cat === 'Info Terkait' || $cat === 'Info') $counts['Info Terkait']++;
                elseif ($cat === 'IPTEK') $counts['IPTEK']++;
                elseif ($cat === 'Kegiatan') $counts['Kegiatan']++;
            }
            return $counts;
        });

        $detail = $request->query('detail');
        $currentBlog = null;
        if ($detail && preg_match('/^[a-zA-Z0-9\-_]+$/', (string)$detail)) {
            $currentBlog = $dataService->getPostBySlug((string)$detail);
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
            'recentPosts' => $recentPosts
        ]);
    }

    /**
     * Display the contact page.
     */
    public function kontak()
    {
        return view('kontak');
    }
}
