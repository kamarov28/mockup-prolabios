<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Sector;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap of all public routes, products, and posts.
     */
    public function sitemap(): Response
    {
        $baseUrl = config('app.url', url('/'));

        // Core Static Pages
        $staticPages = [
            ['loc' => $baseUrl.'/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl.'/profil', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/produk', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $baseUrl.'/sektor', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => $baseUrl.'/layanan', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/informasi', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => $baseUrl.'/kontak', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $baseUrl.'/kebijakan-privasi', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => $baseUrl.'/syarat-ketentuan', 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];

        // Dynamic Products
        $products = Product::select('id', 'title', 'updated_at')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($product) use ($baseUrl) {
                return [
                    'loc'        => $baseUrl.'/produk/detail?id='.$product->id,
                    'lastmod'    => $product->updated_at ? $product->updated_at->toAtomString() : date('c'),
                    'priority'   => '0.8',
                    'changefreq' => 'weekly',
                ];
            })
            ->toArray();

        // Dynamic Sectors
        $sectors = Sector::select('id', 'updated_at')
            ->get()
            ->map(function ($sector) use ($baseUrl) {
                return [
                    'loc'        => $baseUrl.'/sektor?s='.$sector->id,
                    'lastmod'    => $sector->updated_at ? $sector->updated_at->toAtomString() : date('c'),
                    'priority'   => '0.7',
                    'changefreq' => 'weekly',
                ];
            })
            ->toArray();

        // Dynamic Articles / News Posts
        $posts = Post::select('slug', 'updated_at')
            ->where('status', 'online')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($post) use ($baseUrl) {
                return [
                    'loc'        => $baseUrl.'/informasi?detail='.urlencode($post->slug),
                    'lastmod'    => $post->updated_at ? $post->updated_at->toAtomString() : date('c'),
                    'priority'   => '0.7',
                    'changefreq' => 'monthly',
                ];
            })
            ->toArray();

        $allUrls = array_merge($staticPages, $products, $sectors, $posts);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($allUrls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>".htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8')."</loc>\n";
            if (! empty($url['lastmod'])) {
                $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            }
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generate dynamic robots.txt pointing to the XML sitemap.
     */
    public function robots(): Response
    {
        $baseUrl = config('app.url', url('/'));

        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /cart\n";
        $content .= "Disallow: /rfq/\n";
        $content .= "Allow: /\n\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
