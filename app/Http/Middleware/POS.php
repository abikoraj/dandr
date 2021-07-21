<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class POS
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
        $roles=[0,11,12];
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user=Auth::user();
        if(in_array($user->role,$roles)){
            return $next($request);
        }else{
            return redirect()->route($user->getRole().".dashboard");
        }
        return $next($request);
    }
}
