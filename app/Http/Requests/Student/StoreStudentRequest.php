<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_id'     => ['nullable', 'string', 'max:50'],
            'fname'          => ['required', 'string', 'max:100'],
            'lname'          => ['required', 'string', 'max:100'],
            'dateofbirth'    => ['required', 'date'],
            'gender'         => ['required', 'integer', 'in:0,1,2'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['required', 'string', 'max:20'],
            'contactpre'     => ['nullable', 'string', 'max:50'],
            'ename'          => ['nullable', 'string', 'max:255'],
            'ephone'          => ['nullable', 'string', 'max:255'],

            'preaddcountry'  => ['nullable', 'exists:countries,id'],
            'preaddstate'    => ['nullable', 'exists:states,id'],
            'preaddcity'     => ['nullable', 'exists:cities,id'],
            'paddress'       => ['nullable', 'string', 'max:255'],

            'pascountry'     => ['nullable', 'string', 'max:100'],
            'pasnocountry'   => ['nullable', 'string', 'max:100'],
            'passportno'     => ['nullable', 'string', 'max:100'],
            'visatype'       => ['nullable', 'string', 'max:100'],
            'visaexdate'     => ['nullable', 'date'],
            'pvisades'       => ['nullable', 'string', 'max:255'],

            'intakedate'     => ['nullable', 'date'],
            'descountry_id'  => ['required', 'exists:countries,id'],
            'stage_id'       => ['nullable', 'exists:student_stages,id'],
            'metting_note'   => ['nullable', 'string', 'max:255'],

            'photo'          => ['nullable'],
            'assain_user'    => ['required', 'exists:users,id'],
            'source_id'      => ['required', 'exists:student_sources,id'],
            'status'         => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'fname.required'        => 'First name is required.',
            'lname.required'        => 'Last name is required.',
            'phone.required'        => 'Phone no is required.',
            'email.email'           => 'Provide a valid email address.',
            'dateofbirth.required'  => 'Provide a valid date of birth address.',
            'gender.required'       => 'Provide gender.',
            'descountry_id.required'=> 'Preferred destination country is required.',
            'assain_user.required'  => 'Assign user is required.',
            'source_id.required'    => 'Student source is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'fname'         => 'First Name',
            'lname'         => 'Last Name',
            'phone'         => 'Phone No',
            'dateofbirth'   => 'Date of Birth',
            'preaddcountry' => 'Country',
            'preaddstate'   => 'State',
            'preaddcity'    => 'City',
            'intakedate'    => 'Preferred Intake Date',
            'descountry_id' => 'Preferred Destination Country',
            'assain_user'   => 'Assign User',
            'source_id'     => 'Student Source',
            'photo' => 'Student Photo'
        ];
    }
}
