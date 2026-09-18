<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::forceCreate([
            'name' => 'Admin Post Test',
            'email' => 'admin-post-test@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_posts_page(): void
    {
        $response = $this->get(route('admin.posts'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_posts_index_with_articles(): void
    {
        Post::create([
            'slug' => 'artikel-uji-lab',
            'title' => 'Pengujian Mikrobiologi Air',
            'category' => 'Berita',
            'status' => PostStatus::Online,
            'content' => 'Konten pengujian mikrobiologi...',
            'date' => '2026-03-15',
            'is_featured' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.posts'));

        $response->assertStatus(200);
        $response->assertSee('Pengujian Mikrobiologi Air');
        $response->assertSee('artikel-uji-lab');
        $response->assertSee('Berita');
        $response->assertSee('Published');
        $response->assertSee('Unggulan');
    }

    public function test_admin_can_filter_posts_by_search_category_and_status(): void
    {
        Post::create([
            'slug' => 'berita-satu',
            'title' => 'Berita Utama Prolabios',
            'category' => 'Berita',
            'status' => PostStatus::Online,
            'content' => 'Isi berita utama',
            'date' => '2026-03-10',
        ]);

        Post::create([
            'slug' => 'event-dua',
            'title' => 'Seminar Kalibrasi Alat',
            'category' => 'Event',
            'status' => PostStatus::Draft,
            'content' => 'Isi seminar kalibrasi',
            'date' => '2026-03-12',
        ]);

        // Filter by search query
        $resSearch = $this->actingAs($this->adminUser)->get(route('admin.posts', ['s' => 'Seminar']));
        $resSearch->assertSee('Seminar Kalibrasi Alat');
        $resSearch->assertDontSee('Berita Utama Prolabios');

        // Filter by category
        $resCategory = $this->actingAs($this->adminUser)->get(route('admin.posts', ['category' => 'Berita']));
        $resCategory->assertSee('Berita Utama Prolabios');
        $resCategory->assertDontSee('Seminar Kalibrasi Alat');

        // Filter by status
        $resStatus = $this->actingAs($this->adminUser)->get(route('admin.posts', ['status' => 'draft']));
        $resStatus->assertSee('Seminar Kalibrasi Alat');
        $resStatus->assertDontSee('Berita Utama Prolabios');
    }
}
