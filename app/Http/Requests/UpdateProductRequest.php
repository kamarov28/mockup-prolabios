<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('price') && is_string($this->price)) {
            $this->merge([
                'price' => str_replace(['.', ' '], '', $this->price),
            ]);
        }

        if ($this->has('sectors') && is_array($this->sectors)) {
            $this->merge([
                'sector' => implode(',', array_filter($this->sectors)),
            ]);
        }

        if ($this->hasFile('gallery_files')) {
            $files = array_filter((array) $this->file('gallery_files'), fn ($f) => $f && $f->isValid());
            $this->files->set('gallery_files', $files);
        }

        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'title')->ignore($id),
            ],
            'category' => ['required', 'string', 'max:255'],
            'sub_category' => ['nullable', 'string', 'max:255'],
            'packaging' => ['nullable', 'string', 'max:255'],
            'function' => ['nullable', 'string', 'max:1000'],
            'reference_method' => ['nullable', 'string', 'max:500'],
            'catalog' => ['nullable', 'string', 'max:255'],
            'principal_id' => ['nullable', 'integer', 'exists:principals,id'],
            'datasheet_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'datasheet_url' => ['nullable', 'string', 'max:500', 'regex:/^(\/|https?:\/\/)/i'],
            'sector' => ['nullable'],
            'sectors' => ['nullable', 'array'],
            'sectors.*' => ['string'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:2000', 'regex:/^(\/|https?:\/\/)/i'],
            'gallery_files' => ['nullable', 'array', 'max:10'],
            'gallery_files.*' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['string'],
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

    public function messages(): array
    {
        return [
            'title.unique' => 'Nama produk ini sudah digunakan. Gunakan nama yang berbeda.',
        ];
    }
}
