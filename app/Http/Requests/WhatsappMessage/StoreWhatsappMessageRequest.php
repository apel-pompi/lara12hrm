<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWhatsappMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conversation_id' => 'required|exists:whatsapp_conversations,id',
            'message' => 'required|string|max:4000',
            'direction' => 'required|in:incoming,outgoing',
            'message_type' => 'nullable|string|max:50',
            'message_time' => 'nullable|date',
        ];
    }
}
