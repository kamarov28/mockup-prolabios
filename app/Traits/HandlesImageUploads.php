<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesImageUploads
{
    /**
     * Securely handle image uploads with size, extension, mime checks, auto WebP conversion,
     * and path traversal protection.
     *
     * Files are stored on the "public" disk (storage/app/public/{folder}) and served via
     * /storage/{folder}/... after `php artisan storage:link`.
     *
     * Legacy paths under /uploads/ remain accepted as URL fallbacks so existing DB rows still work.
     *
     * @param  string  $fileKey  Input key for file upload
     * @param  string  $urlKey  Input key for URL fallback
     * @param  string|null  $fallback  Default image URL / path
     * @param  string  $folder  Target folder on the public disk
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
        $folder = trim($folder, '/');

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

                if (! in_array($extension, $allowedExtensions, true)) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Format gambar tidak valid. Gunakan format JPG, JPEG, PNG, WEBP, atau GIF.'],
                    ]);
                }

                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (! in_array($mimeType, $allowedMimes, true)) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Tipe file yang diunggah bukan file gambar yang sah.'],
                    ]);
                }

                // Prefer WebP re-encode (resize oversized images, strip metadata)
                $webpPath = $this->encodeToWebp($file, $folder);
                if ($webpPath !== null) {
                    return $webpPath;
                }

                // Fallback: store original extension via Storage
                $filename = time().'_'.Str::random(16).'.'.$extension;
                $relativePath = $folder.'/'.$filename;
                Storage::disk('public')->putFileAs($folder, $file, $filename);

                return '/storage/'.$relativePath;
            }
        }

        $url = trim((string) $request->input($urlKey, ''));
        if ($url !== '') {
            // Local relative paths — legacy /uploads and new /storage/uploads
            if (
                str_starts_with($url, '/uploads/')
                || str_starts_with($url, 'uploads/')
                || str_starts_with($url, '/storage/')
                || str_starts_with($url, 'storage/')
                || str_starts_with($url, '/images/')
                || str_starts_with($url, 'images/')
            ) {
                return str_starts_with($url, '/') ? $url : '/'.$url;
            }

            $sanitized = filter_var($url, FILTER_SANITIZE_URL);
            $valid = filter_var($sanitized, FILTER_VALIDATE_URL);
            if ($valid && in_array(strtolower((string) parse_url($valid, PHP_URL_SCHEME)), ['http', 'https'], true)) {
                $host = strtolower((string) parse_url($valid, PHP_URL_HOST));

                // SSRF Guard: reject localhost, loopback, private & link-local ranges
                if (
                    $host === 'localhost' ||
                    $host === '127.0.0.1' ||
                    $host === '::1' ||
                    str_ends_with($host, '.local') ||
                    str_ends_with($host, '.internal') ||
                    filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false && filter_var($host, FILTER_VALIDATE_IP)
                ) {
                    return $fallback;
                }

                return $valid;
            }

            return $fallback;
        }

        return $fallback;
    }

    /**
     * Securely handle multiple image uploads (e.g. product photo gallery).
     * Same validation rules as handleImageUpload(); stored on the public disk.
     *
     * @param  string  $fileKey  Input key for the file[] array (e.g. 'gallery_files')
     * @param  string  $folder  Target folder on the public disk
     * @param  int  $maxSizeBytes  Max size per file (5MB default)
     * @param  int  $maxFiles  Max number of files accepted per request (10 default)
     * @return array<int, string> List of public URL paths (/storage/...)
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

        $folder = trim($folder, '/');
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

            if ($extension === 'svg' || ! in_array($extension, $allowedExtensions, true)) {
                throw ValidationException::withMessages([
                    $fileKey => ['Format gambar galeri tidak valid. Gunakan JPG, JPEG, PNG, WEBP, atau GIF.'],
                ]);
            }

            $mimeType = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (! in_array($mimeType, $allowedMimes, true)) {
                throw ValidationException::withMessages([
                    $fileKey => ['Salah satu file yang diunggah bukan file gambar yang sah.'],
                ]);
            }

            $storedPath = $this->encodeToWebp($file, $folder);

            if ($storedPath === null) {
                $filename = time().'_'.Str::random(16).'.'.$extension;
                $relativePath = $folder.'/'.$filename;
                Storage::disk('public')->putFileAs($folder, $file, $filename);
                $storedPath = '/storage/'.$relativePath;
            }

            $stored[] = $storedPath;
        }

        return $stored;
    }

    /**
     * Securely handle PDF datasheet / document upload or URL fallback.
     */
    protected function handlePdfUpload(
        Request $request,
        string $fileKey = 'datasheet_file',
        string $urlKey = 'datasheet_url',
        ?string $current = null,
        string $folder = 'datasheets',
        int $maxSizeBytes = 10485760
    ): ?string {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            if ($file && $file->isValid()) {
                if ($file->getSize() > $maxSizeBytes) {
                    throw ValidationException::withMessages([
                        $fileKey => ['Ukuran file PDF terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                    ]);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                if ($extension !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
                    throw ValidationException::withMessages([
                        $fileKey => ['Format dokumen harus berupa file PDF.'],
                    ]);
                }

                $filename = 'datasheet_'.time().'_'.Str::random(12).'.pdf';
                $relativePath = trim($folder, '/').'/'.$filename;
                Storage::disk('public')->putFileAs(trim($folder, '/'), $file, $filename);

                return '/storage/'.$relativePath;
            }
        }

        $urlInput = trim((string) $request->input($urlKey, ''));

        if ($urlInput !== '') {
            // Only accept https:// URLs or existing /storage/ relative paths
            if (
                str_starts_with($urlInput, '/storage/')
                || str_starts_with($urlInput, 'storage/')
            ) {
                return $urlInput;
            }

            if (preg_match('/^https?:\/\//i', $urlInput)) {
                return $urlInput;
            }

            // Reject anything else (javascript:, data:, ftp:, bare paths, etc.)
            return $current;
        }

        return $current;
    }

    /**
     * Resize and re-encode an image resource to WebP format, storing it on the public disk.
     *
     * Strips EXIF/metadata, downsizes images wider than 1920 px while preserving aspect ratio,
     * and encodes at quality 82.  Returns the public-facing path (/storage/…) on success,
     * or null if GD is unavailable or encoding fails (caller should fall back to storing the
     * original file).
     *
     * @param  string  $folder  Target sub-folder on the public disk (already trimmed of slashes)
     */
    private function encodeToWebp(UploadedFile $file, string $folder): ?string
    {
        if (! function_exists('imagewebp') || ! function_exists('imagecreatefromstring')) {
            return null;
        }

        $rawContent = file_get_contents($file->getRealPath());
        $img = @imagecreatefromstring($rawContent);

        if ($img === false) {
            return null;
        }

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
        $relativePath = $folder.'/'.$webpFilename;

        ob_start();
        imagewebp($img, null, 82);
        $binary = ob_get_clean();
        imagedestroy($img);

        if ($binary === false || $binary === '') {
            return null;
        }

        Storage::disk('public')->put($relativePath, $binary);

        return '/storage/'.$relativePath;
    }
}
