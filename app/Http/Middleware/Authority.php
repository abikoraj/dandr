<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authority
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(env('APP_ENV')=="local"){
            return $next($request);

        }else{

            $user = Auth::user();
    
            if ($user->phone != env('authphone', "9852059171")) {
                return response('Not authorized To Update or Delete', 401);
            } else {
                return $next($request);
            }
        }
    }
}
