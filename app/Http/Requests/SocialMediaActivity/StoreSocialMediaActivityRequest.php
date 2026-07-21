<?php

namespace App\Http\Requests\SocialMediaActivity;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaActivityRequest extends FormRequest
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

            'conversation_id' => 'required|exists:social_media_conversations,id',

            'student_id' => 'nullable|exists:students,id',

            'user_id' => 'nullable|exists:users,id',

            'activity' => 'required',

            'title' => 'nullable',

            'description' => 'nullable',

            'meta' => 'nullable|array',

        ];
    }
}
