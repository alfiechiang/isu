<?php

namespace App\Http\Requests\Customers;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required_if:citizenship,foreign|nullable|email',
            'phone' => 'required_if:citizenship,native|nullable|regex:/^09\d{8}$/',
            'password' => 'required|min:8',
            'citizenship' => 'required|in:foreign,native',
            'token' => 'required',
        ];
    }
}
