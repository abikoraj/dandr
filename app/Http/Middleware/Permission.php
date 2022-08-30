<?php

namespace App\Http\Middleware;

use App\Models\UserPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Inline\Element\Code;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $code)
    {

        $user = Auth::user();
        
        if ($user->phone == env('authphone', "9800916365")) {
            return $next($request);
        } else {
            if (DB::table('user_permissions')->where('user_id', $user->id)->where('code', $code)->where('enable', 1)->count() > 0) {
                return $next($request);
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Access Denied', 403);
                } else {
                    return redirect()->route('403');
                }
            }
        }
    }
}
