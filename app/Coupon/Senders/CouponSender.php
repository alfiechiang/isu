<?php

namespace App\Coupon\Senders;

interface CouponSender {
    public function created($coupon);
}
