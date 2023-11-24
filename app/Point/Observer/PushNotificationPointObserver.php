<?php

namespace App\Point\Observer;

class PushNotificationPointObserver implements PointObserver {
    public function created($customer, $point) {
        // 發送推送通知通知用戶點數已經更新
    }
}
