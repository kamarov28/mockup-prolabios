<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMediaController extends Controller
{
    use HandlesImageUploads;

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.required' => 'Pilih file gambar untuk diunggah.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung: JPG, JPEG, PNG, WEBP, atau GIF.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $path = $this->handleImageUpload(
            $request,
            'image',
            'image_url',
            null,
            'uploads/editor'
        );

        if (! $path) {
            return response()->json([
                'error' => 'Gagal menyimpan dan mengonversi gambar.',
            ], 422);
        }

        return response()->json([
            'url' => $path,
        ]);
    }
}
