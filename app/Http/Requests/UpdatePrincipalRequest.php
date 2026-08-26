<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePrincipalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:online,draft'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'logo_url' => ['nullable', 'string', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama prinsipal',
            'address' => 'Alamat',
            'status' => 'Status',
            'logo_file' => 'Logo',
            'logo_url' => 'URL logo',
        ];
    }
}
