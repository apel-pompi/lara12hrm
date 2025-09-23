<?php

namespace App\Http\Requests\Default\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
        $transactionId = $this->route('transaction');
        return [
            'trnname_id'    => ['required', 'integer'],
            'trncode'    => ['required', 'string', 'max:255', 'unique:transactions,trncode,' . $transactionId],
            'branch_id'  => ['required', 'exists:branches,id'],
            'yearname'    => ['required', 'integer', 'digits:4'],
            'monthname'   => ['required', 'integer', 'between:1,12'],
            'lastnumber' => ['nullable', 'integer', 'min:0'],
            'increment'  => ['nullable', 'integer', 'min:0'],
            'user_id' => ['nullable', 'integer'],
            'active'     => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'trnname_id'    => 'transaction name',
            'trncode'    => 'transaction code',
            'branch_id'  => 'branch',
            'yearname'    => 'transaction year',
            'monthname'   => 'transaction month',
            'lastnumber' => 'last number',
            'increment'  => 'increment value',
            'user_id' => 'User Name',
            'active'     => 'status',
        ];
    }
}
