<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|alpha_dash|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'image_url' => 'nullable|url',
        ];
    }
}
