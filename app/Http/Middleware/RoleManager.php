<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user=Auth::user();
        if($user->getRole()==$role){
            Config::set('per.per',$user->permissions);
            return $next($request);
        }else{
            return redirect()->route($user->getRole().".dashboard");
        }
    }
}
