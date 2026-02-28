<?php

namespace App\Http\Requests\ChartOfAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChartOfAccountRequest extends FormRequest
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
        $chartId = $this->route('chartOfAccount')->id ?? null;

        return [
            'groupone' => ['required', 'integer', 'exists:group_ones,id'],
            'grouptwo' => ['required', 'integer', 'exists:group_twos,id'],
            'groupthree' => ['required', 'integer', 'exists:group_threes,id'],
            'accountcode' => ['required', 'string', 'max:255', 'unique:chart_of_accounts,accountcode,' . $chartId],
            'description' => ['required', 'string', 'max:255', 'unique:chart_of_accounts,description,' . $chartId],
            'accounttype' => ['required', 'string', 'max:50'],
            'accountusage' => ['required', 'string', 'max:50'],
            'analyticalcode' => ['nullable', 'string', 'max:50'],
            'active' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
