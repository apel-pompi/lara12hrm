<?php

namespace App\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'iso3' => 'required',
            'iso2' => 'required',
            'phonecode' => 'required',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is not empty',
            'iso3.required' => 'ISO3 is not empty',
            'iso2.required' => 'ISO2 name is not empty',
            'phonecode.required' => 'Phone Code is not empty',
            'currency.required' => 'Currency is not empty',
            'currency_symbol.required' => 'Currency Symbol is not empty',
            'latitude.required' => 'Latitude is not empty',
            'longitude.required' => 'Longitude is not empty',
            'status.required' => 'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'iso3' => 'ISO3',
            'iso2' => 'ISO2',
            'phonecode' => 'Phone Code',
            'currency' => 'Currency',
            'currency_symbol' => 'Currency Symbol',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'status' => 'Status',
        ];
    }
}
