<?php

namespace App\Http\Middleware;

use Closure;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if('ADMIN' == strtoupper($type) && strtoupper($type) !== user()->type) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
