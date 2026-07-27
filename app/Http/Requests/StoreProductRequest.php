<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'catalog' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'image_url' => 'nullable|url',
        ];
    }
}
