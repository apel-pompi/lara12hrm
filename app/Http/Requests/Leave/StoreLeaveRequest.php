<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
            'leaveplan_id' =>'required',
            'empid' =>'required',
            'fromdate' =>'required',
            'todate' =>'required',
            'days' =>'required',
            'substitute' =>'required',
            'reason' =>'required',
            'status' =>'',
        ];
    }

    public function messages(): array
    {
        return [
            'leaveplan_id.required' =>'Leave name is not empty',
            'empid.required' =>'Employee name is not empty',
            'fromdate.required' =>'From date is not empty',
            'todate.required' =>'To date is not empty',
            'days.required' =>'Total Days is not empty',
            'substitute.required' =>'Substitute name is not empty',
            'reason.required' =>'Reason is not empty',
            'status' =>'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'leaveplan_id' =>'Leave name',
            'empid' =>'Employee Name',
            'fromdate' =>'From Date',
            'todate' =>'To Date',
            'days' =>'Total Days',
            'substitute' =>'Substitute Name',
            'reason' =>'Reason',
            'status' =>'Status',
        ];
    }
}
