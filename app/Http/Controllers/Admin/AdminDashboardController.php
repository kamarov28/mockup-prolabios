<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
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
        $productsCount = Product::query()->count('*');
        $postsCount = Post::query()->count('*');
        $sectorsCount = Sector::query()->count('*');
        $rfqsCount = Rfq::query()->count('*');
        $newRfqsCount = Rfq::query()->where('status', 'new')->count('*');
        $principalsCount = Principal::query()->count('*');
        $categoriesCount = ProductCategory::query()->count('*');

        // RFQ pipeline breakdown by status
        $rfqStatusRows = Rfq::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $rfqPipeline = [
            'new' => (int) ($rfqStatusRows['new'] ?? 0),
            'contacted' => (int) ($rfqStatusRows['contacted'] ?? 0),
            'quoted' => (int) ($rfqStatusRows['quoted'] ?? 0),
            'closed' => (int) ($rfqStatusRows['closed'] ?? 0),
        ];

        $recentProducts = Product::query()->latest('created_at')->limit(6)->get(['id', 'catalog', 'title', 'category', 'image'])->toArray();
        $recentPosts = Post::query()->latest('created_at')->limit(6)->get(['id', 'slug', 'title', 'category', 'image', 'status', 'date'])->toArray();
        $recentRfqs = Rfq::query()->with('items')->latest('created_at')->limit(6)->get();

        // Category distribution via GROUP BY (single query, no PHP counting)
        $categoryRows = Product::query()
            ->select(['category', DB::raw('COUNT(*) as total')])
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $categoryDist = [];
        foreach ($categoryRows as $row) {
            $catName = ucwords(str_replace('-', ' ', (string) $row->category));
            $categoryDist[$catName] = (int) ($row->getAttribute('total') ?? 0);
        }

        if (empty($categoryDist)) {
            $categoryDist = ['Belum Ada Produk' => 0];
        }

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount', 'rfqsCount', 'newRfqsCount',
            'principalsCount', 'categoriesCount', 'rfqPipeline',
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
