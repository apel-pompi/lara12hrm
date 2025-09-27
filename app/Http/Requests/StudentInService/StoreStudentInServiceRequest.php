<?php

namespace App\Http\Requests\StudentInService;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentInServiceRequest extends FormRequest
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
            'student_id'        => ['required', 'exists:students,id'],
            'workflow_id'       => ['required', 'exists:workflows,id'],
            'partner_branch_id' => ['required', 'exists:partner_branches,id'],
            'product_id'        => ['required', 'exists:products,id'],
            'startdate'        => ['nullable', 'date'],
            'enddate'           => ['nullable', 'date', 'after_or_equal:startdate'],
            'status'            => ['nullable', 'string', 'max:50'],
            'user_id'           => ['nullable', 'exists:users,id'],
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
            'student_id.required' => 'Please select a student.',
            'student_id.exists'   => 'The selected student is invalid.',
            'workflow_id.required' => 'Workflow is required.',
            'workflow_id.exists'   => 'The selected workflow is invalid.',
            'partner_branch_id.required' => 'Partner branch is required.',
            'partner_branch_id.exists'   => 'The selected partner branch is invalid.',
            'product_id.required' => 'Product is required.',
            'product_id.exists'   => 'The selected product is invalid.',
            'enddate.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}
