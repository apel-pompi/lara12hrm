<?php

namespace App\Http\Requests\SocialMediaConversation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaConversationRequest extends FormRequest
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

            'contact_id'        => 'required|exists:social_media_contacts,id',

            'platform'          => 'required|string|max:30',

            'conversation_id'   => 'nullable|string|max:255',

            'last_message'      => 'nullable|string',

            'last_message_at'   => 'nullable|date',

            'unread_count'      => 'nullable|integer|min:0',

            'status'            => 'nullable|boolean',

        ];
    }
}
