<?php

namespace Tests\Unit;

use App\Enums\PostStatus;
use App\Enums\RfqStatus;
use App\Models\Post;
use App\Models\Rfq;
use Carbon\Carbon;
use Tests\TestCase;

class EnumsAndCastsTest extends TestCase
{
    public function test_rfq_status_enum_has_expected_values_and_helpers(): void
    {
        $this->assertEquals('new', RfqStatus::New->value);
        $this->assertEquals('contacted', RfqStatus::Contacted->value);
        $this->assertEquals('quoted', RfqStatus::Quoted->value);
        $this->assertEquals('closed', RfqStatus::Closed->value);

        $this->assertEquals('Baru', RfqStatus::New->label());
        $this->assertEquals('Dihubungi', RfqStatus::Contacted->label());
        $this->assertEquals('Quoted', RfqStatus::Quoted->label());
        $this->assertEquals('Selesai', RfqStatus::Closed->label());

        $this->assertEquals('admin-badge-warning', RfqStatus::New->badgeClass());
        $this->assertEquals('admin-badge-info', RfqStatus::Contacted->badgeClass());

        $this->assertEquals('0369A1', RfqStatus::Quoted->color());
    }

    public function test_post_status_enum_has_expected_values_and_helpers(): void
    {
        $this->assertEquals('online', PostStatus::Online->value);
        $this->assertEquals('draft', PostStatus::Draft->value);

        $this->assertEquals('Online', PostStatus::Online->label());
        $this->assertEquals('Draft', PostStatus::Draft->label());

        $this->assertEquals('admin-badge-success', PostStatus::Online->badgeClass());
        $this->assertEquals('admin-badge-warning', PostStatus::Draft->badgeClass());
    }

    public function test_rfq_model_casts_status_to_enum(): void
    {
        $rfq = new Rfq;
        $rfq->status = 'quoted';

        $this->assertInstanceOf(RfqStatus::class, $rfq->status);
        $this->assertEquals(RfqStatus::Quoted, $rfq->status);
        $this->assertEquals('Quoted', $rfq->status_label);
        $this->assertEquals('admin-badge-accent', $rfq->status_badge_class);
    }

    public function test_post_model_casts_attributes(): void
    {
        $post = new Post;
        $post->status = 'draft';
        $post->is_featured = 1;
        $post->date = '2026-09-17';

        $this->assertInstanceOf(PostStatus::class, $post->status);
        $this->assertEquals(PostStatus::Draft, $post->status);
        $this->assertTrue($post->is_featured);
        $this->assertInstanceOf(Carbon::class, $post->date);
        $this->assertEquals('2026-09-17', $post->date->format('Y-m-d'));
    }
}
