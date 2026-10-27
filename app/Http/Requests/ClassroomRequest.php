<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:50'],
            'level' => ['required', 'string', 'max:20'],
            'major' => ['nullable', 'string', 'max:50'],
            'homeroom_teacher' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'name.required' => 'Nama rombel/kelas wajib diisi.',
            'level.required' => 'Tingkat/kelas wajib diisi.',
        ];
    }
}
