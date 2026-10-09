<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Services\HomepageService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('Helpers/product_url.php');
    }

    public function boot(): void
    {
        Blade::directive('nonce', function () {
            return '<?php echo \'nonce="\' . (app()->bound(\'csp-nonce\') ? app(\'csp-nonce\') : \'\') . \'"\'; ?>';
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Multi-role admin authorization gates
        Gate::define('manage-system', fn (?User $user) => (bool) $user?->isSuperAdmin());
        Gate::define('manage-rfq', fn (?User $user) => (bool) $user?->canManageRfqs());
        Gate::define('manage-catalog', fn (?User $user) => (bool) $user?->canManageCatalog());
        Gate::define('view-catalog', fn (?User $user) => (bool) $user?->canViewCatalog());
        Gate::define('manage-posts', fn (?User $user) => (bool) $user?->canManagePosts());
        Gate::define('manage-media', fn (?User $user) => (bool) ($user?->canManagePosts() || $user?->canManageCatalog()));

        RateLimiter::for('rfq-submission', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return back()->withErrors([
                    'rate_limit' => 'Terlalu banyak permintaan pengajuan penawaran. Silakan tunggu 1 menit sebelum mencoba kembali.',
                ]);
            });
        });

        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak pengiriman pesan dari koneksi Anda. Silakan tunggu 1 menit sebelum mencoba lagi.',
                ], 429);
            });
        });

        RateLimiter::for('admin-login', function (Request $request) {
            $username = Str::transliterate(Str::lower(trim((string) $request->input('username', ''))));

            return [
                Limit::perMinute(5)->by($username ?: 'guest')->response(function () {
                    return back()->withErrors([
                        'login' => 'Terlalu banyak percobaan login gagal. Silakan tunggu 1 menit.',
                    ]);
                }),
                Limit::perMinute(10)->by($request->ip())->response(function () {
                    return back()->withErrors([
                        'login' => 'Terlalu banyak percobaan login gagal dari koneksi ini. Silakan tunggu 1 menit.',
                    ]);
                }),
            ];
        });

        $this->shareFrontendViewData();
    }

    protected function shareFrontendViewData(): void
    {
        View::composer('*', function ($view) {
            try {
                $request = request();
                if ($request && $request->is('admin', 'admin/*')) {
                    return;
                }
            } catch (\Throwable $e) {
                return;
            }

            static $tablesReady = null;
            static $sharedFrontendData = null;

            if ($tablesReady === null) {
                try {
                    $tablesReady = Schema::hasTable('homepage_settings') && Schema::hasTable('products');
                } catch (\Throwable $e) {
                    $tablesReady = false;
                }
            }

            if (! $tablesReady) {
                return;
            }

            if ($sharedFrontendData === null) {
                try {
                    $siteSettings = app(HomepageService::class)->getHomepageData();

                    $searchSuggestions = Cache::remember('search_suggestions_v2', 3600, function () {
                        $default = ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'];
                        try {
                            $productTitles = Product::query()
                                ->orderByDesc('id')
                                ->limit(200)
                                ->pluck('title')
                                ->toArray();

                            if (! empty($productTitles)) {
                                $wordsList = [];
                                $skip = ['smart', 'digital', 'microbial', 'system', 'recombinant', 'based', 'automatic', 'with', 'without', 'medium', 'base'];
                                foreach ($productTitles as $title) {
                                    $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
                                    $words = explode(' ', $clean);
                                    foreach ($words as $word) {
                                        $word = trim($word);
                                        if (strlen($word) > 3 && ! in_array(strtolower($word), $skip, true)) {
                                            $wordsList[] = $word;
                                        }
                                    }
                                }
                                if (! empty($wordsList)) {
                                    return array_slice(array_values(array_unique($wordsList)), 0, 7);
                                }
                            }
                        } catch (\Exception $e) {
                            Log::warning('search_suggestions cache build failed, using defaults.', [
                                'exception' => $e->getMessage(),
                            ]);
                        }

                        return $default;
                    });

                    $sharedFrontendData = [
                        'siteSettings' => $siteSettings,
                        'waNumber' => '',
                        'waNumberTech' => '',
                        'waDefaultMsg' => '',
                        'searchSuggestions' => $searchSuggestions,
                    ];
                } catch (\Exception $e) {
                    return;
                }
            }

            $view->with($sharedFrontendData);
        });
    }
}
