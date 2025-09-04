<?php

namespace App\Http\Requests\State;

use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
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
            'name' => 'required',
            'country_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'State Name is not empty',
            'country_id.required' => 'Country is not empty',
            'latitude.required' => 'Latitude is not empty',
            'longitude.required' => 'Longitude is not empty',
            'status.required' => 'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'State Name',
            'country_id' => 'Country',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'status' => 'Status',
        ];
    }
}
