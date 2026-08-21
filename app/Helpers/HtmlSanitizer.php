<?php

namespace App\Helpers;

/**
 * HtmlSanitizer
 *
 * Dedicated helper to sanitize and strip dangerous HTML, scripts, and attributes
 * from rich-text inputs (such as product descriptions and blog contents).
 */
class HtmlSanitizer
{
    /**
     * Sanitize HTML content, allowing only safe semantic formatting tags.
     */
    public static function clean(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Allowed formatting tags for rich text
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><span><div><blockquote><a>';

        $stripped = strip_tags($html, $allowedTags);

        // Remove dangerous JS attributes (on*, javascript:, data:)
        $cleaned = preg_replace('/(<[^>]+?)\son[a-zA-Z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '$1', $stripped);
        $cleaned = preg_replace('/href\s*=\s*("|\')\s*(javascript|data):[^\'"]*\1/i', 'href="#"', (string) $cleaned);

        return trim((string) $cleaned);
    }
}
