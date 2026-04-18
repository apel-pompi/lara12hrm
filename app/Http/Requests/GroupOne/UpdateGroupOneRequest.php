<?php

namespace App\Http\Requests\GroupOne;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupOneRequest extends FormRequest
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
        $groupOneId = $this->route('groupOne')->id;
        return [

            'code' => [
                'required',
                'integer',
                'unique:group_ones,code,' . $groupOneId
            ],

            'description' => [
                'required',
                'string',
                'max:255',
                'unique:group_ones,description,' . $groupOneId
            ],

            'active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
