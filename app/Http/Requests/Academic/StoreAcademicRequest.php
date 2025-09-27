<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicRequest extends FormRequest
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
            'adddate' => ['nullable', 'date'],
            'user_id' => ['nullable', 'exists:users,id'],
            'active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Academic name is required.',
            'adddate.nullable' => 'Add date is required.',
            'user_id.nullable' => 'User is required.',
            'user_id.exists'   => 'Selected user does not exist.',
            'active.boolean' => 'Status must be true or false',
        ];
    }
}
