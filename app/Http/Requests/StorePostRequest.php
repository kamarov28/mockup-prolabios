<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'image_url' => 'nullable|url',
        ];
    }
}
