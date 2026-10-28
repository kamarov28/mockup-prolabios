<?php

namespace App\Helpers;

use Mews\Purifier\Facades\Purifier;

/**
 * HtmlSanitizer
 *
 * Dedicated helper to sanitize and strip dangerous HTML, scripts, and attributes
 * from rich-text inputs (such as product descriptions and blog contents).
 *
 * This implementation delegates to HTMLPurifier (via mews/purifier), which uses a
 * real DOM parser — not regex — to enforce the tag/attribute whitelist defined in
 * config/purifier.php under the 'product_description' key.
 *
 * Why HTMLPurifier instead of regex?
 * - Regex-based HTML sanitisation is fundamentally bypassable: browser HTML parsers
 *   are far more permissive than any regex can model (e.g. tab/newline inside
 *   "javascript:", control characters in attribute names, srcdoc on iframe, etc.).
 * - HTMLPurifier uses libxml's actual HTML parser, then re-serialises only the
 *   whitelisted nodes, providing genuine defence-in-depth.
 */
class HtmlSanitizer
{
    /**
     * Sanitize HTML content, allowing only the safe semantic tags defined in
     * config/purifier.php ('product_description' profile).
     *
     * Notable security decisions vs. the old regex sanitizer:
     * - <iframe> is strictly restricted to safe YouTube and Instagram video embeds via HTML.SafeIframe.
     * - All event attributes (onXxx) are stripped by HTMLPurifier's parser by design.
     * - URI schemes are limited to http/https — blocks javascript:, data:, vbscript:.
     *
     * @param  string|null  $html  Raw HTML from the database / editor
     * @return string Sanitized HTML safe for {!! !!} raw output
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Normalize Instagram iframes so they load the interactive embed widget correctly
        $html = (string) preg_replace_callback(
            '#src="((?:https?:)?//(?:www\.)?instagram\.com/(?:p|reel)/[A-Za-z0-9_-]+)/?(?:embed/?)?"#i',
            function ($m) {
                return 'src="'.rtrim($m[1], '/').'/embed/"';
            },
            $html
        );

        // Normalize YouTube watch links in iframes to embed format
        $html = (string) preg_replace(
            '#src="(?:https?:)?//(?:www\.)?youtube\.com/watch\?v=([A-Za-z0-9_-]+)[^"]*"#i',
            'src="https://www.youtube.com/embed/$1"',
            $html
        );
        $html = (string) preg_replace(
            '#src="(?:https?:)?//youtu\.be/([A-Za-z0-9_-]+)[^"]*"#i',
            'src="https://www.youtube.com/embed/$1"',
            $html
        );

        return Purifier::clean($html, 'product_description');
    }

    /**
     * Clean and wrap all embedded product description images with Prolabios watermark protection.
     */
    public static function cleanWithProductWatermark(?string $html): string
    {
        $sanitized = self::clean($html);
        if (empty($sanitized) || !str_contains($sanitized, '<img')) {
            return $sanitized;
        }

        // Wrap non-watermarked <img> tags in a protective watermark container strictly sized to the image
        return (string) preg_replace_callback(
            '#<img\b([^>]*)/?>#i',
            function ($matches) {
                $imgTag = $matches[0];
                $attrs = $matches[1];

                $wrapStyles = [
                    'display: inline-block',
                    'position: relative',
                    'overflow: hidden',
                    'max-width: 100%',
                    'vertical-align: middle',
                    'line-height: 0',
                    'border-radius: 8px',
                    'margin: 8px 0',
                ];

                // If image has inline width percentage or pixels (e.g. from Summernote resize), transfer to container
                $updatedImg = $imgTag;
                if (preg_match('/width\s*:\s*([^;]+);?/i', $attrs, $widthMatch)) {
                    $widthVal = trim($widthMatch[1]);
                    $wrapStyles[] = 'width: ' . $widthVal;
                    $updatedImg = (string) preg_replace('/width\s*:\s*[^;]+;?/i', 'width: 100%;', $imgTag);
                }
                if (preg_match('/float\s*:\s*([^;]+);?/i', $attrs, $floatMatch)) {
                    $wrapStyles[] = 'float: ' . trim($floatMatch[1]);
                }

                $styleAttr = implode('; ', $wrapStyles) . ';';

                return '<span class="product-watermark-wrap" style="' . e($styleAttr) . '">' . $updatedImg . '</span>';
            },
            $sanitized
        );
    }
}
