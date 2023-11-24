<?php

namespace App\Coupon\Senders;

class SMSCouponSender implements CouponSender {
    public function created($coupon) {
        // 傳送簡訊給指定顧客
    }
}
