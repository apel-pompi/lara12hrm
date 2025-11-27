<?php

namespace App\Http\Requests\AttenDeduct;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttenDeductRequest extends FormRequest
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
            'starttime' =>'required',
            'endtime' =>'required',
            'deduct' => 'required',
            'active' =>'required',
            'user_id' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' =>'Branch name is not empty',
            'starttime.required' =>'Start time is not empty',
            'endtime.required' =>'End time is not empty',
            'deduct.required' =>'Deduct day is not empty',
            'active.required' =>'Status is not empty',
            'user_id.nullable' => 'User Name'
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' =>'Branch name',
            'starttime' =>'Start time ',
            'endtime' =>'End Time',
            'deduct' =>'Deduct Day',
            'active' =>'Active',
            'user_id' => 'User'
        ];
    }
}
