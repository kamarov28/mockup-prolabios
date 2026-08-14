<?php

namespace App\Providers;

use App\Services\DataService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ----------------------------------------------------
        // Security Rate Limiters (Strict Per-IP & Endpoint Guards)
        // ----------------------------------------------------
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
            $username = (string) $request->input('username', '');

            return Limit::perMinute(5)->by($request->ip().'|'.$username)->response(function () {
                return back()->withErrors([
                    'login' => 'Terlalu banyak percobaan login gagal. Silakan tunggu 1 menit.',
                ]);
            });
        });
        try {
            $siteSettings = Cache::remember('homepage_settings_v3', 3600, function () {
                $dataService = app(DataService::class);

                return $dataService->getHomepageData();
            });

            // Clean phone number for WhatsApp API
            $rawPhone = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone'] ?? '0821-8792-9433');
            $waNumber = (strpos($rawPhone, '0') === 0) ? '62'.substr($rawPhone, 1) : $rawPhone;

            // Clean technician phone number for WhatsApp API
            $rawPhoneTech = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone_technician'] ?? '0812-837-4867');
            $waNumberTech = (strpos($rawPhoneTech, '0') === 0) ? '62'.substr($rawPhoneTech, 1) : $rawPhoneTech;

            $searchSuggestions = Cache::remember('search_suggestions_v2', 3600, function () {
                $default = ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'];
                try {
                    $productTitles = DB::table('products')->pluck('title')->toArray();
                    if (! empty($productTitles)) {
                        $wordsList = [];
                        foreach ($productTitles as $title) {
                            $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
                            $words = explode(' ', $clean);
                            foreach ($words as $word) {
                                $word = trim($word);
                                if (strlen($word) > 3 && ! in_array(strtolower($word), ['smart', 'digital', 'microbial', 'system', 'recombinant', 'based', 'automatic', 'with', 'without', 'medium', 'base'])) {
                                    $wordsList[] = $word;
                                }
                            }
                        }
                        if (! empty($wordsList)) {
                            return array_slice(array_unique($wordsList), 0, 7);
                        }
                    }
                } catch (\Exception $e) {
                }

                return $default;
            });

            View::share('siteSettings', $siteSettings);
            View::share('waNumber', $waNumber);
            View::share('waNumberTech', $waNumberTech);
            View::share('searchSuggestions', $searchSuggestions);
        } catch (\Exception $e) {
            // Safe fallback during command execution
        }
    }
}
