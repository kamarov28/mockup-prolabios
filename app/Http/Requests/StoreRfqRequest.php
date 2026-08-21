<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRfqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_wa'     => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'notes'        => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format alamat email tidak valid.',
            'company_name.required' => 'Nama instansi atau perusahaan wajib diisi.',
            'phone_wa.required'     => 'Nomor telepon / WhatsApp wajib diisi.',
            'phone_wa.regex'        => 'Nomor WhatsApp hanya boleh berisi angka, spasi, serta karakter + atau - (minimal 8 digit).',
            'notes.max'             => 'Catatan pengadaan maksimal 3000 karakter.',
        ];
    }
}
