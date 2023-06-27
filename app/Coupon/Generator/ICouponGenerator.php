<?php

namespace App\Coupon\Generator;

use Carbon\Carbon;

interface ICouponGenerator {
    public function execution(?Carbon $executionDate);
}

