<?php

namespace Tests\Unit;

use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandlesImageUploadsTest extends TestCase
{
    use HandlesImageUploads;

    public function test_encode_to_webp_handles_palette_indexed_images(): void
    {
        Storage::fake('public');

        // imagecreate() creates a palette-based image (not truecolor)
        $paletteImg = imagecreate(100, 100);
        $bgColor = imagecolorallocate($paletteImg, 255, 0, 0);
        $this->assertFalse(imageistruecolor($paletteImg));

        $tmpFile = tempnam(sys_get_temp_dir(), 'palette_test_').'.png';
        imagepng($paletteImg, $tmpFile);
        imagedestroy($paletteImg);

        $uploadedFile = new UploadedFile($tmpFile, 'palette.png', 'image/png', null, true);

        $request = new Request([], [], [], [], ['image_file' => $uploadedFile]);

        $storedPath = $this->handleImageUpload($request, 'image_file', 'image_url', '', 'uploads/test');

        @unlink($tmpFile);

        $this->assertNotNull($storedPath);
        $this->assertStringEndsWith('.webp', $storedPath);

        $relativeStoragePath = str_replace('/storage/', '', $storedPath);
        Storage::disk('public')->assertExists($relativeStoragePath);
    }
}
