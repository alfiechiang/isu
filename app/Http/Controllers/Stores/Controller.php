<?php

namespace App\Http\Controllers\Stores;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const PAGER = 50;

    public function user(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }
}
