<?php

namespace App\Http\Middleware;

use Closure;
use Brian2694\Toastr\Facades\Toastr;

class SubscriptionAccessUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( moduleStatusCheck('Saas') == True && moduleStatusCheck('SaasSubscription') == True){
            $temp = \Modules\SaasSubscription\Entities\SmPackagePlan::isSubscriptionAutheticate();
            if ($temp == true) {
                return $next($request);
            }else{
                abort("403");
            }
        }else{
            return $next($request);
        }
    }
}