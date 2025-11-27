<?php

namespace App\Http\Requests\SalaryType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryTypeRequest extends FormRequest
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
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => 'required',
            'property' => 'required',
            'amounttype' => 'required',
            'percentage' => 'nullable',
            'amount' => 'nullable',
            'active' => 'nullable',
            'user_id' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch name is not empty',
            'name.required' => 'Salary Type is not empty',
            'property.required' => 'Property is not empty',
            'amounttype.required' => 'Type is not empty',
            'percentage' => 'Percentage',
            'amount' => 'Amount',
            'active' => 'Status is not empty',
            'user_id' => 'User',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' => 'Branch name',
            'name' => 'Salary Type',
            'property' => 'Property',
            'amounttype' => 'Type',
            'percentage' => 'Percentage',
            'amount' => 'Amount',
            'active' => 'Status',
            'user_id' => 'User',
        ];
    }
}
