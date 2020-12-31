<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class UserRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $assignId = null)
    {
       
        $permissions =   session()->get('permission');
        if( (! is_null($permissions)) &&  (Auth::user()->role_id != 1) ){
            if( in_array($assignId , $permissions )){
                return $next($request);
            }
            else{
                abort('403');
            }
        }

        else{
           
            return $next($request);
        }
    }
}