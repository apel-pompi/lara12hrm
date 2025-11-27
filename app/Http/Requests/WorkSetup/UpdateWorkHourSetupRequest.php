<?php

namespace App\Http\Requests\WorkSetup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkHourSetupRequest extends FormRequest
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
            'branch_id' =>['required', 'exists:branches,id'],
            'workhour' =>'required',
            'yearname' =>'required',
            'monthname' =>'required',
            'active' =>'nullable',
            'user_id' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' =>'Branch name is not empty',
            'workhour.required' =>'Working Hours is not empty',
            'yearname.required' =>'Working Year is not empty',
            'monthname.required' =>'Working Month is not empty',
            'active' =>'Status is not empty',
            'user_id' => 'User',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' =>'Branch name',
            'workhour' =>'Working Hours',
            'yearname' =>'Working Year',
            'monthname' =>'Working Month',
            'active' =>'Status',
            'user_id' => 'User',
        ];
    }
}
