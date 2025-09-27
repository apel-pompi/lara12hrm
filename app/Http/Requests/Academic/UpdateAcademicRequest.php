<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicRequest extends FormRequest
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
            'name'    => ['required', 'string', 'max:255', 'unique:academics,name'],
            'adddate' => ['sometimes', 'required', 'date'],
            'user_id' => ['sometimes', 'required', 'exists:users,id'],
            'active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Academic name is required when updating.',
            'adddate.required' => 'Add date is required when updating.',
            'user_id.required' => 'User is required when updating.',
            'user_id.exists'   => 'Selected user does not exist.',
            'active.required'  => 'Active status is required when updating.',
        ];
    }
}
