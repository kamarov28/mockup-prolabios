<?php

/**
 * Canonical public URL for a product (array, model, or stdClass).
 * Prefer /produk/{slug}; fall back to legacy ?id= only if slug missing.
 */
if (! function_exists('product_url')) {
    function product_url(array|object $product): string
    {
        $slug = null;
        $id = null;

        if (is_array($product)) {
            $slug = $product['slug'] ?? null;
            $id = $product['id'] ?? null;
        } else {
            $slug = $product->slug ?? null;
            $id = $product->id ?? null;
        }

        $slug = is_string($slug) ? trim($slug) : '';
        if ($slug !== '') {
            return url('/produk/'.$slug);
        }

        if ($id !== null && $id !== '') {
            return url('/produk/detail?id='.$id);
        }

        return url('/produk');
    }
}
