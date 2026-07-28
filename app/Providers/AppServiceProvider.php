<?php

namespace App\Providers;

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
        try {
            $siteSettings = \Illuminate\Support\Facades\Cache::remember('homepage_settings_v3', 3600, function() {
                $dataService = app(\App\Services\DataService::class);
                return $dataService->getHomepageData();
            });
            
            // Clean phone number for WhatsApp API
            $rawPhone = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone'] ?? '0821-8792-9433');
            $waNumber = (strpos($rawPhone, '0') === 0) ? '62' . substr($rawPhone, 1) : $rawPhone;

            // Clean technician phone number for WhatsApp API
            $rawPhoneTech = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone_technician'] ?? '0812-837-4867');
            $waNumberTech = (strpos($rawPhoneTech, '0') === 0) ? '62' . substr($rawPhoneTech, 1) : $rawPhoneTech;

            $searchSuggestions = \Illuminate\Support\Facades\Cache::remember('search_suggestions_v2', 3600, function () {
                $default = ['Agar', 'Broth', 'Pipette', 'Bactobank', 'Sampler', 'Endotoxin', 'Petriswiss'];
                try {
                    $productTitles = \Illuminate\Support\Facades\DB::table('products')->pluck('title')->toArray();
                    if (!empty($productTitles)) {
                        $wordsList = [];
                        foreach ($productTitles as $title) {
                            $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
                            $words = explode(' ', $clean);
                            foreach ($words as $word) {
                                $word = trim($word);
                                if (strlen($word) > 3 && !in_array(strtolower($word), ['smart', 'digital', 'microbial', 'system', 'recombinant', 'based', 'automatic', 'with', 'without', 'medium', 'base'])) {
                                    $wordsList[] = $word;
                                }
                            }
                        }
                        if (!empty($wordsList)) {
                            return array_slice(array_unique($wordsList), 0, 7);
                        }
                    }
                } catch (\Exception $e) {}
                return $default;
            });

            \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
            \Illuminate\Support\Facades\View::share('waNumber', $waNumber);
            \Illuminate\Support\Facades\View::share('waNumberTech', $waNumberTech);
            \Illuminate\Support\Facades\View::share('searchSuggestions', $searchSuggestions);
        } catch (\Exception $e) {
            // Safe fallback during command execution
        }
    }
}
