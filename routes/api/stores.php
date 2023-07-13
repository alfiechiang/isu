<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Stores;

Route::post('login', [Stores\AuthController::class, 'login']);

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

  Route::get('customers/{customer_id}/points', [Stores\PointCustomerController::class, 'index']);
  Route::post('point-customer/amount', [Stores\PointCustomerController::class, 'amount']);

  Route::get('customers/{customer_id}/stamps', [Stores\StampCustomerController::class, 'index']);
  Route::post('stamp-customer/quantity', [Stores\StampCustomerController::class, 'quantity']);

  Route::post('employees', [Stores\StoreEmployeeController::class, 'create']);
  Route::delete('employees/{uid}', [Stores\StoreEmployeeController::class, 'delete']);
  Route::put('employees/{uid}', [Stores\StoreEmployeeController::class, 'update']);
  Route::get('employees/{uid}/findone', [Stores\StoreEmployeeController::class, 'findone']);
  Route::get('employees', [Stores\StoreEmployeeController::class, 'pageList']);
  Route::get('privilege/role', [Stores\PrivilegeRoleController::class, 'list']);

  Route::get('customers', [Stores\CustomerController::class, 'list']);
  Route::get('customers/{guid}/findone', [Stores\CustomerController::class, 'findone']);
  Route::put('customers/{guid}', [Stores\CustomerController::class, 'update']);
  Route::get('customers/social', [Stores\CustomerController::class, 'social']);

});
