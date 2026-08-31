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

        // Remove dangerous JS / event attributes (e.g. onclick, onerror, onload, onmouseover, formaction, etc.)
        $cleaned = preg_replace('/\s+on[a-zA-Z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $stripped);

        // Remove javascript:, vbscript:, and data: URI schemes in href / src / action (prevent XSS via scheme)
        $cleaned = preg_replace('/(href|src|action)\s*=\s*("|\')\s*(javascript|vbscript|data\s*:\s*text\/html):[^\'"]*\2/i', '$1="#"', (string) $cleaned);

        return trim((string) $cleaned);
    }
}
