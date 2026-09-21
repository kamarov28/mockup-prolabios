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
     * - <iframe> is NOT in the whitelist (was previously allowed; enables XSS via srcdoc).
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

        return Purifier::clean($html, 'product_description');
    }
}
