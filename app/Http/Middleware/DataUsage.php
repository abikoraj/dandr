<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DataUsage
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
        $response = $next($request);
        if(env('APP_DEBUG',false)){
            $response->header('memory_get_peak_usage', memory_get_peak_usage(true));
        }
        return $response;
    }

    
}
