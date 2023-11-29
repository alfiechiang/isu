<?php

namespace App\Http\Middleware;

use App\Models\AccessTokenLog;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        AccessTokenLog::where('access_token',request()->bearerToken())->delete();
        return $request->expectsJson() ? null : route('login');
    }
}
