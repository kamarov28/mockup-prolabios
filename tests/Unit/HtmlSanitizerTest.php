<?php

namespace Tests\Unit;

use App\Helpers\HtmlSanitizer;
use Tests\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_preserves_img_with_inline_style_dimensions_from_summernote(): void
    {
        $input = '<p><img src="/storage/uploads/editor/sample.webp" alt="Sample" style="width: 25%;" /></p>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('style="width:25%;"', str_replace(' ', '', $cleaned));
        $this->assertStringContainsString('/storage/uploads/editor/sample.webp', $cleaned);
    }

    public function test_it_preserves_img_with_width_and_height_attributes(): void
    {
        $input = '<p><img src="/storage/uploads/editor/sample.webp" alt="Sample" width="300" height="200" /></p>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('width="300"', $cleaned);
        $this->assertStringContainsString('height="200"', $cleaned);
    }

    public function test_it_strips_dangerous_css_from_img_style(): void
    {
        $input = '<p><img src="/storage/uploads/editor/sample.webp" alt="Sample" style="position: fixed; width: 50%;" /></p>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringNotContainsString('position', $cleaned);
        $this->assertStringContainsString('width:50%;', str_replace(' ', '', $cleaned));
    }
}
