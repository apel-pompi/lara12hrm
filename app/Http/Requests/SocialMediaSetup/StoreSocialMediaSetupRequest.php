<?php

namespace App\Http\Requests\SocialMediaSetup;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMediaSetupRequest extends FormRequest
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
            'platform' => ['required', 'string', 'in:facebook,whatsapp,messenger'],
            'page_id' => [
                'nullable',
                'required_if:platform,facebook,messenger',
                'string',
                Rule::unique('social_media_setups', 'page_id')
                    ->where(fn ($query) => $query->where('platform', $this->input('platform'))),
            ],
            'whatsapp_business_account_id' => ['nullable', 'required_if:platform,whatsapp', 'string'],
            'access_token' => ['nullable', 'string'],
            'verify_token' => ['nullable', 'string'],
        ];
    }
}
