<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyInfoRequest extends FormRequest
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
            'companyname' =>'',
            'address_one' =>'',
            'address_two' =>'',
            'company_phone' =>'',
            'company_email' =>'',
            'companylogo' =>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'companyname' =>'Company name is not empty',
            'address_one' =>'Company Address line 1 is not empty',
            'address_two' =>'Company Address line 2 is not empty',
            'company_phone' =>'Phone is not empty',
            'company_email' =>'Email is not empty',
            'companylogo' =>'Company Logo is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'companyname' =>'Company name',
            'address_one' =>'Company Address line 1',
            'address_two' =>'Company Address line 2',
            'company_phone' =>'Phone',
            'company_email' =>'Email',
            'companylogo' =>'Company Logo',
        ];
    }
}
