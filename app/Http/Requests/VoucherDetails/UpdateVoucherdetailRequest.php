<?php

namespace App\Http\Requests\VoucherDetails;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherdetailRequest extends FormRequest
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
            'vouchernumber' => 'required|string',
            'accountcode'   => 'required|string',
            'subacccode'     => 'nullable|string',
            'currency'     => 'nullable|string',
            'exchagerate'     => 'nullable|decimal:20,3',
            'primeamt'     => 'nullable|decimal:20,3',
            'baseamt'     => 'nullable|decimal:20,3',
            'branch_id'     => 'required|exists:branches,id',
            'notes'         => 'nullable|string',
            'user_id'       => 'required|exists:users,id',
        ];
    }
}
