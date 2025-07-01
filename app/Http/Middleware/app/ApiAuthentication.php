<?php

namespace App\Http\Middleware\app;

use Closure;
use Session;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Exists;

class ApiAuthentication
{
    //keeping record of api log
    public function handle($request, Closure $next)
    {
      
      $now = date('Y-m-d H:i:s');
      if(!Session::has('x_api_token')){
        $data =   generateUdinXApiToken();
        if(!$data['error']){
          $x_api_token   = $data['data']['accessToken'];
          $refresh_token = $data['data']['refreshToken'];
          $x_api_token_expiry = date('Y-m-d H:i:s', strtotime($now . ' +50 minutes'));
          $x_api_token_refresh_token_expiry = date('Y-m-d H:i:s', strtotime($now . ' +3 hours'));
          Session::put('x_api_token', $x_api_token);
          Session::put('refresh_token', $refresh_token);
          Session::put('x_api_token_expiry', $x_api_token_expiry);
          Session::put('x_api_token_refresh_token_expiry', $x_api_token_refresh_token_expiry);
          $response = $next($request);
          return $response;
        }
      }elseif(Session::has('x_api_token') && Session::get('x_api_token_expiry') < $now && Session::get('x_api_token_refresh_token_expiry') > $now){
        $refresh_token_data =   generateUdinXApiTokenByRefreshToken();
        $x_api_token   = $refresh_token_data['data'];
        $x_api_token_expiry = date('Y-m-d H:i:s', strtotime($now . ' +50 minutes'));
        Session::put('x_api_token', $x_api_token);
        Session::put('x_api_token_expiry', $x_api_token_expiry);
        $response = $next($request);
        return $response;
      }elseif(Session::get('x_api_token_refresh_token_expiry') < $now ){
        Session::put("refresh_token_expired_code", "STFREXP001");
        if(Session::has("refresh_token_expired_code") && Session::get("refresh_token_expired_code") == "STFREXP001"){
          Session::flush();
          Session::put("expired_message", "Your session has expired. Please login again.");
          Log::info("Session expired at: " . $now);
          Log::info("Redirecting to login page due to expired session.");
          // Redirect to login page
          return redirect('/admin/login')->with([
            'module' => 'login',
            'active-page' => 'home'
          ]);
        }
      }else{
        $response = $next($request);
        return $response;
      }
      
    }
}
