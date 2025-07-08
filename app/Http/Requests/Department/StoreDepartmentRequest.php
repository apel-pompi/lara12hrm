<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'deptname' =>'required',
            'active' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'deptname.required' =>'Department name is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'deptname' =>'Department name',
            'active' =>'Status',
        ];
    }
}
