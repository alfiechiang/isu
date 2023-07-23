<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Stores;

Route::post('login', [Stores\AuthController::class, 'login']);
Route::post('forgetpassword', [Stores\AuthController::class, 'forgetpassword']);
Route::post('otp/send', [Stores\MailController::class, 'sendOtp']);
Route::post('otp/check', [Stores\MailController::class, 'checkOtp']);

Route::group([
  'middleware' => 'auth:employee',
], function ($router) {
  Route::post('logout', [Stores\AuthController::class, 'logout']);
  Route::get('menu', [Stores\AuthController::class, 'privilegeMenuList']);
  Route::post('refresh', [Stores\AuthController::class, 'refresh']);
  Route::get('customers/{customer_id}/coupons', [Stores\CouponCustomerController::class, 'index']);
  Route::post('coupon-customer/send', [Stores\CouponCustomerController::class, 'send']);
  Route::get('coupon-customer/{coupon_code}', [Stores\CouponCustomerController::class, 'show']);
  Route::post('coupon-customer/{coupon_code}/redeem', [Stores\CouponCustomerController::class, 'redeem']);
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
  Route::get('point-customers', [Stores\PointCustomerController::class, 'list']);
  Route::post('point-customers', [Stores\PointCustomerController::class, 'create']);
  Route::delete('point-customers/{point_id}', [Stores\PointCustomerController::class, 'delete']);
  Route::get('point-customers/totalPoints', [Stores\PointCustomerController::class, 'totalPoints']);
  Route::get('point-customers/log', [Stores\PointCustomerController::class, 'logList']);

});
