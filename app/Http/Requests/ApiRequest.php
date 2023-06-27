<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
{
    public function validationData(): array
    {
        return count($this->json()->all()) ? $this->json()->all() : $this->all();
    }

    public function getValidatorInstance(): Validator
    {
        return parent::getValidatorInstance();
    }

    protected function failedValidation(Validator $validator)
    {

    }
}
