<?php

namespace App\Http\Requests\GroupTwo;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupTwoRequest extends FormRequest
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
            'groupone' => ['required', 'integer', 'exists:group_ones,code'],
            'code' => ['required', 'integer', 'unique:group_twos,code'],
            'description' => ['required', 'string', 'max:255', 'unique:group_twos,description'],
            'active' => ['required', 'integer', 'in:0,1'], // or just integer
        ];
    }
}
