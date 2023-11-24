<?php

namespace App\Coupon\Senders;

class EmailCouponSender implements CouponSender {
    public function created($coupon) {
        // 寄送 email 給指定顧客
    }
}
