<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsappMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'nullable|string|max:4000',
            'direction' => 'nullable|in:incoming,outgoing',
            'message_type' => 'nullable|string|max:50',
        ];
    }
}
