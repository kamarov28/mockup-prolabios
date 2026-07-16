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
        
        return view('welcome', compact('homeData', 'recentPosts'));
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
        $activeCategory = $request->query('category', 'all'); 
        $activeSubCategory = $request->query('subcategory');
        $searchQuery = $request->query('q') ?? $request->query('s');

        // Sidebar category hierarchy
        $categoriesStructure = [
            'microbiology' => [
                'name' => 'Microbiology',
                'subs' => [
                    'food-safety' => 'Food Safety',
                    'antimicrobial' => 'Antimicrobial Susceptibility Testing',
                    'identification' => 'Microbiological Identification',
                    'preservation' => 'Microorganisms Preservation System (BactoBank)',
                    'staining' => 'Microbial Staining & Fixatives',
                    'consumables' => 'Consumables',
                    'mic-test' => 'MIC Test Strip',
                    'qc-organisms' => 'QC Organisms',
                    'dip-slide' => 'Dip slide',
                    'chemical-indicator' => 'Chemical Indicator',
                    'latex-agglutination' => 'Latex Agglutination Kits',
                    'ready-culture' => 'Ready To Use Culture Media',
                    'biological-indicators' => 'Biological Indicators',
                    'dehydrated-culture' => 'Dehydrated Culture Media',
                    'immunology' => 'Immunology',
                    'endotoxin' => 'Endotoxin'
                ]
            ],
            'reference-standards' => [
                'name' => 'Reference Standards',
                'subs' => [
                    'pharmaceutical' => 'Pharmaceutical Reference Standards',
                    'green-standards' => 'Green Standards',
                    'environmental' => 'Environmental Standards',
                    'food-beverages' => 'Food and Beverages Standards',
                    'agro-chemical' => 'Agro Chemical Standards'
                ]
            ],
            'device' => [
                'name' => 'Device',
                'subs' => [
                    'bsc-lfc' => 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)',
                    'microbiological-instruments' => 'Microbiological Instruments',
                    'liquid-handling' => 'Liquid Handling',
                    'thermometer' => 'Thermometer'
                ]
            ],
            'instruments' => [
                'name' => 'Instruments',
                'subs' => [
                    'liofilchem-giotto-2' => 'Liofilchem® Giotto 2',
                    'agar-filler' => 'Agar Filler',
                    'agar-preparator' => 'Agar Preparator',
                    'kinetic-incubating-reader' => 'Kinetic Incubating Microplate Reader',
                    'mica-diamidex' => 'MICA® Diamidex - Counting Microorganisms Faster'
                ]
            ]
        ];

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
        $id = $request->query('id');
        if ($id) {
            $product = $dataService->getProductByTitle($id);
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
            $cat = $post['category'];
            if ($cat === 'Berita') $categoryCounts['Berita']++;
            elseif ($cat === 'Event') $categoryCounts['Event']++;
            elseif ($cat === 'Info Terkait' || $cat === 'Info') $categoryCounts['Info Terkait']++;
            elseif ($cat === 'IPTEK') $categoryCounts['IPTEK']++;
            elseif ($cat === 'Kegiatan') $categoryCounts['Kegiatan']++;
        }

        // Filter by Category
        $selectedCategory = $request->query('kategori');
        if ($selectedCategory) {
            $posts = array_filter($posts, function($post) use ($selectedCategory) {
                $cat = strtolower($post['category']);
                if ($selectedCategory === 'info') {
                    return $cat === 'info terkait' || $cat === 'info';
                }
                return $cat === strtolower($selectedCategory);
            });
        }

        // Get detail if requested
        $detail = $request->query('detail');
        $currentBlog = null;
        if ($detail) {
            foreach ($posts as $post) {
                if ($post['slug'] === $detail) {
                    $currentBlog = $post;
                    break;
                }
            }
        }

        // Paginate
        $perPage = 4;
        $currentPage = (int)$request->query('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        
        $totalPosts = count($posts);
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) $totalPages = 1;
        
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
