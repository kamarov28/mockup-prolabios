<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminDashboardController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function dashboard()
    {
        $products = $this->dataService->getProducts();
        $productsCount = count($products);
        $allPosts = $this->dataService->getPosts();
        $postsCount = count($allPosts);
        $sectorsCount = count($this->dataService->getSectors());

        $recentProducts = array_slice($products, 0, 5);
        $recentPosts = array_slice($allPosts, 0, 5);

        $categoryDist = [];
        foreach ($products as $p) {
            $catRaw = $p['category'] ?? '';
            if (!empty($catRaw)) {
                $catName = ucwords(str_replace('-', ' ', $catRaw));
                if (!isset($categoryDist[$catName])) {
                    $categoryDist[$catName] = 0;
                }
                $categoryDist[$catName]++;
            }
        }

        if (empty($categoryDist)) {
            $categoryDist = ['Belum Ada Produk' => 0];
        }

        $homeData = $this->dataService->getHomepageData();

        return view('admin.dashboard', compact(
            'productsCount', 'postsCount', 'sectorsCount',
            'recentProducts', 'recentPosts', 'categoryDist', 'homeData'
        ));
    }

    public function homeEdit()
    {
        $homeData = $this->dataService->getHomepageData();
        return view('admin.home-editor', compact('homeData'));
    }

    public function homeUpdate(Request $request)
    {
        $homeData = $this->dataService->getHomepageData();
        $section = $request->input('section', 'homepage');

        if ($section === 'homepage') {
            // Hero
            $homeData['hero_badge'] = $request->input('hero_badge', $homeData['hero_badge'] ?? '');
            $homeData['hero_title'] = $request->input('hero_title', $homeData['hero_title'] ?? '');
            $homeData['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle'] ?? '');
            $homeData['hero_cta_text'] = $request->input('hero_cta_text', $homeData['hero_cta_text'] ?? '');
            $homeData['hero_cta_link'] = $request->input('hero_cta_link', $homeData['hero_cta_link'] ?? '');

            for ($i = 0; $i < 4; $i++) {
                $existing = $homeData['hero_images'][$i] ?? '';
                $homeData['hero_images'][$i] = $this->handleImageUpload(
                    $request, "hero_image_file_$i", "hero_image_url_$i", $existing
                );
            }

            // Bento Grid
            $homeData['bento_title'] = $request->input('bento_title', $homeData['bento_title'] ?? '');
            $homeData['bento_subtitle'] = $request->input('bento_subtitle', $homeData['bento_subtitle'] ?? '');
            for ($i = 0; $i < 4; $i++) {
                $existingBento = $homeData['bento_cards'][$i] ?? [];
                $homeData['bento_cards'][$i] = [
                    'icon'  => $request->input("bento_card_icon_$i", $existingBento['icon'] ?? 'bi-patch-check'),
                    'title' => $request->input("bento_card_title_$i", $existingBento['title'] ?? ''),
                    'desc'  => $request->input("bento_card_desc_$i", $existingBento['desc'] ?? ''),
                ];
            }

            // Interactive Sector Finder
            $homeData['sector_title'] = $request->input('sector_title', $homeData['sector_title'] ?? '');
            $homeData['sector_subtitle'] = $request->input('sector_subtitle', $homeData['sector_subtitle'] ?? '');
            $sectorKeys = ['pharma', 'fnb', 'healthcare', 'brewing'];
            foreach ($sectorKeys as $sKey) {
                $existingSector = $homeData['sector_panels'][$sKey] ?? [];
                $homeData['sector_panels'][$sKey] = [
                    'tag'   => $request->input("sector_tag_$sKey", $existingSector['tag'] ?? ''),
                    'title' => $request->input("sector_title_$sKey", $existingSector['title'] ?? ''),
                    'desc'  => $request->input("sector_desc_$sKey", $existingSector['desc'] ?? ''),
                    'link'  => $request->input("sector_link_$sKey", $existingSector['link'] ?? ''),
                ];
            }

            // Bottom CTA Banner
            $homeData['cta_banner_badge']    = $request->input('cta_banner_badge', $homeData['cta_banner_badge'] ?? '');
            $homeData['cta_banner_title']    = $request->input('cta_banner_title', $homeData['cta_banner_title'] ?? '');
            $homeData['cta_banner_sub']      = $request->input('cta_banner_sub', $homeData['cta_banner_sub'] ?? '');
            $homeData['cta_banner_btn_text'] = $request->input('cta_banner_btn_text', $homeData['cta_banner_btn_text'] ?? '');
            $homeData['cta_banner_btn_url']  = $request->input('cta_banner_btn_url', $homeData['cta_banner_btn_url'] ?? '');
        }

        if ($section === 'banners') {
            $pages = ['products', 'sectors', 'services', 'info', 'contact'];
            foreach ($pages as $p) {
                $homeData["{$p}_title"] = $request->input("{$p}_title", $homeData["{$p}_title"] ?? '');
                $homeData["{$p}_subtitle"] = $request->input("{$p}_subtitle", $homeData["{$p}_subtitle"] ?? '');
                $existingImg = $homeData["{$p}_banner_image"] ?? '';
                $homeData["{$p}_banner_image"] = $this->handleImageUpload(
                    $request, "{$p}_banner_file", "{$p}_banner_url", $existingImg
                );
            }
        }

        if ($section === 'contacts') {
            $homeData['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
            $homeData['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
            $homeData['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
            $homeData['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
            $homeData['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
            $homeData['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
            $homeData['catalog_pdf_url'] = $request->input('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '');
        }

        if ($section === 'general') {
            $homeData['company_name'] = $request->input('company_name', $homeData['company_name'] ?? '');
            $homeData['operational_hours'] = $request->input('operational_hours', $homeData['operational_hours'] ?? '');
            $homeData['social_instagram'] = $request->input('social_instagram', $homeData['social_instagram'] ?? '');
            $homeData['social_facebook'] = $request->input('social_facebook', $homeData['social_facebook'] ?? '');
            $homeData['social_linkedin'] = $request->input('social_linkedin', $homeData['social_linkedin'] ?? '');
            $homeData['social_twitter'] = $request->input('social_twitter', $homeData['social_twitter'] ?? '');

            $existingLogo = $homeData['site_logo'] ?? '';
            $homeData['site_logo'] = $this->handleImageUpload(
                $request, 'site_logo_file', 'site_logo_url', $existingLogo
            );
        }

        $this->dataService->saveHomepageData($homeData);

        return redirect()->route('admin.home.edit', ['section' => $section])->with('success', 'Pengaturan berhasil disimpan!');
    }

    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
            $file = $request->file($fileKey);

            $ext = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowedExtensions, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp.']
                ]);
            }

            $mime = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowedMimes, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                ]);
            }

            $fileName = time() . '_' . Str::random(8) . '.' . $ext;

            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }

        return $request->input($urlKey, $fallback);
    }
}
