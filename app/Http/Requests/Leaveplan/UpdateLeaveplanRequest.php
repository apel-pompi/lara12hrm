<?php

namespace App\Http\Requests\Leaveplan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveplanRequest extends FormRequest
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
            'leavename' =>'required',
            'leaveyear' =>'required',
            'leavedays' =>'required',
            'active' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'leavename.required' =>'Leave Plan is not empty',
            'leaveyear.required' =>'Leave Year is not empty',
            'leavedays.required' =>'Leave Days is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'leavename' =>'Leave Plan name',
            'leaveyear' =>'Leave Year',
            'leavedays' =>'Leave Days',
            'active' =>'Status',
        ];
    }
}
