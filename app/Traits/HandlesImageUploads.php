<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesImageUploads
{
    /**
     * Securely handle image uploads with size, extension, mime checks, auto WebP conversion, and path traversal protection.
     *
     * @param  string  $fileKey  Input key for file upload
     * @param  string  $urlKey  Input key for URL fallback
     * @param  string|null  $fallback  Default image URL
     * @param  string  $folder  Target upload folder inside public/
     * @param  int  $maxSizeBytes  Max file size limit (5MB default)
     */
    protected function handleImageUpload(
        Request $request,
        string $fileKey = 'image_file',
        string $urlKey = 'image_url',
        ?string $fallback = null,
        string $folder = 'uploads',
        int $maxSizeBytes = 5242880
    ): ?string {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);

            if ($file->isValid()) {
                if ($file->getSize() > $maxSizeBytes) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Ukuran file gambar terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                    ]);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                if ($extension === 'svg') {
                    throw ValidationException::withMessages([
                        $fileKey => ['File format SVG tidak diizinkan demi alasan keamanan. Gunakan format JPG, PNG, atau WebP.'],
                    ]);
                }

                if (! in_array($extension, $allowedExtensions)) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Format gambar tidak valid. Gunakan format JPG, JPEG, PNG, WEBP, atau GIF.'],
                    ]);
                }

                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (! in_array($mimeType, $allowedMimes)) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Tipe file yang diunggah bukan file gambar yang sah.'],
                    ]);
                }

                $targetDir = public_path($folder);
                if (! file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Automatic WebP Conversion & Resizing (70-85% file size reduction)
                if (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                    $rawContent = file_get_contents($file->getRealPath());
                    $img = @imagecreatefromstring($rawContent);

                    if ($img !== false) {
                        $width = imagesx($img);
                        $height = imagesy($img);

                        // Downscale oversized images exceeding 1920px width
                        if ($width > 1920) {
                            $newWidth = 1920;
                            $newHeight = (int) round(($height / $width) * 1920);
                            $resized = imagecreatetruecolor($newWidth, $newHeight);

                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);

                            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                            imagedestroy($img);
                            $img = $resized;
                        }

                        $webpFilename = time().'_'.Str::random(16).'.webp';
                        $fullPath = $targetDir.'/'.$webpFilename;

                        imagewebp($img, $fullPath, 82);
                        imagedestroy($img);

                        return '/'.trim($folder, '/').'/'.$webpFilename;
                    }
                }

                // Fallback standard move if GD conversion skipped
                $filename = time().'_'.Str::random(16).'.'.$extension;
                $file->move($targetDir, $filename);

                return '/'.trim($folder, '/').'/'.$filename;
            }
        }

        $url = trim($request->input($urlKey, ''));
        if (! empty($url)) {
            $sanitized = filter_var($url, FILTER_SANITIZE_URL);
            $valid = filter_var($sanitized, FILTER_VALIDATE_URL);
            if ($valid && in_array(strtolower(parse_url($valid, PHP_URL_SCHEME)), ['http', 'https'])) {
                // Optional domain whitelist: only allow same host as the application
                $allowedHost = request()->getHost();
                $urlHost = strtolower(parse_url($valid, PHP_URL_HOST) ?? '');
                if ($urlHost === $allowedHost) {
                    return $valid;
                }
            }

            return $fallback;
        }

        return $fallback;
    }

    /**
     * Securely handle multiple image uploads (e.g. a product photo gallery).
     * Reuses the exact same validation/hardening rules as handleImageUpload()
     * (extension allowlist, MIME check, SVG block, WebP re-encode + resize,
     * random filename) for every file in the batch.
     *
     * @param  string  $fileKey  Input key for the file[] array (e.g. 'gallery_files')
     * @param  string  $folder  Target upload folder inside public/
     * @param  int  $maxSizeBytes  Max size per file (5MB default)
     * @param  int  $maxFiles  Max number of files accepted per request (10 default)
     * @return array<int, string> List of stored relative image paths
     */
    protected function handleMultipleImageUploads(
        Request $request,
        string $fileKey = 'gallery_files',
        string $folder = 'uploads',
        int $maxSizeBytes = 5242880,
        int $maxFiles = 10
    ): array {
        if (! $request->hasFile($fileKey)) {
            return [];
        }

        $files = $request->file($fileKey);
        if (! is_array($files)) {
            $files = [$files];
        }

        $files = array_slice($files, 0, $maxFiles);
        $stored = [];

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            if ($file->getSize() > $maxSizeBytes) {
                throw ValidationException::withMessages([
                    $fileKey => ['Salah satu file gambar galeri berukuran terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if ($extension === 'svg' || ! in_array($extension, $allowedExtensions)) {
                throw ValidationException::withMessages([
                    $fileKey => ['Format gambar galeri tidak valid. Gunakan JPG, JPEG, PNG, WEBP, atau GIF.'],
                ]);
            }

            $mimeType = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (! in_array($mimeType, $allowedMimes)) {
                throw ValidationException::withMessages([
                    $fileKey => ['Salah satu file yang diunggah bukan file gambar yang sah.'],
                ]);
            }

            $targetDir = public_path($folder);
            if (! file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $storedPath = null;

            if (function_exists('imagewebp') && function_exists('imagecreatefromstring')) {
                $rawContent = file_get_contents($file->getRealPath());
                $img = @imagecreatefromstring($rawContent);

                if ($img !== false) {
                    $width = imagesx($img);
                    $height = imagesy($img);

                    if ($width > 1920) {
                        $newWidth = 1920;
                        $newHeight = (int) round(($height / $width) * 1920);
                        $resized = imagecreatetruecolor($newWidth, $newHeight);

                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);

                        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagedestroy($img);
                        $img = $resized;
                    }

                    $webpFilename = time().'_'.Str::random(16).'.webp';
                    $fullPath = $targetDir.'/'.$webpFilename;

                    imagewebp($img, $fullPath, 82);
                    imagedestroy($img);

                    $storedPath = '/'.trim($folder, '/').'/'.$webpFilename;
                }
            }

            if ($storedPath === null) {
                $filename = time().'_'.Str::random(16).'.'.$extension;
                $file->move($targetDir, $filename);
                $storedPath = '/'.trim($folder, '/').'/'.$filename;
            }

            $stored[] = $storedPath;
        }

        return $stored;
    }
}
