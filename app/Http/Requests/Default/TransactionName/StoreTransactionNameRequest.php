<?php

namespace App\Http\Requests\Default\TransactionName;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionNameRequest extends FormRequest
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
            'adddate'  => 'nullable|date',
            'user_id'  => 'nullable|exists:users,id',
            'active'   => 'nullable|boolean',
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
            'adddate.nullable' => 'Please provide the add date.',
            'user_id.nullable' => 'Please select a user.',
        ];
    }
}
