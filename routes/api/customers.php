<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customers;

Route::post('login', [Customers\AuthController::class, 'login']);
Route::post('register', [Customers\AuthController::class, 'register']);
Route::post('otp/send', [Customers\OtpController::class, 'sendOtp']);
Route::group([
    'middleware' => 'auth:customers',
], function ($router) {
    Route::post('logout', [Customers\AuthController::class, 'logout']);
    Route::post('refresh', [Customers\AuthController::class, 'refresh']);

    Route::get('me/profile', [Customers\ProfileController::class, 'index']);
    Route::post('me/profile', [Customers\ProfileController::class, 'store']);

    Route::get('coupons', [Customers\CouponController::class, 'index']);

    Route::get('points', [Customers\PointController::class, 'index']);
    Route::post('scan-store/{store_id}', [Customers\PointController::class, 'scanStore']);

    Route::get('stamps', [Customers\StampController::class, 'index']);
});
