<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'image_url' => 'nullable|string|max:2000|regex:/^(\/|https?:\/\/)/i',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Sektor',
            'description' => 'Deskripsi',
            'image_file' => 'Berkas Gambar',
            'image_url' => 'URL Gambar',
        ];
    }
}
