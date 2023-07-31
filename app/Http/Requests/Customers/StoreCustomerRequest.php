<?php

namespace App\Http\Requests\Customers;

use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rules\File;

class StoreCustomerRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'max:255',
            'email' => 'email',
            'phone' => 'regex:/^09\d{8}$/',
            'birthday' => 'date_format:"Y-m-d"',
            'address' => 'nullable|string',
            'interest' => 'nullable|array',
            'avatar' => 'nullable|string'
        ];
    }
}
