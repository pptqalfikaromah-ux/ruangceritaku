<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File CSV wajib diunggah.',
            'file.mimes' => 'File harus berformat CSV.',
            'file.max' => 'Ukuran file CSV maksimal 5MB.',
        ];
    }
}
