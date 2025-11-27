<?php

namespace App\Http\Requests\GroupTwo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupTwoRequest extends FormRequest
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
        $groupTwoId = $this->route('groupTwo')->id;
        return [
            'groupone' => ['required', 'exists:group_ones,groupone'],
            'grouptwo' => [
                'required',
                'integer',
                'unique:group_twos,grouptwo,' . $groupTwoId
            ],
            'description' => [
                'required',
                'string',
                'max:255',
                'unique:group_twos,description,' . $groupTwoId
            ],

            'active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
