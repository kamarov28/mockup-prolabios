<?php

namespace Tests\Unit;

use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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

    public function test_handle_image_upload_throws_validation_exception_when_file_upload_is_invalid(): void
    {
        $invalidFile = new UploadedFile(
            tempnam(sys_get_temp_dir(), 'test_err_'),
            'corrupt.png',
            'image/png',
            UPLOAD_ERR_INI_SIZE,
            false
        );

        $request = new Request([], [], [], [], ['image_file' => $invalidFile]);

        $this->expectException(ValidationException::class);
        $this->handleImageUpload($request, 'image_file');
    }

    public function test_handle_pdf_upload_throws_validation_exception_when_file_upload_is_invalid(): void
    {
        $invalidFile = new UploadedFile(
            tempnam(sys_get_temp_dir(), 'test_err_pdf_'),
            'corrupt.pdf',
            'application/pdf',
            UPLOAD_ERR_INI_SIZE,
            false
        );

        $request = new Request([], [], [], [], ['datasheet_file' => $invalidFile]);

        $this->expectException(ValidationException::class);
        $this->handlePdfUpload($request, 'datasheet_file');
    }
}
