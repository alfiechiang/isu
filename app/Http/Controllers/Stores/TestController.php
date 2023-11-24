<?php

namespace App\Http\Controllers\Stores;

use Illuminate\Http\Request;

class TestController extends Controller
{

    public function index(Request $request){
        dd('Hello World');
    }
}