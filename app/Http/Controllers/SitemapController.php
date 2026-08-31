<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Sector;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $xml = Cache::remember('sitemap_xml_cache', 86400, function () {
            $baseUrl = rtrim((string) config('app.url', url('/')), '/');

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

            $products = Product::query()
                ->select('id', 'slug', 'title', 'updated_at')
                ->orderByDesc('id')
                ->get()
                ->map(function (Product $product) use ($baseUrl) {
                    $path = ! empty($product->slug)
                        ? '/produk/'.$product->slug
                        : '/produk/detail?id='.$product->id;

                    return [
                        'loc' => $baseUrl.$path,
                        'lastmod' => $product->updated_at ? $product->updated_at->toAtomString() : date('c'),
                        'priority' => '0.8',
                        'changefreq' => 'weekly',
                    ];
                })
                ->toArray();

            $sectors = Sector::query()
                ->select('id', 'updated_at')
                ->get()
                ->map(function ($sector) use ($baseUrl) {
                    return [
                        'loc' => $baseUrl.'/sektor?s='.$sector->id,
                        'lastmod' => $sector->updated_at ? $sector->updated_at->toAtomString() : date('c'),
                        'priority' => '0.7',
                        'changefreq' => 'weekly',
                    ];
                })
                ->toArray();

            $posts = Post::query()
                ->select('slug', 'updated_at')
                ->where('status', 'online')
                ->orderByDesc('id')
                ->get()
                ->map(function ($post) use ($baseUrl) {
                    return [
                        'loc' => $baseUrl.'/informasi/'.rawurlencode($post->slug),
                        'lastmod' => $post->updated_at ? $post->updated_at->toAtomString() : date('c'),
                        'priority' => '0.7',
                        'changefreq' => 'monthly',
                    ];
                })
                ->toArray();

            $allUrls = array_merge($staticPages, $products, $sectors, $posts);

            $content = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($allUrls as $url) {
                $content .= "  <url>\n";
                $content .= '    <loc>'.htmlspecialchars($url['loc'], ENT_XML1, 'UTF-8')."</loc>\n";
                if (! empty($url['lastmod'])) {
                    $content .= '    <lastmod>'.$url['lastmod']."</lastmod>\n";
                }
                $content .= '    <changefreq>'.$url['changefreq']."</changefreq>\n";
                $content .= '    <priority>'.$url['priority']."</priority>\n";
                $content .= "  </url>\n";
            }

            $content .= '</urlset>';

            return $content;
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function robots(): Response
    {
        $baseUrl = rtrim((string) config('app.url', url('/')), '/');

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
