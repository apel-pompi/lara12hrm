<?php

namespace App\Http\Requests\Holidaydt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayDtRequest extends FormRequest
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
            'holidate' =>'required',
            'holitypes' =>'required',
            'holihd_id' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'holidate.required' =>'Holiday Date is not empty',
            'holitypes.required' =>'Holiday Types is not empty',
            'holihd_id.required' =>'Holiday Header is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
           'holidate.required' =>'Holiday Date',
            'holitypes.required' =>'Holiday Types',
            'holihd_id.required' =>'Holiday Header',
        ];
    }
}
