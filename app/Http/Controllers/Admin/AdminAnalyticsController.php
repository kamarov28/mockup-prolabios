<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Services\GoogleAnalyticsService;
use App\Services\HomepageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAnalyticsController extends Controller
{
    public function index(GoogleAnalyticsService $analytics): View
    {
        $status = [
            'service_ready' => $analytics->isServiceAccountReady(),
            'service_email' => $analytics->getServiceAccountEmail(),
            'property_id' => $analytics->getPropertyId(),
            'configured' => $analytics->isConfigured(),
        ];

        return view('admin.analytics.index', compact('status'));
    }

    public function data(Request $request, GoogleAnalyticsService $analytics): JsonResponse
    {
        $refresh = $request->boolean('refresh');
        $days = (int) $request->input('days', 30);
        $days = in_array($days, [7, 30, 90], true) ? $days : 30;

        $data = $analytics->getDashboardSummary($refresh, $days);

        return response()->json($data);
    }

    public function uploadCredentials(Request $request, GoogleAnalyticsService $analytics): RedirectResponse
    {
        $request->validate([
            'credentials_file' => 'required|file|max:512',
        ]);

        $res = $analytics->saveCredentialsFile($request->file('credentials_file'));

        if (! $res['success']) {
            return redirect()->route('admin.analytics.index')->with('error', $res['message']);
        }

        AuditLogger::log('analytics.credentials_upload', 'Analytics', null, [
            'client_email' => $res['client_email'],
        ]);

        return redirect()->route('admin.analytics.index')->with('success', 'File kredensial Google Service Account berhasil dipasang! Robot: ' . $res['client_email']);
    }

    public function updateProperty(Request $request, HomepageService $homepage): RedirectResponse
    {
        $validated = $request->validate([
            'ga4_property_id' => 'required|string|max:50',
        ]);

        $clean = preg_replace('/[^0-9]/', '', (string) $validated['ga4_property_id']);
        if (empty($clean)) {
            return redirect()->route('admin.analytics.index')->with('error', 'GA4 Property ID harus berupa angka numerik.');
        }

        $homeData = $homepage->getHomepageDataFresh();
        $homeData['ga4_property_id'] = $clean;
        $homepage->saveHomepageData(['ga4_property_id' => $clean]);

        AuditLogger::log('analytics.property_update', 'Analytics', null, [
            'property_id' => $clean,
        ]);

        return redirect()->route('admin.analytics.index')->with('success', 'GA4 Property ID berhasil disimpan: ' . $clean);
    }
}
