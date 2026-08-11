<?php

namespace App\Http\Requests\FollowUp;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpRequest extends FormRequest
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
        $id = $this->route('follow_up');

        return [
            'student_id' => 'nullable|exists:students,id',
            'contact_id' => 'nullable|exists:social_media_contacts,id',
            'follow_up_master_id' => 'required|exists:follow_up_masters,id',
            'assigned_to' => 'required|exists:users,id',
            'created_by' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'follow_up_at' => 'required|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|in:Pending,Completed,Cancelled,Missed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'reminder' => 'required|boolean',
            'reminder_before' => 'required|integer|min:0',
            'result' => 'nullable|string',
            'is_auto' => 'required|boolean',
        ];
    }
}
