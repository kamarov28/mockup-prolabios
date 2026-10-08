<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    private string $credentialsPath;
    private ?string $propertyId;

    public function __construct()
    {
        $this->credentialsPath = (string) config(
            'services.google_analytics.credentials_path',
            storage_path('app/analytics/service-account.json')
        );
        $this->propertyId = config('services.google_analytics.property_id')
            ?: (env('GA4_PROPERTY_ID') ? (string) env('GA4_PROPERTY_ID') : null);
    }

    public function isServiceAccountReady(): bool
    {
        return file_exists($this->credentialsPath) && is_readable($this->credentialsPath);
    }

    public function getCredentialsPath(): string
    {
        return $this->credentialsPath;
    }

    public function getServiceAccountEmail(): ?string
    {
        if (! $this->isServiceAccountReady()) {
            return null;
        }

        $raw = @file_get_contents($this->credentialsPath);
        if (! $raw) {
            return null;
        }

        $data = json_decode($raw, true);

        return is_array($data) ? ($data['client_email'] ?? null) : null;
    }

    public function saveCredentialsFile(UploadedFile $file): array
    {
        if (! $file->isValid()) {
            return ['success' => false, 'message' => 'File tidak valid atau rusak saat diunggah.'];
        }

        $raw = file_get_contents($file->getRealPath());
        if (! $raw) {
            return ['success' => false, 'message' => 'Gagal membaca isi file yang diunggah.'];
        }

        $data = json_decode($raw, true);
        if (! is_array($data) || empty($data['client_email']) || empty($data['private_key'])) {
            return [
                'success' => false,
                'message' => 'Format file JSON tidak valid. Pastikan file ini adalah kunci kredensial Service Account Google Cloud asli yang berisi client_email dan private_key.',
            ];
        }

        $dir = dirname($this->credentialsPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0750, true);
        }

        if (file_put_contents($this->credentialsPath, $raw) === false) {
            return ['success' => false, 'message' => 'Gagal menyimpan file ke direktori storage/app/analytics. Periksa permission server.'];
        }

        @chmod($this->credentialsPath, 0600);

        // Reset token cache so fresh credentials take effect immediately
        Cache::forget('ga4_service_access_token');

        return [
            'success' => true,
            'client_email' => $data['client_email'],
            'project_id' => $data['project_id'] ?? null,
        ];
    }

    public function getPropertyId(): ?string
    {
        if (!empty($this->propertyId)) {
            return $this->propertyId;
        }

        try {
            $homepageData = app(HomepageService::class)->getHomepageData();
            if (!empty($homepageData['ga4_property_id'])) {
                return (string) $homepageData['ga4_property_id'];
            }
        } catch (\Throwable) {
            // Ignored during early bootstrap or test mocks
        }

        return env('GA4_PROPERTY_ID') ? (string) env('GA4_PROPERTY_ID') : null;
    }

    public function isConfigured(): bool
    {
        return $this->isServiceAccountReady() && !empty($this->propertyId);
    }

    /**
     * Get OAuth2 Access Token using Google Service Account JWT (RS256).
     * Cached for 50 minutes to avoid redundant token exchanges.
     */
    public function getAccessToken(): ?string
    {
        if (!$this->isServiceAccountReady()) {
            return null;
        }

        return Cache::remember('ga4_service_access_token', 3000, function () {
            $raw = file_get_contents($this->credentialsPath);
            if (!$raw) {
                return null;
            }

            $creds = json_decode($raw, true);
            if (!is_array($creds) || empty($creds['client_email']) || empty($creds['private_key'])) {
                return null;
            }

            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $payload = [
                'iss' => $creds['client_email'],
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
            $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
            $dataToSign = $base64UrlHeader . '.' . $base64UrlPayload;

            $privateKey = openssl_pkey_get_private($creds['private_key']);
            if (!$privateKey) {
                Log::warning('GA4 Service Account: Private key invalid.');
                return null;
            }

            $signature = '';
            if (!openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                Log::warning('GA4 Service Account: Failed to sign JWT assertion.');
                return null;
            }

            $jwt = $dataToSign . '.' . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false || $httpCode !== 200) {
                Log::warning('GA4 Service Account: Token exchange failed (' . $httpCode . '): ' . $response);
                return null;
            }

            $tokenData = json_decode($response, true);
            return $tokenData['access_token'] ?? null;
        });
    }

    /**
     * Run a Google Analytics Data API v1beta report.
     *
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     */
    public function runReport(array $body): array
    {
        $propertyId = $this->getPropertyId();
        if (!$propertyId) {
            return ['status' => 'missing_property_id', 'rows' => []];
        }

        $token = $this->getAccessToken();
        if (!$token) {
            return ['status' => 'auth_failed', 'rows' => []];
        }

        $cleanPropId = preg_replace('/[^0-9]/', '', $propertyId);
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$cleanPropId}:runReport";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false || $httpCode !== 200) {
            Log::warning("GA4 Report query failed ({$httpCode}): " . $response);
            $err = json_decode((string) $response, true);
            return [
                'status' => 'error',
                'http_code' => $httpCode,
                'message' => $err['error']['message'] ?? 'Gagal mengambil data dari Google Analytics API',
                'rows' => [],
            ];
        }

        $data = json_decode($response, true);
        return [
            'status' => 'success',
            'data' => $data,
            'rows' => $data['rows'] ?? [],
            'rowCount' => $data['rowCount'] ?? 0,
        ];
    }

    /**
     * Get aggregated overview dashboard data (cached for 20 minutes).
     *
     * @return array<string, mixed>
     */
    public function getDashboardSummary(bool $forceRefresh = false, int $days = 30): array
    {
        if (! $this->isServiceAccountReady()) {
            return [
                'status' => 'unconfigured',
                'message' => 'File kredensial Service Account belum terpasang di storage/app/analytics/service-account.json',
                'regions' => [],
                'top_pages' => [],
                'total_users' => 0,
                'total_views' => 0,
            ];
        }

        if (! $this->getPropertyId()) {
            return [
                'status' => 'needs_property_id',
                'message' => 'GA4 Property ID belum dikonfigurasi. Masukkan GA4 Property ID di Pengaturan Web atau .env.',
                'regions' => [],
                'top_pages' => [],
                'total_users' => 0,
                'total_views' => 0,
            ];
        }

        $days = in_array($days, [7, 30, 90], true) ? $days : 30;
        $cacheKey = 'ga4_admin_summary_' . $this->getPropertyId() . '_' . $days;
        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 1200, function () use ($days) {
            // 1. Fetch Top Regions
            $regionsReport = $this->runReport([
                'dateRanges' => [['startDate' => "{$days}daysAgo", 'endDate' => 'today']],
                'dimensions' => [['name' => 'region']],
                'metrics' => [
                    ['name' => 'activeUsers'],
                    ['name' => 'screenPageViews'],
                ],
                'orderBys' => [
                    ['metric' => ['metricName' => 'activeUsers'], 'desc' => true],
                ],
                'limit' => 12,
            ]);

            // 2. Fetch Top Pages / Products
            $pagesReport = $this->runReport([
                'dateRanges' => [['startDate' => "{$days}daysAgo", 'endDate' => 'today']],
                'dimensions' => [['name' => 'pagePath']],
                'metrics' => [
                    ['name' => 'screenPageViews'],
                    ['name' => 'activeUsers'],
                ],
                'orderBys' => [
                    ['metric' => ['metricName' => 'screenPageViews'], 'desc' => true],
                ],
                'limit' => 15,
            ]);

            if (($regionsReport['status'] ?? '') !== 'success') {
                return [
                    'status' => 'error',
                    'message' => $regionsReport['message'] ?? 'Gagal mengambil data dari Google Analytics Data API (Status: ' . ($regionsReport['status'] ?? 'unknown') . ')',
                    'regions' => [],
                    'top_pages' => [],
                    'total_users' => 0,
                    'total_views' => 0,
                ];
            }

            // Process Regions
            $regions = [];
            $maxUsers = 1;
            $totalUsers = 0;
            foreach ($regionsReport['rows'] ?? [] as $row) {
                $regionName = trim((string) ($row['dimensionValues'][0]['value'] ?? ''));
                if ($regionName === '' || $regionName === '(not set)') {
                    $regionName = 'Lainnya / Tidak Terdeteksi';
                }
                $users = (int) ($row['metricValues'][0]['value'] ?? 0);
                $views = (int) ($row['metricValues'][1]['value'] ?? 0);
                $totalUsers += $users;
                if ($users > $maxUsers) {
                    $maxUsers = $users;
                }
                $regions[] = [
                    'name' => $regionName,
                    'users' => $users,
                    'views' => $views,
                ];
            }

            // Process Pages
            $topPages = [];
            $totalViews = 0;
            foreach ($pagesReport['rows'] ?? [] as $row) {
                $path = $row['dimensionValues'][0]['value'] ?? '/';
                $views = (int) ($row['metricValues'][0]['value'] ?? 0);
                $users = (int) ($row['metricValues'][1]['value'] ?? 0);
                $totalViews += $views;

                // Format friendly title
                $label = $path;
                if ($path === '/') {
                    $label = 'Beranda (Homepage)';
                } elseif (str_starts_with($path, '/produk/')) {
                    $slug = substr($path, 8);
                    $label = 'Produk: ' . ucwords(str_replace('-', ' ', $slug));
                } elseif ($path === '/produk') {
                    $label = 'Katalog Semua Produk';
                } elseif (str_starts_with($path, '/artikel/')) {
                    $slug = substr($path, 9);
                    $label = 'Artikel: ' . ucwords(str_replace('-', ' ', $slug));
                }

                $topPages[] = [
                    'path' => $path,
                    'label' => $label,
                    'views' => $views,
                    'users' => $users,
                ];
            }

            return [
                'status' => 'success',
                'message' => 'OK',
                'regions' => $regions,
                'top_pages' => $topPages,
                'total_users' => $totalUsers,
                'total_views' => $totalViews,
                'max_users' => $maxUsers,
                'updated_at' => now()->format('H:i:s'),
            ];
        });
    }
}
