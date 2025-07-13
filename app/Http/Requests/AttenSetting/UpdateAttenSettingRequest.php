<?php

namespace App\Http\Requests\AttenSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttenSettingRequest extends FormRequest
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
            'branch_id' =>'required',
            'pname' =>'required',
            'lname' =>'required',
            'ptime' => 'required',
            'ltime' => 'required',
            'active' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' =>'Branch name is not empty',
            'pname.required' =>'Present Name is not empty',
            'lname.required' =>'Late Name is not empty',
            'ptime.required' =>'Present Time is not empty',
            'ltime.required' =>'Late Time is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' =>'Branch name',
            'pname' =>'Present Name',
            'lname' =>'Late Time',
            'ptime' =>'Present Time',
            'ltime' =>'Late Time',
            'active' =>'Active',
        ];
    }
}
