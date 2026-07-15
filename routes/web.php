<?php

use Illuminate\Support\Facades\Route;
use App\Services\DataService;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Str;

// ----------------------------------------------------
// Public Frontend Routes
// ----------------------------------------------------
Route::get('/', function (DataService $dataService) {
    $homeData = $dataService->getHomepageData();
    // Retrieve 3 recent news posts dynamically for the homepage news section
    $posts = $dataService->getPosts();
    $recentPosts = array_slice($posts, 0, 3);
    return view('welcome', compact('homeData', 'recentPosts'));
});

Route::get('/profil', function () {
    return view('profil');
});

Route::get('/produk', function (\Illuminate\Http\Request $request, DataService $dataService) {
    // 1. Ambil semua data produk asli
    $allProducts = $dataService->getProducts();

    // 2. Tangkap parameter filter dari URL (?category=...&subcategory=...)
    $activeCategory = $request->query('category', 'all'); 
    $activeSubCategory = $request->query('subcategory');
    $searchQuery = $request->query('q') ?? $request->query('s');

    // 3. Struktur hierarki menu sidebar sesuai website lama
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

    // 4. Filter produk berdasarkan Kategori, Sub-kategori, dan kata kunci pencarian (s)
    $filteredProducts = array_filter($allProducts, function ($product) use ($activeCategory, $activeSubCategory, $searchQuery) {
        // Jika ada parameter pencarian, cari kecocokan di title, description, atau catalog number
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

        // Normalisasi input string agar pencocokan presisi (slug-style)
        $prodCat = \Illuminate\Support\Str::slug($product['category'] ?? '');
        $prodSub = \Illuminate\Support\Str::slug($product['sub_category'] ?? '');

        $matchCategory = ($prodCat === $activeCategory);
        
        if ($activeSubCategory && $activeSubCategory !== 'all') {
            return $matchCategory && ($prodSub === $activeSubCategory);
        }

        return $matchCategory;
    });

    // 5. Kirim semua variabel kontrol ke halaman view produk
    return view('produk', [
        'products' => $filteredProducts,
        'categoriesStructure' => $categoriesStructure,
        'activeCategory' => $activeCategory,
        'activeSubCategory' => $activeSubCategory
    ]);
});

Route::get('/sektor', function (DataService $dataService) {
    $sectors = $dataService->getSectors();
    $products = $dataService->getProducts();
    return view('sektor', compact('sectors', 'products'));
});

Route::get('/produk/detail', function (DataService $dataService) {
    $product = null;
    $id = request()->get('id');
    if ($id) {
        $product = $dataService->getProductByTitle($id);
    }
    return view('detail-produk', compact('product'));
});

Route::get('/layanan', function () {
    return view('layanan');
});

Route::get('/informasi', function (DataService $dataService) {
    $posts = $dataService->getPosts();
    $recentPosts = array_slice($posts, 0, 3);
    
    // Count categories dynamically
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
    $selectedCategory = request()->get('kategori');
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
    $detail = request()->get('detail');
    $currentBlog = null;
    if ($detail) {
        foreach ($posts as $post) {
            if ($post['slug'] === $detail) {
                $currentBlog = $post;
                break;
            }
        }
    }

    // Paginate remaining list
    $perPage = 4;
    $currentPage = (int)request()->get('page', 1);
    if ($currentPage < 1) $currentPage = 1;
    
    $totalPosts = count($posts);
    $totalPages = (int)ceil($totalPosts / $perPage);
    if ($totalPages < 1) $totalPages = 1;
    
    // Slice posts for current page
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
});

Route::get('/kontak', function () {
    return view('kontak');
});
Route::post('/kontak', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// ----------------------------------------------------
// Admin Login Routes
// ----------------------------------------------------
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// ----------------------------------------------------
// Protected Admin Panel Routes
// ----------------------------------------------------
Route::middleware([AdminAuthenticate::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Home Editor
    Route::get('/home', [AdminController::class, 'homeEdit'])->name('admin.home.edit');
    Route::post('/home', [AdminController::class, 'homeUpdate'])->name('admin.home.update');
    
    // Products CRUD
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('admin.products.create');
    Route::get('/products/create-bulk', [AdminController::class, 'productsCreateBulk'])->name('admin.products.create.bulk');
    Route::post('/products/store-bulk', [AdminController::class, 'productsStoreBulk'])->name('admin.products.store-bulk');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('admin.products.store');
    Route::get('/products/{title}/edit', [AdminController::class, 'productsEdit'])->name('admin.products.edit');
    Route::post('/products/{title}', [AdminController::class, 'productsUpdate'])->name('admin.products.update');
    Route::delete('/products/{title}', [AdminController::class, 'productsDestroy'])->name('admin.products.destroy');
    
    // Posts CRUD
    Route::get('/posts', [AdminController::class, 'postsIndex'])->name('admin.posts');
    Route::get('/posts/create', [AdminController::class, 'postsCreate'])->name('admin.posts.create');
    Route::post('/posts', [AdminController::class, 'postsStore'])->name('admin.posts.store');
    Route::get('/posts/{slug}/edit', [AdminController::class, 'postsEdit'])->name('admin.posts.edit');
    Route::post('/posts/{slug}', [AdminController::class, 'postsUpdate'])->name('admin.posts.update');
    Route::delete('/posts/{slug}', [AdminController::class, 'postsDestroy'])->name('admin.posts.destroy');
    
    // Sectors CRUD
    Route::get('/sectors', [AdminController::class, 'sectorsIndex'])->name('admin.sectors');
    Route::get('/sectors/create', [AdminController::class, 'sectorsCreate'])->name('admin.sectors.create');
    Route::post('/sectors', [AdminController::class, 'sectorsStore'])->name('admin.sectors.store');
    Route::get('/sectors/{id}/edit', [AdminController::class, 'sectorsEdit'])->name('admin.sectors.edit');
    Route::post('/sectors/{id}', [AdminController::class, 'sectorsUpdate'])->name('admin.sectors.update');
    Route::delete('/sectors/{id}', [AdminController::class, 'sectorsDestroy'])->name('admin.sectors.destroy');

    // Google Sheets Sync
    Route::post('/sync-sheets', [AdminController::class, 'syncSheets'])->name('admin.sync-sheets');
});
