<?php

namespace App\Http\Requests\SocialMediaSetup;

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
            'page_id' => ['nullable', 'required_if:platform,facebook', 'string', 'unique:social_media_setups,page_id'],
            'whatsapp_business_account_id' => ['nullable', 'string'],
            'access_token' => ['nullable', 'string'],
            'verify_token' => ['nullable', 'string'],
        ];
    }
}
