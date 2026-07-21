<?php

namespace App\Http\Requests\SocialMediaConversation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaConversationRequest extends FormRequest
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

            'last_message'      => 'sometimes|string',

            'last_message_at'   => 'sometimes|date',

            'unread_count'      => 'sometimes|integer|min:0',

            'status'            => 'sometimes|boolean',

        ];
    }
}
