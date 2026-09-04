<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * DataService
 *
 * Central service facade delegating to focused domain services:
 * - ProductService: Catalog, categories structure, and product caching
 * - PostService: Articles, news, events, and category counts
 * - SectorService: Industry sectors and applications
 * - HomepageService: Homepage layout data and general site settings
 * - HtmlSanitizer: Rich-text sanitization
 */
class DataService
{
    public function __construct(
        protected ProductService $products,
        protected PostService $posts,
        protected SectorService $sectors,
        protected HomepageService $homepage
    ) {}

    // ----------------------------------------------------
    // Products Domain (Delegates to ProductService)
    // ----------------------------------------------------
    public static function getProductsCacheVersion(): int
    {
        return ProductService::getProductsCacheVersion();
    }

    public function clearProductsCache(): void
    {
        $this->products->clearProductsCache();
    }

    public function getCategoriesStructure(): array
    {
        return $this->products->getCategoriesStructure();
    }

    public function getProducts(?array $filters = [], int $limit = 0): Collection
    {
        return $this->products->getProducts($filters, $limit);
    }

    public function getPaginatedProducts(?array $filters = [], int $perPage = 12)
    {
        return $this->products->getPaginatedProducts($filters, $perPage);
    }

    public function getProductByTitle(string $title): ?Product
    {
        return $this->products->getProductByTitle($title);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->products->getProductById($id);
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->products->getProductBySlug($slug);
    }

    public function addProduct(array $product): ?Product
    {
        return $this->products->addProduct($product);
    }

    public function updateProductById(int $id, array $updatedProduct): bool
    {
        return $this->products->updateProductById($id, $updatedProduct);
    }

    public function deleteProductById(int $id): bool
    {
        return $this->products->deleteProductById($id);
    }

    public function upsertProducts(array $rows): bool
    {
        return $this->products->upsertProducts($rows);
    }

    // ----------------------------------------------------
    // Posts Domain (Delegates to PostService)
    // ----------------------------------------------------
    public function getPosts(?array $filters = [], int $limit = 0): array
    {
        return $this->posts->getPosts($filters, $limit);
    }

    public function getPaginatedPosts(?array $filters = [], int $perPage = 4)
    {
        return $this->posts->getPaginatedPosts($filters, $perPage);
    }

    public function getPostBySlug(string $slug): ?array
    {
        return $this->posts->getPostBySlug($slug);
    }

    public function addPost(array $post): bool
    {
        return $this->posts->addPost($post);
    }

    public function updatePost(string $slug, array $updatedPost): bool
    {
        return $this->posts->updatePost($slug, $updatedPost);
    }

    public function deletePost(string $slug): bool
    {
        return $this->posts->deletePost($slug);
    }

    // ----------------------------------------------------
    // Sectors Domain (Delegates to SectorService)
    // ----------------------------------------------------
    public function getSectors(): array
    {
        return $this->sectors->getSectors();
    }

    public function getSectorById(string $id): ?array
    {
        return $this->sectors->getSectorById($id);
    }

    public function addSector(array $sector): bool
    {
        return $this->sectors->addSector($sector);
    }

    public function updateSector(string $id, array $updatedSector): bool
    {
        return $this->sectors->updateSector($id, $updatedSector);
    }

    public function deleteSector(string $id): bool
    {
        return $this->sectors->deleteSector($id);
    }

    // ----------------------------------------------------
    // Homepage & Settings Domain (Delegates to HomepageService)
    // ----------------------------------------------------
    public static function clearSettingsCache(): void
    {
        HomepageService::clearSettingsCache();
    }

    public function getHomepageData(): array
    {
        return $this->homepage->getHomepageData();
    }

    public function getHomepageDataFresh(): array
    {
        return $this->homepage->getHomepageDataFresh();
    }

    public function saveHomepageData(array $data): bool
    {
        return $this->homepage->saveHomepageData($data);
    }

    public function getDefaultHomepageData(): array
    {
        return $this->homepage->getDefaultHomepageData();
    }

    // ----------------------------------------------------
    // HTML Sanitization (Delegates to HtmlSanitizer)
    // ----------------------------------------------------
    public static function sanitizeHtml(?string $html): string
    {
        return HtmlSanitizer::clean($html);
    }
}
