<?php

namespace App\Traits;

use App\Models\Product;

/**
 * ResolvesProducts
 *
 * Trait to look up a Product model either by ID (preferred) or by Title fallback.
 * Shared between CartController, RfqController, and other customer-facing controllers.
 */
trait ResolvesProducts
{
    /**
     * Resolve a Product Eloquent model by ID (preferred) then by Title.
     * Returns null when neither lookup finds a match.
     */
    protected function resolveProduct(?string $id, ?string $title): ?Product
    {
        $product = null;

        if (! empty($id)) {
            $product = $this->dataService->getProductById((int) $id);
        }

        if (! $product && ! empty($title)) {
            $product = $this->dataService->getProductByTitle($title);
        }

        return $product;
    }
}
