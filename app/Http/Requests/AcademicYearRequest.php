<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('tahun_ajaran')?->id;

        return [
            'name' => ['required', 'string', 'max:50', 'regex:/^[0-9]{4}\/[0-9]{4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tahun ajaran wajib diisi.',
            'name.regex' => 'Format tahun ajaran harus berupa format Tahun/Tahun (contoh: 2025/2026).',
        ];
    }
}
