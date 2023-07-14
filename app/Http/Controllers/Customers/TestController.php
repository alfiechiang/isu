<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Models\Customer;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;



class TestController extends Controller
{

    public function axcd(Request $request)
    {
        DB::table('coupon_codes')->delete();
        DB::table('coupon_customers')->delete();
        DB::table('coupons')->delete();
        DB::table('customers')->delete();
        DB::table('invoices')->delete();
        DB::table('login_customers')->delete();
        DB::table('otps')->delete();
        DB::table('personal_access_tokens')->delete();
        DB::table('point_customers')->delete();
        DB::table('social_accounts')->delete();
        DB::table('stamp_customers')->delete();
        DB::table('store_employees')->delete();
        DB::table('store_privilege_intermediates')->delete();
        DB::table('store_privilege_menus')->delete();
        DB::table('store_privilege_roles')->delete();
        DB::table('stores')->delete();
        Artisan::call('db:seed');
    }
}
