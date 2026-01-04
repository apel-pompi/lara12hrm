<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'subcode' => "nullable|string",
            'name'   => 'required|string',
            'subaddress'     => 'required|string',
            'subcountry'     => 'nullable|string',
            'substate'     => 'nullable|string',
            'subcity'     => 'nullable|string',
            'subzipcode'     => 'nullable|string',
            'contact_person'     => 'required|string',
            'subphone'     => 'required|string',
            'subemail'     => 'nullable|string',
            'user_id'       => 'exists:users,id',
            'active'  => ['nullable', 'boolean'],
        ];
    }
}
