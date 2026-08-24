<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminPrincipalController;
use App\Http\Controllers\Admin\AdminProductCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminRfqController;
use App\Http\Controllers\Admin\AdminSectorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------
// SEO & Crawlers
// ----------------------------------------------------
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('seo.robots');

// ----------------------------------------------------
// System Health & Monitoring
// ----------------------------------------------------
Route::get('/health', [HealthController::class, 'check'])->name('system.health');

// ----------------------------------------------------
// Public Frontend Routes
// ----------------------------------------------------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/profil', [PageController::class, 'profil']);
Route::get('/produk', [PageController::class, 'produk'])->name('produk.index');

// Legacy query-string detail (?id=) → 301 to /produk/{slug}
Route::get('/produk/detail', [PageController::class, 'detailProdukLegacy'])->name('produk.detail.legacy');
Route::get('/produk/beli', [PageController::class, 'beliProduk'])->name('produk.beli');

// Canonical product detail by slug (must stay after /produk/detail & /produk/beli)
Route::get('/produk/{slug}', [PageController::class, 'detailProduk'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('produk.detail');

Route::get('/sektor', [PageController::class, 'sektor']);
Route::get('/layanan', [PageController::class, 'layanan']);
Route::get('/informasi/{slug?}', [PageController::class, 'informasi'])->name('informasi');
Route::get('/kontak', [PageController::class, 'kontak']);
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [PageController::class, 'terms'])->name('terms');
Route::post('/kontak', [ContactController::class, 'submit'])
    ->middleware('throttle:contact-form')
    ->name('contact.submit');

// ----------------------------------------------------
// Admin Login Routes
// ----------------------------------------------------
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:admin-login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// ----------------------------------------------------
// Cart & B2B RFQ Routes
// ----------------------------------------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/rfq/checkout', [RfqController::class, 'checkout'])->name('rfq.checkout');
Route::post('/rfq/submit', [RfqController::class, 'store'])->middleware('throttle:rfq-submission')->name('rfq.store');
Route::get('/rfq/success/{number}', [RfqController::class, 'success'])->middleware('throttle:20,1')->name('rfq.success');

// ----------------------------------------------------
// Protected Admin Panel Routes
// ----------------------------------------------------
Route::middleware([AdminAuthenticate::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/home', [AdminDashboardController::class, 'homeEdit'])->name('admin.home.edit');
    Route::post('/home', [AdminDashboardController::class, 'homeUpdate'])->name('admin.home.update');

    Route::get('/guide', [AdminDashboardController::class, 'guide'])->name('admin.guide');

    Route::get('/products', [AdminProductController::class, 'productsIndex'])->name('admin.products');
    Route::get('/products/create', [AdminProductController::class, 'productsCreate'])->name('admin.products.create');
    Route::get('/products/create-bulk', [AdminProductController::class, 'productsCreateBulk'])->name('admin.products.create.bulk');
    Route::post('/products/store-bulk', [AdminProductController::class, 'productsStoreBulk'])->name('admin.products.store-bulk');
    Route::post('/products', [AdminProductController::class, 'productsStore'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'productsEdit'])->name('admin.products.edit');
    Route::match(['post', 'put'], '/products/{id}', [AdminProductController::class, 'productsUpdate'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'productsDestroy'])->name('admin.products.destroy');

    Route::get('/categories', [AdminProductCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminProductCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [AdminProductCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [AdminProductCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::match(['post', 'put'], '/categories/{id}', [AdminProductCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminProductCategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/api/subcategories', [AdminProductCategoryController::class, 'apiSubcategories'])->name('admin.api.subcategories');

    Route::get('/posts', [AdminPostController::class, 'postsIndex'])->name('admin.posts');
    Route::get('/posts/create', [AdminPostController::class, 'postsCreate'])->name('admin.posts.create');
    Route::post('/posts', [AdminPostController::class, 'postsStore'])->name('admin.posts.store');
    Route::get('/posts/{slug}/edit', [AdminPostController::class, 'postsEdit'])->name('admin.posts.edit');
    Route::match(['post', 'put'], '/posts/{slug}', [AdminPostController::class, 'postsUpdate'])->name('admin.posts.update');
    Route::delete('/posts/{slug}', [AdminPostController::class, 'postsDestroy'])->name('admin.posts.destroy');

    Route::get('/sectors', [AdminSectorController::class, 'sectorsIndex'])->name('admin.sectors');
    Route::get('/sectors/create', [AdminSectorController::class, 'sectorsCreate'])->name('admin.sectors.create');
    Route::post('/sectors', [AdminSectorController::class, 'sectorsStore'])->name('admin.sectors.store');
    Route::get('/sectors/{id}/edit', [AdminSectorController::class, 'sectorsEdit'])->name('admin.sectors.edit');
    Route::match(['post', 'put'], '/sectors/{id}', [AdminSectorController::class, 'sectorsUpdate'])->name('admin.sectors.update');
    Route::delete('/sectors/{id}', [AdminSectorController::class, 'sectorsDestroy'])->name('admin.sectors.destroy');

    Route::get('/principals', [AdminPrincipalController::class, 'index'])->name('admin.principals');
    Route::get('/principals/create', [AdminPrincipalController::class, 'create'])->name('admin.principals.create');
    Route::post('/principals', [AdminPrincipalController::class, 'store'])->name('admin.principals.store');
    Route::get('/principals/{id}/edit', [AdminPrincipalController::class, 'edit'])->name('admin.principals.edit');
    Route::match(['post', 'put'], '/principals/{id}', [AdminPrincipalController::class, 'update'])->name('admin.principals.update');
    Route::delete('/principals/{id}', [AdminPrincipalController::class, 'destroy'])->name('admin.principals.destroy');

    Route::get('/rfqs', [AdminRfqController::class, 'index'])->name('admin.rfqs.index');
    Route::get('/rfqs/{id}', [AdminRfqController::class, 'show'])->name('admin.rfqs.show');
    Route::delete('/rfqs/{id}', [AdminRfqController::class, 'destroy'])->name('admin.rfqs.destroy');
});
