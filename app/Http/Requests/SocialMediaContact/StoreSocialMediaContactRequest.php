<?php

namespace App\Http\Requests\SocialMediaContact;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaContactRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'student_id' => 'nullable|exists:students,id',

            'platform' => 'required',

            'platform_user_id' => 'required',

            'page_id' => 'nullable',

            'social_name' => 'nullable',

            'social_username' => 'nullable',

            'phone_number' => 'nullable',

            'phone_number_id' => 'nullable',

            'conversation_id' => 'nullable',

            'status' => 'nullable|boolean'

        ];
    }
}
