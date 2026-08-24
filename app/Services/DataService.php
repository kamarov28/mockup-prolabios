<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sector;
use Illuminate\Support\Collection;

class DataService
{
    public function __construct(
        protected HomepageService $homepage,
        protected ProductService $products,
        protected SectorService $sectors,
        protected PostService $posts,
    ) {}

    public function getProducts(): Collection
    {
        return $this->products->getProducts();
    }

    public function getProductById(int|string $id): ?Product
    {
        return $this->products->getProductById($id);
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->products->getProductBySlug($slug);
    }

    public function getCategories(): Collection
    {
        return ProductCategory::query()->orderBy('name')->get();
    }

    public function getPrincipals(): Collection
    {
        return Principal::query()->orderBy('name')->get();
    }

    public function getSectors(): Collection
    {
        return $this->sectors->getSectors();
    }

    public function getPosts(): Collection
    {
        return $this->posts->getPosts();
    }

    public function getPostBySlug(string $slug): ?Post
    {
        return $this->posts->getPostBySlug($slug);
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
}
