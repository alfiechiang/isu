<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Stores;

Route::post('login', [Stores\AuthController::class, 'login']);
Route::post('forgetpassword', [Stores\AuthController::class, 'forgetpassword']);
Route::post('otp/send', [Stores\MailController::class, 'sendOtp']);
Route::post('otp/check', [Stores\MailController::class, 'checkOtp']);


Route::get('customers/export', [Stores\CustomerController::class, 'export']);

Route::get('custom/coupon/export/{coupon_code}', [Stores\CustomCouponController::class, 'export']);
Route::get('stamp-customer/export', [Stores\StampCustomerController::class, 'export']);
Route::get('point-customers/export', [Stores\PointCustomerController::class, 'export']);

Route::group([
  'middleware' => ['auth:employee','role_modify'],
], function ($router) {
  Route::post('logout', [Stores\AuthController::class, 'logout']);
  Route::post('upload', [Stores\UploadFileController::class, 'upload']);
  Route::get('menu', [Stores\AuthController::class, 'privilegeMenuList']);
  Route::get('dignity', [Stores\AuthController::class, 'dignity']);
  Route::post('refresh', [Stores\AuthController::class, 'refresh']);
  Route::get('customers/{customer_id}/stamps', [Stores\StampCustomerController::class, 'index']);
  Route::post('employees', [Stores\StoreEmployeeController::class, 'create']);
  Route::delete('employees/{uid}', [Stores\StoreEmployeeController::class, 'delete']);
  Route::put('employees/{uid}', [Stores\StoreEmployeeController::class, 'update']);
  Route::get('employees/{uid}/findone', [Stores\StoreEmployeeController::class, 'findone']);
  Route::get('employees', [Stores\StoreEmployeeController::class, 'pageList']);
  Route::get('operaterlog/latest', [Stores\OperatorLogController::class, 'findLatestOne']);
  Route::get('privilege/role', [Stores\PrivilegeRoleController::class, 'list']);
  Route::get('customers', [Stores\CustomerController::class, 'list']);
  Route::get('customers/{guid}/findone', [Stores\CustomerController::class, 'findone']);
  Route::put('customers/{guid}', [Stores\CustomerController::class, 'update']);
  Route::get('customers/social', [Stores\CustomerController::class, 'social']);
  Route::post('stamp-customer', [Stores\StampCustomerController::class, 'create']);
  Route::get('stamp-customer', [Stores\StampCustomerController::class, 'list']);
  Route::get('stamp-customer/log', [Stores\StampCustomerController::class, 'logList']);

  Route::delete('stamp-customer/{stamp_id}', [Stores\StampCustomerController::class, 'delete']);
  Route::post('prize', [Stores\PrizeController::class, 'create']);
  Route::get('prize/{prize_id}/findone', [Stores\PrizeController::class, 'findone']);
  Route::get('prize', [Stores\PrizeController::class, 'list']);
  Route::put('prize/{prize_id}', [Stores\PrizeController::class, 'update']);
  Route::delete('prize/{prize_id}', [Stores\PrizeController::class, 'delete']);
  Route::get('point-customers', [Stores\PointCustomerController::class, 'list']);
  Route::post('point-customers', [Stores\PointCustomerController::class, 'create']);
  Route::delete('point-customers', [Stores\PointCustomerController::class, 'delete']);
  Route::get('point-customers/totalPoints', [Stores\PointCustomerController::class, 'totalPoints']);
  Route::get('point-customers/log', [Stores\PointCustomerController::class, 'logList']);
  Route::get('county', [Stores\CountyController::class, 'list']);
  Route::post('hotel', [Stores\HotelController::class, 'create']);
  Route::delete('hotel/{hotel_id}', [Stores\HotelController::class, 'delete']);
  Route::get('hotel/{hotel_id}/findone', [Stores\HotelController::class, 'findone']);
  Route::put('hotel/{hotel_id}', [Stores\HotelController::class, 'update']);
  Route::get('hotel', [Stores\HotelController::class, 'list']);
  Route::put('hotel/img/{hotel_id}', [Stores\HotelController::class, 'batchImgUpdate']);
  Route::get('hotel/img', [Stores\HotelController::class, 'imgList']);
  Route::post('followplayer', [Stores\FollowPlayerController::class, 'create']);
  Route::put('followplayer/{follow_id}', [Stores\FollowPlayerController::class, 'update']);
  Route::get('followplayer/check/updatepermission/{follow_id}', [Stores\FollowPlayerController::class, 'checkUpdatePermission']);
  Route::delete('followplayer/{follow_id}', [Stores\FollowPlayerController::class, 'delete']);
  Route::get('followplayer/{follow_id}/findone', [Stores\FollowPlayerController::class, 'findone']);
  Route::get('followplayer', [Stores\FollowPlayerController::class, 'list']);
  Route::get('followplayer/ownList', [Stores\FollowPlayerController::class, 'ownList']);

  Route::post('news', [Stores\NewsController::class, 'create']);
  Route::put('news/{news_id}', [Stores\NewsController::class, 'update']);
  Route::delete('news/{news_id}', [Stores\NewsController::class, 'delete']);
  Route::get('news/{news_id}/findone', [Stores\NewsController::class, 'findone']);
  Route::get('news', [Stores\NewsController::class, 'list']);
  Route::post('recommend', [Stores\RecommendController::class, 'create']);
  Route::get('recommend/{recommend_id}/findone', [Stores\RecommendController::class, 'findone']);
  Route::put('recommend/{recommend_id}', [Stores\RecommendController::class, 'update']);
  Route::delete('recommend/{recommend_id}', [Stores\RecommendController::class, 'delete']);
  Route::get('recommend', [Stores\RecommendController::class, 'list']);
  Route::post('custom/coupon', [Stores\CustomCouponController::class, 'create']);
  Route::get('custom/coupon', [Stores\CustomCouponController::class, 'pageList']);
  Route::post('custom/coupon/send/{coupon_code}', [Stores\CustomCouponController::class, 'send']);
  Route::get('custom/coupon/{coupon_code}/findone', [Stores\CustomCouponController::class, 'findone']);
  Route::put('custom/coupon/{coupon_code}', [Stores\CustomCouponController::class, 'update']);
  Route::post('custom/coupon/import', [Stores\CustomCouponController::class, 'import']);
  Route::put('custom/coupon/disable/{coupon_code}', [Stores\CustomCouponController::class, 'findoneCouponDisable']);
  Route::put('custom/coupon/disable/{coupon_code}/{coupon_id}', [Stores\CustomCouponController::class, 'findoneCouponDisableByMember']);
  Route::get('custom/coupon/{coupon_code}/{coupon_id}/findone', [Stores\CustomCouponController::class, 'findoneCouponByMember']);

  Route::get('custom/coupon/customer', [Stores\CustomCouponCustomerController::class, 'pageList']);
  Route::get('custom/coupon/peoplist/{coupon_code}', [Stores\CustomCouponCustomerController::class, 'peoplePageList']);

  Route::get('coupon/disablelog', [Stores\CouponDisableLogController::class, 'pageList']);
  Route::post('coupon/exchange', [Stores\ExchangeCouponController::class, 'exchange']);
  Route::get('coupon', [Stores\CouponCustomerController::class, 'pageList']);
  Route::get('coupon/peoplist/{coupon_code}', [Stores\CouponCustomerController::class, 'peoplePageList']);
  Route::get('coupon/{coupon_code}/{coupon_id}/findone', [Stores\CouponCustomerController::class, 'findoneCouponByMember']);
  Route::put('coupon/disable/{coupon_code}/{coupon_id}', [Stores\CouponCustomerController::class, 'findoneCouponDisableByMember']);

  Route::post('coupon-customer/send', [Stores\CouponCustomerController::class, 'send']);
  Route::post('coupon-customer/{coupon_code}/redeem', [Stores\CouponCustomerController::class, 'redeem']);
});
