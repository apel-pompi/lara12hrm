<?php

namespace App\Http\Requests\Vouhcerheader;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVouhcerheaderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('vouhcerheader');
        
        return [
            'vouchernumber' => "required|string|unique:vouhcerheaders,vouchernumber,{$id}",
            'voucherdate'   => 'required|date',
            'referance'     => 'nullable|string',
            'yearname'      => 'required|digits:4',
            'monthname'     => 'required|integer|min:1|max:12',
            'branch_id'     => 'required|exists:branches,id',
            'status'        => 'nullable|string',
            'user_id'       => 'required|exists:users,id',
        ];
    }
}
