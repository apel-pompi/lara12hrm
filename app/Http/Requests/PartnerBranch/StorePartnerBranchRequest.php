<?php

namespace App\Http\Requests\PartnerBranch;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerBranchRequest extends FormRequest
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
            'branch_name'     => ['required', 'string', 'max:255'],
            'branch_email'    => ['required', 'email', 'max:255'],
            'partner_id'      => ['required', 'exists:partners,id'],
            'branch_state_id' => ['required', 'exists:states,id'],
            'branch_city_id'  => ['nullable', 'exists:cities,id'],
            'branch_phoneno'  => ['nullable', 'string', 'max:50'],
            'user_id'         => ['nullable', 'exists:users,id'],
            'active'          => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_name.required'     => 'Please enter the branch name.',
            'branch_email.required'    => 'Branch email is required.',
            'branch_email.email'       => 'Please enter a valid email address.',
            'partner_id.required'      => 'You must select a partner.',
            'branch_state_id.required' => 'Please select a state.',
            'branch_city_id.exists'    => 'Selected city is not valid.',
            'user_id'         => 'User Name',
            'active.boolean'           => 'Active field must be true or false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_name'     => 'Branch Name',
            'branch_email'    => 'Branch Email',
            'partner_id'      => 'Partner',
            'branch_state_id' => 'State',
            'branch_city_id'  => 'City',
            'branch_phoneno'  => 'Phone Number',
            'user_id'         => 'Assigned User',
            'active'          => 'Status',
        ];
    }
}
