<?php

namespace App\Http\Requests\PersonalInfo;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInfoRequest extends FormRequest
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
            'empid' => 'required',
            'empname' => 'required',
            'joindate' => 'required',
            'branch_id' => 'required',
            'dept_id' => 'required',
            'des_id' => 'required',
            'dateofbirth' => 'required',
            'gender' => 'required',
            'present' => 'required',
            'permanent' => 'required',
            'phonepersonal' => 'required',
            'phoneoffice' => 'required',
            'email' => 'required',
            'blood' => 'required',
            'nidpass' => 'required',
            'photo' => '',
            'active' => '',
        ];
    }

    public function messages(): array
    {
        return [
            'empid' => 'Employee ID is not empty',
            'empname' => 'Employee Name is not empty',
            'joindate' => 'Joning date is not empty',
            'branch_id' => 'Branch Name is not empty',
            'dept_id' => 'Department not empty',
            'des_id' => 'Designation is not empty',
            'dateofbirth' => 'Date of Birth is not empty',
            'gender' => 'Gender is not empty',
            'present' => 'Persent Address is not empty',
            'permanent' => 'Permanant Address is not empty',
            'phoneoffice'  => 'Official Phone No is not empty',
            'phonepersonal' => 'Personal Phone No is not empty',
            'email' => 'Email is not empty',
            'blood' => 'Blood Group is not empty',
            'nidpass' => 'NID or Passport no is not empty',
            'photo' => 'Photo is not empty',
            'active' => 'Status is not empty',
        ];
    }

    public function attributes(): array
    {
        return [
            'empid'        => 'Employee ID',
            'joindate'     => 'Joining Date',
            'branch_id'    => 'Branch Name',
            'dept_id'      => 'Department Name',
            'des_id'       => 'Designation',
            'dateofbirth'  => 'Date of Birth',
            'gender'       => 'Gender',
            'present'      => 'Present Address',
            'permanent'    => 'Permanent Address',
            'phonepersonal' => 'Personal Phone No',
            'phoneoffice'  => 'Official Phone No',
            'email'        => 'Email',
            'blood'        => 'Blood Group',
            'nidpass'      => 'NID or Passport No',
            'photo'        => 'Photo',
            'active'       => 'Status',
        ];
    }
}
