<?php

use Illuminate\Support\Facades\Route;
use App\Services\DataService;
use App\Http\Controllers\AdminController;
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

use Illuminate\Support\Facades\Mail;

Route::get('/test-email-prolabios', function () {
    try {
        Mail::raw('Halo, ini adalah pesan tes langsung tanpa queue dari Laravel Prolabios.', function ($message) {
            $message->to('marketing@prolabios.com')
                    ->subject('TES SMTP LANGSUNG');
        });
        return 'SKSES! Email berhasil dikirim ke Google SMTP.';
    } catch (\Exception $e) {
        return 'ERROR KETEMU: <br>' . $e->getMessage();
    }
});