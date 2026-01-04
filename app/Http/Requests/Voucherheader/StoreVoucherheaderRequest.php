<?php

namespace App\Http\Requests\Voucherheader;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherheaderRequest extends FormRequest
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
            'vouchernumber' => 'unique:voucherheaders,vouchernumber',
            'voucherdate'   => 'required|date',
            'referance'     => 'nullable|string',
            'yearname'      => 'digits:4',
            'monthname'     => 'integer|min:1|max:12',
            'branch_id'     => 'required|exists:branches,id',
            'notes'         => 'nullable|string',
            'status'        => 'nullable|string',
            'user_id'       => 'exists:users,id',
        ];
    }
}
