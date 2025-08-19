<?php

namespace App\Http\Requests\PersonalInfo;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonalInfoRequest extends FormRequest
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
            'empid' => 'required|unique:personal_infos,empid',
            'empname' => 'required',
            'joindate' => 'required',
            'branch_id' => 'required',
            'dept_id' => 'required',
            'des_id' => 'required',
            'dateofbirth' => 'required',
            'gender' => 'required',
            'present' => 'required',
            'permanent' => 'required',
            'phonepersonal' => '',
            'phoneoffice' => '',
            'email' => 'required|unique:personal_infos,email',
            'blood' => 'required',
            'nidpass' => 'required',
            'photo' => 'required|image',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'empid.required' => 'Employee ID is not empty',
            'empname.required' => 'Employee Name is not empty',
            'joindate.required' => 'Joning date is not empty',
            'branch_id.required' => 'Branch Name is not empty',
            'dept_id.required' => 'Department not empty',
            'des_id.required' => 'Designation is not empty',
            'dateofbirth.required' => 'Date of Birth is not empty',
            'gender.required' => 'Gender is not empty',
            'present.required' => 'Persent Address is not empty',
            'permanent.required' => 'Permanant Address is not empty',
            'phoneoffice.required'  => 'Official Phone No is not empty',
            'phonepersonal.required' => 'Personal Phone No is not empty',
            'email.required' => 'Email is not empty',
            'blood.required' => 'Blood Group is not empty',
            'nidpass.required' => 'NID or Passport no is not empty',
            'photo.required' => 'Photo is not empty',
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
