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
        $posts = $dataService->getPosts();
        $recentPosts = array_slice($posts, 0, 3);
        
        $allProducts = $dataService->getProducts();
        $featuredProducts = array_slice($allProducts, 0, 4);
        
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
        $allProducts = $dataService->getProducts();
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

        // Filter products
        $filteredProducts = array_filter($allProducts, function ($product) use ($activeCategory, $activeSubCategory, $searchQuery) {
            if ($searchQuery) {
                $q = trim($searchQuery);
                $titleMatch = stripos($product['title'] ?? '', $q) !== false;
                $descMatch = stripos($product['description'] ?? '', $q) !== false;
                $catalogMatch = stripos($product['catalog'] ?? '', $q) !== false;
                
                if (!$titleMatch && !$descMatch && !$catalogMatch) {
                    return false;
                }
            }

            if ($activeCategory === 'all') {
                return true;
            }

            $prodCat = Str::slug($product['category'] ?? '');
            $prodSub = Str::slug($product['sub_category'] ?? '');

            $matchCategory = ($prodCat === $activeCategory);
            
            if ($activeSubCategory && $activeSubCategory !== 'all') {
                return $matchCategory && ($prodSub === $activeSubCategory);
            }

            return $matchCategory;
        });

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
        $products = $dataService->getProducts();
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
        $posts = $dataService->getPosts();
        $recentPosts = array_slice($posts, 0, 3);
        
        $categoryCounts = [
            'Berita' => 0,
            'Event' => 0,
            'Info Terkait' => 0,
            'IPTEK' => 0,
            'Kegiatan' => 0
        ];
        
        foreach ($posts as $post) {
            $cat = $post['category'] ?? null;
            if ($cat === 'Berita') $categoryCounts['Berita']++;
            elseif ($cat === 'Event') $categoryCounts['Event']++;
            elseif ($cat === 'Info Terkait' || $cat === 'Info') $categoryCounts['Info Terkait']++;
            elseif ($cat === 'IPTEK') $categoryCounts['IPTEK']++;
            elseif ($cat === 'Kegiatan') $categoryCounts['Kegiatan']++;
        }

        // Get and sanitize detail slug (regex validation for safety)
        // Resolved against the full raw posts list before filtering
        $detail = $request->query('detail');
        $currentBlog = null;
        if ($detail && preg_match('/^[a-zA-Z0-9\-_]+$/', (string)$detail)) {
            foreach ($posts as $post) {
                if (($post['slug'] ?? null) === $detail) {
                    $currentBlog = $post;
                    break;
                }
            }
        }

        // Filter and sanitize by Category (allowlist validation)
        $rawKategori = $request->query('kategori');
        $allowedKategori = ['berita', 'event', 'info', 'iptek', 'kegiatan'];
        $selectedCategory = null;
        if ($rawKategori && in_array(strtolower($rawKategori), $allowedKategori)) {
            $selectedCategory = strtolower($rawKategori);
            $posts = array_filter($posts, function($post) use ($selectedCategory) {
                $cat = strtolower($post['category'] ?? '');
                if ($selectedCategory === 'info') {
                    return $cat === 'info terkait' || $cat === 'info';
                }
                return $cat === $selectedCategory;
            });
            $posts = array_values($posts);
        }

        // Paginate
        $perPage = 4;
        $currentPage = (int)$request->query('page', 1);
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        
        $totalPosts = count($posts);
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) {
            $totalPages = 1;
        }
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        
        $offset = ($currentPage - 1) * $perPage;
        $paginatedPosts = array_slice($posts, $offset, $perPage);

        return view('informasi', [
            'posts' => $paginatedPosts,
            'categoryCounts' => $categoryCounts,
            'currentBlog' => $currentBlog,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
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
