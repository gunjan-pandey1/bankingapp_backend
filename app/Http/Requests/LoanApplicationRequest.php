<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'loan_type' => 'required|string',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'duration_month' => 'required|integer',
        ];
    }
    public function authorize()
    {
        return true;
    }
}
