<?php

namespace App\Http\Requests\Quoatations;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoatationsRequest extends FormRequest
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
            'name'    => ['required', 'string', 'max:255'],
            'adddate' => ['nullable', 'date'],
            'user_id' => ['exists:users,id'],
            'active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Stage name is required.',
            'name.string'      => 'Stage name must be a string.',
            'name.max'         => 'Stage name may not be greater than 255 characters.',
            'adddate.nullable' => 'Add date is required.',
            'adddate.date'     => 'Add date must be a valid date.',
            'user_id.exists'   => 'Selected user is invalid.',
            'active.boolean'   => 'Active field must be true or false.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'    => 'Stage Name',
            'adddate' => 'Add Date',
            'user_id' => 'User',
            'active'  => 'Active Status',
        ];
    }
}
