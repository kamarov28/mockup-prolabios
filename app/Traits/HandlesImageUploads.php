<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
        $rawFile = $request->file($fileKey);

        if ($rawFile !== null) {
            Log::info("HandlesImageUploads::handleImageUpload - File diterima pada key [{$fileKey}]", [
                'file_key' => $fileKey,
                'original_name' => $rawFile->getClientOriginalName(),
                'client_mime' => $rawFile->getClientMimeType(),
                'size_bytes' => $rawFile->getSize(),
                'php_upload_error_code' => $rawFile->getError(),
                'php_upload_error_message' => $rawFile->getErrorMessage(),
                'is_valid' => $rawFile->isValid(),
            ]);

            if (! $rawFile->isValid()) {
                Log::warning("HandlesImageUploads::handleImageUpload - File [{$fileKey}] tidak valid menurut PHP upload (kemungkinan upload_max_filesize atau post_max_size terlampaui)", [
                    'error_code' => $rawFile->getError(),
                    'error_message' => $rawFile->getErrorMessage(),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                ]);
            }
        } else {
            Log::info("HandlesImageUploads::handleImageUpload - Tidak ada file terlampir untuk key [{$fileKey}]", [
                'all_uploaded_keys' => array_keys($request->allFiles()),
            ]);
        }

        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);

            if ($file->isValid()) {
                if ($file->getSize() > $maxSizeBytes) {
                    Log::warning("HandlesImageUploads::handleImageUpload - Ukuran file [{$fileKey}] melebihi batas maksimum", [
                        'file_size' => $file->getSize(),
                        'max_size' => $maxSizeBytes,
                    ]);
                    throw ValidationException::withMessages([
                        $fileKey => ['Ukuran file gambar terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                    ]);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                if ($extension === 'svg') {
                    Log::warning('HandlesImageUploads::handleImageUpload - Format SVG ditolak demi keamanan');
                    throw ValidationException::withMessages([
                        $fileKey => ['File format SVG tidak diizinkan demi alasan keamanan. Gunakan format JPG, PNG, atau WebP.'],
                    ]);
                }

                if (! in_array($extension, $allowedExtensions, true)) {
                    Log::warning("HandlesImageUploads::handleImageUpload - Ekstensi file [{$extension}] tidak diizinkan", [
                        'allowed' => $allowedExtensions,
                    ]);
                    throw ValidationException::withMessages([
                        $fileKey => ['Format gambar tidak valid. Gunakan format JPG, JPEG, PNG, WEBP, atau GIF.'],
                    ]);
                }

                $mimeType = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (! in_array($mimeType, $allowedMimes, true)) {
                    Log::warning("HandlesImageUploads::handleImageUpload - Mime type [{$mimeType}] tidak valid", [
                        'allowed' => $allowedMimes,
                    ]);
                    throw ValidationException::withMessages([
                        $fileKey => ['Tipe file yang diunggah bukan file gambar yang sah.'],
                    ]);
                }

                Log::info("HandlesImageUploads::handleImageUpload - Validasi file [{$fileKey}] lolos", [
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size_bytes' => $file->getSize(),
                ]);

                // Prefer WebP re-encode (resize oversized images, strip metadata)
                try {
                    $webpPath = $this->encodeToWebp($file, $folder);
                    if ($webpPath !== null) {
                        Log::info('HandlesImageUploads::handleImageUpload - Gambar berhasil di-encode dan disimpan via WebP', [
                            'path' => $webpPath,
                            'disk' => 'public',
                        ]);

                        return $webpPath;
                    }
                } catch (\Throwable $e) {
                    Log::error("HandlesImageUploads::handleImageUpload - Exception saat encodeToWebp pada key [{$fileKey}]", [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                Log::warning('HandlesImageUploads::handleImageUpload - encodeToWebp mengembalikan null, mencoba fallback simpan ekstensi asli', [
                    'extension' => $extension,
                ]);

                // Fallback: store original extension via Storage
                try {
                    $filename = time().'_'.Str::random(16).'.'.$extension;
                    $relativePath = $folder.'/'.$filename;
                    $stored = Storage::disk('public')->putFileAs($folder, $file, $filename);
                    $exists = Storage::disk('public')->exists($relativePath);

                    if (! $stored || ! $exists) {
                        Log::error('HandlesImageUploads::handleImageUpload - Gagal menyimpan file fallback ke disk public', [
                            'folder' => $folder,
                            'filename' => $filename,
                            'putFileAs_return' => $stored,
                            'exists' => $exists,
                            'root_path' => config('filesystems.disks.public.root'),
                        ]);

                        return $fallback;
                    }

                    $fallbackPath = '/storage/'.$relativePath;
                    Log::info('HandlesImageUploads::handleImageUpload - File berhasil disimpan ke storage via fallback ekstensi asli', [
                        'path' => $fallbackPath,
                        'size_bytes' => Storage::disk('public')->size($relativePath),
                    ]);

                    return $fallbackPath;
                } catch (\Throwable $e) {
                    Log::error('HandlesImageUploads::handleImageUpload - Exception saat menyimpan file fallback ke storage', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return $fallback;
                }
            }
        }

        $url = trim((string) $request->input($urlKey, ''));
        if ($url !== '') {
            Log::info("HandlesImageUploads::handleImageUpload - Mengecek input URL gambar dari key [{$urlKey}]", ['url' => $url]);

            // Local relative paths — legacy /uploads and new /storage/uploads
            if (
                str_starts_with($url, '/uploads/')
                || str_starts_with($url, 'uploads/')
                || str_starts_with($url, '/storage/')
                || str_starts_with($url, 'storage/')
                || str_starts_with($url, '/images/')
                || str_starts_with($url, 'images/')
            ) {
                $resolved = str_starts_with($url, '/') ? $url : '/'.$url;
                Log::info('HandlesImageUploads::handleImageUpload - Menggunakan path lokal dari input URL', ['resolved' => $resolved]);

                return $resolved;
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
                    Log::warning('HandlesImageUploads::handleImageUpload - Host URL eksternal ditolak oleh SSRF Guard', ['host' => $host]);

                    return $fallback;
                }

                Log::info('HandlesImageUploads::handleImageUpload - Menggunakan URL eksternal valid', ['url' => $valid]);

                return $valid;
            }

            Log::warning('HandlesImageUploads::handleImageUpload - Input URL tidak valid, memakai fallback', ['input' => $url, 'fallback' => $fallback]);

            return $fallback;
        }

        Log::info('HandlesImageUploads::handleImageUpload - Tidak ada file maupun URL, memakai fallback', [
            'fileKey' => $fileKey,
            'fallback' => $fallback,
        ]);

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
        $folder = trim($folder, '/');
        $rawFiles = $request->file($fileKey);

        if ($rawFiles !== null) {
            $count = is_array($rawFiles) ? count($rawFiles) : 1;
            Log::info("HandlesImageUploads::handleMultipleImageUploads - File diterima untuk key [{$fileKey}]", [
                'count' => $count,
            ]);
        }

        if (! $request->hasFile($fileKey)) {
            Log::info("HandlesImageUploads::handleMultipleImageUploads - Tidak ada file terlampir atau valid untuk key [{$fileKey}]");

            return [];
        }

        $files = $request->file($fileKey);
        if (! is_array($files)) {
            $files = [$files];
        }

        $files = array_slice($files, 0, $maxFiles);
        $stored = [];

        foreach ($files as $index => $file) {
            if (! $file || ! $file->isValid()) {
                Log::warning("HandlesImageUploads::handleMultipleImageUploads - File galeri index [{$index}] tidak valid menurut PHP upload", [
                    'error_code' => $file?->getError(),
                    'error_message' => $file?->getErrorMessage(),
                ]);

                continue;
            }

            if ($file->getSize() > $maxSizeBytes) {
                Log::warning("HandlesImageUploads::handleMultipleImageUploads - File galeri index [{$index}] melebihi maxSizeBytes");
                throw ValidationException::withMessages([
                    $fileKey => ['Salah satu file gambar galeri berukuran terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if ($extension === 'svg' || ! in_array($extension, $allowedExtensions, true)) {
                Log::warning("HandlesImageUploads::handleMultipleImageUploads - Ekstensi galeri index [{$index}] [{$extension}] tidak valid");
                throw ValidationException::withMessages([
                    $fileKey => ['Format gambar galeri tidak valid. Gunakan JPG, JPEG, PNG, WEBP, atau GIF.'],
                ]);
            }

            $mimeType = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            if (! in_array($mimeType, $allowedMimes, true)) {
                Log::warning("HandlesImageUploads::handleMultipleImageUploads - Mime type galeri index [{$index}] [{$mimeType}] tidak valid");
                throw ValidationException::withMessages([
                    $fileKey => ['Salah satu file yang diunggah bukan file gambar yang sah.'],
                ]);
            }

            Log::info("HandlesImageUploads::handleMultipleImageUploads - Validasi galeri index [{$index}] lolos", [
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size_bytes' => $file->getSize(),
            ]);

            try {
                $storedPath = $this->encodeToWebp($file, $folder);
            } catch (\Throwable $e) {
                Log::error("HandlesImageUploads::handleMultipleImageUploads - Exception saat encodeToWebp galeri index [{$index}]", [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $storedPath = null;
            }

            if ($storedPath === null) {
                try {
                    $filename = time().'_'.Str::random(16).'.'.$extension;
                    $relativePath = $folder.'/'.$filename;
                    $storedFallback = Storage::disk('public')->putFileAs($folder, $file, $filename);
                    $exists = Storage::disk('public')->exists($relativePath);

                    if ($storedFallback && $exists) {
                        $storedPath = '/storage/'.$relativePath;
                        Log::info("HandlesImageUploads::handleMultipleImageUploads - File galeri index [{$index}] disimpan via fallback ekstensi", [
                            'path' => $storedPath,
                        ]);
                    } else {
                        Log::error("HandlesImageUploads::handleMultipleImageUploads - Gagal menyimpan file galeri index [{$index}] ke disk public", [
                            'folder' => $folder,
                            'filename' => $filename,
                            'stored_return' => $storedFallback,
                            'exists' => $exists,
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::error("HandlesImageUploads::handleMultipleImageUploads - Exception saat simpan fallback galeri index [{$index}]", [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            if ($storedPath !== null) {
                $stored[] = $storedPath;
            }
        }

        Log::info('HandlesImageUploads::handleMultipleImageUploads - Selesai memproses galeri', [
            'total_files_stored' => count($stored),
            'stored_paths' => $stored,
        ]);

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
                    Log::warning("HandlesImageUploads::handlePdfUpload - File PDF [{$fileKey}] melebihi maxSizeBytes");
                    throw ValidationException::withMessages([
                        $fileKey => ['Ukuran file PDF terlalu besar (maksimal '.round($maxSizeBytes / 1024 / 1024).'MB).'],
                    ]);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                if ($extension !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
                    Log::warning('HandlesImageUploads::handlePdfUpload - Format dokumen bukan PDF', [
                        'ext' => $extension,
                        'mime' => $file->getMimeType(),
                    ]);
                    throw ValidationException::withMessages([
                        $fileKey => ['Format dokumen harus berupa file PDF.'],
                    ]);
                }

                try {
                    $filename = 'datasheet_'.time().'_'.Str::random(12).'.pdf';
                    $relativePath = trim($folder, '/').'/'.$filename;
                    $stored = Storage::disk('public')->putFileAs(trim($folder, '/'), $file, $filename);
                    $exists = Storage::disk('public')->exists($relativePath);

                    if (! $stored || ! $exists) {
                        Log::error('HandlesImageUploads::handlePdfUpload - Gagal menyimpan file PDF ke disk public', [
                            'folder' => $folder,
                            'filename' => $filename,
                            'stored_return' => $stored,
                            'exists' => $exists,
                        ]);

                        return $current;
                    }

                    $publicPath = '/storage/'.$relativePath;
                    Log::info('HandlesImageUploads::handlePdfUpload - File PDF berhasil disimpan ke storage', [
                        'path' => $publicPath,
                        'size_bytes' => Storage::disk('public')->size($relativePath),
                    ]);

                    return $publicPath;
                } catch (\Throwable $e) {
                    Log::error('HandlesImageUploads::handlePdfUpload - Exception saat menyimpan file PDF', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return $current;
                }
            }
        }

        $urlInput = trim((string) $request->input($urlKey, ''));

        if ($urlInput !== '') {
            // Only accept https:// URLs or existing /storage/ relative paths
            if (
                str_starts_with($urlInput, '/storage/')
                || str_starts_with($urlInput, 'storage/')
            ) {
                Log::info('HandlesImageUploads::handlePdfUpload - Menggunakan path lokal untuk PDF', ['path' => $urlInput]);

                return $urlInput;
            }

            if (preg_match('/^https?:\/\//i', $urlInput)) {
                Log::info('HandlesImageUploads::handlePdfUpload - Menggunakan URL eksternal untuk PDF', ['url' => $urlInput]);

                return $urlInput;
            }

            Log::warning('HandlesImageUploads::handlePdfUpload - Input URL PDF tidak valid, memakai current', ['input' => $urlInput]);

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
        try {
            if (! function_exists('imagewebp') || ! function_exists('imagecreatefromstring')) {
                Log::warning('HandlesImageUploads::encodeToWebp - Fungsi GD imagewebp / imagecreatefromstring tidak tersedia di PHP server', [
                    'function_imagewebp' => function_exists('imagewebp'),
                    'function_imagecreatefromstring' => function_exists('imagecreatefromstring'),
                    'gd_info' => function_exists('gd_info') ? gd_info() : 'GD extension not installed',
                ]);

                return null;
            }

            $realPath = $file->getRealPath();
            if (! $realPath || ! file_exists($realPath)) {
                Log::warning('HandlesImageUploads::encodeToWebp - getRealPath() tidak ditemukan atau file temporary hilang', [
                    'real_path' => $realPath,
                ]);

                return null;
            }

            $rawContent = file_get_contents($realPath);
            if ($rawContent === false || $rawContent === '') {
                Log::warning('HandlesImageUploads::encodeToWebp - file_get_contents mengembalikan konten kosong dari temporary file', [
                    'real_path' => $realPath,
                ]);

                return null;
            }

            $img = @imagecreatefromstring($rawContent);

            if ($img === false) {
                Log::warning('HandlesImageUploads::encodeToWebp - imagecreatefromstring gagal mengurai gambar dari raw content', [
                    'content_len' => strlen($rawContent),
                ]);

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

                Log::info('HandlesImageUploads::encodeToWebp - Gambar di-resize ke resolusi proporsional', [
                    'original_dimensions' => "{$width}x{$height}",
                    'resized_dimensions' => "{$newWidth}x{$newHeight}",
                ]);
            }

            $webpFilename = time().'_'.Str::random(16).'.webp';
            $relativePath = $folder.'/'.$webpFilename;

            if (! imageistruecolor($img)) {
                imagepalettetotruecolor($img);
                Log::info('HandlesImageUploads::encodeToWebp - Gambar palette/indexed dikonversi ke truecolor');
            }

            ob_start();
            $encodeOk = imagewebp($img, null, 82);
            $binary = ob_get_clean();
            imagedestroy($img);

            if (! $encodeOk || $binary === false || $binary === '') {
                Log::warning('HandlesImageUploads::encodeToWebp - imagewebp() gagal memproduksi binary WebP', [
                    'encode_return' => $encodeOk,
                    'binary_length' => is_string($binary) ? strlen($binary) : 'not_string',
                ]);

                return null;
            }

            Log::info('HandlesImageUploads::encodeToWebp - Setelah proses encoding ke WebP selesai', [
                'binary_size_bytes' => strlen($binary),
                'target_relative_path' => $relativePath,
            ]);

            $stored = Storage::disk('public')->put($relativePath, $binary);
            $exists = Storage::disk('public')->exists($relativePath);

            if (! $stored || ! $exists) {
                Log::error('HandlesImageUploads::encodeToWebp - Gagal menyimpan file WebP ke disk public Storage', [
                    'relative_path' => $relativePath,
                    'storage_put_return' => $stored,
                    'exists_on_disk' => $exists,
                    'disk_root' => config('filesystems.disks.public.root'),
                ]);

                return null;
            }

            $publicUrl = '/storage/'.$relativePath;
            Log::info('HandlesImageUploads::encodeToWebp - Setelah file berhasil disimpan ke storage', [
                'public_url' => $publicUrl,
                'disk' => 'public',
                'stored_file_size' => Storage::disk('public')->size($relativePath),
            ]);

            return $publicUrl;
        } catch (\Throwable $e) {
            Log::error('HandlesImageUploads::encodeToWebp - Exception tak terduga saat encoding WebP', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
