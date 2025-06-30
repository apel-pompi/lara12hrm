<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
            'branchname' =>'required',
            'description' =>'required',
            'person' =>'required',
            'designation' =>'required',
            'address' =>'required',
            'email' =>'required',
            'phone' =>'required',
            'active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'branchname.required' =>'Branch name is not empty',
            'description.required' =>'Description is not empty',
            'person.required' =>'Employee name is not empty',
            'designation.required' =>'Designation is not empty',
            'address.required' =>'Address is not empty',
            'email.required' =>'Email is not empty',
            'phone.required' =>'Phone is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'branchname' =>'Branch name',
            'description' =>'Description',
            'person' =>'Employee name',
            'designation' =>'Designation',
            'address' =>'Address',
            'email' =>'Email',
            'phone' =>'Phone No',
            'active' =>'Status',
        ];
    }
}
