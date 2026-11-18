<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorageGarbageCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_replacing_or_deleting_product_cleans_up_unreferenced_storage_files(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('uploads/old-img.webp', 'old-image-bytes');
        Storage::disk('public')->put('uploads/new-img.webp', 'new-image-bytes');
        Storage::disk('public')->put('datasheets/spec.pdf', 'pdf-bytes');

        $product = Product::create([
            'catalog' => 'GC-001',
            'title' => 'Garbage Collection Test Product',
            'category' => 'microbiology',
            'image' => '/storage/uploads/old-img.webp',
            'datasheet_url' => '/storage/datasheets/spec.pdf',
            'price' => 100000,
            'stock' => 5,
        ]);

        // Replace image -> old-img.webp must be deleted, new-img.webp & spec.pdf kept
        $product->update(['image' => '/storage/uploads/new-img.webp']);

        Storage::disk('public')->assertMissing('uploads/old-img.webp');
        Storage::disk('public')->assertExists('uploads/new-img.webp');
        Storage::disk('public')->assertExists('datasheets/spec.pdf');

        // Delete product -> remaining storage files must be deleted
        $product->delete();

        Storage::disk('public')->assertMissing('uploads/new-img.webp');
        Storage::disk('public')->assertMissing('datasheets/spec.pdf');
    }

    public function test_shared_storage_file_is_preserved_if_another_product_still_uses_it(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('uploads/shared.webp', 'shared-bytes');

        $p1 = Product::create([
            'catalog' => 'SH-001',
            'title' => 'Shared Asset Product 1',
            'category' => 'microbiology',
            'image' => '/storage/uploads/shared.webp',
            'price' => 50000,
            'stock' => 2,
        ]);

        $p2 = Product::create([
            'catalog' => 'SH-002',
            'title' => 'Shared Asset Product 2',
            'category' => 'microbiology',
            'image' => '/storage/uploads/shared.webp',
            'price' => 50000,
            'stock' => 2,
        ]);

        // Deleting p1 must NOT delete shared.webp because p2 still references it
        $p1->delete();
        Storage::disk('public')->assertExists('uploads/shared.webp');

        // Deleting p2 finally removes shared.webp
        $p2->delete();
        Storage::disk('public')->assertMissing('uploads/shared.webp');
    }
}
