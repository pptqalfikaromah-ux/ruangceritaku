<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CounselorAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $assignmentId = $this->route('penugasan')?->id;

        return [
            'counselor_id' => ['required', 'exists:counselors,id'],
            'classroom_id' => [
                'required',
                'exists:classrooms,id',
                Rule::unique('counselor_assignments', 'classroom_id')
                    ->where(fn ($query) => $query->where('semester_id', $this->semester_id)->whereNull('deleted_at'))
                    ->ignore($assignmentId),
            ],
            'semester_id' => ['required', 'exists:semesters,id'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'counselor_id.required' => 'Guru BK wajib dipilih.',
            'classroom_id.required' => 'Rombel/Kelas wajib dipilih.',
            'classroom_id.unique' => 'Rombel ini sudah memiliki Guru BK pada semester yang dipilih.',
            'semester_id.required' => 'Semester penugasan wajib dipilih.',
        ];
    }
}
