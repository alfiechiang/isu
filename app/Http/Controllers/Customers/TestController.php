<?php

namespace App\Http\Controllers\Customers;

use App\Http\Response;
use App\Models\Customer;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function axcd(Request $request)
    {
        $type = $request->get('type');
        switch ($type) {
            case 'clear_account':
                SocialAccount::truncate();
                DB::table('customers')->delete(); 
                DB::table('otps')->delete();                  
                return Response::success();
                break;
            default:
                break;
        }
    }
}
