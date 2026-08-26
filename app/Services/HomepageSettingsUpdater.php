<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;

/**
 * Builds validated patches for admin "Pengaturan Web" sections.
 * Behavior mirrors the former AdminDashboardController::homeUpdate body.
 */
class HomepageSettingsUpdater
{
    use HandlesImageUploads;

    private const TEXT_RULE = 'nullable|string|max:5000';

    private const SHORT_TEXT_RULE = 'nullable|string|max:500';

    /** @var list<string> */
    private const LINK_RULE = ['nullable', 'string', 'max:500', 'regex:/^(https?:\/\/|\/).+/i'];

    /** @var list<string> */
    public const ALLOWED_SECTIONS = ['homepage', 'contacts', 'general'];

    public function validate(Request $request, string $section): void
    {
        match ($section) {
            'homepage' => $this->validateHomepage($request),
            'contacts' => $this->validateContacts($request),
            'general' => $this->validateGeneral($request),
            default => null,
        };
    }

    /**
     * @param  array<string, mixed>  $homeData
     * @return array{patch: array<string, mixed>, error: string|null}
     */
    public function buildPatch(Request $request, string $section, array $homeData): array
    {
        return match ($section) {
            'homepage' => ['patch' => $this->patchHomepage($request, $homeData), 'error' => null],
            'contacts' => $this->patchContacts($request, $homeData),
            'general' => ['patch' => $this->patchGeneral($request, $homeData), 'error' => null],
            default => ['patch' => [], 'error' => 'Section tidak valid.'],
        };
    }

    private function validateHomepage(Request $request): void
    {
        $request->validate([
            'hero_badge' => self::SHORT_TEXT_RULE,
            'hero_title' => self::SHORT_TEXT_RULE,
            'hero_subtitle' => self::TEXT_RULE,
            'hero_cta_text' => self::SHORT_TEXT_RULE,
            'hero_cta_link' => self::LINK_RULE,
            'bento_title' => self::SHORT_TEXT_RULE,
            'bento_subtitle' => self::SHORT_TEXT_RULE,
            'sector_title' => self::SHORT_TEXT_RULE,
            'sector_subtitle' => self::SHORT_TEXT_RULE,
            'cta_banner_badge' => self::SHORT_TEXT_RULE,
            'cta_banner_title' => self::SHORT_TEXT_RULE,
            'cta_banner_sub' => self::TEXT_RULE,
            'cta_banner_btn_text' => self::SHORT_TEXT_RULE,
            'cta_banner_btn_url' => self::LINK_RULE,
            'sector_link_pharma' => self::LINK_RULE,
            'sector_link_fnb' => self::LINK_RULE,
            'sector_link_healthcare' => self::LINK_RULE,
            'sector_link_brewing' => self::LINK_RULE,
        ]);
    }

    private function validateContacts(Request $request): void
    {
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

    private function validateGeneral(Request $request): void
    {
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

    /**
     * @param  array<string, mixed>  $homeData
     * @return array<string, mixed>
     */
    private function patchHomepage(Request $request, array $homeData): array
    {
        $patch = [];
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

        return $patch;
    }

    /**
     * @param  array<string, mixed>  $homeData
     * @return array{patch: array<string, mixed>, error: string|null}
     */
    private function patchContacts(Request $request, array $homeData): array
    {
        $patch = [];
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
            return ['patch' => [], 'error' => 'URL Google Maps embed tidak valid.'];
        }
        $patch['google_maps_embed_url'] = $maps;

        return ['patch' => $patch, 'error' => null];
    }

    /**
     * @param  array<string, mixed>  $homeData
     * @return array<string, mixed>
     */
    private function patchGeneral(Request $request, array $homeData): array
    {
        $patch = [];
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

        return $patch;
    }
}
