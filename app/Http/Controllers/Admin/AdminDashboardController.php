<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\HtmlSanitizer;
use App\Models\Rfq;
use App\Services\AuditLogger;
use App\Services\DataService;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    use HandlesImageUploads;

    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function dashboard()
    {
        // Use COUNT/LIMIT queries instead of loading entire tables to memory
        $productsCount = DB::table('products')->count();
        $postsCount = DB::table('posts')->count();
        $sectorsCount = DB::table('sectors')->count();
        $rfqsCount = DB::table('rfqs')->whereNull('deleted_at')->count();

        $recentProducts = DB::table('products')->latest()->limit(5)->get()->map(fn ($r) => (array) $r)->toArray();
        $recentPosts = DB::table('posts')->latest()->limit(5)->get()->map(fn ($r) => (array) $r)->toArray();
        $recentRfqs = Rfq::with('items')->latest()->limit(5)->get();

        // Category distribution via GROUP BY (single query, no PHP counting)
        $categoryRows = DB::table('products')
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

        $homeData = $this->dataService->getHomepageData();

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount', 'rfqsCount',
            'recentProducts', 'recentPosts', 'recentRfqs', 'categoryDist', 'homeData'
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
        $allowedSections = ['homepage', 'banners', 'contacts', 'general'];
        if (! in_array($section, $allowedSections, true)) {
            return redirect()->back()->with('error', 'Section tidak valid.');
        }

        $textRule = 'nullable|string|max:5000';
        $shortTextRule = 'nullable|string|max:500';
        $linkRule = ['nullable', 'string', 'max:500', 'regex:/^(https?:\/\/|\/).+/i'];

        if ($section === 'homepage') {
            $request->validate([
                'hero_badge' => $shortTextRule,
                'hero_title' => $shortTextRule,
                'hero_subtitle' => $textRule,
                'hero_cta_text' => $shortTextRule,
                'hero_cta_link' => $linkRule,
                'bento_title' => $shortTextRule,
                'bento_subtitle' => $shortTextRule,
                'sector_title' => $shortTextRule,
                'sector_subtitle' => $shortTextRule,
                'cta_banner_badge' => $shortTextRule,
                'cta_banner_title' => $shortTextRule,
                'cta_banner_sub' => $textRule,
                'cta_banner_btn_text' => $shortTextRule,
                'cta_banner_btn_url' => $linkRule,
                'sector_link_pharma' => $linkRule,
                'sector_link_fnb' => $linkRule,
                'sector_link_healthcare' => $linkRule,
                'sector_link_brewing' => $linkRule,
            ]);
        }

        if ($section === 'banners') {
            $pages = ['products', 'sectors', 'services', 'info', 'contact'];
            $bannerRules = [];
            foreach ($pages as $p) {
                $bannerRules["{$p}_title"] = $shortTextRule;
                $bannerRules["{$p}_subtitle"] = $textRule;
                $bannerRules["{$p}_banner_url"] = 'nullable|string|max:2000';
            }
            $request->validate($bannerRules);
        }

        if ($section === 'contacts') {
            $request->validate([
                'contact_phone' => 'nullable|string|max:50',
                'contact_phone_marketing' => 'nullable|string|max:50',
                'contact_phone_finance' => 'nullable|string|max:50',
                'contact_phone_technician' => 'nullable|string|max:50',
                'whatsapp_default_message' => 'nullable|string|max:1000',
                'contact_email' => 'nullable|email|max:255',
                'contact_address' => 'nullable|string|max:1000',
                'catalog_pdf_url' => 'nullable|string|max:2000',
                'google_maps_embed_url' => 'nullable|string|max:3000',
            ]);
        }

        if ($section === 'general') {
            $request->validate([
                'company_name' => 'nullable|string|max:255',
                'operational_hours' => 'nullable|string|max:255',
                'meta_default_description' => 'nullable|string|max:1000',
                'meta_default_keywords' => 'nullable|string|max:1000',
                'google_search_console_id' => 'nullable|string|max:255',
                'social_instagram' => 'nullable|string|max:500',
                'social_facebook' => 'nullable|string|max:500',
                'social_linkedin' => 'nullable|string|max:500',
                'social_twitter' => 'nullable|string|max:500',
            ]);
        }

        // Fresh DB read (no cache) so we don't merge on top of stale values
        $homeData = $this->dataService->getHomepageDataFresh();
        $patch = [];

        if ($section === 'homepage') {
            $patch['hero_badge'] = $request->input('hero_badge', $homeData['hero_badge'] ?? '');
            $patch['hero_title'] = HtmlSanitizer::clean($request->input('hero_title', $homeData['hero_title'] ?? ''));
            $patch['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle'] ?? '');
            $patch['hero_cta_text'] = $request->input('hero_cta_text', $homeData['hero_cta_text'] ?? '');
            $patch['hero_cta_link'] = $request->input('hero_cta_link', $homeData['hero_cta_link'] ?? '');

            $heroImages = $homeData['hero_images'] ?? [];
            for ($i = 0; $i < 4; $i++) {
                $existing = $heroImages[$i] ?? '';
                $heroImages[$i] = $this->handleImageUpload(
                    $request, "hero_image_file_$i", "hero_image_url_$i", $existing
                );
            }
            $patch['hero_images'] = $heroImages;

            $patch['bento_title'] = $request->input('bento_title', $homeData['bento_title'] ?? '');
            $patch['bento_subtitle'] = $request->input('bento_subtitle', $homeData['bento_subtitle'] ?? '');
            $bentoCards = $homeData['bento_cards'] ?? [];
            for ($i = 0; $i < 4; $i++) {
                $existingBento = $bentoCards[$i] ?? [];
                $bentoCards[$i] = [
                    'icon' => $request->input("bento_card_icon_$i", $existingBento['icon'] ?? 'bi-patch-check'),
                    'title' => $request->input("bento_card_title_$i", $existingBento['title'] ?? ''),
                    'desc' => $request->input("bento_card_desc_$i", $existingBento['desc'] ?? ''),
                ];
            }
            $patch['bento_cards'] = $bentoCards;

            $patch['sector_title'] = $request->input('sector_title', $homeData['sector_title'] ?? '');
            $patch['sector_subtitle'] = $request->input('sector_subtitle', $homeData['sector_subtitle'] ?? '');
            $sectorKeys = ['pharma', 'fnb', 'healthcare', 'brewing'];
            $sectorPanels = $homeData['sector_panels'] ?? [];
            foreach ($sectorKeys as $sKey) {
                $existingSector = $sectorPanels[$sKey] ?? [];
                $sectorPanels[$sKey] = [
                    'tag' => $request->input("sector_tag_$sKey", $existingSector['tag'] ?? ''),
                    'title' => $request->input("sector_title_$sKey", $existingSector['title'] ?? ''),
                    'desc' => $request->input("sector_desc_$sKey", $existingSector['desc'] ?? ''),
                    'link' => $request->input("sector_link_$sKey", $existingSector['link'] ?? ''),
                ];
            }
            $patch['sector_panels'] = $sectorPanels;

            $patch['cta_banner_badge'] = $request->input('cta_banner_badge', $homeData['cta_banner_badge'] ?? '');
            $patch['cta_banner_title'] = $request->input('cta_banner_title', $homeData['cta_banner_title'] ?? '');
            $patch['cta_banner_sub'] = $request->input('cta_banner_sub', $homeData['cta_banner_sub'] ?? '');
            $patch['cta_banner_btn_text'] = $request->input('cta_banner_btn_text', $homeData['cta_banner_btn_text'] ?? '');
            $patch['cta_banner_btn_url'] = $request->input('cta_banner_btn_url', $homeData['cta_banner_btn_url'] ?? '');
        }

        if ($section === 'banners') {
            $pages = ['products', 'sectors', 'services', 'info', 'contact'];
            foreach ($pages as $p) {
                $patch["{$p}_title"] = $request->input("{$p}_title", $homeData["{$p}_title"] ?? '');
                $patch["{$p}_subtitle"] = $request->input("{$p}_subtitle", $homeData["{$p}_subtitle"] ?? '');
                $existingImg = $homeData["{$p}_banner_image"] ?? '';
                $patch["{$p}_banner_image"] = $this->handleImageUpload(
                    $request, "{$p}_banner_file", "{$p}_banner_url", $existingImg
                );
            }
        }

        if ($section === 'contacts') {
            $patch['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
            $patch['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
            $patch['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
            $patch['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
            $patch['whatsapp_default_message'] = $request->input('whatsapp_default_message', $homeData['whatsapp_default_message'] ?? '');
            $patch['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
            $patch['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
            $patch['catalog_pdf_url'] = $request->input('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '');

            $maps = (string) $request->input('google_maps_embed_url', $homeData['google_maps_embed_url'] ?? '');
            if ($maps !== '' && ! preg_match('/^https:\/\/(www\.)?(google\.[a-z.]+\/maps|maps\.google\.)/i', $maps)) {
                return redirect()->back()->withInput()->with('error', 'URL Google Maps embed tidak valid.');
            }
            $patch['google_maps_embed_url'] = $maps;
        }

        if ($section === 'general') {
            $patch['company_name'] = $request->input('company_name', $homeData['company_name'] ?? '');
            $patch['operational_hours'] = $request->input('operational_hours', $homeData['operational_hours'] ?? '');
            $patch['meta_default_description'] = $request->input('meta_default_description', $homeData['meta_default_description'] ?? '');
            $patch['meta_default_keywords'] = $request->input('meta_default_keywords', $homeData['meta_default_keywords'] ?? '');
            $patch['google_search_console_id'] = $request->input('google_search_console_id', $homeData['google_search_console_id'] ?? '');
            $patch['social_instagram'] = $request->input('social_instagram', $homeData['social_instagram'] ?? '');
            $patch['social_facebook'] = $request->input('social_facebook', $homeData['social_facebook'] ?? '');
            $patch['social_linkedin'] = $request->input('social_linkedin', $homeData['social_linkedin'] ?? '');
            $patch['social_twitter'] = $request->input('social_twitter', $homeData['social_twitter'] ?? '');

            $existingLogo = $homeData['site_logo'] ?? '';
            $patch['site_logo'] = $this->handleImageUpload(
                $request, 'site_logo_file', 'site_logo_url', $existingLogo
            );

            $existingFavicon = $homeData['site_favicon'] ?? '';
            $patch['site_favicon'] = $this->handleImageUpload(
                $request, 'site_favicon_file', 'site_favicon_url', $existingFavicon
            );
        }

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
