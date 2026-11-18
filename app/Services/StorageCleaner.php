<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Principal;
use App\Models\Product;
use App\Models\Sector;
use Illuminate\Support\Facades\Storage;

class StorageCleaner
{
    /**
     * Safely delete an uploaded file on the public disk (/storage/...) if no other DB record references it.
     */
    public static function deleteIfUnreferenced(?string $publicUrl): void
    {
        $path = self::extractRelativeStoragePath($publicUrl);
        if ($path === null) {
            return;
        }

        if (self::isStillReferenced('/storage/'.$path)) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Normalize /storage/... URL into relative disk path and block traversal.
     */
    public static function extractRelativeStoragePath(?string $publicUrl): ?string
    {
        if ($publicUrl === null) {
            return null;
        }

        $trimmed = trim($publicUrl);
        if ($trimmed === '' || str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return null;
        }

        $normalized = '/'.ltrim($trimmed, '/');
        if (! str_starts_with($normalized, '/storage/')) {
            return null;
        }

        $relative = substr($normalized, strlen('/storage/'));
        if ($relative === '' || str_contains($relative, '..') || str_contains($relative, "\0")) {
            return null;
        }

        return $relative;
    }

    /**
     * Check across Product, Post, Principal, and Sector tables before deleting physical files.
     */
    public static function isStillReferenced(string $storageUrl): bool
    {
        $altUrl = ltrim($storageUrl, '/');
        $candidates = [$storageUrl, $altUrl];

        if (
            Product::query()
                ->whereIn('image', $candidates)
                ->orWhereIn('datasheet_url', $candidates)
                ->orWhereIn('msds_url', $candidates)
                ->orWhere('gallery_images', 'like', '%'.addcslashes($storageUrl, '%_\\').'%')
                ->exists()
        ) {
            return true;
        }

        if (Post::query()->whereIn('image', $candidates)->exists()) {
            return true;
        }

        if (Principal::query()->whereIn('logo', $candidates)->exists()) {
            return true;
        }

        if (Sector::query()->whereIn('image', $candidates)->exists()) {
            return true;
        }

        return false;
    }
}
