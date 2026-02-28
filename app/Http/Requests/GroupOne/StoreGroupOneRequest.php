<?php

namespace App\Http\Requests\GroupOne;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupOneRequest extends FormRequest
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
            'code' => ['required', 'integer', 'unique:group_ones,code'],
            'description' => ['required', 'string', 'max:255', 'unique:group_ones,description'],
            'active' => ['nullable', 'integer', 'in:0,1'], // or just integer
        ];
    }
}
