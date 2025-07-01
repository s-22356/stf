<?php

namespace App\Http\Middleware\app;

use Closure;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Exists;
use Session;


class LoginTokenUdinMicroService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $now = date('Y-m-d H:i:s');
        if( Session::has('udin_token') && Session::get('udin_token_expiry') < $now){
            $refresh_token_data =   generateUdinLoginTokenByRefreshToken();
            $login_token   = $refresh_token_data['data'];
            $login_token_expiry = date('Y-m-d H:i:s', strtotime($now . ' +2 minutes'));
            Session::put('udin_token', $login_token);
            Session::put('udin_token_expiry', $login_token_expiry);
            $response = $next($request);
            return $response;
        }
        $response = $next($request);
        return $response;
    }
}
