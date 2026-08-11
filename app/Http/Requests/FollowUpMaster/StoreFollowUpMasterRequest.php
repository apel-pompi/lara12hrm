<?php

namespace App\Http\Requests\FollowUpMaster;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFollowUpMasterRequest extends FormRequest
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
        $id = $this->route('follow_up_master');

        return [
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:follow_up_masters,code,'.$id,
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'default_priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
        ];
    }
}
