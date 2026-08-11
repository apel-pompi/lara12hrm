<?php

namespace App\Http\Requests\FollowUpActivity;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpActivityRequest extends FormRequest
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

            'student_id' => 'required|exists:students,id',

            'follow_up_master_id' =>
            'required'
                . '|exists:follow_up_masters,id',
            'follow_up_status_id' =>
            'required'
                . '|exists:follow_up_statuses,id',

            'assigned_to' => 'required|exists:users,id',

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'follow_up_date' => 'required|date',

            'follow_up_time' => 'nullable|date_format:H:i:s',

            'priority' => 'required|in:Low,Medium,High,Urgent',

            'status' => 'required|in:Pending,Completed,Cancelled,Rescheduled,Missed',

            'remarks' => 'nullable|string',

            'completed_at' => 'nullable|date',

            'is_auto' => 'required|boolean',

            'meta' => 'nullable|array',

        ];
    }
}
