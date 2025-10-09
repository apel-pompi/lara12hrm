<?php

namespace App\Http\Requests\ProductFeeHd;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductFeesHdRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'product_id' => 'nullable',
            'country_id' => 'nullable|array',
            'ins_id' => 'required|exists:installments,id',
            'netamount' => 'required',

            'rows' => 'required|array|min:1',
            'rows.*.fees_id' => 'nullable|integer',
            'rows.*.ins_amount' => 'required|numeric|min:0',
            'rows.*.insqty' => 'required|integer|min:1',
            'rows.*.pay_type' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Fee Option Name',
            'product_id' => 'Product Name',
            'country_id' => 'Country of Residency',
            'ins_id' => 'Installment Type',
            'rows.*.pay_type' => 'Payment Type',
            'netamount' => 'Net Amount',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required.',
            'name.max' => ':attribute cannot exceed :max characters.',
            'ins_id.required' => 'Please select an :attribute.',
            'ins_id.exists' => 'The selected :attribute is invalid.',
            'rows.*.pay_type.required' => ':attribute is required.',
            'rows.*.fees_id.required' => 'Please select a Fee Type.',
            'rows.*.ins_amount.required' => 'Amount is required.',
            'netamount.required' => 'The selected :attribute is invalid.',
        ];
    }
}
