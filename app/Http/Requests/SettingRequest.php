<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            // Profil Sekolah
            'school_name' => ['nullable', 'string', 'max:150'],
            'school_npsn' => ['nullable', 'string', 'max:30'],
            'school_level' => ['nullable', 'string', 'max:50'],
            'school_address' => ['nullable', 'string'],
            'school_phone' => ['nullable', 'string', 'max:30'],
            'school_email' => ['nullable', 'email', 'max:100'],
            'headmaster_name' => ['nullable', 'string', 'max:150'],
            'headmaster_nip' => ['nullable', 'string', 'max:50'],

            // Pengaturan Aplikasi
            'app_name' => ['nullable', 'string', 'max:100'],
            'app_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'theme_color' => ['nullable', 'string', 'max:30'],
            'app_footer' => ['nullable', 'string', 'max:200'],
            'ims_enabled' => ['nullable', 'in:0,1'],
        ];
    }
}
