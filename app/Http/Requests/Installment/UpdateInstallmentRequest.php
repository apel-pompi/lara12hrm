<?php

namespace App\Http\Requests\Installment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstallmentRequest extends FormRequest
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
            'name'    => ['required', 'string', 'max:255', 'unique:installments,name'],
            'active'  => ['nullable', 'boolean'],
        ];
    }

    public function message(): array
    {
        return [
            'name.required' => 'Fees Name cannot be empty',
            'name.string'   => 'Fees Name must be a string',
            'name.max'      => 'Fees Name cannot exceed 255 characters',
            'active.boolean' => 'Status must be true or false',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'    => 'Name',
            'active'  => 'Status',
        ];
    }
}
