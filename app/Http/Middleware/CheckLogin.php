<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class CheckLogin
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
        if(Session()->has('logged')){
            return $next($request);
        }
        return response()->json(['code'=>401,'message'=>'没有登录']);
    }
}
