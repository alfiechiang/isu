<?php

namespace App\Point\Observer;

interface PointObserver {
    public function created($customer, $point);
}
