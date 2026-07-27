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
        $postsCount = count($this->dataService->getPosts());
        $sectorsCount = count($this->dataService->getSectors());

        $recentProducts = array_slice($products, 0, 5);
        $recentPosts = array_slice($this->dataService->getPosts(), 0, 5);

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

        $homeData['hero_title'] = $request->input('hero_title', $homeData['hero_title']);
        $homeData['hero_subtitle'] = $request->input('hero_subtitle', $homeData['hero_subtitle']);
        $homeData['focus_title'] = $request->input('focus_title', $homeData['focus_title']);
        $homeData['about_title'] = $request->input('about_title', $homeData['about_title']);
        $homeData['about_description'] = $request->input('about_description', $homeData['about_description']);
        $homeData['hotline_label'] = $request->input('hotline_label', $homeData['hotline_label']);
        $homeData['hotline_number'] = $request->input('hotline_number', $homeData['hotline_number']);
        $homeData['hotline_description'] = $request->input('hotline_description', $homeData['hotline_description']);

        for ($i = 0; $i < 4; $i++) {
            $existing = $homeData['hero_images'][$i] ?? '';
            $homeData['hero_images'][$i] = $this->handleImageUpload(
                $request, "hero_image_file_$i", "hero_image_url_$i", $existing
            );
        }

        for ($i = 0; $i < 3; $i++) {
            $existingCard = $homeData['focus_cards'][$i] ?? [];
            $existingImg = $existingCard['image'] ?? '';

            $homeData['focus_cards'][$i] = [
                'title' => $request->input("focus_card_title_$i", $existingCard['title'] ?? ''),
                'description' => $request->input("focus_card_desc_$i", $existingCard['description'] ?? ''),
                'image' => $this->handleImageUpload(
                    $request, "focus_card_file_$i", "focus_card_url_$i", $existingImg
                )
            ];
        }

        $homeData['contact_phone'] = $request->input('contact_phone', $homeData['contact_phone'] ?? '');
        $homeData['contact_phone_marketing'] = $request->input('contact_phone_marketing', $homeData['contact_phone_marketing'] ?? '');
        $homeData['contact_phone_finance'] = $request->input('contact_phone_finance', $homeData['contact_phone_finance'] ?? '');
        $homeData['contact_phone_technician'] = $request->input('contact_phone_technician', $homeData['contact_phone_technician'] ?? '');
        $homeData['contact_email'] = $request->input('contact_email', $homeData['contact_email'] ?? '');
        $homeData['contact_address'] = $request->input('contact_address', $homeData['contact_address'] ?? '');
        $homeData['catalog_pdf_url'] = $request->input('catalog_pdf_url', $homeData['catalog_pdf_url'] ?? '');

        $homeData['company_name'] = $request->input('company_name', $homeData['company_name'] ?? '');
        $homeData['operational_hours'] = $request->input('operational_hours', $homeData['operational_hours'] ?? '');
        $homeData['social_instagram'] = $request->input('social_instagram', $homeData['social_instagram'] ?? '');
        $homeData['social_facebook'] = $request->input('social_facebook', $homeData['social_facebook'] ?? '');
        $homeData['social_linkedin'] = $request->input('social_linkedin', $homeData['social_linkedin'] ?? '');

        $this->dataService->saveHomepageData($homeData);

        return redirect()->route('admin.home.edit')->with('success', 'Homepage content updated successfully!');
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
