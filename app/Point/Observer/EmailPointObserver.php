<?php

namespace App\Point\Observer;

class EmailPointObserver implements PointObserver {
    public function created($customer, $point) {
        // 發送電子郵件通知用戶點數已經更新
    }
}
