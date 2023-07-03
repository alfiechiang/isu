<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customers;

Route::post('login', [Customers\AuthController::class, 'login']);
Route::post('register', [Customers\AuthController::class, 'register']);
Route::post('forgetpassword', [Customers\AuthController::class, 'forgetPassword']);
Route::post('otp/send', [Customers\OtpController::class, 'sendOtp']);
Route::post('otp/check', [Customers\OtpController::class, 'checkOtp']);
Route::post('login/{provider_name}/register', [Customers\SocialLoginController::class, 'register']);
Route::post('login/{provider_name}', [Customers\SocialLoginController::class, 'auth']);

Route::group([
    'middleware' => 'auth:customers',
], function ($router) {
    Route::post('logout', [Customers\AuthController::class, 'logout']);
    Route::post('refresh', [Customers\AuthController::class, 'refresh']);
    Route::post('upload', [Customers\UploadFileController::class, 'upload']);
    Route::get('me/profile', [Customers\ProfileController::class, 'index']);
    Route::put('me/profile', [Customers\ProfileController::class, 'store']);
    Route::put('resetpassword', [Customers\AuthController::class, 'resetPassword']);
    Route::get('socialaccounts', [Customers\AuthController::class, 'socialAccounts']);
    Route::get('coupons', [Customers\CouponController::class, 'index']);
    Route::get('points', [Customers\PointController::class, 'index']);
    Route::post('scan-store/{store_id}', [Customers\PointController::class, 'scanStore']);
    Route::get('stamps', [Customers\StampController::class, 'index']);
});
