<?php

use Illuminate\Support\Facades\Route;
use App\Services\DataService;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminSectorController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Str;

// ----------------------------------------------------
// Public Frontend Routes
// ----------------------------------------------------
Route::get('/', [PageController::class, 'home']);
Route::get('/profil', [PageController::class, 'profil']);
Route::get('/produk', [PageController::class, 'produk']);
Route::get('/sektor', [PageController::class, 'sektor']);
Route::get('/produk/detail', [PageController::class, 'detailProduk']);
Route::get('/layanan', [PageController::class, 'layanan']);
Route::get('/informasi', [PageController::class, 'informasi']);
Route::get('/kontak', [PageController::class, 'kontak']);
Route::post('/kontak', [\App\Http\Controllers\ContactController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('contact.submit');

// ----------------------------------------------------
// Admin Login Routes
// ----------------------------------------------------
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:5,1');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// ----------------------------------------------------
// Cart & B2B RFQ Routes
// ----------------------------------------------------
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

Route::get('/rfq/checkout', [\App\Http\Controllers\RfqController::class, 'checkout'])->name('rfq.checkout');
Route::post('/rfq/submit', [\App\Http\Controllers\RfqController::class, 'store'])->middleware('throttle:10,1')->name('rfq.store');
Route::get('/rfq/success/{number}', [\App\Http\Controllers\RfqController::class, 'success'])->name('rfq.success');
Route::get('/rfq/track/{number}', [\App\Http\Controllers\RfqController::class, 'track'])->name('rfq.track');
Route::post('/rfq/approve/{number}', [\App\Http\Controllers\RfqController::class, 'approve'])->middleware('throttle:5,1')->name('rfq.approve');
Route::get('/rfq/pdf/{number}', [\App\Http\Controllers\RfqController::class, 'pdf'])->name('rfq.pdf');

// ----------------------------------------------------
// Protected Admin Panel Routes
// ----------------------------------------------------
Route::middleware([AdminAuthenticate::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    
    // Home Editor
    Route::get('/home', [AdminDashboardController::class, 'homeEdit'])->name('admin.home.edit');
    Route::post('/home', [AdminDashboardController::class, 'homeUpdate'])->name('admin.home.update');
    
    // Admin RFQ Management
    Route::get('/rfq', [\App\Http\Controllers\Admin\AdminRfqController::class, 'index'])->name('admin.rfq');
    Route::get('/rfq/{id}/respond', [\App\Http\Controllers\Admin\AdminRfqController::class, 'respond'])->name('admin.rfq.respond');
    Route::post('/rfq/{id}/update', [\App\Http\Controllers\Admin\AdminRfqController::class, 'updateQuotation'])->name('admin.rfq.update');
    Route::delete('/rfq/{id}', [\App\Http\Controllers\Admin\AdminRfqController::class, 'destroy'])->name('admin.rfq.destroy');

    // Products CRUD
    Route::get('/products', [AdminProductController::class, 'productsIndex'])->name('admin.products');
    Route::get('/products/create', [AdminProductController::class, 'productsCreate'])->name('admin.products.create');
    Route::get('/products/create-bulk', [AdminProductController::class, 'productsCreateBulk'])->name('admin.products.create.bulk');
    Route::post('/products/store-bulk', [AdminProductController::class, 'productsStoreBulk'])->name('admin.products.store-bulk');
    Route::post('/products', [AdminProductController::class, 'productsStore'])->name('admin.products.store');
    Route::get('/products/{title}/edit', [AdminProductController::class, 'productsEdit'])->name('admin.products.edit');
    Route::post('/products/{title}', [AdminProductController::class, 'productsUpdate'])->name('admin.products.update');
    Route::delete('/products/{title}', [AdminProductController::class, 'productsDestroy'])->name('admin.products.destroy');
    
    // Posts CRUD
    Route::get('/posts', [AdminPostController::class, 'postsIndex'])->name('admin.posts');
    Route::get('/posts/create', [AdminPostController::class, 'postsCreate'])->name('admin.posts.create');
    Route::post('/posts', [AdminPostController::class, 'postsStore'])->name('admin.posts.store');
    Route::get('/posts/{slug}/edit', [AdminPostController::class, 'postsEdit'])->name('admin.posts.edit');
    Route::post('/posts/{slug}', [AdminPostController::class, 'postsUpdate'])->name('admin.posts.update');
    Route::delete('/posts/{slug}', [AdminPostController::class, 'postsDestroy'])->name('admin.posts.destroy');
    
    // Sectors CRUD
    Route::get('/sectors', [AdminSectorController::class, 'sectorsIndex'])->name('admin.sectors');
    Route::get('/sectors/create', [AdminSectorController::class, 'sectorsCreate'])->name('admin.sectors.create');
    Route::post('/sectors', [AdminSectorController::class, 'sectorsStore'])->name('admin.sectors.store');
    Route::get('/sectors/{id}/edit', [AdminSectorController::class, 'sectorsEdit'])->name('admin.sectors.edit');
    Route::post('/sectors/{id}', [AdminSectorController::class, 'sectorsUpdate'])->name('admin.sectors.update');
    Route::delete('/sectors/{id}', [AdminSectorController::class, 'sectorsDestroy'])->name('admin.sectors.destroy');

    // Principals CRUD
    Route::get('/principals', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'index'])->name('admin.principals');
    Route::get('/principals/create', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'create'])->name('admin.principals.create');
    Route::post('/principals', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'store'])->name('admin.principals.store');
    Route::get('/principals/{id}/edit', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'edit'])->name('admin.principals.edit');
    Route::post('/principals/{id}', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'update'])->name('admin.principals.update');
    Route::delete('/principals/{id}', [\App\Http\Controllers\Admin\AdminPrincipalController::class, 'destroy'])->name('admin.principals.destroy');
});