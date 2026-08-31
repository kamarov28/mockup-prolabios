<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HealthController extends Controller
{
    /**
     * System health check and operational metrics endpoint.
     */
    public function check(): JsonResponse
    {
        $status = 'healthy';
        $httpCode = 200;
        $details = [];
        $isLocal = app()->environment('local');

        // 1. Database Connectivity Check
        try {
            DB::connection()->getPdo();
            $details['database'] = 'connected';
        } catch (\Throwable $e) {
            $status = 'unhealthy';
            $httpCode = 503;
            $details['database'] = $isLocal ? 'disconnected ('.$e->getMessage().')' : 'disconnected';
            \Log::error('Health check DB connection failed: '.$e->getMessage());
        }

        // 2. Queue Backlog and Failure Checks
        try {
            $hasJobsTable = Schema::hasTable('jobs');
            $hasFailedJobsTable = Schema::hasTable('failed_jobs');

            $pendingJobs = $hasJobsTable ? DB::table('jobs')->count() : 0;
            $failedJobs = $hasFailedJobsTable ? DB::table('failed_jobs')->count() : 0;

            $details['queue'] = [
                'status' => $pendingJobs > 200 ? 'backlogged' : 'normal',
            ];

            if ($pendingJobs > 500) {
                $status = 'degraded';
            }
        } catch (\Throwable $e) {
            $details['queue'] = [
                'status' => 'error',
            ];
            \Log::error('Health check Queue inspect failed: '.$e->getMessage());
        }

        // 3. Cache Driver Check
        try {
            Cache::put('_health_check_ping', 1, 10);
            $cacheOk = Cache::get('_health_check_ping') === 1;
            $details['cache'] = $cacheOk ? 'operational' : 'degraded';
        } catch (\Throwable $e) {
            $details['cache'] = 'error';
            \Log::error('Health check Cache ping failed: '.$e->getMessage());
        }

        // 4. Storage Write Check
        $storageWritable = is_writable(storage_path('framework/views'));
        $details['storage'] = [
            'views_writable' => $storageWritable,
        ];

        if (! $storageWritable) {
            $status = 'degraded';
        }

        $payload = [
            'status' => $status,
            'environment' => $isLocal || app()->runningUnitTests() ? config('app.env') : 'production',
            'timestamp' => now()->toIso8601String(),
            'checks' => $details,
        ];

        return response()->json($payload, $httpCode);
    }
}
