<?php

namespace App\Http\Requests\SocialMediaActivity;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaActivityRequest extends FormRequest
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

            'activity' => 'sometimes',

            'title' => 'sometimes',

            'description' => 'sometimes',

            'meta' => 'sometimes|array',

        ];
    }
}
