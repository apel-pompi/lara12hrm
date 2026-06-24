<?php

namespace App\Http\Requests\FacebookForm;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserWiseFormRequest extends FormRequest
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
        return [
            'form_id' => ['required', 'exists:facebook_forms,id', Rule::unique('user_wise_forms', 'form_id')],
            'team_id' => ['required', 'exists:users,id'],
            'counsilor_id' => ['required', 'array', 'min:1'],
            'counsilor_id.*' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:0,1'],
        ];
    }
}
