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
// Protected Admin Panel Routes
// ----------------------------------------------------
Route::middleware([AdminAuthenticate::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    
    // Home Editor
    Route::get('/home', [AdminDashboardController::class, 'homeEdit'])->name('admin.home.edit');
    Route::post('/home', [AdminDashboardController::class, 'homeUpdate'])->name('admin.home.update');
    
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
});