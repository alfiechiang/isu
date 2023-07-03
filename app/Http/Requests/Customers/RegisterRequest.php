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
            'phone' => 'required|regex:/^9\d{8}$/',
            'password' => 'required|min:6',
            'repeat_password' => 'required|min:6',
        ];
    }
}
