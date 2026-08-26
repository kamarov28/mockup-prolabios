<?php

namespace App\Http\Requests;

use App\Models\Rfq;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRfqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(Rfq::statusOptions()))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status',
            'admin_notes' => 'Catatan internal',
        ];
    }
}
