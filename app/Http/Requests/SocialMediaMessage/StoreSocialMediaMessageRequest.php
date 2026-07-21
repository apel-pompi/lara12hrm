<?php

namespace App\Http\Requests\SocialMediaMessage;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaMessageRequest extends FormRequest
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

            'contact_id' => 'required|exists:social_media_contacts,id',

            'platform' => 'required',

            'direction' => 'required',

            'sender_type' => 'required',

            'meta_message_id' => 'nullable',

            'message' => 'nullable',

            'attachment' => 'nullable',

            'attachment_type' => 'nullable',

            'status' => 'nullable'

        ];
    }
}
