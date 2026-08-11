<?php

namespace App\Http\Requests\FollowUpStatus;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFollowUpStatusRequest extends FormRequest
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
            'code' => 'required|string|max:50|unique:follow_up_statuses,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'color' => 'required|string|max:30',
            'icon' => 'nullable|string|max:50',
            'is_completed' => 'required|boolean',
            'is_cancelled' => 'required|boolean',
            'is_default' => 'required|boolean',
            'allow_reschedule' => 'required|boolean',
            'allow_edit' => 'required|boolean',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }
}
