<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255'
        ];
    }
    public function messages(): array
    {
        return [
            'account_name.required' => 'Account name is required',
            'account_name.string' => 'Account name should be a string',
            // 'account_name.max' => 'Account name should not exceed 255 characters',
            'account_number.required' => 'Account number is required',
            'ifsc_code.required' => 'IFSC code is required',
            'ifsc_code.string' => 'IFSC code should be a string',
            // 'ifsc_code.max' => 'IFSC code should not exceed 20 characters',
            'bank_name.required' => 'Bank name is required',    
            'bank_name.string' => 'Bank name should be a string',
            // 'bank_name.max' => 'Bank name should not exceed 255 characters',
        ];
    }
}

