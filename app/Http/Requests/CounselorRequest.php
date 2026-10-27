<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounselorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $rules = [
            'nip_nuptk' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'title' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'create_account' => ['nullable', 'boolean'],
        ];

        if ($this->boolean('create_account')) {
            $rules['username'] = ['required', 'string', 'min:3', 'max:50', 'unique:users,username'];
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Guru BK wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'username.required' => 'Username akun login wajib diisi jika membuat akun.',
            'username.unique' => 'Username ini sudah digunakan.',
            'password.required' => 'Password akun login wajib diisi jika membuat akun.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }
}
