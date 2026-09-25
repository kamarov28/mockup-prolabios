<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );
    }

    public function test_guest_cannot_upload_media(): void
    {
        $response = $this->postJson(route('admin.media.upload'), [
            'image' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertUnauthorized();
    }

    public function test_admin_can_upload_image_and_receives_webp_storage_url(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('photo.png', 400, 300);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'image' => $file,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['url']);

        $url = $response->json('url');
        $this->assertStringStartsWith('/storage/uploads/editor/', $url);

        $relativePath = str_replace('/storage/', '', $url);
        Storage::disk('public')->assertExists($relativePath);
    }

    public function test_upload_rejects_non_image_files(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('malicious.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.upload'), [
            'image' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }
}
