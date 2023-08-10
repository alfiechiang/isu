<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Customers;

Route::get('whosyourdaddy', [Customers\TestController::class, 'axcd']);


Route::post('login', [Customers\AuthController::class, 'login']);
Route::post('register', [Customers\AuthController::class, 'register']);
Route::post('register/check', [Customers\AuthController::class, 'checkRegister']);
Route::post('token/check', [Customers\AuthController::class, 'checkToken']);
Route::post('socialaccount/check', [Customers\SocialLoginController::class, 'checkoutSocailAccount']);

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
    Route::post('socialaccount/bind', [Customers\SocialLoginController::class, 'bindSocialAccount']);
    Route::get('coupons', [Customers\CouponController::class, 'index']);
    Route::get('points', [Customers\PointController::class, 'index']);
    Route::post('scan-store/{store_id}', [Customers\PointController::class, 'scanStore']);
    Route::get('stamps', [Customers\StampController::class, 'index']);
    Route::post('stamps/deliver', [Customers\StampController::class, 'deliver']);
    Route::post('stamps/exchangeStamp', [Customers\StampController::class, 'exchangeStamp']);
    Route::get('points', [Customers\PointController::class, 'index']);
    Route::get('points/totalPoints', [Customers\PointController::class, 'totalPoints']);
    Route::post('points/exchangeToStamps', [Customers\PointController::class, 'exchangeToStamps']);

    Route::get('hotel', [Customers\HotelController::class, 'list']);
    Route::get('hotel/stronghold', [Customers\HotelController::class, 'stronghold']);
    Route::get('followplayer', [Customers\FollowPlayerController::class, 'list']);
    Route::get('followplayer/{follow_id}/findone', [Customers\FollowPlayerController::class, 'findone']);
    Route::get('followplayer/stronghold', [Customers\FollowPlayerController::class, 'stronghold']);

});
