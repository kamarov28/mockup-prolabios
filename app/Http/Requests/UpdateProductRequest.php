<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'sub_category' => ['nullable', 'string', 'max:255'],
            'catalog' => ['nullable', 'string', 'max:255'],
            'principal_id' => ['nullable', 'integer', 'exists:principals,id'],
            'sector' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:2000', 'regex:/^(\/|https?:\/\/)/i'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Judul Produk',
            'category' => 'Kategori',
            'sub_category' => 'Sub Kategori',
            'catalog' => 'Nomor Katalog',
            'sector' => 'Sektor',
            'description' => 'Deskripsi',
            'price' => 'Harga',
            'stock' => 'Stok',
            'image_file' => 'Berkas Gambar',
            'image_url' => 'URL Gambar',
        ];
    }
}
