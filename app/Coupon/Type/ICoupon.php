<?php

namespace App\Coupon\Type;

interface ICoupon {
    public function isAvailable();
    public function getDescription();
//    public function getMinimumSpendAmount();
//    public function applyDiscount();
}
