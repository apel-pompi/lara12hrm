<?php

namespace App\Http\Requests\GroupThree;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupThreeRequest extends FormRequest
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
            'groupone' => ['required', 'exists:group_ones,id'],
            'grouptwo' => ['required', 'exists:group_twos,id'],
            'code' => ['required', 'string', 'max:255', 'unique:group_threes,code'],
            'description' => ['required', 'string', 'max:255', 'unique:group_threes,description'],
            'active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
