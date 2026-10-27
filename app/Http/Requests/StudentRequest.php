<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $studentId = $this->route('murid')?->id;

        return [
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'nisn')->ignore($studentId)->whereNull('deleted_at'),
            ],
            'nis' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:150'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'guardian_name' => ['nullable', 'string', 'max:150'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['required', 'in:aktif,lulus,pindah,nonaktif'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'username' => [
                $studentId ? 'nullable' : 'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($this->route('murid')?->user_id),
            ],
            'password' => [
                $studentId ? 'nullable' : 'required',
                'string',
                'min:4',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN ini sudah terdaftar pada sistem.',
            'name.required' => 'Nama lengkap murid wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'username.required' => 'Username akun login murid wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'password.required' => 'Password akun login murid wajib diisi.',
            'password.min' => 'Password minimal 4 karakter.',
        ];
    }
}
