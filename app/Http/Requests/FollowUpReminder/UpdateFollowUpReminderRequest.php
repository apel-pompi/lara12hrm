<?php

namespace App\Http\Requests\FollowUpReminder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpReminderRequest extends FormRequest
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
        $id = $this->route('follow_up_reminder');

        return [
            'follow_up_id' => 'required|exists:follow_ups,id',
            'remind_at' => 'required|date',
            'channel' => 'required|in:System,Email,SMS,WhatsApp,Messenger,Push',
            'status' => 'required|in:Pending,Sent,Failed,Cancelled',
            'sent_at' => 'nullable|date',
            'error_message' => 'nullable|string',
            'payload' => 'nullable|array',
        ];
    }
}
