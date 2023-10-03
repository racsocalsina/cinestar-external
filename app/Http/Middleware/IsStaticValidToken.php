<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class IsStaticValidToken
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
        $token = env('EXTERNAL_TOKEN');
        if (!$request->get('token') || $request->get('token')  != $token){
            //return  ApiResponse::unauthorized();
        }
        return $next($request);
    }
}
