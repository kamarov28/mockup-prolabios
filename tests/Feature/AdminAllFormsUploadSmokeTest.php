<?php

namespace Tests\Feature;

use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAllFormsUploadSmokeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        ProductCategory::create([
            'key' => 'microbiology',
            'name' => 'Microbiology',
        ]);
    }

    public function test_product_create_and_update_with_image_file_and_url(): void
    {
        $imageFile = UploadedFile::fake()->image('product.jpg', 600, 600);
        $datasheet = UploadedFile::fake()->create('spec.pdf', 100, 'application/pdf');

        $res = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Test Culture Media',
            'catalog' => 'TCM-01',
            'category' => 'microbiology',
            'image_file' => $imageFile,
            'image_url' => 'https://example.com/external-prod.jpg',
            'datasheet_file' => $datasheet,
            'datasheet_url' => '/storage/datasheets/spec.pdf',
        ]);

        $res->assertRedirect(route('admin.products'));
        $product = Product::where('title', 'Test Culture Media')->first();
        $this->assertNotNull($product);

        // Update product with image url
        $updateRes = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $product->id]), [
            'title' => 'Test Culture Media Updated',
            'category' => 'microbiology',
            'image_url' => '/storage/uploads/updated.webp',
            'datasheet_url' => 'https://example.com/datasheet.pdf',
        ]);

        $updateRes->assertRedirect(route('admin.products'));
    }

    public function test_post_create_and_update_with_image_file_and_url(): void
    {
        $imageFile = UploadedFile::fake()->image('article.png', 800, 400);

        $res = $this->actingAs($this->admin)->post(route('admin.posts.store'), [
            'title' => 'Teknologi Laboratorium Terkini',
            'category' => 'berita',
            'content' => '<p>Ulasan teknologi lab.</p>',
            'image_file' => $imageFile,
            'image_url' => '/images/news.jpg',
            'status_option' => 'online_now',
        ]);

        $res->assertRedirect(route('admin.posts'));

        $updateRes = $this->actingAs($this->admin)->put(route('admin.posts.update', ['slug' => 'teknologi-laboratorium-terkini']), [
            'title' => 'Teknologi Laboratorium Terkini v2',
            'category' => 'berita',
            'content' => '<p>Ulasan teknologi lab baru.</p>',
            'image_url' => 'https://example.com/news2.jpg',
            'status_option' => 'online_now',
        ]);

        $updateRes->assertRedirect(route('admin.posts'));
    }

    public function test_principal_create_and_update_with_logo_file_and_url(): void
    {
        $logoFile = UploadedFile::fake()->image('logo.png', 200, 100);

        $res = $this->actingAs($this->admin)->post(route('admin.principals.store'), [
            'name' => 'Liofilchem Italy',
            'address' => 'Roseto degli Abruzzi, Italy',
            'status' => 'online',
            'logo_file' => $logoFile,
            'logo_url' => '/images/vendor/liofilchem.png',
        ]);

        $res->assertRedirect(route('admin.principals'));
        $principal = Principal::where('name', 'Liofilchem Italy')->first();
        $this->assertNotNull($principal);

        $updateRes = $this->actingAs($this->admin)->put(route('admin.principals.update', ['id' => $principal->id]), [
            'name' => 'Liofilchem Diagnostic',
            'status' => 'online',
            'logo_url' => 'https://example.com/liofilchem.webp',
        ]);

        $updateRes->assertRedirect(route('admin.principals'));
    }

    public function test_sector_create_and_update_with_image_file_and_url(): void
    {
        $imageFile = UploadedFile::fake()->image('sector.jpg', 800, 600);

        $res = $this->actingAs($this->admin)->post(route('admin.sectors.store'), [
            'id' => 'food-testing',
            'name' => 'Food & Beverage Testing',
            'description' => 'Paragraf 1',
            'image_file' => $imageFile,
            'image_url' => '/images/sectors/food.jpg',
        ]);

        $res->assertRedirect(route('admin.sectors'));
        $sector = Sector::find('food-testing');
        $this->assertNotNull($sector);

        $updateRes = $this->actingAs($this->admin)->put(route('admin.sectors.update', ['id' => 'food-testing']), [
            'name' => 'Food & Beverage Testing Updated',
            'description' => 'Paragraf 1 baru',
            'image_url' => 'https://example.com/food.jpg',
        ]);

        $updateRes->assertRedirect(route('admin.sectors'));
    }

    public function test_homepage_settings_update_with_uploaded_images_and_links(): void
    {
        $res = $this->actingAs($this->admin)->post(route('admin.home.update'), [
            'section' => 'homepage',
            'hero_badge' => 'Official Lab Supply',
            'hero_title' => 'Precision & Accuracy',
            'hero_subtitle' => 'Solusi reagen lab.',
            'hero_cta_text' => 'Katalog Produk',
            'hero_cta_link' => '/produk',
            'hero_image_file_0' => UploadedFile::fake()->image('slide1.jpg', 1200, 600),
            'sector_id_pharma' => 'food-testing',
            'sector_link_pharma' => '/sektor?s=food-testing',
        ]);

        $res->assertRedirect(route('admin.home.edit', ['section' => 'homepage']));
    }
}
