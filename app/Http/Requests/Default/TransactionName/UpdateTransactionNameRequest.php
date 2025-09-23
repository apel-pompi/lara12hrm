<?php

namespace App\Http\Requests\Default\TransactionName;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionNameRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'adddate'  => 'required|date',
            'user_id'  => 'required|exists:users,id',
            'active'   => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'    => 'Transaction Name',
            'adddate' => 'Added Date',
            'user_id' => 'User',
            'active'  => 'Status',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'The transaction name is required.',
            'adddate.required' => 'Please provide the add date.',
            'user_id.required' => 'Please select a user.',
        ];
    }
}
