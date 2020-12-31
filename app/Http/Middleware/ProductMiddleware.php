<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;
use App\Envato\Envato;
use GuzzleHttp\Client;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;

class ProductMiddleware
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
        if (!Schema::hasTable('sm_general_settings')) {
            return redirect('install');
        }
        if (User::checkAuth() == false || User::checkAuth() == null) {
            return redirect()->route('system.config');
        } else {
            return $next($request);
        }
    }
}
