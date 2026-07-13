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
            $dataService = app(\App\Services\DataService::class);
            $siteSettings = $dataService->getHomepageData();
            
            // Clean phone number for WhatsApp API
            $rawPhone = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone'] ?? '0821-8792-9433');
            $waNumber = (strpos($rawPhone, '0') === 0) ? '62' . substr($rawPhone, 1) : $rawPhone;

            // Clean technician phone number for WhatsApp API
            $rawPhoneTech = preg_replace('/[^0-9]/', '', $siteSettings['contact_phone_technician'] ?? '0812-837-4867');
            $waNumberTech = (strpos($rawPhoneTech, '0') === 0) ? '62' . substr($rawPhoneTech, 1) : $rawPhoneTech;

            \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
            \Illuminate\Support\Facades\View::share('waNumber', $waNumber);
            \Illuminate\Support\Facades\View::share('waNumberTech', $waNumberTech);
        } catch (\Exception $e) {
            // Safe fallback during command execution
        }

        // Cache busting helper for assets
        \Illuminate\Support\Facades\Blade::directive('assetVersion', function ($expression) {
            return "<?php echo asset($expression) . '?v=' . filemtime(public_path(substr($expression, 1, -1))); ?>";
        });
    }
}
