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

        $expectedUser = config('contact.admin_username');
        $expectedPass = config('contact.admin_password');

        if (hash_equals((string)$expectedUser, (string)$username) && hash_equals((string)$expectedPass, (string)$password)) {
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

        // Mengisi distribusi kategori secara dinamis berdasarkan data produk yang ada
                $categoryDist = [];
                foreach ($products as $p) {
                    $catRaw = $p['category'] ?? '';
                    if (!empty($catRaw)) {
                        // Mengubah slug (misal: culture-media) menjadi nama berhuruf kapital (Culture Media)
                        $catName = ucwords(str_replace('-', ' ', $catRaw));
                        
                        if (!isset($categoryDist[$catName])) {
                            $categoryDist[$catName] = 0;
                        }
                        $categoryDist[$catName]++;
                    }
                }
                
                // Jaga-jaga jika database benar-benar kosong agar chart tidak error membaca array kosong
                if (empty($categoryDist)) {
                    $categoryDist = ['Belum Ada Produk' => 0];
                }

        $homeData = $this->dataService->getHomepageData();

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount',
            'recentProducts', 'recentPosts', 'categoryDist', 'homeData'
        ));
    }

    // ----------------------------------------------------
    // Helper for File Uploads (Fallback to URL text field)
    // ----------------------------------------------------
    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
            $file = $request->file($fileKey);
            
            $ext = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            
            if (!in_array($ext, $allowedExtensions, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp, svg.']
                ]);
            }

            $mime = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (!in_array($mime, $allowedMimes, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                ]);
            }

            $fileName = time() . '_' . Str::random(8) . '.' . $ext;
            
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

        $homeData['hero_title'] = $request->input('hero_title', $homeData['hero_title']);
        $homeData['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle']);
        $homeData['focus_title'] = $request->input('focus_title', $homeData['focus_title']);
        $homeData['about_title'] = $request->input('about_title', $homeData['about_title']);
        $homeData['about_description'] = $request->input('about_description', $homeData['about_description']);
        $homeData['hotline_label'] = $request->input('hotline_label', $homeData['hotline_label']);
        $homeData['hotline_number'] = $request->input('hotline_number', $homeData['hotline_number']);
        $homeData['hotline_description'] = $request->input('hotline_description', $homeData['hotline_description']);

        for ($i = 0; $i < 4; $i++) {
            $existing = $homeData['hero_images'][$i] ?? '';
            $homeData['hero_images'][$i] = $this->handleImageUpload(
                $request, "hero_image_file_$i", "hero_image_url_$i", $existing
            );
        }

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

        $homeData['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
        $homeData['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
        $homeData['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
        $homeData['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
        $homeData['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
        $homeData['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
        $homeData['catalog_pdf_url'] = $request->input('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '');

        $homeData['company_name'] = $request->input('company_name', $homeData['company_name'] ?? '');
        $homeData['operational_hours'] = $request->input('operational_hours', $homeData['operational_hours'] ?? '');
        $homeData['social_instagram'] = $request->input('social_instagram', $homeData['social_instagram'] ?? '');
        $homeData['social_facebook'] = $request->input('social_facebook', $homeData['social_facebook'] ?? '');
        $homeData['social_linkedin'] = $request->input('social_linkedin', $homeData['social_linkedin'] ?? '');
        $homeData['social_twitter'] = $request->input('social_twitter', $homeData['social_twitter'] ?? '');

        $homeData['site_logo'] = $this->handleImageUpload(
            $request, 'site_logo_file', 'site_logo_url', $homeData['site_logo'] ?? ''
        );
        
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
        $query = \Illuminate\Support\Facades\DB::table('products');
        
        $search = $request->input('s');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('catalog', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $category = $request->input('category');
        if ($category) {
            $query->where('category', $category);
        }

        $sector = $request->input('sector');
        if ($sector) {
            $query->where(function($q) use ($sector) {
                $q->where('sector', $sector)
                  ->orWhere('sector', 'like', "%{$sector}%")
                  ->orWhere('sector', 'like', "%,{$sector},%")
                  ->orWhere('sector', 'like', "%,{$sector}")
                  ->orWhere('sector', 'like', "{$sector},%");
            });
        }

        // Filter Rentang Tanggal (Advanced)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Pengurutan Urutan (Advanced Sorting)
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $totalProducts = $query->count();
        $perPage = 15;
        $totalPages = (int)ceil($totalProducts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedProducts = $query->skip($offset)->take($perPage)->get()->map(fn($r) => (array) $r)->toArray();

        $sectors = $this->dataService->getSectors();

        return view('admin.products.index', [
            'products' => $paginatedProducts,
            'sectors' => $sectors,
            'search' => $search,
            'category' => $category,
            'sector' => $sector,
            'sort' => $sort,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function productsCreate()
    {
        $sectors = $this->dataService->getSectors();
        
        // Inisialisasi data array kosong agar view form.blade.php tidak crash membaca key object
        $product = [
            'title' => '',
            'catalog' => '',
            'category' => '',
            'sub_category' => '',
            'sector' => '',
            'image' => '',
            'description' => ''
        ];
        
        return view('admin.products.form', compact('sectors', 'product'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'sub_category' => 'nullable|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $title = $request->input('title');
        
        if ($this->dataService->getProductByTitle($title)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80');

        $product = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $title,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
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
            'sub_category' => 'nullable|string',
            'catalog' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $newTitle = $request->input('title');
        
        if ($newTitle !== $title && $this->dataService->getProductByTitle($newTitle)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image']);

        $updatedProduct = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $newTitle,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
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
        $productsToStore = [];

        foreach ($titles as $id => $title) {
            $title = trim($title);
            $category = trim($request->input("category.{$id}", ''));

            if (empty($title) || empty($category)) {
                continue;
            }

            $catalog = trim($request->input("catalog.{$id}", ''));
            $subCategory = trim($request->input("sub_category.{$id}", '')); 
            $sector = trim($request->input("sector.{$id}", ''));
            $description = trim($request->input("description.{$id}", ''));
            $imageUrl = trim($request->input("image_url.{$id}", ''));

            $image = null;
            if ($request->hasFile("image_file.{$id}") && $request->file("image_file.{$id}")->isValid()) {
                $file = $request->file("image_file.{$id}");
                
                $ext = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                if (!in_array($ext, $allowedExtensions, true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "image_file.{$id}" => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp, svg.']
                    ]);
                }

                $mime = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!in_array($mime, $allowedMimes, true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "image_file.{$id}" => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                    ]);
                }

                $fileName = time() . '_' . Str::random(8) . '.' . $ext;
                $uploadPath = public_path('uploads');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $image = asset('uploads/' . $fileName);
            } else {
                $image = $imageUrl;
            }

            if (empty($image)) {
                $image = 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
            }

            $productsToStore[] = [
                'catalog' => $catalog,
                'title' => $title,
                'category' => $category,
                'sub_category' => $subCategory, 
                'sector' => $sector ?: null,
                'description' => $description,
                'image' => $image
            ];
        }

        $savedCount = count($productsToStore);
        if ($savedCount > 0) {
            $this->dataService->upsertProducts($productsToStore);
            return redirect()->route('admin.products')->with('success', "Berhasil menyimpan $savedCount produk secara massal!");
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Harap isi minimal judul dan kategori produk.');
    }

    // ----------------------------------------------------
    // Posts CRUD Handlers
    // ----------------------------------------------------
    public function postsIndex(Request $request)
    {
        $query = \Illuminate\Support\Facades\DB::table('posts');

        $search = $request->input('s');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $category = $request->input('category');
        if ($category) {
            $query->where('category', $category);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'title_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $totalPosts = $query->count();
        $perPage = 10;
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedPosts = $query->skip($offset)->take($perPage)->get()->map(fn($r) => (array) $r)->toArray();

        return view('admin.posts.index', [
            'posts' => $paginatedPosts,
            'search' => $search,
            'category' => $category,
            'sort' => $sort,
            'start_date' => $startDate,
            'end_date' => $endDate,
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

        if ($this->dataService->getPostBySlug($slug)) {
            $slug .= '-' . rand(10, 99);
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80');

        $post = [
            'slug' => $slug,
            'title' => $request->input('title'),
            'date' => date('d M Y'), 
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
            
            if ($newSlug !== $slug && $this->dataService->getPostBySlug($newSlug)) {
                $newSlug .= '-' . rand(10, 99);
            }
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $post['image']);

        $updatedPost = [
            'slug' => $newSlug,
            'title' => $newTitle,
            'date' => $post['date'], 
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

        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $existingImg = $sector['image'] ?? '';
        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $existingImg);

        $updatedSector = [
            'id' => $id, 
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