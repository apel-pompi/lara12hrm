<?php

namespace App\Http\Requests\Holidayhd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayHdRequest extends FormRequest
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
            'branch_id' =>'required',
            'holiyear' =>'required',
            'holimonth' =>'required',
            'holidays' =>'required',
            'holiworking' =>'required',
            'active' =>'required',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' =>'Branch name is not empty',
            'holiyear.required' =>'Holi Year is not empty',
            'holimonth.required' =>'Holi Month is not empty',
            'holidays.required' =>'Holi Days is not empty',
            'holiworking.required' =>'Holi Working Day is not empty',
            'active.required' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' =>'Branch name',
            'holiyear' =>'Holi Year',
            'holimonth' =>'Holi Month',
            'holidays' =>'Holi Days',
            'holiworking' =>'Holi Working Day',
            'active' =>'Status',
        ];
    }
}
