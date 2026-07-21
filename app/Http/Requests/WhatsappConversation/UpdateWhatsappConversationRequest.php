<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsappConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'nullable|string|max:20',
            'name' => 'nullable|string|max:255',
            'is_read' => 'nullable|boolean',
            'last_message_at' => 'nullable|date',
        ];
    }
}
