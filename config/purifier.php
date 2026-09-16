<?php

/**
 * HTMLPurifier configuration for Prolabios.
 *
 * Applied to product descriptions and blog content rendered with {!! !!}
 * in public-facing Blade templates.
 *
 * Security notes:
 * - <iframe> is intentionally EXCLUDED — enables XSS via srcdoc and arbitrary embedding.
 * - URI schemes are whitelisted to http/https only — strips javascript:, data:, vbscript:.
 * - Event attributes (onXxx) are stripped by HTMLPurifier's parser by design.
 * - HTML5 elements (figure, figcaption, s, del, sub, sup) are registered globally via
 *   settings.custom_definition, which mews/purifier applies to every profile.
 *
 * @link https://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding'         => 'UTF-8',
    'finalize'         => true,
    'ignoreNonStrings' => false,
    'cachePath'        => storage_path('app/purifier'),
    'cacheFileMode'    => 0755,

    'settings' => [
        // ------------------------------------------------------------------
        // Profile used by HtmlSanitizer::clean() for product descriptions.
        // ------------------------------------------------------------------
        'product_description' => [
            // HTML.Allowed uses HTML 4.01 tag names; HTML5 extras registered below.
            'HTML.Allowed' => implode(',', [
                'p', 'br', 'hr',
                'strong', 'b', 'em', 'i', 'u', 's', 'del', 'sub', 'sup',
                'ul', 'ol', 'li',
                'h2', 'h3', 'h4', 'h5', 'h6',
                'table', 'thead', 'tbody', 'tfoot', 'tr',
                'th[scope|colspan|rowspan]',
                'td[colspan|rowspan]',
                'span[style]',
                'div[style]',
                'blockquote', 'code', 'pre',
                'a[href|title|target|rel]',
                'img[src|alt|width|height|loading]',
                'figure', 'figcaption',
                // NOTE: <iframe> is deliberately omitted — was in the old regex sanitizer
                // but enables XSS via srcdoc and arbitrary frame embedding.
            ]),

            // Limit CSS properties allowed in style="" attributes
            'CSS.AllowedProperties' => implode(',', [
                'color', 'background-color',
                'font-size', 'font-weight', 'font-style', 'font-family',
                'text-decoration', 'text-align',
                'padding', 'padding-left', 'padding-right',
                'margin', 'margin-left', 'margin-right',
                'border', 'border-collapse',
                'width', 'max-width', 'height',
            ]),

            // Only allow http/https URIs — strips javascript:, data:, vbscript:, etc.
            'URI.AllowedSchemes' => ['http' => true, 'https' => true],

            // Allow target="_blank" on links (common for product datasheets)
            'Attr.AllowedFrameTargets' => ['_blank', '_self'],

            // Preserve the author's structure — do not auto-wrap in <p>
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty'   => true,

            'HTML.Doctype' => 'HTML 4.01 Transitional',
        ],

        // ------------------------------------------------------------------
        // Global custom HTML5 element & attribute definitions.
        // mews/purifier reads these from purifier.settings.custom_definition,
        // purifier.settings.custom_elements, and purifier.settings.custom_attributes
        // and applies them to every profile.
        //
        // HTMLPurifier is HTML 4.01-based internally, so HTML5 elements used in
        // HTML.Allowed must also be declared here or HTMLPurifier will throw
        // "Element X is not supported".
        // ------------------------------------------------------------------
        'custom_definition' => [
            'id'    => 'prolabios-html5',
            'rev'   => 2,
            'debug' => false,
            'elements' => [
                ['figure',     'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],
                ['s',          'Inline', 'Inline', 'Common'],
                ['del',        'Block',  'Flow',   'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
                ['sub',        'Inline', 'Inline', 'Common'],
                ['sup',        'Inline', 'Inline', 'Common'],
            ],
            'attributes' => [
                ['a',   'target',  'Enum#_blank,_self,_top'],
                ['a',   'rel',     'Text'],
                ['img', 'loading', 'Enum#lazy,eager,auto'],
            ],
        ],
    ],
];
