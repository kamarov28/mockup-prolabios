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

        // Allowed formatting tags for rich text (including images, tables, embeds)
        $allowedTags = '<p><br><strong><b><em><i><u><s><del><sub><sup><ul><ol><li><h2><h3><h4><h5><h6><table><thead><tbody><tfoot><tr><th><td><span><div><blockquote><a><img><hr><iframe><figure><figcaption><code><pre>';

        $stripped = strip_tags($html, $allowedTags);

        // Remove dangerous JS attributes (e.g. onclick, onerror, onload, onmouseover)
        $cleaned = preg_replace('/(<[^>]+?)\son[a-zA-Z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/i', '$1', $stripped);

        // Remove javascript: and vbscript: pseudoprotocols in href or src
        $cleaned = preg_replace('/(href|src)\s*=\s*("|\')\s*(javascript|vbscript):[^\'"]*\2/i', '$1="#"', (string) $cleaned);

        return trim((string) $cleaned);
    }
}
