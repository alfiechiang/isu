<?php

namespace App\Http\Middleware;

use App\Models\StorePrivilegeRoleLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StorePrivilegeRoleModify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token=$request->bearerToken();
        $log=StorePrivilegeRoleLog::where('access_token',$access_token)->first();
        if(!empty($log)){
            $log->delete();
            route('login');
        }
        return $next($request);
    }
}
