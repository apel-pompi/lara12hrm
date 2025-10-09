<?php

namespace App\Http\Requests\ProductFeeHd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductFeesHdRequest extends FormRequest
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
            'pay_type' => 'required|string|max:255',
            'netamount' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Fee Option Name',
            'product_id' => 'Product Name',
            'country_id' => 'Country of Residency',
            'ins_id' => 'Installment Type',
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
            'pay_type' => 'Payment Type',
            'netamount.required' => 'The selected :attribute is invalid.',
        ];
    }
}
