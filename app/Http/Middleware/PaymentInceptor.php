<?php

namespace App\Http\Middleware;

use App\PaymentManager;
use Closure;
use Illuminate\Http\Request;

class PaymentInceptor
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

        return $next($request);
    }
}
