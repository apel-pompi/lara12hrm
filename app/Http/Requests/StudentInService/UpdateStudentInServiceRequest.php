<?php

namespace App\Http\Requests\StudentInService;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentInServiceRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id'        => ['sometimes', 'exists:students,id'],
            'workflow_id'       => ['sometimes', 'exists:workflows,id'],
            'partner_branch_id' => ['sometimes', 'exists:partner_branches,id'],
            'product_id'        => ['sometimes', 'exists:products,id'],
            'startdate'        => ['nullable', 'date'],
            'enddate'           => ['nullable', 'date', 'after_or_equal:startdate'],
            'status'            => ['nullable', 'string', 'max:50'],
            'user_id'           => ['sometimes', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id'        => 'student',
            'workflow_id'       => 'workflow',
            'partner_branch_id' => 'partner branch',
            'product_id'        => 'product',
            'startdate'        => 'start date',
            'enddate'           => 'end date',
            'status'            => 'status',
            'user_id'           => 'user',
        ];
    }

    public function messages(): array
    {
        return [
            'enddate.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}
