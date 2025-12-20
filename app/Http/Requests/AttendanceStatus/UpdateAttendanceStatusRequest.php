<?php

namespace App\Http\Requests\AttendanceStatus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceStatusRequest extends FormRequest
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
            'empid'        => 'required|exists:personal_infos,empid',
            'branch_id'    => 'required|exists:branches,id',
            'yearname'     => 'required|integer|min:0',
            'monthname'    => 'required|integer|min:0',
            'workhour'     => 'required',
            'totalhour'    => 'required',
            'deducthour'   => 'nullable',
            'hrsurplus'    => 'nullable',
            'nethour'      => 'required',
            'absent'       => 'nullable|integer|min:0',
            'leave'        => 'nullable|integer|min:0',
            'totaldeduct'  => 'nullable|numeric|min:0',
            'payablehour'      => 'required|numeric|min:0',
            'salary'       => 'nullable|numeric|min:0',
            'payment'      => 'nullable|numeric|min:0',
            'active'       => 'nullable|integer|min:0',
            'user_id'      => 'required|exists:users,id',
        ];
    }
}
