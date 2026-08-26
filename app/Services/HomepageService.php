<?php

namespace App\Services;

use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomepageService
{
    /**
     * Clear all website settings and homepage cache entries.
     */
    public static function clearSettingsCache(): void
    {
        Cache::forget('homepage_data_v1');
        Cache::forget('homepage_settings_v3');
        Cache::forget('site_settings_global');
    }

    /**
     * Cached homepage settings (public reads).
     */
    public function getHomepageData(): array
    {
        return Cache::remember('homepage_data_v1', 3600, function () {
            return $this->loadHomepageDataFromDatabase();
        });
    }

    /**
     * Fresh read bypassing cache — use before admin updates to avoid stale overwrites.
     */
    public function getHomepageDataFresh(): array
    {
        return $this->loadHomepageDataFromDatabase();
    }

    private function loadHomepageDataFromDatabase(): array
    {
        $default = $this->getDefaultHomepageData();

        try {
            $rows = HomepageSetting::query()->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            // Table not yet created (first boot before migrate) – fall back to defaults
            return $default;
        }

        if (empty($rows)) {
            return $default;
        }

        $decoded = [];
        foreach ($rows as $key => $val) {
            $decoded[$key] = $this->decodeSettingValue($val);
        }

        return array_merge($default, $decoded);
    }

    /**
     * Upsert only the provided keys (partial update). Wrapped in a transaction.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveHomepageData(array $data): bool
    {
        if ($data === []) {
            return true;
        }

        DB::transaction(function () use ($data) {
            $now = now();
            foreach ($data as $key => $val) {
                if (! is_string($key) || $key === '') {
                    continue;
                }

                $encoded = is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;

                HomepageSetting::query()->upsert(
                    [
                        'key'        => $key,
                        'value'      => $encoded,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ],
                    ['key'],
                    ['value', 'updated_at']
                );
            }
        });

        self::clearSettingsCache();

        return true;
    }

    private function decodeSettingValue(mixed $val): mixed
    {
        if (! is_string($val)) {
            return $val;
        }
        $decoded = json_decode($val, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $val;
    }

    public function getDefaultHomepageData(): array
    {
        return [
            // 1. Hero Section
            'hero_badge'    => 'PRECISION LABORATORY SOLUTIONS',
            'hero_title'    => 'Uncompromised <span class="text-accent">Testing Accuracy</span> for Research & Industry.',
            'hero_subtitle' => 'Official provider of analytical instruments, culture media, and laboratory reagents meeting strict international quality standards.',
            'hero_cta_text' => 'Explore Product Catalog',
            'hero_cta_link' => '/produk',
            'hero_images'   => [
                'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            ],

            // 2. Bento Grid
            'bento_title'    => 'Infrastructure & Reliability Standards',
            'bento_subtitle' => 'Engineered to fulfill strict regulatory compliance and ensure seamless laboratory testing continuity.',
            'bento_cards'    => [
                [
                    'icon'  => 'bi-patch-check',
                    'title' => 'ISO & AKL Certified Products',
                    'desc'  => 'Over 1,000+ officially accredited reagents and instruments, guaranteeing distribution legality for BPOM and ISO 17025 audit compliance.',
                ],
                [
                    'icon'  => 'bi-file-earmark-code',
                    'title' => 'Instant COA & MSDS Access',
                    'desc'  => 'Every batch of reagents and culture media comes with official Certificate of Analysis (COA) and MSDS ready for lab validation download.',
                ],
                [
                    'icon'  => 'bi-snow',
                    'title' => 'Safe Cold-Chain Logistics',
                    'desc'  => 'Tested cold-chain infrastructure ensuring temperature-sensitive reagents remain stable and active upon arrival at your laboratory.',
                ],
                [
                    'icon'  => 'bi-tools',
                    'title' => 'Integrated After-Sales & Calibration',
                    'desc'  => 'Comprehensive equipment qualification (IQ/OQ/PQ), routine calibration services, and technical training by application specialists.',
                ],
            ],

            // 3. Interactive Sector Finder
            'sector_title'    => 'Interactive Sector Finder',
            'sector_subtitle' => 'Select your industry sector to explore tailored testing workflows and relevant products.',
            'sector_panels'   => [
                'pharma' => [
                    'tag'   => 'PHARMACEUTICAL & COSMETICS',
                    'title' => 'Endotoxin Testing & Sterilization Validation',
                    'desc'  => 'LAL Endotoxin Test Kits (Bioendo), sterility media, and environmental monitoring tools for GMP compliance.',
                    'link'  => '/sektor',
                ],
                'fnb' => [
                    'tag'   => 'FOOD & BEVERAGE',
                    'title' => 'Microbiology & Hygiene Monitoring',
                    'desc'  => 'Rapid pathogen detection (Salmonella, Listeria, E. coli) and ATP hygiene indicators ensuring food safety compliance for HACCP & BPOM.',
                    'link'  => '/sektor',
                ],
                'healthcare' => [
                    'tag'   => 'HOSPITAL & CLINICAL',
                    'title' => 'Clinical Diagnostics Support',
                    'desc'  => 'Culture media, rapid tests, and lab consumables supporting hospital laboratories and clinical diagnostics workflows.',
                    'link'  => '/sektor',
                ],
                'brewing' => [
                    'tag'   => 'BREWING & FERMENTATION',
                    'title' => 'Yeast & Contamination Control',
                    'desc'  => 'Specialized media and detection kits for brewery quality control and fermentation process monitoring.',
                    'link'  => '/sektor',
                ],
            ],

            // 4. CTA Banner
            'cta_banner_badge'    => 'B2B PROCUREMENT',
            'cta_banner_title'    => 'Need a formal quotation for your laboratory?',
            'cta_banner_sub'      => 'Submit an RFQ with your product list — our sales team will follow up with pricing, bulk availability, and compliance documentation.',
            'cta_banner_btn_text' => 'Contact Sales / Request Quote',
            'cta_banner_btn_url'  => '/kontak',

            'focus_title'         => 'Interactive Sector Finder',
            'focus_cards'         => [],
            'about_title'         => 'Tentang Prolabios',
            'about_description'   => 'Prolabios Mitra Analitika (PMA) dibangun untuk menjadi distributor terkemuka produk mikrobiologi di Indonesia.',
            'hotline_label'       => 'Layanan Pelanggan 24/7',
            'hotline_number'      => '0821-8792-9433',
            'hotline_description' => 'Hubungi kami via telepon atau WhatsApp untuk konsultasi produk.',

            // Contact
            'contact_phone'            => '0821-8792-9433',
            'contact_phone_marketing'  => '021-3874-1447',
            'contact_phone_finance'    => '021-8792-9433',
            'contact_phone_technician' => '0812-837-4867',
            'whatsapp_default_message' => 'Halo Prolabios, saya ingin berkonsultasi mengenai produk dan penawaran alat laboratorium.',
            'contact_email'            => 'marketing@prolabios.com',
            'contact_address'          => 'GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914',
            'catalog_pdf_url'          => 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs',
            'google_maps_embed_url'    => 'https://maps.google.com/maps?q=PT.+Prolabios+Mitra+Analitika&t=&z=17&ie=UTF8&iwloc=&output=embed',

            // General / SEO / social
            'company_name'             => 'PT. Prolabios Mitra Analitika',
            'site_logo'                => '',
            'site_favicon'             => '',
            'meta_default_description' => 'PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium, media kultur mikrobiologi, dan instrumen ilmiah di Indonesia.',
            'meta_default_keywords'    => 'prolabios, alat laboratorium, mikrobiologi, instrumen lab, media kultur, bioendo, terragene',
            'google_search_console_id' => '',
            'operational_hours'        => 'Senin - Jumat: 08.00 - 17.00',
            'social_instagram'         => 'https://instagram.com/prolabios',
            'social_facebook'          => 'https://facebook.com/prolabios',
            'social_linkedin'          => 'https://linkedin.com/company/prolabios',
            'social_twitter'           => 'https://twitter.com/prolabios',

            // Page banners
            'products_title'        => 'Semua Produk',
            'products_subtitle'     => 'Menampilkan semua produk kami',
            'products_banner_image' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            'sectors_title'        => 'Sektor Industri',
            'sectors_subtitle'     => 'Kami melayani berbagai macam sektor industri di Indonesia',
            'sectors_banner_image' => 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            'services_title'        => 'Layanan Kami',
            'services_subtitle'     => 'Dukungan purnajual dan layanan konsultasi terpadu',
            'services_banner_image' => 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            'info_title'        => 'News & Activity',
            'info_subtitle'     => 'Ikuti berita terkini, tips laboratorium, dan artikel ilmiah',
            'info_banner_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            'contact_title'        => 'Hubungi Kami',
            'contact_subtitle'     => 'Ada pertanyaan atau butuh konsultasi? Tim kami siap melayani Anda.',
            'contact_banner_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        ];
    }
}
