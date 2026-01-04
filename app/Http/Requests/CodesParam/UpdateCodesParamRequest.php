<?php

namespace App\Http\Requests\CodesParam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCodesParamRequest extends FormRequest
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
            'type' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'accdisc' => ['nullable', 'string', 'max:255'],
            'cracc' => ['nullable', 'string', 'max:255'],
            'dracc' => ['nullable', 'string', 'max:255'],
            'props' => ['nullable', 'string', 'max:255'],
            'percent' => ['nullable', 'integer'],
            'acctax' => ['nullable', 'string', 'max:255'],
            'branch_id'    => 'required',
            'user_id'      => 'nullable|exists:users,id',
            'active' => ['nullable', 'integer', 'in:0,1'], // or just integer
        ];
    }
}
