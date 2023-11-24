<?php

namespace App\Services\CustomerRole;

use App\Models\Customer;

class CustomerService
{
    public function isInformationComplete(Customer $customer): bool
    {
        $requiredFields = ['name', 'email', 'phone', 'gender', 'citizenship', 'avatar', 'birthday', 'address', 'interest'];

        foreach ($requiredFields as $field) {
            if (($customer->{$field}) == '') {
                return false;
            }
        }

        return true;
    }
}
