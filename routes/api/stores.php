<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Stores;

Route::post('login', [Stores\AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:employee',
], function ($router) {
    Route::post('logout', [Stores\AuthController::class, 'logout']);
    Route::post('refresh', [Stores\AuthController::class, 'refresh']);

    Route::resource('customers', Stores\CustomerController::class, ['only' => ['index', 'show', 'destroy']]);

    Route::get('customers/{customer_id}/coupons', [Stores\CouponCustomerController::class, 'index']);
    Route::post('coupon-customer/send', [Stores\CouponCustomerController::class, 'send']);
    Route::get('coupon-customer/{coupon_code}', [Stores\CouponCustomerController::class, 'show']);
    Route::post('coupon-customer/{coupon_code}/redeem', [Stores\CouponCustomerController::class, 'redeem']);

    Route::get('customers/{customer_id}/points', [Stores\PointCustomerController::class, 'index']);
    Route::post('point-customer/amount', [Stores\PointCustomerController::class, 'amount']);

    Route::get('customers/{customer_id}/stamps', [Stores\StampCustomerController::class, 'index']);
    Route::post('stamp-customer/quantity', [Stores\StampCustomerController::class, 'quantity']);

    Route::resource('employees', Stores\StoreEmployeeController::class, ['only' => [
        'index', 'store', 'show', 'update', 'destroy'
    ]]);

    Route::resource('stores', Stores\StoreController::class, ['only' => ['index']]);
});
