<?php

namespace App\Traits;

use App\Models\Product;
use App\Services\DataService;
use App\Services\ProductService;
use Illuminate\Support\Str;

/**
 * Resolve Product by id (preferred), then slug, then title.
 * Shared by CartController and RfqController.
 */
trait ResolvesProducts
{
    protected function resolveProduct(?string $id, ?string $title): ?Product
    {
        $service = $this->productLookupService();

        if (! empty($id)) {
            $product = $service->getProductById((int) $id);
            if ($product) {
                return $product;
            }
        }

        if (empty($title)) {
            return null;
        }

        // Prefer slug-shaped lookup before exact title (legacy cart rows may store either)
        $slug = Str::slug($title);
        if ($slug !== '') {
            $product = $service->getProductBySlug($slug);
            if ($product) {
                return $product;
            }
        }

        return $service->getProductByTitle($title);
    }

    private function productLookupService(): DataService|ProductService
    {
        if (isset($this->products) && $this->products instanceof ProductService) {
            return $this->products;
        }

        if (isset($this->dataService) && $this->dataService instanceof DataService) {
            return $this->dataService;
        }

        return app(DataService::class);
    }
}
