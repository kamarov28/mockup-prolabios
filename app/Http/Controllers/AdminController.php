<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    // ----------------------------------------------------
    // Authentication Handlers
    // ----------------------------------------------------
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $expectedUser = env('ADMIN_USERNAME', 'admin');
        $expectedPass = env('ADMIN_PASSWORD', 'prolabios2026');

        if ($username === $expectedUser && $password === $expectedPass) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Administrator!');
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password yang Anda masukkan salah.');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }

    // ----------------------------------------------------
    // Dashboard Handler
    // ----------------------------------------------------
    public function dashboard()
    {
        $products = $this->dataService->getProducts();
        $productsCount = count($products);
        $postsCount = count($this->dataService->getPosts());
        $sectorsCount = count($this->dataService->getSectors());

        $recentProducts = array_slice($products, 0, 5);
        $recentPosts = array_slice($this->dataService->getPosts(), 0, 5);

        // Count product categories distribution for the chart
        $categoryDist = [
            'Culture Media' => 0,
            'Instruments' => 0,
            'Chemicals & Reagents' => 0,
            'Consumables' => 0
        ];
        foreach ($products as $p) {
            $cat = $p['category'] ?? '';
            if ($cat === 'culture-media') $categoryDist['Culture Media']++;
            elseif ($cat === 'instruments') $categoryDist['Instruments']++;
            elseif ($cat === 'chemicals') $categoryDist['Chemicals & Reagents']++;
            elseif ($cat === 'consumables') $categoryDist['Consumables']++;
        }

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount',
            'recentProducts', 'recentPosts', 'categoryDist'
        ));
    }

    // ----------------------------------------------------
    // Helper for File Uploads (Fallback to URL text field)
    // ----------------------------------------------------
    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists in public folder
            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }
        
        return $request->input($urlKey, $fallback);
    }

    // ----------------------------------------------------
    // Home Editor Handlers
    // ----------------------------------------------------
    public function homeEdit()
    {
        $homeData = $this->dataService->getHomepageData();
        return view('admin.home-editor', compact('homeData'));
    }

    public function homeUpdate(Request $request)
    {
        $homeData = $this->dataService->getHomepageData();

        // 1. Text values
        $homeData['hero_title'] = $request->input('hero_title', $homeData['hero_title']);
        $homeData['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle']);
        $homeData['focus_title'] = $request->input('focus_title', $homeData['focus_title']);
        $homeData['about_title'] = $request->input('about_title', $homeData['about_title']);
        $homeData['about_description'] = $request->input('about_description', $homeData['about_description']);
        $homeData['hotline_label'] = $request->input('hotline_label', $homeData['hotline_label']);
        $homeData['hotline_number'] = $request->input('hotline_number', $homeData['hotline_number']);
        $homeData['hotline_description'] = $request->input('hotline_description', $homeData['hotline_description']);

        // 2. Hero Slideshow Images
        for ($i = 0; $i < 4; $i++) {
            $existing = $homeData['hero_images'][$i] ?? '';
            $homeData['hero_images'][$i] = $this->handleImageUpload(
                $request, "hero_image_file_$i", "hero_image_url_$i", $existing
            );
        }

        // 3. Focus Cards
        for ($i = 0; $i < 3; $i++) {
            $existingCard = $homeData['focus_cards'][$i] ?? [];
            $existingImg = $existingCard['image'] ?? '';
            
            $homeData['focus_cards'][$i] = [
                'title' => $request->input("focus_card_title_$i", $existingCard['title'] ?? ''),
                'description' => $request->input("focus_card_desc_$i", $existingCard['description'] ?? ''),
                'image' => $this->handleImageUpload(
                    $request, "focus_card_file_$i", "focus_card_url_$i", $existingImg
                )
            ];
        }

        // 4. Extended Page Configurations
        $homeData['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
        $homeData['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
        $homeData['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
        $homeData['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
        $homeData['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
        $homeData['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
        
        $homeData['products_title'] = $request->input('products_title', $homeData['products_title'] ?? '');
        $homeData['products_subtitle'] = $request->input('products_subtitle', $homeData['products_subtitle'] ?? '');
        
        $homeData['sectors_title'] = $request->input('sectors_title', $homeData['sectors_title'] ?? '');
        $homeData['sectors_subtitle'] = $request->input('sectors_subtitle', $homeData['sectors_subtitle'] ?? '');
        
        $homeData['services_title'] = $request->input('services_title', $homeData['services_title'] ?? '');
        $homeData['services_subtitle'] = $request->input('services_subtitle', $homeData['services_subtitle'] ?? '');
        
        $homeData['info_title'] = $request->input('info_title', $homeData['info_title'] ?? '');
        $homeData['info_subtitle'] = $request->input('info_subtitle', $homeData['info_subtitle'] ?? '');
        
        $homeData['contact_title'] = $request->input('contact_title', $homeData['contact_title'] ?? '');
        $homeData['contact_subtitle'] = $request->input('contact_subtitle', $homeData['contact_subtitle'] ?? '');

        // 5. Extended Page Images/Banners
        $homeData['products_banner_image'] = $this->handleImageUpload(
            $request, "products_banner_file", "products_banner_url", $homeData['products_banner_image'] ?? ''
        );
        $homeData['sectors_banner_image'] = $this->handleImageUpload(
            $request, "sectors_banner_file", "sectors_banner_url", $homeData['sectors_banner_image'] ?? ''
        );
        $homeData['services_banner_image'] = $this->handleImageUpload(
            $request, "services_banner_file", "services_banner_url", $homeData['services_banner_image'] ?? ''
        );
        $homeData['info_banner_image'] = $this->handleImageUpload(
            $request, "info_banner_file", "info_banner_url", $homeData['info_banner_image'] ?? ''
        );
        $homeData['contact_banner_image'] = $this->handleImageUpload(
            $request, "contact_banner_file", "contact_banner_url", $homeData['contact_banner_image'] ?? ''
        );

        $this->dataService->saveHomepageData($homeData);

        $section = $request->input('section');
        $params = $section ? ['section' => $section] : [];

        return redirect()->route('admin.home.edit', $params)->with('success', 'Layout dan konfigurasi halaman berhasil diperbarui!');
    }

    // ----------------------------------------------------
    // Products CRUD Handlers
    // ----------------------------------------------------
    public function productsIndex(Request $request)
    {
        $products = $this->dataService->getProducts();
        
        // Filter by search
        $search = $request->input('s');
        if ($search) {
            $products = array_filter($products, function ($p) use ($search) {
                return Str::contains(strtolower($p['title']), strtolower($search)) || 
                       Str::contains(strtolower($p['catalog'] ?? ''), strtolower($search)) ||
                       Str::contains(strtolower($p['description'] ?? ''), strtolower($search));
            });
        }

        // Filter by category
        $category = $request->input('category');
        if ($category) {
            $products = array_filter($products, function ($p) use ($category) {
                return ($p['category'] ?? '') === $category;
            });
        }

        // Filter by sector
        $sector = $request->input('sector');
        if ($sector) {
            $products = array_filter($products, function ($p) use ($sector) {
                $prodSectors = explode(',', $p['sector'] ?? '');
                return in_array($sector, $prodSectors);
            });
        }

        // Paginate products list (15 per page)
        $totalProducts = count($products);
        $perPage = 15;
        $totalPages = (int)ceil($totalProducts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedProducts = array_slice($products, $offset, $perPage);

        $sectors = $this->dataService->getSectors();

        return view('admin.products.index', [
            'products' => $paginatedProducts,
            'sectors' => $sectors,
            'search' => $search,
            'category' => $category,
            'sector' => $sector,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function productsCreate()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('sectors'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $title = $request->input('title');
        
        // Validate uniqueness of title
        if ($this->dataService->getProductByTitle($title)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80');

        $product = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $title,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sector' => $request->input('sector') ?: '',
            'image' => $image
        ];

        $this->dataService->addProduct($product);

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function productsEdit(string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('product', 'sectors'));
    }

    public function productsUpdate(Request $request, string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $newTitle = $request->input('title');
        
        // Validate uniqueness if title changed
        if ($newTitle !== $title && $this->dataService->getProductByTitle($newTitle)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image']);

        $updatedProduct = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $newTitle,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sector' => $request->input('sector') ?: '',
            'image' => $image
        ];

        $this->dataService->updateProduct($title, $updatedProduct);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productsDestroy(string $title)
    {
        $this->dataService->deleteProduct($title);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }
    public function productsCreateBulk()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.products.bulk-form', compact('sectors'));
    }

    public function productsStoreBulk(Request $request)
    {
        $titles = $request->input('title', []);
        $savedCount = 0;

        foreach ($titles as $id => $title) {
            $title = trim($title);
            $category = trim($request->input("category.{$id}", ''));

            if (empty($title) || empty($category)) {
                continue;
            }

            $catalog = trim($request->input("catalog.{$id}", ''));
            $sector = trim($request->input("sector.{$id}", ''));
            $description = trim($request->input("description.{$id}", ''));
            $imageUrl = trim($request->input("image_url.{$id}", ''));

            // Handle file upload for this specific card
            $image = null;
            if ($request->hasFile("image_file.{$id}")) {
                $file = $request->file("image_file.{$id}");
                $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads');
                if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $image = asset('uploads/' . $fileName);
            } else {
                $image = $imageUrl;
            }

            if (empty($image)) {
                $image = 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
            }

            $productData = [
                'catalog' => $catalog,
                'title' => $title,
                'category' => $category,
                'sector' => $sector ?: null,
                'description' => $description,
                'image' => $image
            ];

            $existing = $this->dataService->getProductByTitle($title);
            if ($existing) {
                $this->dataService->updateProduct($title, $productData);
            } else {
                $this->dataService->addProduct($productData);
            }

            $savedCount++;
        }

        if ($savedCount > 0) {
            return redirect()->route('admin.products')->with('success', "Berhasil menyimpan $savedCount produk secara massal!");
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Harap isi minimal judul dan kategori produk.');
    }

    // ----------------------------------------------------
    // Posts CRUD Handlers
    // ----------------------------------------------------
    public function postsIndex(Request $request)
    {
        $posts = $this->dataService->getPosts();

        // Paginate posts list (10 per page)
        $totalPosts = count($posts);
        $perPage = 10;
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedPosts = array_slice($posts, $offset, $perPage);

        return view('admin.posts.index', [
            'posts' => $paginatedPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function postsCreate()
    {
        return view('admin.posts.form');
    }

    public function postsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'content' => 'required|string'
        ]);

        $slug = Str::slug($request->input('title'));

        // Check unique slug
        if ($this->dataService->getPostBySlug($slug)) {
            $slug .= '-' . rand(10, 99);
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80');

        $post = [
            'slug' => $slug,
            'title' => $request->input('title'),
            'date' => date('d M Y'), // Set automatically
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->addPost($post);

        return redirect()->route('admin.posts')->with('success', 'Artikel baru berhasil diterbitkan!');
    }

    public function postsEdit(string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }
        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(Request $request, string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'content' => 'required|string'
        ]);

        $newTitle = $request->input('title');
        $newSlug = $post['slug'];
        if ($newTitle !== $post['title']) {
            $newSlug = Str::slug($newTitle);
            
            // Check unique slug if changed
            if ($newSlug !== $slug && $this->dataService->getPostBySlug($newSlug)) {
                $newSlug .= '-' . rand(10, 99);
            }
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $post['image']);

        $updatedPost = [
            'slug' => $newSlug,
            'title' => $newTitle,
            'date' => $post['date'], // Keep original date
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->updatePost($slug, $updatedPost);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function postsDestroy(string $slug)
    {
        $this->dataService->deletePost($slug);
        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil dihapus!');
    }

    // ----------------------------------------------------
    // Sectors CRUD Handlers
    // ----------------------------------------------------
    public function sectorsIndex()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.sectors.index', compact('sectors'));
    }

    public function sectorsCreate()
    {
        return view('admin.sectors.form');
    }

    public function sectorsStore(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_dash',
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $id = strtolower($request->input('id'));

        if ($this->dataService->getSectorById($id)) {
            return redirect()->back()->withInput()->with('error', 'Sektor dengan ID tersebut sudah ada.');
        }

        // Split description by new lines into an array
        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', '');

        $sector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->addSector($sector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor industri baru berhasil ditambahkan!');
    }

    public function sectorsEdit(string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }
        return view('admin.sectors.form', compact('sector'));
    }

    public function sectorsUpdate(Request $request, string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        // Split description by new lines into an array
        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $existingImg = $sector['image'] ?? '';
        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $existingImg);

        $updatedSector = [
            'id' => $id, // Keep original ID as key
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->updateSector($id, $updatedSector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function sectorsDestroy(string $id)
    {
        $this->dataService->deleteSector($id);
        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil dihapus!');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    // ----------------------------------------------------
    // Authentication Handlers
    // ----------------------------------------------------
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->is_admin) {
                Auth::logout();
                return redirect()->back()->withInput()->with('error', 'Anda tidak memiliki akses admin.');
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Administrator!');
        }

        return redirect()->back()->withInput()->with('error', 'Email atau password yang Anda masukkan salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }

    // ----------------------------------------------------
    // Dashboard Handler
    // ----------------------------------------------------
    public function dashboard()
    {
        $products = $this->dataService->getProducts();
        $productsCount = count($products);
        $postsCount = count($this->dataService->getPosts());
        $sectorsCount = count($this->dataService->getSectors());

        $recentProducts = array_slice($products, 0, 5);
        $recentPosts = array_slice($this->dataService->getPosts(), 0, 5);

        // Count product categories distribution for the chart
        $categoryDist = [
            'Culture Media' => 0,
            'Instruments' => 0,
            'Chemicals & Reagents' => 0,
            'Consumables' => 0
        ];
        foreach ($products as $p) {
            $cat = $p['category'] ?? '';
            if ($cat === 'culture-media') $categoryDist['Culture Media']++;
            elseif ($cat === 'instruments') $categoryDist['Instruments']++;
            elseif ($cat === 'chemicals') $categoryDist['Chemicals & Reagents']++;
            elseif ($cat === 'consumables') $categoryDist['Consumables']++;
        }

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount',
            'recentProducts', 'recentPosts', 'categoryDist'
        ));
    }

    // ----------------------------------------------------
    // Helper for File Uploads (Fallback to URL text field)
    // ----------------------------------------------------
    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists in public folder
            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }
        
        return $request->input($urlKey, $fallback);
    }

    // ----------------------------------------------------
    // Home Editor Handlers
    // ----------------------------------------------------
    public function homeEdit()
    {
        $homeData = $this->dataService->getHomepageData();
        return view('admin.home-editor', compact('homeData'));
    }

    public function homeUpdate(Request $request)
    {
        $homeData = $this->dataService->getHomepageData();

        // 1. Text values
        $homeData['hero_title'] = $request->input('hero_title', $homeData['hero_title']);
        $homeData['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle']);
        $homeData['focus_title'] = $request->input('focus_title', $homeData['focus_title']);
        $homeData['about_title'] = $request->input('about_title', $homeData['about_title']);
        $homeData['about_description'] = $request->input('about_description', $homeData['about_description']);
        $homeData['hotline_label'] = $request->input('hotline_label', $homeData['hotline_label']);
        $homeData['hotline_number'] = $request->input('hotline_number', $homeData['hotline_number']);
        $homeData['hotline_description'] = $request->input('hotline_description', $homeData['hotline_description']);

        // 2. Hero Slideshow Images
        for ($i = 0; $i < 4; $i++) {
            $existing = $homeData['hero_images'][$i] ?? '';
            $homeData['hero_images'][$i] = $this->handleImageUpload(
                $request, "hero_image_file_$i", "hero_image_url_$i", $existing
            );
        }

        // 3. Focus Cards
        for ($i = 0; $i < 3; $i++) {
            $existingCard = $homeData['focus_cards'][$i] ?? [];
            $existingImg = $existingCard['image'] ?? '';
            
            $homeData['focus_cards'][$i] = [
                'title' => $request->input("focus_card_title_$i", $existingCard['title'] ?? ''),
                'description' => $request->input("focus_card_desc_$i", $existingCard['description'] ?? ''),
                'image' => $this->handleImageUpload(
                    $request, "focus_card_file_$i", "focus_card_url_$i", $existingImg
                )
            ];
        }

        // 4. Extended Page Configurations
        $homeData['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
        $homeData['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
        $homeData['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
        $homeData['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
        $homeData['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
        $homeData['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
        
        $homeData['products_title'] = $request->input('products_title', $homeData['products_title'] ?? '');
        $homeData['products_subtitle'] = $request->input('products_subtitle', $homeData['products_subtitle'] ?? '');
        
        $homeData['sectors_title'] = $request->input('sectors_title', $homeData['sectors_title'] ?? '');
        $homeData['sectors_subtitle'] = $request->input('sectors_subtitle', $homeData['sectors_subtitle'] ?? '');
        
        $homeData['services_title'] = $request->input('services_title', $homeData['services_title'] ?? '');
        $homeData['services_subtitle'] = $request->input('services_subtitle', $homeData['services_subtitle'] ?? '');
        
        $homeData['info_title'] = $request->input('info_title', $homeData['info_title'] ?? '');
        $homeData['info_subtitle'] = $request->input('info_subtitle', $homeData['info_subtitle'] ?? '');
        
        $homeData['contact_title'] = $request->input('contact_title', $homeData['contact_title'] ?? '');
        $homeData['contact_subtitle'] = $request->input('contact_subtitle', $homeData['contact_subtitle'] ?? '');

        // 5. Extended Page Images/Banners
        $homeData['products_banner_image'] = $this->handleImageUpload(
            $request, "products_banner_file", "products_banner_url", $homeData['products_banner_image'] ?? ''
        );
        $homeData['sectors_banner_image'] = $this->handleImageUpload(
            $request, "sectors_banner_file", "sectors_banner_url", $homeData['sectors_banner_image'] ?? ''
        );
        $homeData['services_banner_image'] = $this->handleImageUpload(
            $request, "services_banner_file", "services_banner_url", $homeData['services_banner_image'] ?? ''
        );
        $homeData['info_banner_image'] = $this->handleImageUpload(
            $request, "info_banner_file", "info_banner_url", $homeData['info_banner_image'] ?? ''
        );
        $homeData['contact_banner_image'] = $this->handleImageUpload(
            $request, "contact_banner_file", "contact_banner_url", $homeData['contact_banner_image'] ?? ''
        );

        $this->dataService->saveHomepageData($homeData);

        $section = $request->input('section');
        $params = $section ? ['section' => $section] : [];

        return redirect()->route('admin.home.edit', $params)->with('success', 'Layout dan konfigurasi halaman berhasil diperbarui!');
    }

    // ----------------------------------------------------
    // Products CRUD Handlers
    // ----------------------------------------------------
    public function productsIndex(Request $request)
    {
        $products = $this->dataService->getProducts();
        
        // Filter by search
        $search = $request->input('s');
        if ($search) {
            $products = array_filter($products, function ($p) use ($search) {
                return Str::contains(strtolower($p['title']), strtolower($search)) || 
                       Str::contains(strtolower($p['catalog'] ?? ''), strtolower($search)) ||
                       Str::contains(strtolower($p['description'] ?? ''), strtolower($search));
            });
        }

        // Filter by category
        $category = $request->input('category');
        if ($category) {
            $products = array_filter($products, function ($p) use ($category) {
                return ($p['category'] ?? '') === $category;
            });
        }

        // Filter by sector
        $sector = $request->input('sector');
        if ($sector) {
            $products = array_filter($products, function ($p) use ($sector) {
                $prodSectors = explode(',', $p['sector'] ?? '');
                return in_array($sector, $prodSectors);
            });
        }

        // Paginate products list (15 per page)
        $totalProducts = count($products);
        $perPage = 15;
        $totalPages = (int)ceil($totalProducts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedProducts = array_slice($products, $offset, $perPage);

        $sectors = $this->dataService->getSectors();

        return view('admin.products.index', [
            'products' => $paginatedProducts,
            'sectors' => $sectors,
            'search' => $search,
            'category' => $category,
            'sector' => $sector,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function productsCreate()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('sectors'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $title = $request->input('title');
        
        // Validate uniqueness of title
        if ($this->dataService->getProductByTitle($title)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80');

        $product = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $title,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sector' => $request->input('sector') ?: '',
            'image' => $image
        ];

        $this->dataService->addProduct($product);

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function productsEdit(string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('product', 'sectors'));
    }

    public function productsUpdate(Request $request, string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $newTitle = $request->input('title');
        
        // Validate uniqueness if title changed
        if ($newTitle !== $title && $this->dataService->getProductByTitle($newTitle)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image']);

        $updatedProduct = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $newTitle,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sector' => $request->input('sector') ?: '',
            'image' => $image
        ];

        $this->dataService->updateProduct($title, $updatedProduct);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productsDestroy(string $title)
    {
        $this->dataService->deleteProduct($title);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }
    public function productsCreateBulk()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.products.bulk-form', compact('sectors'));
    }

    public function productsStoreBulk(Request $request)
    {
        $titles = $request->input('title', []);
        $savedCount = 0;

        foreach ($titles as $id => $title) {
            $title = trim($title);
            $category = trim($request->input("category.{$id}", ''));

            if (empty($title) || empty($category)) {
                continue;
            }

            $catalog = trim($request->input("catalog.{$id}", ''));
            $sector = trim($request->input("sector.{$id}", ''));
            $description = trim($request->input("description.{$id}", ''));
            $imageUrl = trim($request->input("image_url.{$id}", ''));

            // Handle file upload for this specific card
            $image = null;
            if ($request->hasFile("image_file.{$id}")) {
                $file = $request->file("image_file.{$id}");
                $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $uploadPath = public_path('uploads');
                if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $image = asset('uploads/' . $fileName);
            } else {
                $image = $imageUrl;
            }

            if (empty($image)) {
                $image = 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
            }

            $productData = [
                'catalog' => $catalog,
                'title' => $title,
                'category' => $category,
                'sector' => $sector ?: null,
                'description' => $description,
                'image' => $image
            ];

            $existing = $this->dataService->getProductByTitle($title);
            if ($existing) {
                $this->dataService->updateProduct($title, $productData);
            } else {
                $this->dataService->addProduct($productData);
            }

            $savedCount++;
        }

        if ($savedCount > 0) {
            return redirect()->route('admin.products')->with('success', "Berhasil menyimpan $savedCount produk secara massal!");
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Harap isi minimal judul dan kategori produk.');
    }

    // ----------------------------------------------------
    // Posts CRUD Handlers
    // ----------------------------------------------------
    public function postsIndex(Request $request)
    {
        $posts = $this->dataService->getPosts();

        // Paginate posts list (10 per page)
        $totalPosts = count($posts);
        $perPage = 10;
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedPosts = array_slice($posts, $offset, $perPage);

        return view('admin.posts.index', [
            'posts' => $paginatedPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function postsCreate()
    {
        return view('admin.posts.form');
    }

    public function postsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'content' => 'required|string'
        ]);

        $slug = Str::slug($request->input('title'));

        // Check unique slug
        if ($this->dataService->getPostBySlug($slug)) {
            $slug .= '-' . rand(10, 99);
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80');

        $post = [
            'slug' => $slug,
            'title' => $request->input('title'),
            'date' => date('d M Y'), // Set automatically
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->addPost($post);

        return redirect()->route('admin.posts')->with('success', 'Artikel baru berhasil diterbitkan!');
    }

    public function postsEdit(string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }
        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(Request $request, string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'content' => 'required|string'
        ]);

        $newTitle = $request->input('title');
        $newSlug = $post['slug'];
        if ($newTitle !== $post['title']) {
            $newSlug = Str::slug($newTitle);
            
            // Check unique slug if changed
            if ($newSlug !== $slug && $this->dataService->getPostBySlug($newSlug)) {
                $newSlug .= '-' . rand(10, 99);
            }
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $post['image']);

        $updatedPost = [
            'slug' => $newSlug,
            'title' => $newTitle,
            'date' => $post['date'], // Keep original date
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->updatePost($slug, $updatedPost);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function postsDestroy(string $slug)
    {
        $this->dataService->deletePost($slug);
        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil dihapus!');
    }

    // ----------------------------------------------------
    // Sectors CRUD Handlers
    // ----------------------------------------------------
    public function sectorsIndex()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.sectors.index', compact('sectors'));
    }

    public function sectorsCreate()
    {
        return view('admin.sectors.form');
    }

    public function sectorsStore(Request $request)
    {
        $request->validate([
            'id' => 'required|alpha_dash',
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $id = strtolower($request->input('id'));

        if ($this->dataService->getSectorById($id)) {
            return redirect()->back()->withInput()->with('error', 'Sektor dengan ID tersebut sudah ada.');
        }

        // Split description by new lines into an array
        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', '');

        $sector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->addSector($sector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor industri baru berhasil ditambahkan!');
    }

    public function sectorsEdit(string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }
        return view('admin.sectors.form', compact('sector'));
    }

    public function sectorsUpdate(Request $request, string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);

        // Split description by new lines into an array
        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $existingImg = $sector['image'] ?? '';
        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $existingImg);

        $updatedSector = [
            'id' => $id, // Keep original ID as key
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->updateSector($id, $updatedSector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function sectorsDestroy(string $id)
    {
        $this->dataService->deleteSector($id);
        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil dihapus!');
    }

}
