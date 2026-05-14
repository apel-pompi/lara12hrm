<?php

namespace App\Http\Requests\GroupFour;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupFourRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $groupFourId = $this->route('groupFour')->id;
        return [
            'groupone' => ['required', 'exists:group_ones,id'],
            'grouptwo' => ['required', 'exists:group_twos,id'],
            'groupthree' => ['required', 'exists:group_threes,id'],
            'code' => ['required', 'string', 'max:255', 'unique:group_fours,code,' . $groupFourId],
            'description' => ['required', 'string', 'max:255', 'unique:group_fours,description,' . $groupFourId],
            'active' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
