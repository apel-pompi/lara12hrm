<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name'              => ['required', 'string', 'max:255'],
            'partner_id'        => ['required', 'integer', 'exists:partners,id'],
            'product_type_id'   => ['required', 'integer', 'exists:product_type_setups,id'],
            'revinue_type'      => ['required', 'integer', 'in:0,1'], // example: 0 = revenue form client, 1 = revenue form partner
            'duration'          => ['required', 'string', 'max:255'],
            'intak_month'       => ['required', 'array'],
            'description'       => ['nullable','string'],
            'note'              => ['nullable', 'string', 'max:255'],
            'user_id'           => ['nullable', 'integer', 'exists:users,id'],
            'active'            => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Product Name is required',
            'partner_id.required'        => 'Partner is required',
            'product_type_id.required'   => 'Product Type is required',
            'revinue_type.required'      => 'Revenue Type is required',
            'duration.required'          => 'Duration is required',
            'intak_month.array'       => 'Intake Month is required',
            'description'       => 'Description is required',
            'user_id'           => 'User is required',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => 'Product Name',
            'partner_id'        => 'Partner',
            'product_type_id'   => 'Product Type',
            'revinue_type'      => 'Revenue Type',
            'duration'          => 'Duration',
            'intak_month'       => 'Intake Month',
            'description'       => 'Description',
            'note'              => 'Note',
            'user_id'           => 'User',
            'active'            => 'Status',
        ];
    }
}
