<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequest extends FormRequest
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
            'name'               => ['required', 'string', 'max:255'],
            'brn'                => ['nullable', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'fax'                => ['nullable', 'string', 'max:100'],
            'phoneno'            => ['nullable', 'string', 'max:50'],
            'phone_code'         => ['nullable', 'string', 'max:10'],
            'master_cat_id'      => ['required', 'integer', 'exists:master_categories,id'],
            'partner_type_id'    => ['required', 'integer', 'exists:partner_type_setups,id'],
            'currency'           => ['nullable', 'integer'],
            'country_id'         => ['required', 'integer', 'exists:countries,id'],
            'state_id'           => ['required', 'integer', 'exists:states,id'],
            'city_id'            => ['required', 'integer', 'exists:cities,id'],
            'workflow_id'        => ['required', 'array'],
            'workflow_id.*'      => ['integer', 'exists:workflows,id'],
            'photo'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:1024'],
            'partner_branch_id'  => ['nullable', 'string', 'max:255'],
            'website'            => ['nullable', 'string', 'max:255'],
            'active'             => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Partner Name cannot be empty',
            'name.string'   => 'Partner Name must be a string',
            'name.max'      => 'Partner Name cannot exceed 255 characters',

            'brn.string'    => 'Business Registration Number must be a string',
            'brn.max'       => 'Business Registration Number cannot exceed 255 characters',

            'email.required' => 'Email cannot be empty',
            'email.email'   => 'Email must be a valid email address',
            'email.max'     => 'Email cannot exceed 255 characters',

            'fax.string'    => 'Fax must be a string',
            'fax.max'       => 'Fax cannot exceed 100 characters',

            'phoneno.string' => 'Phone must be a string',
            'phoneno.max'   => 'Phone cannot exceed 50 characters',

            'phone_code.string' => 'Phone code must be a string',
            'phone_code.max'    => 'Phone code cannot exceed 10 characters',

            'master_cat_id.required'   => 'Master Category cannot be empty',
            'master_cat_id.integer'    => 'Master Category must be a valid integer',
            'master_cat_id.exists'     => 'Selected Master Category does not exist',

            'partner_type_id.required' => 'Partner Type cannot be empty',
            'partner_type_id.integer'  => 'Partner Type must be a valid integer',
            'partner_type_id.exists'   => 'Selected Partner Type does not exist',

            'currency.integer'         => 'Currency must be a valid integer',
            'currency.exists'          => 'Selected Currency does not exist',

            'country_id.required'      => 'Country cannot be empty',
            'country_id.integer'       => 'Country must be a valid integer',
            'country_id.exists'        => 'Selected Country does not exist',

            'state_id.required'        => 'State cannot be empty',
            'state_id.integer'         => 'State must be a valid integer',
            'state_id.exists'          => 'Selected State does not exist',

            'city_id.required'         => 'City cannot be empty',
            'city_id.integer'          => 'City must be a valid integer',
            'city_id.exists'           => 'Selected City does not exist',

            'workflow_id.array'        => 'Workflows must be an array',
            'workflow_id.*.integer'    => 'Each Workflow must be a valid integer',
            'workflow_id.*.exists'     => 'Selected Workflow does not exist',

            'partner_branch_id.string' => 'Partner Branch must be a string',
            'partner_branch_id.max'    => 'Partner Branch cannot exceed 255 characters',

            'photo.image'              => 'Photo must be an image',
            'photo.mimes'              => 'Photo must be jpg, jpeg, png, gif, or webp',
            'photo.max'                => 'Photo cannot exceed 2MB',

            'website.string'           => 'Website must be a string',
            'website.max'              => 'Website cannot exceed 255 characters',

            'active.boolean'           => 'Status must be true or false',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'               => 'Partner Name',
            'brn'                => 'Business Registration Number',
            'email'              => 'Email',
            'fax'                => 'Fax',
            'phoneno'            => 'Phone',
            'phone_code'         => 'Phone Code',
            'master_cat_id'      => 'Master Category',
            'partner_type_id'    => 'Partner Type',
            'currency_id'        => 'Currency',
            'country_id'         => 'Country',
            'state_id'           => 'State',
            'city_id'            => 'City',
            'workflow_id'        => 'Workflows',
            'photo'              => 'Photo',
            'partner_branch_id' => 'Partner Branch',
            'website'            => 'Website',
            'active'             => 'Status',
        ];
    }
}
