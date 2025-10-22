<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
            'fname'          => ['sometimes', 'required', 'string', 'max:100'],
            'lname'          => ['sometimes', 'required', 'string', 'max:100'],
            'dateofbirth'    => ['required', 'date'],
            'gender'         => ['required', 'integer', 'in:0,1,2'],
            'email'          => ['nullable', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:20'],
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
            'descountry_id'  => ['sometimes', 'required', 'exists:countries,id'],
            'stage_id'       => ['nullable', 'exists:student_stages,id'],
            'metting_note'   => ['nullable', 'string', 'max:255'],

            'photo'          => ['nullable'],
            'assain_user'    => ['sometimes', 'required', 'exists:users,id'],
            'source_id'      => ['sometimes', 'required', 'exists:student_sources,id'],
            'status'         => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return (new StoreStudentRequest())->messages();
    }

    public function attributes(): array
    {
        return (new StoreStudentRequest())->attributes();
    }
}
