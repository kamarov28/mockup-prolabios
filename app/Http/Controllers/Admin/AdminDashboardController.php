<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\Sector;
use App\Services\AuditLogger;
use App\Services\DataService;
use App\Services\HomepageSettingsUpdater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected DataService $dataService,
        protected HomepageSettingsUpdater $homepageSettings
    ) {}

    public function dashboard()
    {
        // Use COUNT/LIMIT queries instead of loading entire tables to memory
        $productsCount = Product::count();
        $postsCount = Post::count();
        $sectorsCount = Sector::count();
        $rfqsCount = Rfq::count();

        $recentProducts = Product::latest()->limit(5)->get()->toArray();
        $recentPosts = Post::latest()->limit(5)->get()->toArray();
        $recentRfqs = Rfq::with('items')->latest()->limit(5)->get();

        // Category distribution via GROUP BY (single query, no PHP counting)
        $categoryRows = Product::query()
            ->select('category', DB::raw('COUNT(*) as total'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $categoryDist = [];
        foreach ($categoryRows as $row) {
            $catName = ucwords(str_replace('-', ' ', $row->category));
            $categoryDist[$catName] = $row->total;
        }

        if (empty($categoryDist)) {
            $categoryDist = ['Belum Ada Produk' => 0];
        }

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount', 'rfqsCount',
            'recentProducts', 'recentPosts', 'recentRfqs', 'categoryDist'
        ));
    }

    public function homeEdit()
    {
        $homeData = $this->dataService->getHomepageData();

        return view('admin.home-editor', compact('homeData'));
    }

    public function homeUpdate(Request $request)
    {
        $section = $request->input('section', 'homepage');
        $tab = $request->input('tab');

        if (! in_array($section, HomepageSettingsUpdater::ALLOWED_SECTIONS, true)) {
            return redirect()->back()->with('error', 'Section tidak valid.');
        }

        $this->homepageSettings->validate($request, $section);

        // Fresh DB read (no cache) so we don't merge on top of stale values
        $homeData = $this->dataService->getHomepageDataFresh();
        $result = $this->homepageSettings->buildPatch($request, $section, $homeData);

        if ($result['error']) {
            return redirect()->back()->withInput()->with('error', $result['error']);
        }

        $patch = $result['patch'];
        $this->dataService->saveHomepageData($patch);

        AuditLogger::log('settings.update', 'Settings', $section, [
            'section' => $section,
            'tab' => $tab,
            'keys' => array_keys($patch),
        ]);

        $redirectParams = ['section' => $section];
        if ($tab) {
            $redirectParams['tab'] = $tab;
        }

        return redirect()->route('admin.home.edit', $redirectParams)->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function guide()
    {
        return view('admin.guide');
    }
}
