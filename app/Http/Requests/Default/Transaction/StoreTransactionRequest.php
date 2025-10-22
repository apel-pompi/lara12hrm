<?php

namespace App\Http\Requests\Default\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:transactions,name'],
            'trncode'    => ['required', 'string', 'max:255', 'unique:transactions,trncode'],
            'lastnumber' => ['required', 'integer', 'min:0'],
            'increment'  => ['required', 'integer', 'min:0'],
            'user_id' => ['nullable', 'integer'],
            'adddate' => ['nullable','date'],
            'active'     => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'    => 'transaction name',
            'trncode'    => 'transaction code',
            'lastnumber' => 'last number',
            'increment'  => 'increment value',
            'user_id' => 'User Name',
            'adddate' => 'Add transaction Date',
            'active'     => 'status',
        ];
    }

    public function messages(): array
    {
        return [
            'trncode.unique' => 'This transaction code already exists.',
            'branch_id.exists' => 'Selected branch does not exist.',
            'yearname.digits' => 'Transaction year must be 4 digits.',
            'monthname.between' => 'Transaction month must be between 1 and 12.',
        ];
    }
}
