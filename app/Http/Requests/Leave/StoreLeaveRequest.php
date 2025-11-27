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
            'leaveplan_id' => 'required',
            'empid' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'requestdays' => 'required',
            'approveddate' => 'nullable',
            'approveddays' => 'nullable',
            'substitute' => 'required',
            'contact_address' => 'required',
            'reason' => 'required',
            'status' => 'nullable',
            'user_id' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'leaveplan_id.required' => 'Leave name is not empty',
            'empid.required' => 'Employee name is not empty',
            'fromdate.required' => 'From date is not empty',
            'todate.required' => 'To date is not empty',
            'requestdays.required' => 'Total Days is not empty',
            'approveddate' => 'Approved Date',
            'approveddays' => 'Approved Days',
            'substitute.required' => 'Substitute name is not empty',
            'contact_address.required' => 'Contact address is not empty',
            'reason.required' => 'Reason is not empty',
            'status' => 'Status is not empty',
            'user_id' => 'User',
        ];
    }

    public function attributes(): array
    {
        return [
            'leaveplan_id' => 'Leave name',
            'empid' => 'Employee Name',
            'fromdate' => 'From Date',
            'todate' => 'To Date',
            'requestdays' => 'Total Days',
            'approveddate' => 'Approved Date',
            'approveddays' => 'Approved Days',
            'substitute' => 'Substitute Name',
            'contact_address' => 'Contact address',
            'reason' => 'Reason',
            'status' => 'Status',
            'user_id' => 'User',
        ];
    }
}
