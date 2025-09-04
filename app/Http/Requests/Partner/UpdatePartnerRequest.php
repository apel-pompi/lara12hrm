<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerRequest extends FormRequest
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
            'name' => '',
            'workflow_id' => '',
            'master_cat_id' => '',
            'partner_type_id' => '',
            'state_id' => '',
            'city_id' => '',
            'brn' => '',
            'currency' => '',
            'phone' => '',
            'email' => '',
            'fax' => '',
            'website' => '',
            'photo' => '',
            'partner_branch_id' => '',
            'user_id' => '',
            'active' => '',
        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Partner Name is not empty',
            'workflow_id' => 'Workfloes is not empty',
            'master_cat_id' => 'Master Category is not empty',
            'partner_type_id' => 'partner Type is not empty',
            'state_id' => 'State is not empty',
            'city_id' => 'City is not empty',
            'brn' => 'Business Registration Number is not empty',
            'currency' => 'Currency is not empty',
            'phone' => 'Phone is not empty',
            'partner_branch_id' => 'Partner Branch is not empty',
            'user_id' => 'Username is not empty',
            'active' => 'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Partner Name',
            'workflow_id' => 'Workfloes',
            'master_cat_id' => 'Master Category',
            'partner_type_id' => 'partner Type',
            'state_id' => 'State',
            'city_id' => 'City',
            'brn' => 'Business Registration Number',
            'currency' => 'Currency',
            'phone' => 'Phone',
            'partner_branch_id' => 'Partner Branch',
            'user_id' => 'Username',
            'active' => 'Status',
        ];
    }
}
