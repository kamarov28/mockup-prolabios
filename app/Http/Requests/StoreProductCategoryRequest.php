<?php

namespace App\Http\Requests;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && (bool) Auth::user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'key' => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/|unique:product_categories,key',
            'parent_id' => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = ProductCategory::find($value);
                        if ($parent && ! is_null($parent->parent_id)) {
                            $fail('Subkategori tidak bisa dijadikan induk kategori (maksimal 2 tingkat hirarki).');
                        }
                    }
                },
            ],
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'key.regex' => 'Key hanya boleh berisi huruf kecil, angka, dan tanda hubung (-)',
            'key.unique' => 'Key ini sudah dipakai kategori lain.',
        ];
    }
}
