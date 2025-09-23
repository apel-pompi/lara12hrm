<?php

namespace App\Http\Requests\StudentApplication;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentApplicationRequest extends FormRequest
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
            'student_id'         => ['required', 'exists:students,id'],
            'workflow_id'        => ['required', 'exists:workflows,id'],
            'partner_branch_id'  => ['required', 'exists:partner_branches,id'],
            'product_id'         => ['required', 'exists:products,id'],
            'stage'              => ['nullable', 'string', 'max:255'],
            'status'             => ['nullable', 'string', 'max:255'],
            'saleprice'          => ['nullable', 'numeric', 'min:0'],
            'user_id'            => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required'       => 'Please select a student.',
            'student_id.exists'         => 'The selected student is invalid.',
            
            'workflow_id.required'       => 'Please select a workflow.',
            'workflow_id.exists'         => 'The selected workflow is invalid.',

            'partner_branch_id.required' => 'Please select a partner branch.',
            'partner_branch_id.exists'   => 'The selected partner branch is invalid.',

            'product_id.required'        => 'Please select a product.',
            'product_id.exists'          => 'The selected product is invalid.',

            'stage.nullable'             => 'Stage',
            'status.nullable'            => 'Status',
            'saleprice.nullable'         => 'Sale price is required.',
            'saleprice.numeric'          => 'Sale price must be a number.',
            'user_id.nullable'           => 'User',
        ];
    }
}
