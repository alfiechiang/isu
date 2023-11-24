<?php

namespace App\Coupon\Generator;

use App\Coupon\CouponService;
use App\Models\Customer;

class CouponGenerator
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    protected function paginateAndProcessModels($models, callable $issueCoupon): void
    {
        $perPage = 1000;
        $count = $models->count();
        $totalPages = ceil($count / $perPage);

        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++) {
            $pageModels = $models->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

            foreach ($pageModels as $model) {
                $issueCoupon($model);
            }
        }
    }
}
